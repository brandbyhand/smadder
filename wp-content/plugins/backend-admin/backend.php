<?php
/*
Plugin Name: Brand by Hand - Backend
Plugin URI: http://brandbyhand.dk
Description: Customizes the WordPress dashboard screen.
Version: 1.1
Author: Brand by Hand
Author URI: http://brandbyhand.dk
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html
*/

// Enqueuing our styles correctly
function megatron_admin_styles() {
    wp_register_style( 'megatron_admin_stylesheet', plugins_url( '/css/style.css', __FILE__ ) );
    wp_enqueue_style( 'megatron_admin_stylesheet' );
}
add_action( 'admin_enqueue_scripts', 'megatron_admin_styles' );


// Change the footer text
function megatron_admin_footer_text () {
    echo '<img src="' . plugins_url( 'images/brandbyhand_hand.png' , __FILE__ ) . '">Denne hjemmeside er skr&aelig;ddersyet af <a href="http://brandbyhand.dk">Brand by Hand</a>.';
}
add_filter( 'admin_footer_text', 'megatron_admin_footer_text' );

// remove unwanted dashboard widgets for relevant users
function megatron_remove_dashboard_widgets() {
    $user = wp_get_current_user();
    if ( ! $user->has_cap( 'manage_options' ) ) {
        remove_meta_box( 'dashboard_recent_comments', 'dashboard', 'normal' );
        remove_meta_box( 'dashboard_incoming_links', 'dashboard', 'normal' );
        remove_meta_box( 'dashboard_quick_press', 'dashboard', 'side' );
        remove_meta_box( 'dashboard_primary', 'dashboard', 'side' );
        remove_meta_box( 'dashboard_secondary', 'dashboard', 'side' );
    }
}
add_action( 'wp_dashboard_setup', 'megatron_remove_dashboard_widgets' );

// add new dashboard widgets
function megatron_add_dashboard_widgets() {
    wp_add_dashboard_widget( 'megatron_dashboard_welcome', 'Velkommen', 'megatron_add_welcome_widget' );
}
//HUSK ALTID AT TILPASSE VIDEOEN NÅR DU EMBEDDER FRA YOUTUBE SÅ DEN FÅR EN BREDDE PÅ 100%
function megatron_add_welcome_widget(){ ?>
 
    Her vil du kunne finde brugerguides til at hj&aelig;lpe dig med vedligeholdelsen af din hjemmeside.
	<br/>
    <br/>
	<iframe width="100%" height="auto" src="https://www.youtube.com/embed/_GN9Yet42YQ?rel=0" frameborder="0" allowfullscreen></iframe>
	<br/>
	<i>Video til hj&aelig;lp af søgeoptimering på hjemmesiden</i>
    <br/>
    <br />
	Mange hilsener
	<br/>
	<br/>
	<a href="http://brandbyhand.dk/">Brand by Hand</a>
	<br/>
	<strong>Tlf.:</strong> 70 605 602
	<br/>
	<strong>Mail:</strong> service@brandbyhand.dk
 
<?php }
add_action( 'wp_dashboard_setup', 'megatron_add_dashboard_widgets' );

//Style loginpage
function my_login_logo() { ?>
    <style type="text/css">
        .login h1 a {
            background-image: url(<?php echo plugins_url( 'images/my_login.png', __FILE__ ) ?>);
        }
    </style>
<?php }
add_action( 'login_enqueue_scripts', 'my_login_logo' );

//RENAME WP-ADMIN PAGE
if ( defined( 'ABSPATH' ) && ! class_exists( 'Rename_WP_Login' ) ) {

	class Rename_WP_Login {
		private $wp_login_php;

		private function basename() {
			return plugin_basename( __FILE__ );
		}

		private function path() {
			return trailingslashit( dirname( __FILE__ ) );
		}

		private function use_trailing_slashes() {
			return '/' === substr( get_option( 'permalink_structure' ), -1, 1 );
		}

		private function user_trailingslashit( $string ) {
			return $this->use_trailing_slashes() ? trailingslashit( $string ) : untrailingslashit( $string );
		}

		private function wp_template_loader() {
			global $pagenow;

			$pagenow = 'index.php';

			if ( ! defined( 'WP_USE_THEMES' ) ) {
				define( 'WP_USE_THEMES', true );
			}

			wp();

			if ( $_SERVER['REQUEST_URI'] === $this->user_trailingslashit( str_repeat( '-/', 10 ) ) ) {
				$_SERVER['REQUEST_URI'] = $this->user_trailingslashit( '/wp-login-php/' );
			}

			require_once( ABSPATH . WPINC . '/template-loader.php' );

			die;
		}

		private function new_login_slug() {
			if (
				( $slug = get_option( 'rwl_page' ) ) || (
					is_multisite() &&
					is_plugin_active_for_network( $this->basename() ) &&
					( $slug = get_site_option( 'rwl_page', 'logind' ) )
				) ||
				( $slug = 'logind' )
			) {
				return $slug;
			}
		}

		public function new_login_url( $scheme = null ) {
			if ( get_option( 'permalink_structure' ) ) {
				return $this->user_trailingslashit( home_url( '/', $scheme ) . $this->new_login_slug() );
			} else {
				return home_url( '/', $scheme ) . '?' . $this->new_login_slug();
			}
		}

		public function __construct() {
			global $wp_version;

			if ( version_compare( $wp_version, '4.0-RC1-src', '<' ) ) {
				add_action( 'admin_notices', array( $this, 'admin_notices_incompatible' ) );
				add_action( 'network_admin_notices', array( $this, 'admin_notices_incompatible' ) );

				return;
			}

			register_activation_hook( $this->basename(), array( $this, 'activate' ) );
			register_uninstall_hook( $this->basename(), array( 'Rename_WP_Login', 'uninstall' ) );

			add_action( 'admin_init', array( $this, 'admin_init' ) );
			add_action( 'admin_notices', array( $this, 'admin_notices' ) );
			add_action( 'network_admin_notices', array( $this, 'admin_notices' ) );

			if ( is_multisite() && ! function_exists( 'is_plugin_active_for_network' ) ) {
				require_once( ABSPATH . '/wp-admin/includes/plugin.php' );
			}

			add_filter( 'plugin_action_links_' . $this->basename(), array( $this, 'plugin_action_links' ) );

			if ( is_multisite() && is_plugin_active_for_network( $this->basename() ) ) {
				add_filter( 'network_admin_plugin_action_links_' . $this->basename(), array( $this, 'plugin_action_links' ) );

				add_action( 'wpmu_options', array( $this, 'wpmu_options' ) );
				add_action( 'update_wpmu_options', array( $this, 'update_wpmu_options' ) );
			}

			add_action( 'plugins_loaded', array( $this, 'plugins_loaded' ), 1 );
			add_action( 'wp_loaded', array( $this, 'wp_loaded' ) );

			add_filter( 'site_url', array( $this, 'site_url' ), 10, 4 );
			add_filter( 'network_site_url', array( $this, 'network_site_url' ), 10, 3 );
			add_filter( 'wp_redirect', array( $this, 'wp_redirect' ), 10, 2 );

			add_filter( 'site_option_welcome_email', array( $this, 'welcome_email' ) );

			remove_action( 'template_redirect', 'wp_redirect_admin_locations', 1000 );
		}

		public function admin_notices_incompatible() {
			echo '<div class="error"><p>' . sprintf( __( 'Please upgrade to the latest version of WordPress to activate %s.', 'rename-wp-login' ), '<strong>' . __( 'Rename wp-login.php', 'rename-wp-login' ) . '</strong>' ) . '</p></div>';
		}

		public function activate() {
			add_option( 'rwl_redirect', '1' );
			delete_option( 'rwl_admin' );
		}

		public static function uninstall() {
			global $wpdb;

			if ( is_multisite() ) {
				$blogs = $wpdb->get_col( "SELECT blog_id FROM {$wpdb->blogs}" );

				if ( $blogs ) {
					foreach ( $blogs as $blog ) {
						switch_to_blog( $blog );
						delete_option( 'rwl_page' );
					}

					restore_current_blog();
				}

				delete_site_option( 'rwl_page' );
			} else {
				delete_option( 'rwl_page' );
			}
		}

		public function wpmu_options() {
			$out = '';

			$out .= '<h3>' . __( 'Rename wp-login.php', 'rename-wp-login' ) . '</h3>';
			$out .= '<p>' . __( 'This option allows you to set a networkwide default, which can be overridden by individual sites. Simply go to to the site’s permalink settings to change the url.', 'rename-wp-login' ) . '</p>';
			$out .= '<table class="form-table">';
				$out .= '<tr valign="top">';
					$out .= '<th scope="row">' . __( 'Networkwide default', 'rename-wp-login' ) . '</th>';
					$out .= '<td><input id="rwl-page-input" type="text" name="rwl_page" value="' . get_site_option( 'rwl_page', 'logind' )  . '"></td>';
				$out .= '</tr>';
			$out .= '</table>';

			echo $out;
		}

		public function update_wpmu_options() {
			if (
				( $rwl_page = sanitize_title_with_dashes( $_POST['rwl_page'] ) ) &&
				strpos( $rwl_page, 'wp-login' ) === false &&
				! in_array( $rwl_page, $this->forbidden_slugs() )
			) {
				update_site_option( 'rwl_page', $rwl_page );
			}
		}

		public function admin_init() {
			global $pagenow;

			add_settings_section(
				'rename-wp-login-section',
				__( 'Rename wp-login.php', 'rename-wp-login' ),
				array( $this, 'rwl_section_desc' ),
				'permalink'
			);

			add_settings_field(
				'rwl-page',
				'<label for="rwl-page">' . __( 'Login url', 'rename-wp-login' ) . '</label>',
				array( $this, 'rwl_page_input' ),
				'permalink',
				'rename-wp-login-section'
			);

			if ( isset( $_POST['rwl_page'] ) && $pagenow === 'options-permalink.php' ) {
				if (
					( $rwl_page = sanitize_title_with_dashes( $_POST['rwl_page'] ) ) &&
					strpos( $rwl_page, 'wp-login' ) === false &&
					! in_array( $rwl_page, $this->forbidden_slugs() )
				) {
					if ( is_multisite() && $rwl_page === get_site_option( 'rwl_page', 'logind' ) ) {
						delete_option( 'rwl_page' );
					} else {
						update_option( 'rwl_page', $rwl_page );
					}
				}
			}

			if ( get_option( 'rwl_redirect' ) ) {
				delete_option( 'rwl_redirect' );

				if ( is_multisite() && is_super_admin() && is_plugin_active_for_network( $this->basename() ) ) {
					$redirect = network_admin_url( 'settings.php#rwl-page-input' );
				} else {
					$redirect = admin_url( 'options-permalink.php#rwl-page-input' );
				}

				wp_safe_redirect( $redirect );

				die;
			}
		}

		public function rwl_section_desc() {
			$out = '';

			if ( is_multisite() && is_super_admin() && is_plugin_active_for_network( $this->basename() ) ) {
				$out .= '<p>' . sprintf( __( 'To set a networkwide default, go to %s.', 'rename-wp-login' ), '<a href="' . network_admin_url( 'settings.php#rwl-page-input' ) . '">' . __( 'Network Settings', 'rename-wp-login' ) . '</a>') . '</p>';
			}

			echo $out;
		}

		public function rwl_page_input() {
			if ( get_option( 'permalink_structure' ) ) {
				echo '<code>' . trailingslashit( home_url() ) . '</code> <input id="rwl-page-input" type="text" name="rwl_page" value="' . $this->new_login_slug()  . '">' . ( $this->use_trailing_slashes() ? ' <code>/</code>' : '' );
			} else {
				echo '<code>' . trailingslashit( home_url() ) . '?</code> <input id="rwl-page-input" type="text" name="rwl_page" value="' . $this->new_login_slug()  . '">';
			}
		}

		public function admin_notices() {
			global $pagenow;

			if ( ! is_network_admin() && $pagenow === 'options-permalink.php' && isset( $_GET['settings-updated'] ) ) {
				echo '<div class="updated"><p>' . sprintf( __( 'Your login page is now here: %s. Bookmark this page!', 'rename-wp-login' ), '<strong><a href="' . $this->new_login_url() . '">' . $this->new_login_url() . '</a></strong>' ) . '</p></div>';
			}
		}

		public function plugin_action_links( $links ) {
			if ( is_network_admin() && is_plugin_active_for_network( $this->basename() ) ) {
				array_unshift( $links, '<a href="' . network_admin_url( 'settings.php#rwl-page-input' ) . '">' . __( 'Settings' ) . '</a>' );
			} elseif ( ! is_network_admin() ) {
				array_unshift( $links, '<a href="' . admin_url( 'options-permalink.php#rwl-page-input' ) . '">' . __( 'Settings' ) . '</a>' );
			}

			return $links;
		}

		public function plugins_loaded() {
			global $pagenow;

			if (
				! is_multisite() && (
					strpos( $_SERVER['REQUEST_URI'], 'wp-signup' ) !== false ||
					strpos( $_SERVER['REQUEST_URI'], 'wp-activate' ) !== false
				)
			) {
				wp_die( __( 'This feature is not enabled.', 'rename-wp-login' ) );
			}

			$request = parse_url( $_SERVER['REQUEST_URI'] );

			if ( (
					strpos( $_SERVER['REQUEST_URI'], 'wp-login.php' ) !== false ||
					untrailingslashit( $request['path'] ) === site_url( 'wp-login', 'relative' )
				) &&
				! is_admin()
			) {
				$this->wp_login_php = true;
				$_SERVER['REQUEST_URI'] = $this->user_trailingslashit( '/' . str_repeat( '-/', 10 ) );
				$pagenow = 'index.php';
			} elseif (
				untrailingslashit( $request['path'] ) === home_url( $this->new_login_slug(), 'relative' ) || (
					! get_option( 'permalink_structure' ) &&
					isset( $_GET[$this->new_login_slug()] ) &&
					empty( $_GET[$this->new_login_slug()] )
			) ) {
				$pagenow = 'wp-login.php';
			}
		}

		public function wp_loaded() {
			global $pagenow;

			if ( is_admin() && ! is_user_logged_in() && ! defined( 'DOING_AJAX' ) ) {
				wp_die( __( 'You must log in to access the admin area.', 'rename-wp-login' ) );
			}

			$request = parse_url( $_SERVER['REQUEST_URI'] );

			if (
				$pagenow === 'wp-login.php' &&
				$request['path'] !== $this->user_trailingslashit( $request['path'] ) &&
				get_option( 'permalink_structure' )
			) {
				wp_safe_redirect( $this->user_trailingslashit( $this->new_login_url() ) . ( ! empty( $_SERVER['QUERY_STRING'] ) ? '?' . $_SERVER['QUERY_STRING'] : '' ) );
				die;
			} elseif ( $this->wp_login_php ) {
				if (
					( $referer = wp_get_referer() ) &&
					strpos( $referer, 'wp-activate.php' ) !== false &&
					( $referer = parse_url( $referer ) ) &&
					! empty( $referer['query'] )
				) {
					parse_str( $referer['query'], $referer );

					if (
						! empty( $referer['key'] ) &&
						( $result = wpmu_activate_signup( $referer['key'] ) ) &&
						is_wp_error( $result ) && (
							$result->get_error_code() === 'already_active' ||
							$result->get_error_code() === 'blog_taken'
					) ) {
						wp_safe_redirect( $this->new_login_url() . ( ! empty( $_SERVER['QUERY_STRING'] ) ? '?' . $_SERVER['QUERY_STRING'] : '' ) );
						die;
					}
				}

				$this->wp_template_loader();
			} elseif ( $pagenow === 'wp-login.php' ) {
				global $error, $interim_login, $action, $user_login;

				@require_once ABSPATH . 'wp-login.php';

				die;
			}
		}

		public function site_url( $url, $path, $scheme, $blog_id ) {
			return $this->filter_wp_login_php( $url, $scheme );
		}

		public function network_site_url( $url, $path, $scheme ) {
			return $this->filter_wp_login_php( $url, $scheme );
		}

		public function wp_redirect( $location, $status ) {
			return $this->filter_wp_login_php( $location );
		}

		public function filter_wp_login_php( $url, $scheme = null ) {
			if ( strpos( $url, 'wp-login.php' ) !== false ) {
				if ( is_ssl() ) {
					$scheme = 'https';
				}

				$args = explode( '?', $url );

				if ( isset( $args[1] ) ) {
					parse_str( $args[1], $args );
					$url = add_query_arg( $args, $this->new_login_url( $scheme ) );
				} else {
					$url = $this->new_login_url( $scheme );
				}
			}

			return $url;
		}

		public function welcome_email( $value ) {
			return $value = str_replace( 'wp-login.php', trailingslashit( get_site_option( 'rwl_page', 'logind' ) ), $value );
		}

		public function forbidden_slugs() {
			$wp = new WP;
			return array_merge( $wp->public_query_vars, $wp->private_query_vars );
		}
	}

	new Rename_WP_Login;
}

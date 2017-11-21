<?php

/*
Plugin Name: Brand by Hand - Pagebuilder
Plugin URI: PLUGIN_URL
Description: This plugin adds a column field to ACF as well as styling to the backend
Version: 2.4.1
Author: Brand by Hand
Author URI: https://brandbyhand.dk
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html
*/

// exit if accessed directly
if( ! defined( 'ABSPATH' ) ) exit;

// check if class already exists
if( !class_exists('acf_plugin_columns') ) :

// require WP updates file
require_once('assets/update/wp-updates-plugin.php');
new WPUpdatesPluginUpdater_1705( 'http://wp-updates.com/api/2/plugin', plugin_basename(__FILE__));


class acf_plugin_columns {
	
	/*
	*  __construct
	*
	*  This function will setup the class functionality
	*
	*  @type	function
	*  @date	17/02/2016
	*  @since	1.0.0
	*
	*  @param	n/a
	*  @return	n/a
	*/
	
	function __construct() {
		
		// vars
		$this->settings = array(
			'version'	=> '1.0.0',
			'url'		=> plugin_dir_url( __FILE__ ),
			'path'		=> plugin_dir_path( __FILE__ )
		);
		
		
		// set text domain
		// https://codex.wordpress.org/Function_Reference/load_plugin_textdomain
		load_plugin_textdomain( 'acf-columns', false, plugin_basename( dirname( __FILE__ ) ) . '/lang' ); 
		
		
		// include field
		add_action('acf/include_field_types', 	array($this, 'include_field_types')); // v5
		add_action('acf/register_fields', 		array($this, 'include_field_types')); // v4
		
	}
	
	
	/*
	*  include_field_types
	*
	*  This function will include the field type class
	*
	*  @type	function
	*  @date	17/02/2016
	*  @since	1.0.0
	*
	*  @param	$version (int) major ACF version. Defaults to false
	*  @return	n/a
	*/
	
	function include_field_types( $version = false ) {
		
		// support empty $version
		if( !$version ) $version = 4;
		
		
		// include
		include_once('fields/acf-columns-v' . $version . '.php');
		
	}

	
}
// Add styles and js init
function acf_admin_enqueue() {
	global $post;
	global $current_screen;

	if($current_screen->id == 'upload'){
		return;
	}
	if(strpos($current_screen->id, "acf-options") == true || $post->post_type != 'acf-field-group'):

		// enqueue jquery UI script
		//wp_enqueue_script( 'bbh-jquery-ui', plugin_dir_url(__FILE__) . 'assets/js/jquery-ui.min.js', array('jquery'), '1.0.0', true );

		// enqueue plugin jQuery
		wp_enqueue_script( 'bbh-pagebuilder-js', plugin_dir_url(__FILE__) . 'assets/js/input.js', array('jquery'), '1.0.0', true );

		// Localize the script with new data
		$translation_array = array(
			'markup' => '<div class="layout-info"><div class="layout-inner"></div></div>',
			'themepath' => get_stylesheet_directory_uri(),
		);
		wp_localize_script( 'bbh-pagebuilder-js', 'layout_material', $translation_array );

		// Enqueued script with localized data.
		wp_enqueue_script( 'bbh-pagebuilder-js' );




		// enqueue stylesheet
		wp_enqueue_style( 'bbh-pagebuilder-styles', plugin_dir_url(__FILE__) . 'assets/css/input.css', array(), 'all', false);
	endif;
}

// hook in styles and js
add_action('acf/input/admin_head', 'acf_admin_enqueue');

// remove wysiwyg media upload buttons.
// remove_action('media_buttons', 'media_buttons');


function acf_settings_custom_columns_style() {
	?>
	<style type="text/css">

		.acf-field-object-column tr[data-name="name"],
		.acf-field-object-column tr[data-name="instructions"],
		.acf-field-object-column tr[data-name="required"],
		.acf-field-object-column tr[data-name="conditional_logic"] {
			display: none !important;
		}

	</style>

	
	<?php
}

add_action('acf/input/admin_head', 'acf_settings_custom_columns_style');


/*======================================================
=            TinyMCE / WYSIWYG editor hooks            =
======================================================*/

/*----------  Remove all buttons from row 1  ----------*/

function myplugin_tinymce_buttons( $buttons ) {

  return [''];//array_diff( $buttons, $remove );
 }
add_filter( 'mce_buttons', 'myplugin_tinymce_buttons' );
add_filter( 'mce_buttons_2', 'myplugin_tinymce_buttons' );
/*----------  Add selected buttons  ----------*/

function my_mce_buttons_2( $buttons ) { 
  /**
   * Add in a core button that's disabled by default
   */
 array_unshift( $buttons, 'formatselect, forecolor, bold, italic, |, alignleft, aligncenter, alignright, |, bullist, numlist, |, link, unlink, |, undo, redo' );

      return $buttons;
}
add_filter( 'mce_buttons', 'my_mce_buttons_2' );

// initialize
new acf_plugin_columns();


// class_exists check
endif;


	
?>
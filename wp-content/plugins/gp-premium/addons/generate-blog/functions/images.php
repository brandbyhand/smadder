<?php
/**
 * Load Image Resizer
 */
if ( ! defined( 'GP_IMAGE_RESIZER' ) && ! is_admin() ) :
	require plugin_dir_path( __FILE__ ) . 'otf_regen_thumbs.php';
endif;

if ( ! function_exists( 'generate_get_blog_image_attributes' ) ) :
function generate_get_blog_image_attributes()
{
	$generate_settings = wp_parse_args( 
		get_option( 'generate_blog_settings', array() ), 
		generate_blog_get_defaults() 
	);
		
	$atts = array(
		'width' => intval( $generate_settings['post_image_width'] ),
		'height' => ( '' !== $generate_settings['post_image_height'] ) ? intval( $generate_settings['post_image_height'] ) : 9999,
		'crop' => ( '' == $generate_settings['post_image_height'] || 9999 == $generate_settings['post_image_width'] ) ? false : true
	);
	
	return apply_filters( 'generate_blog_image_attributes', $atts );
}
endif;

if ( ! function_exists( 'generate_blog_setup' ) ) :
	add_action('wp','generate_blog_setup');
	function generate_blog_setup()
	{
		$generate_settings = wp_parse_args( 
			get_option( 'generate_blog_settings', array() ), 
			generate_blog_get_defaults() 
		);
		
		// Remove the default function and add our own
		remove_action( 'generate_after_entry_header', 'generate_post_image' );
		add_action( 'generate_after_entry_header', 'generate_blog_post_image' );
		
		if ( 'post-image-above-header' == $generate_settings['post_image_position'] ) :
			remove_action( 'generate_after_entry_header', 'generate_blog_post_image' );
			add_action( 'generate_before_content', 'generate_blog_post_image' );
			
			if ( function_exists('generate_page_header_post_image') ) :
				remove_action( 'generate_after_entry_header', 'generate_page_header_post_image' );
				add_action( 'generate_before_content', 'generate_page_header_post_image' );
			endif;
		endif;
	}
endif;

if ( ! function_exists( 'generate_blog_post_image' ) ) :
	function generate_blog_post_image()
	{
		if ( ! has_post_thumbnail() )
			return;
			
		if ( function_exists( 'is_woocommerce' ) ) :
			if ( is_woocommerce() )
				return;
		endif;
			
		$generate_settings = wp_parse_args( 
			get_option( 'generate_blog_settings', array() ), 
			generate_blog_get_defaults() 
		);
		
		$image_atts = generate_get_blog_image_attributes();
			
		global $post;
		if ( ! is_singular() && ! is_404() ) {
		
			if ( '' !== $generate_settings['post_image_width'] ) :
				?>
				<div class="post-image">
					<a href="<?php the_permalink();?>" title="<?php the_title(); ?>"><?php the_post_thumbnail( array( $image_atts[ 'width' ], $image_atts[ 'height' ], $image_atts[ 'crop' ] ), array( 'itemprop' => 'image' ) );?></a>
				</div>
				<?php
			else :
				?>
				<div class="post-image">
					<a href="<?php the_permalink();?>" title="<?php the_title(); ?>"><?php the_post_thumbnail('full', array( 'itemprop' => 'image' )); ?></a>
				</div>
				<?php
			endif;
		}
	}
endif;
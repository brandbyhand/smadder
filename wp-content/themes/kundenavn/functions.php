<?php
/**
 * Generate child theme functions and definitions
 *
 * @package Generate
 */
 
function custom_excerpt_length( $length ) {
	return 24;
}
add_filter( 'excerpt_length', 'custom_excerpt_length', 999 );

/* Translate function */
add_filter('gettext', 'translate_text');
add_filter('ngettext', 'translate_text');

function translate_text($translated) {
    //$translated = str_ireplace('Eng', 'Da', $translated);
    return $translated;
}



/*=================================================
=            Enqueue styles og scripts            =
=================================================*/

add_action( 'wp_enqueue_scripts', 'bbh_enqueue_scripts_styles' );
function bbh_enqueue_scripts_styles() {
    
    /*----------  Scripts  ----------*/
    
    wp_enqueue_script( 'brandbyhandscripts', get_stylesheet_directory_uri() . '/js/bbh_scripts.js', array( 'jquery' ), false, true ); // custom Brand By Hand scripts

    /*----------  Styles  ----------*/
    
    wp_enqueue_style( 'bootstrapcss', get_stylesheet_directory_uri() . '/bootstrap/bootstrap.css', '1.0', 'all');
}
/*----------  Mobile detect library  ----------*/
include(STYLESHEETPATH . '/include/mobile/Mobile_Detect.php');

/*--===================================
=            Mobile detect            =
====================================--*/
add_filter( 'body_class','my_body_classes' );
function my_body_classes( $classes ) {
    $detect = new Mobile_Detect;
    
    if ( $detect->isMobile() && !$detect->isTablet() ) {
        $classes[] = 'mobile-detected';
    } elseif( $detect->isTablet() ) {
        $classes[] = 'tablet-detected';
    } else{
        $classes[] = 'desktop-detected';
    }
    
    return $classes;
}

/*=============================================
=            TinyMCE button plugin            =
=============================================*/
function bbh_mce_button() {
    // Check if WYSIWYG is enabled
    if ( get_user_option( 'rich_editing' ) == 'true' ) {
        add_filter( 'mce_external_plugins', 'bbh_tinymce_plugin' );
        add_filter( 'mce_buttons', 'register_mce_buttons' );
    }
}
add_action('admin_head', 'bbh_mce_button');


// Add the path to the js file with the custom button function
function bbh_tinymce_plugin( $plugin_array ) {
    $plugin_array['bbh_custom_mce_button'] = get_stylesheet_directory_uri() .'/include/tinymce-button/mce.js';

    return $plugin_array;
}

// Register and add new button in the editor
function register_mce_buttons( $buttons ) {
    array_push( $buttons, 'bbh_custom_mce_button' );

    return $buttons;
}

function wpdocs_bbh_add_editor_styles() {
    add_editor_style( 'include/tinymce-button/custom-editor-style.css' );
}
add_action( 'admin_init', 'wpdocs_bbh_add_editor_styles' );

/*=========================================
=            Remove meta boxes            =
=========================================*/

add_action( 'after_setup_theme','bbh_remove_metaboxes' );
function bbh_remove_metaboxes()
{
    remove_action('add_meta_boxes', 'generate_add_footer_widget_meta_box'); // Footer widgets
    remove_action( 'add_meta_boxes', 'generate_add_de_meta_box' ); // Deactivate elements
    remove_action('add_meta_boxes', 'generate_add_page_builder_meta_box' ); // Page builder integration
}

/*==============================================================
=            Move Yoast SEO metabox to low priority            =
==============================================================*/
// Move Yoast to bottom of page
function yoasttobottom() {
    return 'low';
}
add_filter( 'wpseo_metabox_prio', 'yoasttobottom');


/*=========================================
=            Remove menu items            =
=========================================*/
function bbh_remove_menus(){
  
  //remove_menu_page( 'index.php' );                  //Dashboard
  //remove_menu_page( 'jetpack' );                    //Jetpack* 
  //remove_menu_page( 'edit.php' );                   //Posts
  //remove_menu_page( 'upload.php' );                 //Media
  //remove_menu_page( 'edit.php?post_type=page' );    //Pages
  remove_menu_page( 'edit-comments.php' );          //Comments
  //remove_menu_page( 'themes.php' );                 //Appearance
  //remove_menu_page( 'plugins.php' );                //Plugins
  //remove_menu_page( 'users.php' );                  //Users
  //remove_menu_page( 'tools.php' );                  //Tools
  //remove_menu_page( 'options-general.php' );        //Settings
  
}
add_action( 'admin_menu', 'bbh_remove_menus' );

/*============================================
=            Remove toolbar nodes            =
============================================*/
add_action( 'admin_bar_menu', 'bbh_remove_nodes', 999 );

function bbh_remove_nodes( $wp_admin_bar ) {
    $wp_admin_bar->remove_node( 'comments' );
}

/*================================================
=            Remove editor from pages            =
================================================*/


add_action( 'admin_head', 'bbh_remove_content_editor', 11 ); 

function bbh_remove_content_editor() {

    // This will remove support for post thumbnails on ALL Post Types
    remove_post_type_support('page', 'editor');


}

/*=========================================================================
=            Remove "Add media" button from pagebuilder popups            =
=========================================================================*/

add_action('admin_head', 'my_custom_fonts');

function my_custom_fonts() {
  echo '<style>
    .popup-content-outer .wp-media-buttons{
        display: none !important;
    }
  </style>';
}



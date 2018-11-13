<?php

/* Constants
~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~ */

	/**
	 * Version bumper
	 */
	define('VERSION', wp_get_theme()->get('Version'));
	define('VERSION_QS', '?v='.VERSION);


	/**
	 * Google Maps API Key
	 */
	define('API_KEY', '');


	/**
	 * Google Fonts
	 */
	define('GOOGLE_FONTS', 'Open+Sans:400,700');


	/**
	 * Paths
	 */
	define('HOME', home_url());
	define('THEME', get_template_directory_uri());
	define('ASSETS', THEME . '/assets');
	define('IMAGES', ASSETS . '/images');
	define('CSS', ASSETS . '/css');
	define('JS', ASSETS . '/js');


	/**
	 * Image sizes
	 */
	define('SZ_BANNER' , '');
	define('SZ_SLIDER' , '');
	define('SZ_THUMB', '');



/* Includes
~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~ */

	// require_once locate_template('lib/whatever.php');


/* Initial setup
~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~ */

	function blankos_theme_setup() {

		// Make theme available for translation
		load_theme_textdomain( 'blankos', get_template_directory() . '/languages' );


		// Enable plugins to manage the document title
		add_theme_support('title-tag');


		// Register wp_nav_menu() menus
		register_nav_menus(array(
			'main_1'   => __( 'Main Menu 1', 'blankos' ),
			'main_2'   => __( 'Main Menu 2', 'blankos' ),
			'footer_1' => __( 'Footer Menu 1', 'blankos' ),
			'footer_2' => __( 'Footer Menu 2', 'blankos' ),
		));


		// Add post thumbnails
		add_theme_support('post-thumbnails');


		// Add Custom Image sizes..
		add_image_size('small', 400, 300);
		add_image_size('hd', 1920, 1080);


		// Add post formats
		// add_theme_support('post-formats', array('aside', 'gallery', 'link', 'image', 'quote', 'video', 'audio'));


		// Add HTML5 markup for captions etc..
		add_theme_support( 'html5', array( 'comment-list', 'comment-form', 'search-form', 'gallery', 'caption' ) );


		// Tell the TinyMCE editor to use a custom stylesheet
		add_editor_style(CSS.'/editor.css');

	}
	add_action('after_setup_theme', 'blankos_theme_setup');


	/**
	 * Register sidebars
	 */
	function blankos_widgets_init() {

		register_sidebar(array(
			'name'          => __('Sidebar 1', 'blankos'),
			'id'            => 'sidebar-1',
			'before_widget' => '<div class="widget %1$s %2$s">',
			'after_widget'  => '</div>',
			'before_title'  => '<h3>',
			'after_title'   => '</h3>',
		));

		register_sidebar(array(
			'name'          => __('Sidebar 2', 'blankos'),
			'id'            => 'sidebar-2',
			'before_widget' => '<div class="widget %1$s %2$s">',
			'after_widget'  => '</div>',
			'before_title'  => '<h3>',
			'after_title'   => '</h3>',
		));
	}
	add_action('widgets_init', 'blankos_widgets_init');


	/**
	 * Load google fonts in head..
	 * uses GOOGLE_FONTS constant above
	 */
	function blankos_load_google_fonts() {

		if( ! defined( 'GOOGLE_FONTS' ) ) return;

		echo '<!-- google fonts -->'."\n";
		echo '<link href="//fonts.googleapis.com/css?family=' . GOOGLE_FONTS . '" rel="stylesheet" type="text/css" />'."\n";

	}
	add_action( 'wp_head', 'blankos_load_google_fonts' , 1);


/* Utility functions
~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~ */

	/**
	 * Responsive Image Helper Function
	 *
	 * @param string $image_id the id of the image (from ACF or similar)
	 * @param string $image_size the size of the thumbnail image or custom image size
	 * @param string $max_width the max width this image will be shown to build the sizes attribute 
	 */
	function blankos_responsive_image( $image_id,$image_size,$max_width ) {

		// check the image ID is not blank
		if ( $image_id != '' ) {

			// set the default src image size
			$image_src = wp_get_attachment_image_url( $image_id, $image_size );

			// set the srcset with various image sizes
			$image_srcset = wp_get_attachment_image_srcset( $image_id, $image_size );

			// generate the markup for the responsive image
			echo 'src="'.$image_src.'" srcset="'.$image_srcset.'" sizes="(max-width: '.$max_width.') 100vw, '.$max_width.'"';
		}
	}


	/**
	 * Clean up the_excerpt()
	 */
	function blankos_excerpt_more($more) {

		return ' &hellip;';
	}
	add_filter('excerpt_more', 'blankos_excerpt_more');


	/**
	 * Set the except length
	 */
	function blankos_excerpt_length($length) {

		return 15;
	}
	add_filter('excerpt_length', 'blankos_excerpt_length', 999);


	/**
	 * Helper function for generating custom excerpts on the fly
	 */
	function blankos_excerptify($text='', $excerpt_length=20, $excerpt_more=null) {

		global $post;
		if ($text != '') {

			$text = strip_shortcodes($text);
			$text = apply_filters('the_content', $text);
			$text = str_replace(']]>', ']]>', $text);
			if ($excerpt_more) {

				$excerpt_more = apply_filters('excerpt_more', ' ' . '&hellip;');
			}
			$text = wp_trim_words($text, $excerpt_length, $excerpt_more);
		}
		return apply_filters('the_excerpt', $text);
	}


	/**
	 * Explode a \n delimited textarea into a list
	 */
	function blankos_list_explode($str, $class=null) {

		$lists = explode("\n", $str);
		$lists_html = '';

		if ($class) {
			$class = ' class="'.trim($class).'"';
		}

		foreach ($lists as $list) {
			$lists_html .= "<li{$class}>{$list}</li>";
		}

		return $lists_html;
	}


	/**
	 * Get post thumbnail alt similarly to get_post_thumbnail_url()
	 */
	function get_the_post_thumbnail_alt($post_id=null) {

		if (!$post_id) $post_id = $post->ID;
		$thumbnail_id = get_post_thumbnail_id($post_id);
		$alt = get_post_meta($thumbnail_id, '_wp_attachment_image_alt', true);
		return ($alt);
	}


	/*
	 * Echo image placholder
	 */
	function placehold($text='', $class='') {
		echo '<img src="//placehold.it/500x500?text='.$text.'" alt="placeholder" class="'. $class .'">';
	}


	/*
	 * Telephone number sanitize
	 */
	function tel($tel) {
		return str_replace(' ', '', $tel);
	}


	/*
	 * Useful for debugging..
	 */
	function pp($var) {
		echo "<pre>";
		print_r($var);
		echo "</pre>";
	}


/* ACF
~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~ */

	/**
	* Set gmaps api key in back end..
	*/
	add_filter('acf/settings/google_api_key', function() {
	  return API_KEY;
	});


	/**
	* Translate the relationship result (requires qTranslateX)
	*/
	// function blankos_relationship_result( $title, $post, $field, $post_id ) {

	// 	$editLang = $_COOKIE['qtrans_edit_language'];
	// 	if($editLang) {
	// 		return qtranxf_use($editLang, $title, false, false);
	// 	} else {
	// 		return qtranxf_useCurrentLanguageIfNotFoundUseDefaultLanguage($title);
	// 	}
	// }
	// add_filter('acf/fields/relationship/result', 'blankos_relationship_result', 10, 4);
	// add_filter('acf/fields/post_object/result', 'blankos_relationship_result', 10, 4);
	// add_filter('acf/fields/page_link/result', 'blankos_relationship_result', 10, 4);


	/**
	* Create ACF options page, if installed..
	*/
	function blankos_acf_add_options_page() {
		if ( function_exists('acf_add_options_page') ) {
			acf_add_options_page();
		}
	}
	add_action('after_setup_theme', 'blankos_acf_add_options_page');


	// enable custom fields..
	add_filter( 'acf/settings/remove_wp_meta_box', '__return_false' );


/* YOAST
~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~ */

	function blankos_wpseo_title($title) {

		// logic here..
		return $title;
	}
	add_filter( 'wpseo_title', 'blankos_wpseo_title' );


	function blankos_wpseo_metadesc($desc) {

		// logic here..
		return $desc;
	}
	add_filter( 'wpseo_metadesc', 'blankos_wpseo_metadesc' );


/* Custom
~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~ */


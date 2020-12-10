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
		add_image_size('small', 375, 375);
		add_image_size('hd', 1920, 1920);


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
	 * Cleanup..
	 */
	remove_action( 'wp_head', 'wp_generator' );
	remove_action( 'wp_head', 'print_emoji_detection_script', 7 );
	remove_action( 'admin_print_scripts', 'print_emoji_detection_script' );
	remove_action( 'wp_print_styles', 'print_emoji_styles' );
	remove_action( 'admin_print_styles', 'print_emoji_styles' );

	function blankos_deregister_styles() {
		wp_dequeue_style( 'wp-block-library' );
	}
	add_action( 'wp_print_styles', 'blankos_deregister_styles', 100 );

	function blankos_deregister_scripts() {
		wp_deregister_script( 'wp-embed' );
	}
	add_action( 'wp_footer', 'blankos_deregister_scripts' );


/* Enqueue
~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~ */

	/**
	 * Enqueue styles..
	 */
	function blankos_enqueue_styles() {

		/**
		 * Load google fonts in head..
		 * uses GOOGLE_FONTS constant above
		 */
		if ( defined( 'GOOGLE_FONTS' ) ) {
			wp_enqueue_style( 'gf', 'https://fonts.googleapis.com/css?family='.GOOGLE_FONTS.'&display=swap', false, null );
		}

		wp_enqueue_style( 'libs', CSS.'/libs.css', false, VERSION );
		wp_enqueue_style( 'main', CSS.'/main.css', false, VERSION );
	}
	add_action( 'wp_enqueue_scripts', 'blankos_enqueue_styles' );


	/**
	 * Enqueue scripts..
	 */
	function blankos_enqueue_scripts() {

		wp_enqueue_script( 'libs', JS.'/libs.js', ['jquery'], VERSION, true );
		wp_enqueue_script( 'main', JS.'/main.js', ['libs'], VERSION, true );
	}
	add_action( 'wp_enqueue_scripts', 'blankos_enqueue_scripts' );


/* Nav
~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~ */

	/**
	 * Add classes to the list items of the menus..
	 */
	function blankos_nav_menu_link_attributes( $atts, $item, $args ) {

		if ($args->link_class) {
			$atts['class'] = $args->link_class;
		}

		return $atts;
	}
	add_filter( 'nav_menu_link_attributes', 'blankos_nav_menu_link_attributes', 1, 3 );


	/**
	 * Add the option to add classes to list items..
	 */
	function blankos_nav_menu_css_class($classes, $item, $args) {

		if ($args->list_item_class) {
			$classes[] = $args->list_item_class;
		}

		// let's add "active" as a class to the li
		if( in_array('current-menu-item', $classes) ){
			$classes[] = 'active ';
		}

		return $classes;
	}
	add_filter('nav_menu_css_class', 'blankos_nav_menu_css_class', 1, 3);


/* Utility functions
~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~ */

	/**
	 * Responsive Image Helper Function (src)
	 *
	 * @param string $image_id the id of the image (from ACF or similar)
	 * @param string $image_size the size of the thumbnail image or custom image size
	 * @param string $max_width the max width this image will be shown to build the sizes attribute
	 */
	function blankos_responsive_image_src( $image_id, $image_size, $max_width ) {

		// check the image ID is not blank
		if ( $image_id != '' ) {

			// set the default src image size
			$image_src = wp_get_attachment_image_url( $image_id, $image_size );

			// set the srcset with various image sizes
			$image_srcset = wp_get_attachment_image_srcset( $image_id, $image_size );

			// generate the markup for the responsive image
			return 'src="'.$image_src.'" srcset="'.$image_srcset.'" sizes="(max-width: '.$max_width.') 100vw, '.$max_width.'"';
		}
	}


	/**
	 * Responsive Image Helper Function (img)
	 */
	function blankos_responsive_image( $image_id, $image_size, $max_width, $class='' ) {

		$src = blankos_responsive_image_src( $image_id, $image_size, $max_width );

		if ( $src ) {

			$alt = 'alt="'. get_post_meta( $image_id, '_wp_attachment_image_alt', true) .'"';
			$class = $class ? 'class="'. $class .'"' : '';

			return "<img $src $alt $class>";
		}


	}


	/**
	 * Responsive Image Helper Function (ACF)
	 */
	function blankos_responsive_image_acf( $image_arr, $image_size, $max_width, $class='' ) {

		// check the image ID is not blank
		if ( is_array($image_arr) && isset($image_arr['id']) ) {

			$src = blankos_responsive_image_src( $image_arr['id'], $image_size, $max_width );

			if ( $src ) {

				$alt = 'alt="'. $image_arr['alt'] .'"';
				$class = $class ? 'class="'. $class .'"' : '';

				return "<img $src $alt $class>";
			}
		}
	}


	/**
	 * Add the wp-editor back into WordPress after it was removed in 4.2.2.
	 *
	 * @see https://wordpress.org/support/topic/you-are-currently-editing-the-page-that-shows-your-latest-posts?replies=3#post-7130021
	 * @param $post
	 * @return void
	 */
	function fix_no_editor_on_posts_page($post) {

		if( $post->ID != get_option( 'page_for_posts' ) ) { return; }

		remove_action( 'edit_form_after_title', '_wp_posts_page_notice' );
		add_post_type_support( 'page', 'editor' );

	}
	add_action( 'edit_form_after_title', 'fix_no_editor_on_posts_page', 0 );


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
	 * Get post thumbnail alt similarly to get_post_thumbnail_url()
	 */
	function get_the_post_thumbnail_alt($post_id=null) {

		if (!$post_id) $post_id = $post->ID;
		$thumbnail_id = get_post_thumbnail_id($post_id);
		$alt = get_post_meta($thumbnail_id, '_wp_attachment_image_alt', true);
		return ($alt);
	}


	/**
	 * Echo image placholder
	 */
	function placehold($text='', $class='') {
		echo '<img src="//placehold.it/500x500?text='.$text.'" alt="placeholder" class="'. $class .'">';
	}


	/**
	 * Telephone number sanitize
	 */
	function tel($tel) {
		return str_replace(' ', '', $tel);
	}


	/**
	 * Useful for debugging..
	 */
	function pp($var) {
		echo "<pre>";
		print_r($var);
		echo "</pre>";
	}


	/**
	 * Print directly to the console in devtools..
	 */
	function pc($var) {
		echo '<script>console.log(`', print_r($var, true), '`)</script>';
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


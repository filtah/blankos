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
	define('GOOGLE_FONTS', 'Archivo+Black|Work+Sans:400,700');


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
		load_theme_textdomain( 'blankos', get_template_directory() . '/lang' );

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
	function imd_widgets_init() {

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
	add_action('widgets_init', 'imd_widgets_init');


	/**
	 * Load google fonts in head..
	 * uses GOOGLE_FONTS constant above
	 */
	function imd_load_google_fonts() {

		if( ! defined( 'GOOGLE_FONTS' ) ) return;

		echo '<!-- google fonts -->'."\n";
		echo '<link href="//fonts.googleapis.com/css?family=' . GOOGLE_FONTS . '" rel="stylesheet" type="text/css" />'."\n";

	}
	add_action( 'wp_head', 'imd_load_google_fonts' , 1);


/* Utility functions
~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~ */

	


<?php
/**
 * Portfolio Theme functions and definitions
 *
 * @link https://developer.wordpress.org/themes/basics/theme-functions/
 *
 * @package Portfolio_Theme
 */

if ( ! defined( '_S_VERSION' ) ) {
	// Replace the version number of the theme on each release.
	define( '_S_VERSION', '1.0.0' );
}

/**
 * Sets up theme defaults and registers support for various WordPress features.
 *
 * Note that this function is hooked into the after_setup_theme hook, which
 * runs before the init hook. The init hook is too late for some features, such
 * as indicating support for post thumbnails.
 */
function portfolio_theme_setup() {
	/*
		* Make theme available for translation.
		* Translations can be filed in the /languages/ directory.
		* If you're building a theme based on Portfolio Theme, use a find and replace
		* to change 'portfolio-theme' to the name of your theme in all the template files.
		*/
	load_theme_textdomain( 'portfolio-theme', get_template_directory() . '/languages' );

	// Add default posts and comments RSS feed links to head.
	add_theme_support( 'automatic-feed-links' );

	/*
		* Let WordPress manage the document title.
		* By adding theme support, we declare that this theme does not use a
		* hard-coded <title> tag in the document head, and expect WordPress to
		* provide it for us.
		*/
	add_theme_support( 'title-tag' );

	/*
		* Enable support for Post Thumbnails on posts and pages.
		*
		* @link https://developer.wordpress.org/themes/functionality/featured-images-post-thumbnails/
		*/
	add_theme_support( 'post-thumbnails' );

	// This theme uses wp_nav_menu() in one location.
	register_nav_menus(
		array(
			'menu-1' => esc_html__( 'Primary', 'portfolio-theme' ),
		)
	);

	/*
		* Switch default core markup for search form, comment form, and comments
		* to output valid HTML5.
		*/
	add_theme_support(
		'html5',
		array(
			'search-form',
			'comment-form',
			'comment-list',
			'gallery',
			'caption',
			'style',
			'script',
		)
	);

	// Set up the WordPress core custom background feature.
	add_theme_support(
		'custom-background',
		apply_filters(
			'portfolio_theme_custom_background_args',
			array(
				'default-color' => 'ffffff',
				'default-image' => '',
			)
		)
	);

	// Add theme support for selective refresh for widgets.
	add_theme_support( 'customize-selective-refresh-widgets' );

	/**
	 * Add support for core custom logo.
	 *
	 * @link https://codex.wordpress.org/Theme_Logo
	 */
	add_theme_support(
		'custom-logo',
		array(
			'height'      => 250,
			'width'       => 250,
			'flex-width'  => true,
			'flex-height' => true,
		)
	);
}
add_action( 'after_setup_theme', 'portfolio_theme_setup' );

/**
 * Set the content width in pixels, based on the theme's design and stylesheet.
 *
 * Priority 0 to make it available to lower priority callbacks.
 *
 * @global int $content_width
 */
function portfolio_theme_content_width() {
	$GLOBALS['content_width'] = apply_filters( 'portfolio_theme_content_width', 640 );
}
add_action( 'after_setup_theme', 'portfolio_theme_content_width', 0 );

/**
 * Register widget area.
 *
 * @link https://developer.wordpress.org/themes/functionality/sidebars/#registering-a-sidebar
 */
function portfolio_theme_widgets_init() {
	register_sidebar(
		array(
			'name'          => esc_html__( 'Sidebar', 'portfolio-theme' ),
			'id'            => 'sidebar-1',
			'description'   => esc_html__( 'Add widgets here.', 'portfolio-theme' ),
			'before_widget' => '<section id="%1$s" class="widget %2$s">',
			'after_widget'  => '</section>',
			'before_title'  => '<h2 class="widget-title">',
			'after_title'   => '</h2>',
		)
	);
}
add_action( 'widgets_init', 'portfolio_theme_widgets_init' );

/**
 * Enqueue scripts and styles.
 */
function portfolio_theme_scripts() {
	// Enqueue Google Fonts: Doto for headings
	wp_enqueue_style(
		'portfolio-theme-doto-font',
		'https://fonts.googleapis.com/css2?family=Doto:wght@400;700&display=swap',
		array(),
		null
	);

	wp_enqueue_style( 'portfolio-theme-style', get_stylesheet_uri(), array('portfolio-theme-doto-font'), _S_VERSION );
	wp_style_add_data( 'portfolio-theme-style', 'rtl', 'replace' );

	wp_enqueue_script( 'portfolio-theme-navigation', get_template_directory_uri() . '/js/navigation.js', array(), _S_VERSION, true );

	if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
		wp_enqueue_script( 'comment-reply' );
	}

	wp_enqueue_script(
		'portfolio-main', 
		get_template_directory_uri() . '/js/main.js',
		array(), // No dependencies
		filemtime(get_template_directory() . '/js/main.js'), // Version based on file modification time
		true // Load in footer
	);
	
	// Enqueue video autoplay script only on homepage and CPT archive
	if (is_front_page() || is_post_type_archive('projects')) {
		wp_enqueue_script(
			'portfolio-video-autoplay', 
			get_template_directory_uri() . '/js/video-autoplay.js',
			array('portfolio-main'), // Depends on main.js
			filemtime(get_template_directory() . '/js/video-autoplay.js'), 
			true // Load in footer
		);
	}
}
add_action( 'wp_enqueue_scripts', 'portfolio_theme_scripts' );

/**
 * Implement the Custom Header feature.
 */
require get_template_directory() . '/inc/custom-header.php';

/**
 * Custom template tags for this theme.
 */
require get_template_directory() . '/inc/template-tags.php';

/**
 * Functions which enhance the theme by hooking into WordPress.
 */
require get_template_directory() . '/inc/template-functions.php';

/**
 * Customizer additions.
 */
require get_template_directory() . '/inc/customizer.php';

/**
 * Load Jetpack compatibility file.
 */
if ( defined( 'JETPACK__VERSION' ) ) {
	require get_template_directory() . '/inc/jetpack.php';
}

// Register custom hierarchical taxonomies for 'projects'
function register_custom_taxonomies() {
	// Role Taxonomy (Hierarchical)
	$labels_role = array(
		'name'              => _x('Roles', 'taxonomy general name'),
		'singular_name'     => _x('Role', 'taxonomy singular name'),
		'search_items'      => __('Search Roles'),
		'all_items'         => __('All Roles'),
		'parent_item'       => __('Parent Role'),
		'parent_item_colon' => __('Parent Role:'),
		'edit_item'         => __('Edit Role'),
		'update_item'       => __('Update Role'),
		'add_new_item'      => __('Add New Role'),
		'new_item_name'     => __('New Role Name'),
		'menu_name'         => __('Roles'),
	);

	register_taxonomy('role', array('projects'), array(
		'hierarchical' => true,
		'labels' => $labels_role,
		'show_ui' => true,
		'show_admin_column' => true,
		'query_var' => true,
		'rewrite' => array('slug' => 'role'),
	));

	// Industry Taxonomy (Custom Hierarchical)
	$labels_industry = array(
		'name'              => _x('Industries', 'taxonomy general name'),
		'singular_name'     => _x('Industry', 'taxonomy singular name'),
		'search_items'      => __('Search Industries'),
		'all_items'         => __('All Industries'),
		'parent_item'       => __('Parent Industry'),
		'parent_item_colon' => __('Parent Industry:'),
		'edit_item'         => __('Edit Industry'),
		'update_item'       => __('Update Industry'),
		'add_new_item'      => __('Add New Industry'),
		'new_item_name'     => __('New Industry Name'),
		'menu_name'         => __('Industries'),
	);

	register_taxonomy('industry', array('projects'), array(
		'hierarchical' => true,
		'labels' => $labels_industry,
		'show_ui' => true,
		'show_admin_column' => true,
		'query_var' => true,
		'rewrite' => array('slug' => 'industry'),
	));
}
add_action('init', 'register_custom_taxonomies');

// Register Custom Post Type: projects
function register_projects_cpt() {
	$labels = array(
		'name'               => _x('Projects', 'post type general name'),
		'singular_name'      => _x('Project', 'post type singular name'),
		'menu_name'          => 'Projects',
		'name_admin_bar'     => 'Project',
		'add_new'            => 'Add New',
		'add_new_item'       => 'Add New Project',
		'new_item'           => 'New Project',
		'edit_item'          => 'Edit Project',
		'view_item'          => 'View Project',
		'all_items'          => 'All Projects',
		'search_items'       => 'Search Projects',
		'parent_item_colon'  => 'Parent Projects:',
		'not_found'          => 'No projects found.',
		'not_found_in_trash' => 'No projects found in Trash.'
	);

	$args = array(
		'labels'             => $labels,
		'public'             => true,
		'publicly_queryable' => true,
		'show_ui'            => true,
		'show_in_menu'       => true,
		'query_var'          => true,
		'rewrite'            => array('slug' => 'projects'),
		'capability_type'    => 'post',
		'has_archive'        => true,
		'hierarchical'       => false,
		'menu_position'      => 5,
		'supports'           => array('title', 'editor', 'thumbnail', 'excerpt'),
		'show_in_rest'       => true,
		'taxonomies'         => array('role', 'industry'),
	);

	register_post_type('projects', $args);
}
add_action('init', 'register_projects_cpt');

// Enable ACF Local JSON for version control
add_filter('acf/settings/save_json', 'acf_json_save_point');
function acf_json_save_point($path) {
    // Set save path to your theme's /acf-json folder
    return get_stylesheet_directory() . '/acf-json';
}

add_filter('acf/settings/load_json', 'acf_json_load_point');
function acf_json_load_point($paths) {
    // Clear the default path
    unset($paths[0]);
    // Add custom path
    $paths[] = get_stylesheet_directory() . '/acf-json';
    return $paths;
}

function enqueue_masonry_script() {
  wp_enqueue_script(
    'masonry-layout',
    get_template_directory_uri() . '/js/masonry.js',
    array(),
    '1.0.0',
    true
  );
}
add_action('wp_enqueue_scripts', 'enqueue_masonry_script');

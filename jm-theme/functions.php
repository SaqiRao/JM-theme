<?php
/**
 * JM-theme functions and definitions
 *
 * @link https://developer.wordpress.org/themes/basics/theme-functions/
 *
 * @package JM-theme
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
function jm_theme_setup() {
	/*
		* Make theme available for translation.
		* Translations can be filed in the /languages/ directory.
		* If you're building a theme based on JM-theme, use a find and replace
		* to change 'jm-theme' to the name of your theme in all the template files.
		*/
	load_theme_textdomain( 'jm-theme', get_template_directory() . '/languages' );

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
			'menu-1' => esc_html__( 'Primary', 'jm-theme' ),
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
			'jm_theme_custom_background_args',
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
		'site-logo',
		array(
			'height'      => 250,
			'width'       => 250,
			'flex-width'  => true,
			'flex-height' => true,
		)
	);

	remove_theme_support('custom-logo');

	// add_theme_support('custom-logo', array(
    //     'width'       => 200,  
    //     'height'      => 100,  
    //     'flex-width'  => true, 
    //     'flex-height' => true, 
    // ));
}
add_action( 'after_setup_theme', 'jm_theme_setup' );



/**
 * Set the content width in pixels, based on the theme's design and stylesheet.
 *
 * Priority 0 to make it available to lower priority callbacks.
 *
 * @global int $content_width
 */
function jm_theme_content_width() {
	$GLOBALS['content_width'] = apply_filters( 'jm_theme_content_width', 640 );
}
add_action( 'after_setup_theme', 'jm_theme_content_width', 0 );

/**
 * Register widget area.
 *
 * @link https://developer.wordpress.org/themes/functionality/sidebars/#registering-a-sidebar
 */
function jm_theme_widgets_init() {
	register_sidebar(
		array(
			'name'          => esc_html__( 'Sidebar', 'jm-theme' ),
			'id'            => 'sidebar-1',
			'description'   => esc_html__( 'Add widgets here.', 'jm-theme' ),
			'before_widget' => '<section id="%1$s" class="widget %2$s">',
			'after_widget'  => '</section>',
			'before_title'  => '<h2 class="widget-title">',
			'after_title'   => '</h2>',
		)
	);
}
add_action( 'widgets_init', 'jm_theme_widgets_init' );

/**
 * Enqueue scripts and styles.
 */
function jm_theme_scripts() {
	wp_enqueue_style( 'jm-theme-style', get_stylesheet_uri(), array(), _S_VERSION );
	wp_style_add_data( 'jm-theme-style', 'rtl', 'replace' );

	wp_enqueue_script( 'jm-theme-navigation', get_template_directory_uri() . '/js/navigation.js', array(), _S_VERSION, true );

	if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
		wp_enqueue_script( 'comment-reply' );
	}

	   // Enqueue main script
	   wp_enqueue_script(
        'post-rating-js',
        get_template_directory_uri() . '/js/post-rating.js', // Change path if needed
        ['jquery'], // Dependency
        '1.0',
        true // Load in footer
    );

	    // Enqueue Bootstrap CSS
		wp_enqueue_style('bootstrap-css', 'https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css', false, '4.5.2', 'all');

		// Enqueue Bootstrap JS (with jQuery dependency)
		wp_enqueue_script('bootstrap-js', 'https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.bundle.min.js', array('jquery'), '4.5.2', true);

    // Pass AJAX URL to the script
    wp_localize_script('post-rating-js', 'ajaxurl', [
        'ajax_url' => admin_url('admin-ajax.php'),
    ]);

	    // Enqueue the CSS file
		wp_enqueue_style(
			'post-rating-css',
			get_template_directory_uri() . '/css/post-rating.css', // File path
			[],
			'1.0' // Version
		);
}
add_action( 'wp_enqueue_scripts', 'jm_theme_scripts' );

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

function save_post_rating() {
    // Ensure user is logged in
    if (!is_user_logged_in()) {
        wp_send_json_error(['message' => 'You must be logged in to rate a post.']);
    }

    // Get user and post data
    $user_id = get_current_user_id();
    $post_id = intval($_POST['post_id']);
    $rating = intval($_POST['rating']);

    // Validate inputs
    if (!$post_id || $rating < 1 || $rating > 5) {
        wp_send_json_error(['message' => 'Invalid data provided.']);
    }

    // Get existing ratings for this post
    $post_ratings = get_post_meta($post_id, 'user_ratings', true);

    if (!$post_ratings || !is_array($post_ratings)) {
        $post_ratings = [];
    }

    // Update the rating for this user
    $post_ratings[$user_id] = $rating;

    // Save back to post meta
    update_post_meta($post_id, 'user_ratings', $post_ratings);

    wp_send_json_success(['message' => 'Thank you for your rating!']);
}
add_action('wp_ajax_save_post_rating', 'save_post_rating');
add_action('wp_ajax_nopriv_save_post_rating', 'save_post_rating');


// Add a meta box for displaying ratings
function add_post_ratings_meta_box() {
    add_meta_box(
        'post_ratings_meta_box',       // Unique ID
        'Post Ratings',                // Box title
        'display_post_ratings_meta_box', // Callback function
        'post',                        // Post type
        'side',                        // Context (side, normal, or advanced)
        'default'                      // Priority
    );
}
add_action('add_meta_boxes', 'add_post_ratings_meta_box');

// Display the meta box content
function display_post_ratings_meta_box($post) {
    // Get ratings from post meta
    $post_ratings = get_post_meta($post->ID, 'user_ratings', true);

    if ($post_ratings && is_array($post_ratings)) {
        echo '<ul style="margin: 0; padding: 0; list-style: none;">';
        foreach ($post_ratings as $user_id => $rating) {
            $user_info = get_userdata($user_id);
            $user_name = $user_info ? $user_info->display_name : 'Unknown User';
            echo '<li style="margin-bottom: 10px;">';
            echo '<strong>' . esc_html($user_name) . ':</strong> ';
            echo '<div class="star-rating" style="display: inline-block; margin-left: 5px;">';
            for ($i = 1; $i <= 5; $i++) {
                $star_class = $i <= $rating ? 'star-filled' : 'star-empty';
                echo '<span class="' . esc_attr($star_class) . '" style="color: ' . ($i <= $rating ? 'gold' : '#ccc') . '; font-size: 18px;">&#9733;</span>';
            }
            echo '</div>';
            echo '</li>';
        }
        echo '</ul>';
    } else {
        echo '<p>No ratings for this post yet.</p>';
    }
}

function jm_customize_css() {
    ?>
    <style type="text/css">
        body {
            color: <?php echo get_theme_mod('jm_font_color', '#000000'); ?>;
        }
    </style>
    <?php
}
add_action('wp_head', 'jm_customize_css');

function load_more_posts() {
    $paged = $_POST['page'] + 1;

    $args = array(
        'post_type' => 'post',
        'posts_per_page' => 6,
        'paged' => $paged,
    );

    $query = new WP_Query($args);

    if ($query->have_posts()) :
        while ($query->have_posts()) : $query->the_post();
    ?>
            <div class="post">
                <h2><?php the_title(); ?></h2>
                <p><?php the_excerpt(); ?></p>
            </div>
    <?php
        endwhile;
        wp_reset_postdata();
    else :
        wp_send_json(false);
    endif;

    die();
}

add_action('wp_ajax_load_more_posts', 'load_more_posts');
add_action('wp_ajax_nopriv_load_more_posts', 'load_more_posts');



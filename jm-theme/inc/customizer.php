<?php
/**
 * JM-theme Theme Customizer
 *
 * @package JM-theme
 */

/**
 * Add postMessage support for site title and description for the Theme Customizer.
 *
 * @param WP_Customize_Manager $wp_customize Theme Customizer object.
 */
function jm_theme_customize_register( $wp_customize ) {
	$wp_customize->get_setting( 'blogname' )->transport         = 'postMessage';
	$wp_customize->get_setting( 'blogdescription' )->transport  = 'postMessage';
	$wp_customize->get_setting( 'header_textcolor' )->transport = 'postMessage';

	if ( isset( $wp_customize->selective_refresh ) ) {
		$wp_customize->selective_refresh->add_partial(
			'blogname',
			array(
				'selector'        => '.site-title a',
				'render_callback' => 'jm_theme_customize_partial_blogname',
			)
		);
		$wp_customize->selective_refresh->add_partial(
			'blogdescription',
			array(
				'selector'        => '.site-description',
				'render_callback' => 'jm_theme_customize_partial_blogdescription',
			)
		);
	}

	// Add setting for the logo using 'jm_theme_logo'
	$wp_customize->add_setting('jm_theme_logo', array(
		'default' => '',
		'transport' => 'refresh',
	));

	// Add control for the logo using 'jm_theme_logo'
	$wp_customize->add_control(new WP_Customize_Image_Control($wp_customize, 'jm_theme_logo_control', array(
		'label' => __('JM Theme Logo', 'theme-textdomain'),
		'section' => 'title_tagline',
		'settings' => 'jm_theme_logo',
		'description' => __('Upload your JM Theme site logo.', 'theme-textdomain'),
	)));

	// Add Section for Fonts
	$wp_customize->add_section('jm_font_section', array(
		'title'    => __('Font Settings', 'jm-theme'),
		'priority' => 30,
	));

	// Add Font Color Setting
	$wp_customize->add_setting('jm_font_color', array(
		'default'   => '#000000', 
		'transport' => 'refresh', 
	));

	// Add Font Color Control
	$wp_customize->add_control(new WP_Customize_Color_Control($wp_customize, 'jm_font_color_control', array(
		'label'    => __('Font Color', 'jm-theme'),
		'section'  => 'jm_font_section',
		'settings' => 'jm_font_color',
	)));
}
add_action( 'customize_register', 'jm_theme_customize_register' );

/**
 * Render the site title for the selective refresh partial.
 *
 * @return void
 */
function jm_theme_customize_partial_blogname() {
	bloginfo( 'name' );
}

/**
 * Render the site tagline for the selective refresh partial.
 *
 * @return void
 */
function jm_theme_customize_partial_blogdescription() {
	bloginfo( 'description' );
}

/**
 * Binds JS handlers to make Theme Customizer preview reload changes asynchronously.
 */
function jm_theme_customize_preview_js() {
	wp_enqueue_script( 'jm-theme-customizer', get_template_directory_uri() . '/js/customizer.js', array( 'customize-preview' ), _S_VERSION, true );
}
add_action( 'customize_preview_init', 'jm_theme_customize_preview_js' );

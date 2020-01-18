<?php
/**
 * Acoustics Theme Customizer
 *
 *
 * @author      CodeGearThemes
 * @category    WordPress
 * @package     Acoustics
 * @version     1.0.0
 *
 */

/**
 * Add postMessage support for site title and description for the Theme Customizer.
 *
 * @param WP_Customize_Manager $wp_customize Theme Customizer object.
 */
function acoustics_customize_register( $wp_customize ) {
  $wp_customize->get_setting( 'blogname' )->transport         = 'postMessage';
	$wp_customize->get_setting( 'blogdescription' )->transport  = 'postMessage';
	$wp_customize->get_setting( 'header_textcolor' )->transport = 'postMessage';

	$wp_customize->register_section_type( 'Acoustics_Customize_Control_Premium' );
	$wp_customize->add_section( new Acoustics_Customize_Control_Premium( $wp_customize, 'premium', array(
				'title'  =>    esc_html__('Acoustic Premium Version', 'acoustics'),
				'button' => esc_html__( 'Upgrade Now','acoustics' ),
				'link'   => esc_url( 'https://codegearthemes.com/products/acoustic-pro' ),
				'priority' => 0,
			)
		)
	);


	require get_template_directory() . '/inc/core/acoustics-customizer.php';


	if ( isset( $wp_customize->selective_refresh ) ) {
		$wp_customize->selective_refresh->add_partial( 'blogname', array(
			'selector'        => '.site-title a',
			'render_callback' => 'acoustics_customize_partial_blogname',
		) );
		$wp_customize->selective_refresh->add_partial( 'blogdescription', array(
			'selector'        => '.site-description',
			'render_callback' => 'acoustics_customize_partial_blogdescription',
		) );
}
}

add_action( 'customize_register', 'acoustics_customize_register' );

/**
 * Render the site title for the selective refresh partial.
 *
 * @return void
 */
function acoustics_customize_partial_blogname() {
  bloginfo( 'name' );
}

/**
 * Render the site tagline for the selective refresh partial.
 *
 * @return void
 */
function acoustics_customize_partial_blogdescription() {
  bloginfo( 'description' );
}

/**
 * Binds JS handlers to make Theme Customizer preview reload changes asynchronously.
 */
function acoustics_customize_preview_js() {
  wp_enqueue_script( 'acoustics-customizer', get_template_directory_uri() . '/assets/admin/js/customizer.js', array( 'customize-preview' ), '20151215', true );
}

add_action( 'customize_preview_init', 'acoustics_customize_preview_js' );

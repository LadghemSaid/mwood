<?php
/**
 * Register widget area.
 *
 * @author      CodeGearThemes
 * @category    WordPress
 * @package     Acoustics
 * @version     1.0.0
 *
 */
function acoustics_widgets_init() {
  register_sidebar( array(
		'name'          => esc_html__( 'Sidebar', 'acoustics' ),
		'id'            => 'sidebar',
		'description'   => esc_html__( 'Add widgets here.', 'acoustics' ),
		'before_widget' => '<div id="%1$s" class="widget %2$s">',
		'after_widget'  => '</div>',
		'before_title'  => '<h3 class="widget-title"><span>',
		'after_title'   => '</span></h3>',
	) );

	if ( class_exists( 'WooCommerce' ) ) {
		register_sidebar( array(
			'name'          => esc_html__( 'Shop Sidebar', 'acoustics' ),
			'id'            => 'sidebar-shop',
			'description'   => esc_html__( 'Add widgets here to display content in Shop page.', 'acoustics' ),
			'before_widget' => '<div id="%1$s" class="widget %2$s">',
			'after_widget'  => '</div>',
			'before_title'  => '<h3 class="widget-title"><span>',
			'after_title'   => '</span></h3>',
		) );
}

register_sidebar(array(
		'name' => esc_html__('Footer Column I ', 'acoustics'),
		'id' => 'footer-column-1',
		'description' => esc_html__('Add widgets here to display to displays content on the top of the footer section.', 'acoustics'),
		'before_widget' => '<div id="%1$s" class="widget widget_recent_entries %2$s">',
		'after_widget' => '</div>',
		'before_title' => '<h3 class="title"><span>',
		'after_title' => '</span></h3>',
	));

	register_sidebar(array(
		'name' => esc_html__('Footer Column II ', 'acoustics'),
		'id' => 'footer-column-2',
		'description' => esc_html__('Add widgets here to display to displays content on the top of the footer section.', 'acoustics'),
		'before_widget' => '<div id="%1$s" class="widget widget_recent_entries %2$s">',
		'after_widget' => '</div>',
		'before_title' => '<h3 class="title"><span>',
		'after_title' => '</span></h3>',
	));

	register_sidebar(array(
		'name' => esc_html__('Footer Column III', 'acoustics'),
		'id' => 'footer-column-3',
		'description' => esc_html__('Add widgets here to display to displays content on the top of the footer section.', 'acoustics'),
		'before_widget' => '<div id="%1$s" class="widget widget_recent_entries %2$s">',
		'after_widget' => '</div>',
		'before_title' => '<h3 class="title"><span>',
		'after_title' => '</span></h3>',
	));

	register_sidebar(array(
		'name' => esc_html__('Footer Column IV', 'acoustics'),
		'id' => 'footer-column-4',
		'description' => esc_html__('Add widgets here to display to displays content on the top of the footer section.', 'acoustics'),
		'before_widget' => '<div id="%1$s" class="widget widget_recent_entries %2$s">',
		'after_widget' => '</div>',
		'before_title' => '<h3 class="title"><span>',
		'after_title' => '</span></h3>',
	));

	register_sidebar(array(
		'name' => esc_html__('Footer Column V', 'acoustics'),
		'id' => 'footer-column-5',
		'description' => esc_html__('Add widgets here to display to displays content on the top of the footer section.', 'acoustics'),
		'before_widget' => '<div id="%1$s" class="widget widget_recent_entries %2$s">',
		'after_widget' => '</div>',
		'before_title' => '<h3 class="title"><span>',
		'after_title' => '</span></h3>',
	));
}

add_action( 'widgets_init', 'acoustics_widgets_init' );

<?php
/**
 * Acoustics Theme Customizer
 *
 * @author      CodeGearThemes
 * @category    WordPress
 * @package     Acoustics
 * @version     1.0.0
 *
 */

/*-------------------------------------
 #Social
---------------------------------------*/
$wp_customize->add_panel( 'acoustics_social_panel', array(
	   'title' => esc_html__( 'Social Profiles', 'acoustics' ),
	   'description' => esc_html__( 'Social settings', 'acoustics' ),
	   'priority' => 15,
	)
);

$wp_customize->add_section( 'acoustics_social_section', array(
	'title'          => esc_html__( 'Social Links', 'acoustics' ),
	'capability'     => 'edit_theme_options',
	'theme_supports' => '',
	'panel'			 => 'acoustics_social_panel',
	'priority'       => 10,
));

$social_icons = array( 'twitter', 'facebook', 'linkedin', 'instagram','pinterest', 'youtube' );
foreach( $social_icons as $icon) {
  $label = ucfirst($icon);
  $wp_customize->add_setting( 'acoustics_'.$icon.'_url', array(
	'sanitize_callback' => 'esc_url_raw'
  ));

  $wp_customize->add_control( 'acoustics_'.$icon.'_url', array(
	'label'         => esc_html( $label ),
	'type'          => 'url',
	'section'       => 'acoustics_social_section',
  ));
}

/*-------------------------------------
  #Landing Page
---------------------------------------*/
$wp_customize->add_panel( 'acoustics_landing_panel', array(
	   'title' => esc_html__( 'Home Sections', 'acoustics' ),
	   'description' => esc_html__( 'Home / Landing page settings', 'acoustics' ),
	   'priority' => 25,
	)
);

$wp_customize->get_section('header_image')->panel = 'acoustics_landing_panel';
$wp_customize->get_section('header_image')->title = esc_html__( 'Hero Section', 'acoustics');
$wp_customize->get_section('header_image')->priority = 5;

$wp_customize->add_setting( 'acoustics_hero_section_enable', array(
		'default'             => false,
		'transport' 		  => 'refresh',
		'sanitize_callback'   => 'acoustics_sanitize_checkbox',
	)
);

$wp_customize->add_control( 'acoustics_hero_section_enable' , array(
		'label'         => esc_html__( 'Enable Section', 'acoustics' ),
		'type'			=> 'checkbox',
		'section'       => 'header_image',
		'priority'      => 1,
	)
);

$wp_customize->add_setting( 'acoustics_hero_section_title', array(
	 'sanitize_callback' => 'sanitize_text_field'
	)
);

$wp_customize->add_control( 'acoustics_hero_section_title',
    array(
        'label'    => esc_html__( 'Caption Title', 'acoustics' ),
        'section'  => 'header_image',
        'settings' => 'acoustics_hero_section_title',
        'type'     => 'text',
		'priority'      => 15,
    )
);

$wp_customize->add_setting( 'acoustics_hero_section_details', array(
	 'sanitize_callback' => 'sanitize_text_field'
	)
);

$wp_customize->add_control( 'acoustics_hero_section_details', array(
        'label'    => esc_html__( 'Caption Details', 'acoustics' ),
        'section'  => 'header_image',
        'settings' => 'acoustics_hero_section_details',
        'type'     => 'text',
		'priority'      => 20,
    )
);

$wp_customize->add_setting( 'acoustics_hero_section_link', array(
	 'sanitize_callback' => 'esc_url_raw'
	)
);

$wp_customize->add_control( 'acoustics_hero_section_link',
    array(
        'label'    => esc_html__( 'Button Link', 'acoustics' ),
        'section'  => 'header_image',
        'settings' => 'acoustics_hero_section_link',
        'type'     => 'url',
		'priority'      => 25,
    )
);

$wp_customize->add_section( 'acoustics_featured_section', array(
		'title'      =>  esc_html__('Featured Section', 'acoustics'),
		'priority'   =>  5,
		'panel' 	 => 'acoustics_landing_panel'
	)
);

$wp_customize->add_setting( 'acoustics_featured_section_enable', array(
		'default'             => false,
		'transport' 		  => 'refresh',
		'sanitize_callback'   => 'acoustics_sanitize_checkbox',
	)
);

$wp_customize->add_control( 'acoustics_featured_section_enable' , array(
		'label'         => esc_html__( 'Enable Section', 'acoustics' ),
		'type'			=> 'checkbox',
		'section'       => 'acoustics_featured_section',
		'priority'      => 5,
	)
);

$acoustic_woocommerce = false;
if( class_exists( 'WooCommerce' ) ):
	$acoustic_woocommerce = true;
	$acoustics_product_collections = acoustics_product_categories();
endif;

if( $acoustic_woocommerce ):
	for( $i = 0; $i < 3; $i++) {

		$wp_customize->add_setting( 'acoustics_featured_categories_'.$i , array(
				'default'     		=> 0,
				'transport' => 'refresh',
				'sanitize_callback' => 'absint',
			)
		);

		$wp_customize->add_control( 'acoustics_featured_categories_'.$i , array(
				'label'       => esc_html__('Category', 'acoustics'),
				'description' => esc_html__('Display product category with link.', 'acoustics'),
				'section'     => 'acoustics_featured_section',
				'type'        => 'select',
				'choices'     => $acoustics_product_collections,
			)
		);

	}
else:

  $wp_customize->add_setting( 'acoustics_featured_info', array(
	    'sanitize_callback'    => 'sanitize_text_field'
	  )
  );

  $wp_customize->add_control( new Acoustics_Customize_Control_Information( $wp_customize,'acoustics_featured_info', array(
	    'label'           => esc_html__('Information','acoustics'),
	    'description'     => esc_html__('Install WooCommerce Plugin to list more options.','acoustics'),
		'section'         => 'acoustics_featured_section',
	  )
	)
  );

endif;

$wp_customize->add_section( 'acoustics_newarrival_section', array(
		'title'      =>  esc_html__('New Arrivals', 'acoustics'),
		'priority'   =>  10,
		'panel' 	 => 'acoustics_landing_panel'
	)
);

$wp_customize->add_setting( 'acoustics_newarrival_section_enable', array(
		'default'             => false,
		'sanitize_callback'   => 'acoustics_sanitize_checkbox',
	)
);

$wp_customize->add_control( 'acoustics_newarrival_section_enable' , array(
		'label'         => esc_html__( 'Enable Section', 'acoustics' ),
		'type'			=> 'checkbox',
		'section'       => 'acoustics_newarrival_section',
		'priority'      => 5,
	)
);

if( $acoustic_woocommerce ):
	$wp_customize->add_setting( 'acoustics_newarrival_collection' , array(
			'default'     => 0,
			'sanitize_callback' => 'absint',
		)
	);

	$wp_customize->add_control( 'acoustics_newarrival_collection' , array(
			'label'       => esc_html__('Select Category', 'acoustics'),
			'description' => esc_html__('Display product from the selected product category as new arrival.', 'acoustics'),
			'section'     => 'acoustics_newarrival_section',
			'type'        => 'select',
			'choices'     => $acoustics_product_collections,
		)
	);
else:

	$wp_customize->add_setting( 'acoustics_newarrival_info', array(
   	   'sanitize_callback'    => 'sanitize_text_field'
   	 )
    );

    $wp_customize->add_control( new Acoustics_Customize_Control_Information( $wp_customize,'acoustics_newarrival_info', array(
   	   'label'           => esc_html__('Information','acoustics'),
   	   'description'     => esc_html__('Install WooCommerce Plugin to list more options.','acoustics'),
   	   'section'         => 'acoustics_newarrival_section',
   	 )
      )
    );

endif;

$wp_customize->add_section( 'acoustics_product_category_section', array(
		'title'      =>  esc_html__('Featured Category', 'acoustics'),
		'priority'   =>  15,
		'panel' 	 => 'acoustics_landing_panel'
	)
);

$wp_customize->add_setting( 'acoustics_product_category_section_enable', array(
		'default'             => false,
		'transport' 		  => 'refresh',
		'sanitize_callback'   => 'acoustics_sanitize_checkbox',
	)
);

$wp_customize->add_control( 'acoustics_product_category_section_enable' , array(
		'label'         => esc_html__( 'Enable Section', 'acoustics' ),
		'type'			=> 'checkbox',
		'section'       => 'acoustics_product_category_section',
		'priority'      => 5,
	)
);

if( $acoustic_woocommerce ):
	for( $i = 0; $i < 4; $i++) {
  		$wp_customize->add_setting( 'acoustics_product_categories_'.$i , array(
				'default'     		=> 0,
				'transport' => 'refresh',
				'sanitize_callback' => 'absint',
			)
		);

		$wp_customize->add_control( 'acoustics_product_categories_'.$i , array(
				'label'       => esc_html__('Category', 'acoustics'),
				'description' => esc_html__('Display product category.', 'acoustics'),
				'section'     => 'acoustics_product_category_section',
				'type'        => 'select',
				'choices'     => $acoustics_product_collections,
			)
		);
	}
else:

	$wp_customize->add_setting( 'acoustics_product_categories_info', array(
   	   'sanitize_callback'    => 'sanitize_text_field'
   	 )
    );

    $wp_customize->add_control( new Acoustics_Customize_Control_Information( $wp_customize,'acoustics_product_categories_info', array(
   	   'label'           => esc_html__('Information','acoustics'),
   	   'description'     => esc_html__('Install WooCommerce Plugin to list more options.','acoustics'),
   	   'section'         => 'acoustics_product_category_section',
   	 )
      )
    );

endif;

$wp_customize->add_section( 'acoustics_bestseller_section', array(
		'title'      =>  esc_html__('Best Sellers', 'acoustics'),
		'priority'   =>  20,
		'panel' 	 => 'acoustics_landing_panel'
	)
);

$wp_customize->add_setting( 'acoustics_bestseller_section_enable', array(
		'default'             => false,
		'sanitize_callback'   => 'acoustics_sanitize_checkbox',
	)
);

$wp_customize->add_control( 'acoustics_bestseller_section_enable' , array(
		'label'         => esc_html__( 'Enable Section', 'acoustics' ),
		'type'			=> 'checkbox',
		'section'       => 'acoustics_bestseller_section',
		'priority'      => 5,
	)
);

if( $acoustic_woocommerce ):
	$wp_customize->add_setting( 'acoustics_bestseller_collection' , array(
			'default'     => 0,
			'sanitize_callback' => 'absint',
		)
	);

	$wp_customize->add_control( 'acoustics_bestseller_collection' , array(
			'label'       => esc_html__('Select Category', 'acoustics'),
			'description' => esc_html__('Display product from the selected product category as new arrival.', 'acoustics'),
			'section'     => 'acoustics_bestseller_section',
			'type'        => 'select',
			'choices'     => $acoustics_product_collections,
		)
	);
else:
	$wp_customize->add_setting( 'acoustics_bestseller_info', array(
  	 'sanitize_callback'    => 'sanitize_text_field'
     )
    );

    $wp_customize->add_control( new Acoustics_Customize_Control_Information( $wp_customize,'acoustics_bestseller_info', array(
	  	 'label'           => esc_html__('Information','acoustics'),
	  	 'description'     => esc_html__('Install WooCommerce Plugin to list more options.','acoustics'),
	  	 'section'         => 'acoustics_bestseller_section',
	    )
	  )
    );
endif;

$wp_customize->add_section( 'acoustics_product_category_grid_section', array(
		'title'      =>  esc_html__('Category Grid', 'acoustics'),
		'priority'   =>  20,
		'panel' 	 => 'acoustics_landing_panel'
	)
);

$wp_customize->add_setting( 'acoustics_product_category_grid_section_enable', array(
		'default'             => false,
		'transport' 		  => 'refresh',
		'sanitize_callback'   => 'acoustics_sanitize_checkbox',
	)
);

$wp_customize->add_control( 'acoustics_product_category_grid_section_enable' , array(
		'label'         => esc_html__( 'Enable Section', 'acoustics' ),
		'type'			=> 'checkbox',
		'section'       => 'acoustics_product_category_grid_section',
		'priority'      => 5,
	)
);

if( $acoustic_woocommerce ):
  $collection = 6;
  for( $i = 0; $i < $collection; $i++) {
       $wp_customize->add_setting( 'acoustics_product_categories_grid_'.$i , array(
				'default'     		=> 0,
				'transport' => 'refresh',
				'sanitize_callback' => 'absint',
			)
		);

		$wp_customize->add_control( 'acoustics_product_categories_grid_'.$i , array(
				'label'       => esc_html__('Category', 'acoustics'),
				'description' => esc_html__('Display product category.', 'acoustics'),
				'section'     => 'acoustics_product_category_grid_section',
				'type'        => 'select',
				'choices'     => $acoustics_product_collections,
			)
		);
	}
else:
	$wp_customize->add_setting( 'acoustics_category_grid_info', array(
  	 'sanitize_callback'    => 'sanitize_text_field'
     )
    );

    $wp_customize->add_control( new Acoustics_Customize_Control_Information( $wp_customize,'acoustics_category_grid_info', array(
	  	 'label'           => esc_html__('Information','acoustics'),
	  	 'description'     => esc_html__('Install WooCommerce Plugin to list more options.','acoustics'),
	  	 'section'         => 'acoustics_product_category_grid_section',
	    )
	  )
    );
endif;


$wp_customize->add_section( 'acoustics_values_section', array(
		'title'      =>  esc_html__('Proposition', 'acoustics'),
		'priority'   =>  25,
		'panel' 	 => 'acoustics_landing_panel'
	)
);

$wp_customize->add_setting( 'acoustics_values_section_enable', array(
		'default'             => false,
		'transport' 		  => 'refresh',
		'sanitize_callback'   => 'acoustics_sanitize_checkbox',
	)
);

$wp_customize->add_control( 'acoustics_values_section_enable' , array(
		'label'         => esc_html__( 'Enable Section', 'acoustics' ),
		'type'			=> 'checkbox',
		'section'       => 'acoustics_values_section',
		'priority'      => 5,
	)
);

$acoustics_collections = acoustics_categories();
$wp_customize->add_setting( 'acoustics_values_category' , array(
		'default'     		=> 0,
		'transport' => 'refresh',
		'sanitize_callback' => 'absint',
	)
);

$wp_customize->add_control( 'acoustics_values_category', array(
		'label'       => esc_html__('Select Category', 'acoustics'),
		'description' => esc_html__('Selected cateogry post will be shown as value section content & image.', 'acoustics'),
		'section'     => 'acoustics_values_section',
		'type'        => 'select',
		'choices'     => $acoustics_collections,
	)
);


/*-------------------------------------
  #Footer
---------------------------------------*/
$wp_customize->add_panel( 'acoustics_footer_panel', array(
   'title' => esc_html__( 'Footer Settings', 'acoustics' ),
   'description' => esc_html__( 'Footer section & settings', 'acoustics' ),
   'priority' => 30,
	)
);

$wp_customize->add_section( 'acoustics_footer_section', array(
	'title'          => esc_html__( 'Copyright Setting', 'acoustics' ),
	'capability'     => 'edit_theme_options',
	'theme_supports' => '',
	'panel'			 => 'acoustics_footer_panel',
	'priority'       => 5,
));

$wp_customize->add_setting( 'acoustics_footer_copyright', array(
  'sanitize_callback' => 'sanitize_text_field',
));

$wp_customize->add_control( 'acoustics_footer_copyright', array(
  'label'     => esc_html__( 'Copyright Text', 'acoustics' ),
  'type'      => 'text',
  'section'   => 'acoustics_footer_section'
));

/*-------------------------------------
  #Layout
---------------------------------------*/
$wp_customize->add_panel( 'acoustics_layout_panel', array(
   'title' => esc_html__( 'Layout Settings', 'acoustics' ),
   'description' => esc_html__( 'Archive, post & page layout settings', 'acoustics' ),
   'priority' => 35,
	)
);


$wp_customize->add_section( 'acoustics_archive_section', array(
	'title'          => esc_html__( 'Archive Sidebar', 'acoustics' ),
	'capability'     => 'edit_theme_options',
	'theme_supports' => '',
	'panel'			 => 'acoustics_layout_panel',
	'priority'       => 5,
	)
);

$wp_customize->add_setting('acoustics_archive_layout', array(
  'default'  =>      'left-sidebar',
  'sanitize_callback' => 'acoustics_sanitize_radioimage'
  )
);

$wp_customize->add_control( new Acoustics_Customize_Control_Radio_Image( $wp_customize,'acoustics_archive_layout', array(
  'section'       =>      'acoustics_archive_section',
  'label'         =>      esc_html__('Archive Sidebar', 'acoustics'),
  'type'          =>      'radio-image',
  'choices'       =>      array(
	    'left-sidebar'  => '%s/assets/src/layout/left-sidebar.png',
		'no-sidebar'    => '%s/assets/src/layout/no-sidebar.png',
	    'right-sidebar' => '%s/assets/src/layout/right-sidebar.png',
	  )
  ))
);

$wp_customize->add_section( 'acoustics_page_section', array(
	'title'          => esc_html__( 'Page Sidebar', 'acoustics' ),
	'capability'     => 'edit_theme_options',
	'theme_supports' => '',
	'panel'			 => 'acoustics_layout_panel',
	'priority'       => 10,
	)
);

$wp_customize->add_setting('acoustics_page_layout', array(
  'default'  =>      'no-sidebar',
  'sanitize_callback' => 'acoustics_sanitize_radioimage'
  )
);

$wp_customize->add_control( new Acoustics_Customize_Control_Radio_Image( $wp_customize,'acoustics_page_layout', array(
  'section'       =>      'acoustics_page_section',
  'label'         =>      esc_html__('Page Sidebar', 'acoustics'),
  'type'          =>      'radio-image',
  'choices'       =>      array(
	    'left-sidebar'  => '%s/assets/src/layout/left-sidebar.png',
		'no-sidebar'    => '%s/assets/src/layout/no-sidebar.png',
	    'right-sidebar' => '%s/assets/src/layout/right-sidebar.png',
	  )
  ))
);

$wp_customize->add_section( 'acoustics_post_section', array(
	'title'          => esc_html__( 'Post Sidebar', 'acoustics' ),
	'capability'     => 'edit_theme_options',
	'theme_supports' => '',
	'panel'			 => 'acoustics_layout_panel',
	'priority'       => 15,
	)
);

$wp_customize->add_setting('acoustics_post_layout', array(
  'default'  =>      'right-sidebar',
  'sanitize_callback' => 'acoustics_sanitize_radioimage'
  )
);

$wp_customize->add_control( new Acoustics_Customize_Control_Radio_Image( $wp_customize,'acoustics_post_layout', array(
  'section'       =>      'acoustics_post_section',
  'label'         =>      esc_html__('Post Sidebar', 'acoustics'),
  'type'          =>      'radio-image',
  'choices'       =>      array(
	    'left-sidebar'  => '%s/assets/src/layout/left-sidebar.png',
		'no-sidebar'    => '%s/assets/src/layout/no-sidebar.png',
	    'right-sidebar' => '%s/assets/src/layout/right-sidebar.png',
	  )
  ))
);

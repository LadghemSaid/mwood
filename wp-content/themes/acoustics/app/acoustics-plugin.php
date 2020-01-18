<?php
/**
* Plugins Activation
*
* @author      CodeGearThemes
* @category    WordPress
* @package     Acoustics
* @version     1.0.0
*/
function acoustics_register_required_plugins() {
  $plugins = array(
        array(
            'name'      => __( 'Everest Forms', 'acoustics' ),
            'slug'      => 'everest-forms',
            'required'  => false,
			'force_activation'   => false,
            'force_deactivation' => false,
        ),
		array(
            'name'      => __( 'WooCommerce', 'acoustics' ),
            'slug'      => 'woocommerce',
            'required'  => false,
            'force_activation'   => false,
            'force_deactivation' => false,
        ),
    );
    $config = array(
        'id'           => 'acoustics',                 // Unique ID for hashing notices for multiple instances of TGMPA.
        'default_path' => '',                      // Default absolute path to bundled plugins.
        'menu'         => 'tgmpa-install-plugins', // Menu slug.
        'has_notices'  => true,                    // Show admin notices or not.
        'dismissable'  => true,                    // If false, a user cannot dismiss the nag message.
        'dismiss_msg'  => '',                      // If 'dismissable' is false, this message will be output at top of nag.
        'is_automatic' => true,                   // Automatically activate plugins after installation or not.
        'message'      => '',                      // Message to output right before the plugins table.
    );

    tgmpa( $plugins, $config );
}

add_action( 'tgmpa_register', 'acoustics_register_required_plugins' );

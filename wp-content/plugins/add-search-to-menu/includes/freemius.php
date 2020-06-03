<?php

/**
 * Loads Freemius SDK
 */

if ( !defined( 'ABSPATH' ) ) {
    exit;
    // Exits if accessed directly.
}

// Creates a helper function for easy Freemius SDK access.
function is_fs()
{
    global  $is_fs ;
    
    if ( !isset( $is_fs ) ) {
        // Include Freemius SDK.
        require_once dirname( __FILE__ ) . '/freemius/start.php';
        $is_fs = fs_dynamic_init( array(
            'id'              => '2086',
            'slug'            => 'add-search-to-menu',
            'type'            => 'plugin',
            'public_key'      => 'pk_e05b040b84ff5014d0f0955127743',
            'is_premium'      => false,
            'premium_suffix'  => '',
            'has_addons'      => false,
            'has_paid_plans'  => true,
            'has_affiliation' => 'selected',
            'menu'            => array(
            'slug'        => 'ivory-search',
            'support'     => false,
            'affiliation' => false,
        ),
            'is_live'         => true,
        ) );
    }
    
    return $is_fs;
}

// Init Freemius.
is_fs();
// Signal that SDK was initiated.
do_action( 'is_fs_loaded' );
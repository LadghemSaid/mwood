<?php

namespace DgoraWcas\Admin;

// Exit if accessed directly
use  DgoraWcas\Settings ;
if ( !defined( 'ABSPATH' ) ) {
    exit;
}
class AdminMenu
{
    public function __construct()
    {
        add_action( 'admin_menu', array( $this, 'addMenu' ), 20 );
    }
    
    /**
     * Add meun items
     *
     * @return void
     */
    public function addMenu()
    {
        add_submenu_page(
            'woocommerce',
            __( 'Ajax Search for WooCommerce', 'ajax-search-for-woocommerce' ),
            __( 'AJAX search bar', 'ajax-search-for-woocommerce' ),
            'manage_options',
            'dgwt_wcas_settings',
            array( $this, 'settingsPage' )
        );
        add_submenu_page(
            'dgwt_wcas_settings',
            'Ajax Search for WooCommerce Debug',
            'Ajax Search for WooCommerce [Hidden]',
            'manage_options',
            'dgwt_wcas_debug',
            array( $this, 'debugPage' )
        );
    }
    
    /**
     * Settings page
     *
     * @return void
     */
    public function settingsPage()
    {
        Settings::output();
    }
    
    /**
     * Debug page
     *
     * @return void
     */
    public function debugPage()
    {
    }

}
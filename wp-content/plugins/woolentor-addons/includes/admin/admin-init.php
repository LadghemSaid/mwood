<?php

if( ! defined( 'ABSPATH' ) ) exit(); // Exit if accessed directly

class Woolentor_Admin_Setting{

    public function __construct(){
        add_action('admin_enqueue_scripts', array( $this, 'woolentor_enqueue_admin_scripts' ) );
        $this->woolentor_admin_settings_page();
    }

    /*
    *  Setting Page
    */
    public function woolentor_admin_settings_page() {
        require_once('include/class.settings-api.php');
        require_once('include/template-library.php');
        if( is_plugin_active('woolentor-addons-pro/woolentor_addons_pro.php') ){
            require_once WOOLENTOR_ADDONS_PL_PATH_PRO.'includes/admin/admin-setting.php';
        }else{
            require_once('include/admin-setting.php');
        }
    }

    /*
    *  Enqueue admin scripts
    */
    public function woolentor_enqueue_admin_scripts( $hook ){
        
        wp_enqueue_style( 'woolentor-admin', WOOLENTOR_ADDONS_PL_URL . 'includes/admin/assets/css/admin_optionspanel.css', FALSE, WOOLENTOR_VERSION );

        // wp core styles
        wp_enqueue_style( 'wp-jquery-ui-dialog' );
        // wp core scripts
        wp_enqueue_script( 'jquery-ui-dialog' );
        
        wp_enqueue_script( 'woolentor-admin-main', WOOLENTOR_ADDONS_PL_URL . 'includes/admin/assets/js/woolentor-admin.js', array( 'jquery' ), WOOLENTOR_VERSION, TRUE );
        $datalocalize = array(
            'contenttype' => woolentor_get_option( 'notification_content_type','woolentor_sales_notification_tabs', 'actual' ),
        );
        wp_localize_script( 'woolentor-admin-main', 'admin_wllocalize_data', $datalocalize );

    }

}

new Woolentor_Admin_Setting();
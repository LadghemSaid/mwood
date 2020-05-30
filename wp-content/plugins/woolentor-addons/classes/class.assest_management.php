<?php

namespace WooLentor;

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

/**
* Assest Management
*/
class Assets_Management{
    
    private static $instance = null;
    public static function instance() {
        if ( is_null( self::$instance ) ) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    function __construct(){
        $this->init();
    }

    public function init() {

        // Register Scripts
        add_action( 'wp_enqueue_scripts', [ $this, 'register_scripts' ] );

        // Frontend Scripts
        add_action( 'wp_enqueue_scripts', [ $this, 'enqueue_frontend_scripts' ] );

    }

    // Register frontend scripts
    public function register_scripts(){
        
        // Register Css file
        wp_register_style(
            'htflexboxgrid',
            WOOLENTOR_ADDONS_PL_URL . 'assets/css/htflexboxgrid.css',
            array(),
            WOOLENTOR_VERSION
        );
        
        wp_register_style(
            'simple-line-icons',
            WOOLENTOR_ADDONS_PL_URL . 'assets/css/simple-line-icons.css',
            array(),
            WOOLENTOR_VERSION
        );

        wp_register_style(
            'woolentor-widgets',
            WOOLENTOR_ADDONS_PL_URL . 'assets/css/woolentor-widgets.css',
            array(),
            WOOLENTOR_VERSION
        );

        wp_register_style(
            'slick',
            WOOLENTOR_ADDONS_PL_URL . 'assets/css/slick.css',
            array(),
            WOOLENTOR_VERSION
        );

        // Register JS file
        wp_register_script(
            'slick',
            WOOLENTOR_ADDONS_PL_URL . 'assets/js/slick.min.js',
            array('jquery'),
            WOOLENTOR_VERSION,
            TRUE
        );

        wp_register_script(
            'countdown-min',
            WOOLENTOR_ADDONS_PL_URL . 'assets/js/jquery.countdown.min.js',
            array('jquery'),
            WOOLENTOR_VERSION,
            TRUE
        );

        wp_register_script(
            'woolentor-widgets-scripts',
            WOOLENTOR_ADDONS_PL_URL . 'assets/js/woolentor-widgets-active.js',
            array('jquery'),
            WOOLENTOR_VERSION,
            TRUE
        );

    }

    // Enqueue frontend scripts
    public function enqueue_frontend_scripts() {
        // CSS File
        wp_enqueue_style( 'htflexboxgrid' );
        wp_enqueue_style( 'font-awesome' );
        wp_enqueue_style( 'simple-line-icons' );
        wp_enqueue_style( 'slick' );
        wp_enqueue_style( 'woolentor-widgets' );
        if ( is_rtl() ) {
          wp_enqueue_style(  'woolentor-widgets-rtl',  WOOLENTOR_ADDONS_PL_URL . 'assets/css/woolentor-widgets-rtl.css', array(), WOOLENTOR_VERSION );
        }

        //Localize Scripts
        $localizeargs = array(
            'woolentorajaxurl' => admin_url( 'admin-ajax.php' ),
            'ajax_nonce'       => wp_create_nonce( 'woolentor_psa_nonce' ),
        );
        wp_localize_script( 'jquery', 'woolentor_addons', $localizeargs );

        // Ajax Search
        if( woolentor_get_option( 'ajaxsearch', 'woolentor_others_tabs', 'off' ) == 'on' ){
            wp_enqueue_style(
                'woolentor-ajax-search',
                WOOLENTOR_ADDONS_PL_URL . 'assets/addons/ajax-search/css/ajax-search.css',
                WOOLENTOR_VERSION
            );
            wp_enqueue_script(
                'jquery-nicescroll',
                WOOLENTOR_ADDONS_PL_URL . 'assets/addons/ajax-search/js/jquery.nicescroll.min.js',
                array( 'jquery' ),
                WOOLENTOR_VERSION,
                TRUE
            );
            wp_enqueue_script(
                'woolentor-ajax-search',
                WOOLENTOR_ADDONS_PL_URL . 'assets/addons/ajax-search/js/ajax-search.js',
                array('jquery'),
                WOOLENTOR_VERSION,
                TRUE
            );
        }

        // Single Product Ajax Add to Cart
        if( woolentor_get_option( 'ajaxcart_singleproduct', 'woolentor_others_tabs', 'off' ) == 'on' ){
            if ( 'yes' === get_option('woocommerce_enable_ajax_add_to_cart') ) {
                wp_enqueue_script(
                    'jquery-single-product-ajax-cart',
                    WOOLENTOR_ADDONS_PL_URL . 'assets/js/single_product_ajax_add_to_cart.js',
                    array( 'jquery' ),
                    WOOLENTOR_VERSION,
                    TRUE
                );
            }
        }

    }


}

Assets_Management::instance();
<?php

/**
 * Plugin Name: AJAX Search for WooCommerce
 * Plugin URI: https://ajaxsearch.pro?utm_source=wp-admin&utm_medium=referral&utm_campaign=author_uri&utm_gen=utmdc
 * Description: The most popular WooCommerce product search. Gives your users a well-designed advanced AJAX search bar with live search suggestions.
 * Version: 1.7.1
 * Author: Damian Góra
 * Author URI: https://ajaxsearch.pro?utm_source=wp-admin&utm_medium=referral&utm_campaign=author_uri&utm_gen=utmdc
 * Text Domain: ajax-search-for-woocommerce
 * Domain Path: /languages
 * WC requires at least: 3.3
 * WC tested up to: 4.1
 *
 */
// Exit if accessed directly
if ( !defined( 'ABSPATH' ) ) {
    exit;
}

if ( !class_exists( 'DGWT_WC_Ajax_Search' ) && !function_exists( 'dgoraAsfwFs' ) ) {
    $fspath = dirname( __FILE__ ) . '/fs/config.php';
    if ( file_exists( $fspath ) ) {
        require_once $fspath;
    }
    final class DGWT_WC_Ajax_Search
    {
        private static  $instance ;
        private  $tnow ;
        public  $engine = 'native' ;
        /**
         * @var \DgoraWcas\Settings
         */
        public  $settings ;
        public  $multilingual ;
        /**
         * @var \DgoraWcas\Integrations\Themes\ThemesCompatibility
         */
        public  $themeCompatibility ;
        public  $brands ;
        public  $nativeSearch ;
        public  $tntsearch ;
        public  $tntsearchValid = false ;
        public  $tntsearchMySql ;
        public  $tntsearchMySqlValid = false ;
        public  $mobileDetect ;
        public  $searchInstances = 0 ;
        public static function getInstance()
        {
            
            if ( !isset( self::$instance ) && !self::$instance instanceof DGWT_WC_Ajax_Search ) {
                self::$instance = new DGWT_WC_Ajax_Search();
                self::$instance->constants();
                self::$instance->loadTextdomain();
                if ( !self::$instance->checkRequirements() ) {
                    return;
                }
                self::$instance->systemHooks();
                $mobileDetectExist = class_exists( 'Mobile_Detect' );
                self::$instance->autoload();
                self::$instance->mobileDetect = ( !$mobileDetectExist ? new \Detection\MobileDetect() : new \Mobile_Detect() );
                $setup = new \DgoraWcas\Setup();
                $setup->init();
                self::$instance->settings = new \DgoraWcas\Settings();
                self::$instance->hooks();
                self::$instance->multilingual = new \DgoraWcas\Multilingual();
                self::$instance->nativeSearch = new \DgoraWcas\Engines\WordPressNative\Search();
                // @TODO Temporary always use native WordPress DetailsBox engine.
                // Replace with details.php and shortinit in future releases
                new \DgoraWcas\Engines\WordPressNative\DetailsBox();
                new \DgoraWcas\Personalization();
                new \DgoraWcas\Scripts();
                $embeddingViaMenu = new \DgoraWcas\EmbeddingViaMenu();
                $embeddingViaMenu->init();
                self::$instance->themeCompatibility = new \DgoraWcas\Integrations\Themes\ThemesCompatibility();
                new \DgoraWcas\Integrations\Plugins\PluginsCompatibility();
                self::$instance->brands = new \DgoraWcas\Integrations\Brands();
                self::$instance->brands->init();
                \DgoraWcas\Shortcode::register();
                
                if ( is_admin() ) {
                    \DgoraWcas\Admin\Install::maybeInstall();
                    new \DgoraWcas\Admin\AdminMenu();
                    new \DgoraWcas\Admin\Promo\FeedbackNotice();
                    new \DgoraWcas\Admin\Promo\Upgrade();
                    new \DgoraWcas\Admin\Requirements();
                    $regenerateImages = new \DgoraWcas\Admin\RegenerateImages();
                    $regenerateImages->init();
                }
                
                new \DgoraWcas\Integrations\Solver();
            }
            
            self::$instance->tnow = time();
            return self::$instance;
        }
        
        /**
         * Constructor Function
         */
        private function __construct()
        {
            self::$instance = $this;
        }
        
        /**
         * Uninstall, Activate, Deactivate hooks
         *
         * @return void
         */
        private function systemHooks()
        {
            register_deactivation_hook( __FILE__, function () {
            } );
        }
        
        /**
         * Check requirements
         *
         * @return void
         */
        private function checkRequirements()
        {
            if ( version_compare( PHP_VERSION, '5.5.0' ) < 0 ) {
                
                if ( version_compare( PHP_VERSION, '5.3.0' ) < 0 ) {
                    add_action( 'admin_notices', array( $this, 'adminNoticeReqPhp53' ) );
                    return false;
                } else {
                    add_action( 'admin_notices', array( $this, 'adminNoticeReqPhp55' ) );
                }
            
            }
            
            if ( !class_exists( 'WooCommerce' ) || !class_exists( 'WC_AJAX' ) ) {
                add_action( 'admin_notices', array( $this, 'admin_notice_no_woocommerce' ) );
                return false;
            }
            
            return true;
        }
        
        /**
         * Notice: PHP version less than 5.3
         * @return void
         */
        public function adminNoticeReqPhp53()
        {
            ?>
            <div class="notice notice-error dgwt-wcas-notice">
                <p>
                    <?php 
            _e( '<b>AJAX Search for WooCommerce</b>: You need PHP version at least 5.3 to run this plugin. You are currently using PHP version ', 'ajax-search-for-woocommerce' );
            echo  PHP_VERSION . '.' ;
            ?>
                </p>
            </div>
            <?php 
        }
        
        /**
         * Notice: PHP version less than 5.5
         *
         * @return void
         */
        public function adminNoticeReqPhp55()
        {
            if ( defined( 'DISABLE_NAG_NOTICES' ) && DISABLE_NAG_NOTICES ) {
                return;
            }
            $screen = get_current_screen();
            if ( empty($screen->id) || $screen->id !== 'dashboard' && $screen->id !== 'plugins' ) {
                return;
            }
            if ( !empty($_GET['dgwt-wcas-php55-notice']) && $_GET['dgwt-wcas-php55-notice'] === 'dismiss' ) {
                set_transient( 'dgwt-wcas-php55-notice-dismiss', '1', 60 * 60 * 24 * 7 );
            }
            
            if ( !get_transient( 'dgwt-wcas-php55-notice-dismiss' ) ) {
                ?>
                <div class="notice notice-error dgwt-wcas-notice">
                    <p>
                        <?php 
                printf( __( "<b>AJAX Search for WooCommerce</b>:<br /> Your PHP version <b><i>%s</i></b> will not longer supported in the next plugin releases.", 'ajax-search-for-woocommerce' ), PHP_VERSION );
                _e( ' You have to update your PHP version to least 5.5 (recommended 7.2 or greater).', 'ajax-search-for-woocommerce' );
                echo  '<br />' ;
                _e( "If you cannot upgrade your PHP version yourself, you can send an email to your host.", 'ajax-search-for-woocommerce' );
                echo  '<br /><br />' ;
                echo  '<span style="font-weight:bold; color: #dc3232">' . __( 'If you do not upgrade the php version, the next plugin release will not work!', 'ajax-search-for-woocommerce' ) . '</span>' ;
                echo  '<br />' ;
                echo  '<br />' ;
                echo  '<a href="' . esc_url( add_query_arg( array(
                    'dgwt-wcas-php55-notice' => 'dismiss',
                ), $_SERVER['REQUEST_URI'] ) ) . '">' . __( 'Remind me again in week.', 'ajax-search-for-woocommerce' ) . '</a>' ;
                ?>
                    </p>
                </div>
                <?php 
            }
        
        }
        
        /**
         * Notice: requires WooCommerce
         *
         * @return void
         */
        public function admin_notice_no_woocommerce()
        {
            ?>
            <div class="notice notice-error dgwt-wcas-notice">
                <p>
                    <?php 
            printf( __( '<b>AJAX Search for WooCommerce</b> is enabled but not effective. It requires %s in order to work.', 'ajax-search-for-woocommerce' ), '<a href="https://pl.wordpress.org/plugins/woocommerce/"  target="_blank">WooCommerce</a>' );
            ?>
                </p>
            </div>
            <?php 
        }
        
        /**
         * Setup plugin constants
         *
         * @return void
         */
        private function constants()
        {
            $v = get_file_data( __FILE__, array(
                'Version' => 'Version',
            ), 'plugin' );
            $this->define( 'DGWT_WCAS_VERSION', $v['Version'] );
            $this->define( 'DGWT_WCAS_NAME', 'AJAX Search for WooCommerce' );
            $this->define( 'DGWT_WCAS_FILE', __FILE__ );
            $this->define( 'DGWT_WCAS_DIR', plugin_dir_path( __FILE__ ) );
            $this->define( 'DGWT_WCAS_URL', plugin_dir_url( __FILE__ ) );
            $this->define( 'DGWT_WCAS_SETTINGS_KEY', 'dgwt_wcas_settings' );
            $this->define( 'DGWT_WCAS_SEARCH_ACTION', 'dgwt_wcas_ajax_search' );
            $this->define( 'DGWT_WCAS_RESULT_DETAILS_ACTION', 'dgwt_wcas_result_details' );
            $this->define( 'DGWT_WCAS_GET_PRICES_ACTION', 'dgwt_wcas_get_prices' );
            $this->define( 'DGWT_WCAS_WC_AJAX_ENDPOINT', true );
            $this->define( 'DGWT_WCAS_DEBUG', false );
        }
        
        /**
         * Define constant if not already set
         *
         * @param  string $name
         * @param  string|bool $value
         *
         * @return void
         */
        private function define( $name, $value )
        {
            if ( !defined( $name ) ) {
                define( $name, $value );
            }
        }
        
        /**
         * PSR-4 autoload
         *
         * @return void
         */
        public function autoload()
        {
            $suffix = '';
            if ( file_exists( DGWT_WCAS_DIR . 'vendor' . $suffix . '/autoload.php' ) ) {
                require_once DGWT_WCAS_DIR . 'vendor' . $suffix . '/autoload.php';
            }
            require_once DGWT_WCAS_DIR . 'widget.php';
        }
        
        /**
         * Actions and filters
         *
         * @return void
         */
        private function hooks()
        {
            add_action( 'admin_init', array( $this, 'adminScripts' ), 8 );
        }
        
        /**
         * Enqueue admin sripts
         *
         * @return void
         */
        public function adminScripts()
        {
            // Register CSS
            wp_register_style(
                'dgwt-wcas-admin-style',
                DGWT_WCAS_URL . 'assets/css/admin-style.css',
                array(),
                DGWT_WCAS_VERSION
            );
            // Register JS
            $min = ( !DGWT_WCAS_DEBUG ? '.min' : '' );
            wp_register_script(
                'dgwt-wcas-admin-js',
                DGWT_WCAS_URL . 'assets/js/admin' . $min . '.js',
                array( 'jquery' ),
                DGWT_WCAS_VERSION
            );
            
            if ( \DgoraWcas\Helpers::isSettingsPage() ) {
                $localize = array(
                    'labels' => \DgoraWcas\Helpers::getLabels(),
                );
                $localize = apply_filters( 'dgwt/wcas/scripts/admin/localize', $localize );
                wp_localize_script( 'dgwt-wcas-admin-js', 'dgwt_wcas', $localize );
                // Enqueue CSS
                wp_enqueue_style( 'dgwt-wcas-admin-style' );
                wp_enqueue_style( 'wp-color-picker' );
                wp_enqueue_script( 'dgwt-wcas-admin-js' );
                wp_enqueue_script( 'wp-color-picker' );
                wp_enqueue_script(
                    'dgwt-wcas-admin-popper-js',
                    DGWT_WCAS_URL . 'assets/js/popper.min.js',
                    array( 'jquery' ),
                    DGWT_WCAS_VERSION
                );
                wp_enqueue_script(
                    'dgwt-wcas-admin-tooltip-js',
                    DGWT_WCAS_URL . 'assets/js/tooltip.min.js',
                    array( 'jquery' ),
                    DGWT_WCAS_VERSION
                );
                $min = ( !DGWT_WCAS_DEBUG ? '.min' : '' );
                wp_enqueue_style(
                    'dgwt-wcas-style',
                    apply_filters( 'dgwt/wcas/scripts/css_style_url', DGWT_WCAS_URL . 'assets/css/style' . $min . '.css' ),
                    array(),
                    DGWT_WCAS_VERSION
                );
            }
            
            if ( \DgoraWcas\Helpers::isCheckoutPage() ) {
                wp_enqueue_style( 'dgwt-wcas-admin-style' );
            }
        }
        
        /**
         * Register text domain
         *
         * @return void
         */
        private function loadTextdomain()
        {
            $lang_dir = dirname( plugin_basename( DGWT_WCAS_FILE ) ) . '/languages/';
            load_plugin_textdomain( 'ajax-search-for-woocommerce', false, $lang_dir );
        }
    
    }
    // Init the plugin
    function DGWT_WCAS()
    {
        return DGWT_WC_Ajax_Search::getInstance();
    }
    
    add_action( 'plugins_loaded', 'DGWT_WCAS' );
}

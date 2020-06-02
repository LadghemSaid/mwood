<?php

namespace DgoraWcas\Admin;

class Requirements
{
    private  $notices = array() ;
    public function __construct()
    {
        add_action( 'admin_page_dgwt_wcas_settings-pricing', array( $this, 'maybePrintNotice' ) );
    }
    
    /**
     * Check minimal requirements for the premium version
     *
     * @return void
     */
    public function checkRequirements()
    {
        $allow = false;
        $plugins = $this->getIncompatiblePlugins();
        if ( $this->checkPHPVersion() && $this->checkPHPExtensions() && empty($plugins['langs']) && empty($plugins['other']) ) {
            $allow = true;
        }
        return $allow;
    }
    
    /**
     * Check PHP version
     *
     * @return bool
     */
    private function checkPHPVersion()
    {
        $passed = version_compare( PHP_VERSION, '5.5.0', '>=' );
        if ( !$passed ) {
            $this->notices[] = sprintf( __( 'Required PHP version 5.5 or higher. You use %s', 'ajax-search-for-woocommerce' ), PHP_VERSION );
        }
        return $passed;
    }
    
    /**
     * Check required PHP extensions
     *
     * @return bool
     */
    private function checkPHPExtensions()
    {
        $hasExtensions = true;
        
        if ( !extension_loaded( 'mbstring' ) ) {
            $hasExtensions = false;
            $this->notices[] = sprintf( __( 'Required PHP extension: %s', 'ajax-search-for-woocommerce' ), 'mbstring' );
        }
        
        
        if ( !extension_loaded( 'pdo_mysql' ) ) {
            $hasExtensions = false;
            $this->notices[] = sprintf( __( 'Required PHP extension: %s', 'ajax-search-for-woocommerce' ), 'pdo_mysql' );
        }
        
        return $hasExtensions;
    }
    
    /**
     * @return string
     */
    private function getUploadPath()
    {
        $path = '';
        $upload_dir = wp_upload_dir();
        if ( !empty($upload_dir['basedir']) ) {
            $path = $upload_dir['basedir'];
        }
        return $path;
    }
    
    /**
     * Incompatible Plugins
     *
     * @return array
     */
    private function getIncompatiblePlugins()
    {
        $plugins = array(
            'langs' => array(),
            'other' => array(),
        );
        // GTranslate
        if ( class_exists( 'GTranslate' ) ) {
            $plugins['langs'][] = 'GTranslate';
        }
        if ( !empty($plugins['langs']) ) {
            foreach ( $plugins['langs'] as $plugin ) {
                $this->notices[] = sprintf( __( 'You use the %s plugin. The Ajax Search for WooCommerce PRO does not support multilingual yet.', 'ajax-search-for-woocommerce' ), $plugin );
            }
        }
        // WooCommerce Product Sort and Display
        if ( defined( 'WC_PSAD_VERSION' ) ) {
            $plugins['other'][] = 'WooCommerce Product Sort and Display';
        }
        return $plugins;
    }
    
    /**
     * Display error notice on pricing page if necessary
     *
     * @return void
     */
    public function maybePrintNotice()
    {
        
        if ( !$this->checkRequirements() ) {
            echo  '<div class="dgwt-wcas-requirements">' ;
            echo  '<div class="dgwt-wcas-requirements__inner">' ;
            echo  '<h2>' . __( 'Attention! Read this before the upgrade.', 'ajax-search-for-woocommerce' ) . '</h2>' ;
            echo  '<h4>' . __( 'Ajax Search for WooCommerce PRO may not work properly in your environment for the following reasons:', 'ajax-search-for-woocommerce' ) . '</h4>' ;
            
            if ( !empty($this->notices) ) {
                echo  '<ol>' ;
                foreach ( $this->notices as $notice ) {
                    echo  '<li>' . $notice . '</li>' ;
                }
                echo  '</ol>' ;
            }
            
            $mailto = 'mailto:dgoraplugins@gmail.com?subject=' . __( 'Ajax Search for WooCommerce PRO - Requirements', 'ajax-search-for-woocommerce' );
            echo  '<p>' . sprintf( __( 'If you have any questions, do not hesitate contact <a href="%s">our support</a>.', 'ajax-search-for-woocommerce' ), $mailto ) . '</p>' ;
            echo  '</div>' ;
            echo  '</div>' ;
        }
    
    }
    
    private function isDefaultUploadPath()
    {
        $compatible = false;
        $defaultUploadDir = WP_CONTENT_DIR . '/uploads';
        $dynamicUploadDir = rtrim( $this->getUploadPath(), '/' );
        if ( $defaultUploadDir === $dynamicUploadDir ) {
            $compatible = true;
        }
        return $compatible;
    }
    
    /**
     * Check if can load wp-load.php file without WordPress init (unknown ABSPATH)
     *
     * @return bool
     */
    private function checkLoadWPLoadFile()
    {
        $success = false;
        $wpLoad = dirname( __FILE__ );
        $maxDepth = 10;
        while ( !file_exists( $wpLoad . '/wp-load.php' ) && $maxDepth > 0 ) {
            $wpLoad = dirname( $wpLoad );
            $maxDepth--;
        }
        if ( file_exists( $wpLoad ) ) {
            $success = true;
        }
        return $success;
    }

}
<?php
    /**
    *  Single Product Custom Layout
    */
    class Woolentor_Woo_Custom_Template_Layout{


        public static $wl_woo_elementor_template = array();

        private static $_instance = null;
        public static function instance() {
            if ( is_null( self::$_instance ) ) {
                self::$_instance = new self();
            }
            return self::$_instance;
        }
        
        function __construct(){
            add_action('init', array( $this, 'init' ) );
        }

        public function init(){

            // Product details page
            add_filter( 'wc_get_template_part', array( $this, 'wl_get_product_page_template' ), 99, 3 );
            add_filter( 'template_include', array( $this, 'wl_get_product_elementor_template' ), 100 );
            add_action( 'woolentor_woocommerce_product_content', array( $this, 'wl_get_product_content_elementor' ), 5 );
            add_action( 'woolentor_woocommerce_product_content', array( $this, 'wl_get_default_product_data' ), 10 );

            // Product Archive Page
            add_action('template_redirect', array($this, 'woolentor_product_archive_template'), 999);
            add_filter('template_include', array($this, 'woolentor_redirect_product_archive_template'), 999);
            add_action( 'woolentor_woocommerce_archive_product_content', array( $this, 'woolentor_archive_product_page_content') );
        }

        public function wl_get_product_page_template( $template, $slug, $name ) {
            if ( 'content' === $slug && 'single-product' === $name ) {
                if ( Woolentor_Woo_Custom_Template_Layout::wl_woo_custom_product_template() ) {
                    $template = WOOLENTOR_ADDONS_PL_PATH . 'wl-woo-templates/single-product.php';
                }
            }
            return $template;
        }

        //Based on elementor template
        public function wl_get_product_elementor_template( $template ) {
            if ( is_embed() ) {
                return $template;
            }
            if ( is_singular( 'product' ) ) {
                $templateid = get_page_template_slug( woolentor_get_option( 'singleproductpage', 'woolentor_woo_template_tabs', '0' ) );
                if ( 'elementor_header_footer' === $templateid ) {
                    $template = WOOLENTOR_ADDONS_PL_PATH . 'wl-woo-templates/single-product-fullwidth.php';
                } elseif ( 'elementor_canvas' === $templateid ) {
                    $template = WOOLENTOR_ADDONS_PL_PATH . 'wl-woo-templates/single-product-canvas.php';
                }
            }
            return $template;
        }

        public static function wl_get_product_content_elementor( $post ) {
            if ( Woolentor_Woo_Custom_Template_Layout::wl_woo_custom_product_template() ) {
                $wltemplateid = woolentor_get_option( 'singleproductpage', 'woolentor_woo_template_tabs', '0' );
                $wlindividualid = get_post_meta( get_the_ID(), '_selectproduct_layout', true ) ? get_post_meta( get_the_ID(), '_selectproduct_layout', true ) : '0';
                if( $wlindividualid != '0' ){ $wltemplateid = $wlindividualid; }
                echo Elementor\Plugin::instance()->frontend->get_builder_content_for_display( $wltemplateid );
            } else {
                the_content();
            }
        }

        // product data
        public function wl_get_default_product_data() {
            WC()->structured_data->generate_product_data();
        }

        public static function wl_woo_custom_product_template() {
            $templatestatus = false;
            if ( is_product() ) {
                global $post;
                if ( ! isset( self::$wl_woo_elementor_template[ $post->ID ] ) ) {
                    $single_product_default = woolentor_get_option( 'singleproductpage', 'woolentor_woo_template_tabs', '0' );
                    if ( ! empty( $single_product_default ) && 'default' !== $single_product_default ) {
                        $templatestatus                              = true;
                        self::$wl_woo_elementor_template[ $post->ID ] = true;
                    }
                } else {
                    $templatestatus = self::$wl_woo_elementor_template[ $post->ID ];
                }
            }
            return apply_filters( 'wl_woo_custom_product_template', $templatestatus );
        }

        /*
        * Archive Page
        */
        public function woolentor_product_archive_template() {
            $archive_template_id = 0;
            if ( defined('WOOCOMMERCE_VERSION') ) {
                $termobj = get_queried_object();
                if ( is_shop() || ( is_tax('product_cat') && is_product_category() ) || ( is_tax('product_tag') && is_product_tag() ) || ( isset( $termobj->taxonomy ) && is_tax( $termobj->taxonomy ) ) ) {
                    $product_achive_custom_page_id = woolentor_get_option( 'productarchivepage', 'woolentor_woo_template_tabs', '0' );

                    // Meta value
                    $wltermlayoutid = 0;
                    if(( is_tax('product_cat') && is_product_category() ) || ( is_tax('product_tag') && is_product_tag() )){
                        $wltermlayoutid = get_term_meta( $termobj->term_id, 'wooletor_selectcategory_layout', true ) ? get_term_meta( $termobj->term_id, 'wooletor_selectcategory_layout', true ) : '0';
                    }
                    if( $wltermlayoutid != '0' ){ 
                        $archive_template_id = $wltermlayoutid; 
                    }else{
                        if (!empty($product_achive_custom_page_id)) {
                            $archive_template_id = $product_achive_custom_page_id;
                        }
                    }
                    return $archive_template_id;
                }
                return $archive_template_id;
            }
        }

        public function woolentor_redirect_product_archive_template($template){
            $archive_template_id = $this->woolentor_product_archive_template();
            $templatefile   = array();
            $templatefile[] = 'wl-woo-templates/archive-product.php';
            if( $archive_template_id != '0' ){
                $template = locate_template( $templatefile );
                if ( ! $template || ( ! empty( $status_options['template_debug_mode'] ) && current_user_can( 'manage_options' ) ) ){
                    $template = WOOLENTOR_ADDONS_PL_PATH . '/wl-woo-templates/archive-product.php';
                }
                $page_template_slug = get_page_template_slug( $archive_template_id );
                if ( 'elementor_header_footer' === $page_template_slug ) {
                    $template = WOOLENTOR_ADDONS_PL_PATH . '/wl-woo-templates/archive-product-fullwidth.php';
                } elseif ( 'elementor_canvas' === $page_template_slug ) {
                    $template = WOOLENTOR_ADDONS_PL_PATH . '/wl-woo-templates/archive-product-canvas.php';
                }
            }
            return $template;
        }

        // Element Content
        public function woolentor_archive_product_page_content( $post ){
            $archive_template_id = $this->woolentor_product_archive_template();
            if( $archive_template_id != '0' ){
                echo Elementor\Plugin::instance()->frontend->get_builder_content_for_display( $archive_template_id );
            }else{ the_content(); }
        }

    }

    Woolentor_Woo_Custom_Template_Layout::instance();
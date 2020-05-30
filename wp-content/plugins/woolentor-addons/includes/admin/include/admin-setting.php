<?php

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly.

class Woolentor_Admin_Settings {

    private $settings_api;

    function __construct() {
        $this->settings_api = new Woolentor_Settings_API();

        add_action( 'admin_init', [ $this, 'admin_init' ] );
        add_action( 'admin_menu', [ $this, 'admin_menu' ], 220 );
        add_action( 'wsa_form_bottom_woolentor_general_tabs', [ $this, 'woolentor_html_general_tabs' ] );
        add_action( 'wsa_form_top_woolentor_elements_tabs', [ $this, 'woolentor_html_popup_box' ] );
        add_action( 'wsa_form_bottom_woolentor_themes_library_tabs', [ $this, 'woolentor_html_themes_library_tabs' ] );
        
        add_action( 'wsa_form_bottom_woolentor_buy_pro_tabs', [ $this, 'woolentor_html_buy_pro_tabs' ] );

    }

    function admin_init() {

        //set the settings
        $this->settings_api->set_sections( $this->woolentor_admin_get_settings_sections() );
        $this->settings_api->set_fields( $this->woolentor_admin_fields_settings() );

        //initialize settings
        $this->settings_api->admin_init();
    }

    // Plugins menu Register
    function admin_menu() {

        $menu = 'add_menu_' . 'page';
        $menu(
            'woolentor_panel',
            esc_html__( 'WooLentor', 'woolentor' ),
            esc_html__( 'WooLentor', 'woolentor' ),
            'woolentor_page',
            NULL,
            WOOLENTOR_ADDONS_PL_URL.'includes/admin/assets/images/menu-icon.png',
            100
        );
        
        add_submenu_page(
            'woolentor_page', 
            esc_html__( 'Settings', 'woolentor' ),
            esc_html__( 'Settings', 'woolentor' ), 
            'manage_options', 
            'woolentor', 
            array ( $this, 'plugin_page' ) 
        );

    }

    // Options page Section register
    function woolentor_admin_get_settings_sections() {
        $sections = array(
            
            array(
                'id'    => 'woolentor_general_tabs',
                'title' => esc_html__( 'General', 'woolentor' )
            ),

            array(
                'id'    => 'woolentor_woo_template_tabs',
                'title' => esc_html__( 'WooCommerce Template', 'woolentor' )
            ),

            array(
                'id'    => 'woolentor_elements_tabs',
                'title' => esc_html__( 'Elements', 'woolentor' )
            ),

            array(
                'id'    => 'woolentor_themes_library_tabs',
                'title' => esc_html__( 'Theme Library', 'woolentor' )
            ),

            array(
                'id'    => 'woolentor_rename_label_tabs',
                'title' => esc_html__( 'Rename Label', 'woolentor' )
            ),

            array(
                'id'    => 'woolentor_sales_notification_tabs',
                'title' => esc_html__( 'Sales Notification', 'woolentor' )
            ),

            array(
                'id'    => 'woolentor_others_tabs',
                'title' => esc_html__( 'Other', 'woolentor' )
            ),

            array(
                'id'    => 'woolentor_buy_pro_tabs',
                'title' => esc_html__( 'Buy Pro', 'woolentor' )
            ),

        );
        return $sections;
    }

    // Options page field register
    protected function woolentor_admin_fields_settings() {

        $settings_fields = array(

            'woolentor_general_tabs' => array(),

            'woolentor_woo_template_tabs' => array(

                array(
                    'name'  => 'enablecustomlayout',
                    'label'  => __( 'Enable / Disable Template Builder', 'woolentor' ),
                    'desc'  => __( 'Enable', 'woolentor' ),
                    'type'  => 'checkbox',
                    'default' => 'on',
                ),

                array(
                    'name'  => 'shoppageproductlimit',
                    'label' => __( 'Product Limit', 'woolentor' ),
                    'desc' => wp_kses_post( 'You can Handle Shop page product limit', 'woolentor' ),
                    'min'               => 1,
                    'max'               => 100,
                    'step'              => '1',
                    'type'              => 'number',
                    'std'               => '10',
                    'sanitize_callback' => 'floatval'
                ),

                array(
                    'name'    => 'singleproductpage',
                    'label'   => __( 'Single Product Template', 'woolentor' ),
                    'desc'    => __( 'You can select Custom Product details layout', 'woolentor' ),
                    'type'    => 'select',
                    'default' => '0',
                    'options' => woolentor_elementor_template()
                ),

                array(
                    'name'    => 'productarchivepage',
                    'label'   => __( 'Product Archive Page Template', 'woolentor' ),
                    'desc'    => __( 'You can select Custom Product Shop page layout', 'woolentor' ),
                    'type'    => 'select',
                    'default' => '0',
                    'options' => woolentor_elementor_template()
                ),

                array(
                    'name'    => 'productcartpagep',
                    'label'   => __( 'Cart Page Template', 'woolentor' ),
                    'desc'    => __( 'You can select Custom cart page layout <span>( Pro )</span>', 'woolentor' ),
                    'type'    => 'select',
                    'default' => '0',
                    'options' => array(
                        'select'=>'Select Template',
                    ),
                    'class'=>'proelement',
                ),

                array(
                    'name'    => 'productcheckoutpagep',
                    'label'   => __( 'Checkout Page Template', 'woolentor' ),
                    'desc'    => __( 'You can select Custom checkout page layout <span>( Pro )</span>', 'woolentor' ),
                    'type'    => 'select',
                    'default' => '0',
                    'options' => array(
                        'select'=>'Select Template',
                    ),
                    'class'=>'proelement',
                ),

                array(
                    'name'    => 'productthankyoupagep',
                    'label'   => __( 'Thank You Page Template', 'woolentor' ),
                    'desc'    => __( 'You can select Custom thank you page layout <span>( Pro )</span>', 'woolentor' ),
                    'type'    => 'select',
                    'default' => '0',
                    'options' => array(
                        'select'=>'Select Template',
                    ),
                    'class'=>'proelement',
                ),

                array(
                    'name'    => 'productmyaccountpagep',
                    'label'   => __( 'My Account Page Template', 'woolentor' ),
                    'desc'    => __( 'You can select Custom my account page layout <span>( Pro )</span>', 'woolentor' ),
                    'type'    => 'select',
                    'default' => '0',
                    'options' => array(
                        'select'=>'Select Template',
                    ),
                    'class'=>'proelement',
                ),

                array(
                    'name'    => 'productmyaccountloginpagep',
                    'label'   => __( 'My Account Login page Template', 'woolentor' ),
                    'desc'    => __( 'You can select Custom my account login page layout <span>( Pro )</span>', 'woolentor' ),
                    'type'    => 'select',
                    'default' => '0',
                    'options' => array(
                        'select'=>'Select Template',
                    ),
                    'class'=>'proelement',
                ),

            ),

            'woolentor_elements_tabs' => array(

                array(
                    'name'  => 'product_tabs',
                    'label'  => __( 'Product Tab', 'woolentor' ),
                    'type'  => 'checkbox',
                    'default' => 'on',
                    'class'=>'woolentor_table_row',
                ),

                array(
                    'name'  => 'universal_product',
                    'label'  => __( 'Universal Product', 'woolentor' ),
                    'type'  => 'checkbox',
                    'default' => 'on',
                    'class'=>'woolentor_table_row',
                ),

                array(
                    'name'  => 'add_banner',
                    'label'  => __( 'Ads Banner', 'woolentor' ),
                    'type'  => 'checkbox',
                    'default' => 'on',
                    'class'=>'woolentor_table_row',
                ),

                array(
                    'name'  => 'special_day_offer',
                    'label'  => __( 'Special Day Offer', 'woolentor' ),
                    'type'  => 'checkbox',
                    'default' => 'on',
                    'class'=>'woolentor_table_row',
                ),

                array(
                    'name'  => 'wb_archive_product',
                    'label'  => __( 'Product Archive', 'woolentor' ),
                    'type'  => 'checkbox',
                    'default' => 'on',
                    'class'=>'woolentor_table_row',
                ),

                array(
                    'name'  => 'wb_product_title',
                    'label'  => __( 'Product Title', 'woolentor' ),
                    'type'  => 'checkbox',
                    'default' => 'on',
                    'class'=>'woolentor_table_row',
                ),

                array(
                    'name'  => 'wb_product_related',
                    'label'  => __( 'Related Product', 'woolentor' ),
                    'type'  => 'checkbox',
                    'default' => 'on',
                    'class'=>'woolentor_table_row',
                ),

                array(
                    'name'  => 'wb_product_add_to_cart',
                    'label'  => __( 'Add To Cart Button', 'woolentor' ),
                    'type'  => 'checkbox',
                    'default' => 'on',
                    'class'=>'woolentor_table_row',
                ),

                array(
                    'name'  => 'wb_product_additional_information',
                    'label'  => __( 'Additional Information', 'woolentor' ),
                    'type'  => 'checkbox',
                    'default' => 'on',
                    'class'=>'woolentor_table_row',
                ),

                array(
                    'name'  => 'wb_product_data_tab',
                    'label'  => __( 'Product Data Tab', 'woolentor' ),
                    'type'  => 'checkbox',
                    'default' => 'on',
                    'class'=>'woolentor_table_row',
                ),

                array(
                    'name'  => 'wb_product_description',
                    'label'  => __( 'Product Description', 'woolentor' ),
                    'type'  => 'checkbox',
                    'default' => 'on',
                    'class'=>'woolentor_table_row',
                ),

                array(
                    'name'  => 'wb_product_short_description',
                    'label'  => __( 'Product Short Description', 'woolentor' ),
                    'type'  => 'checkbox',
                    'default' => 'on',
                    'class'=>'woolentor_table_row',
                ),

                array(
                    'name'  => 'wb_product_price',
                    'label'  => __( 'Product Price', 'woolentor' ),
                    'type'  => 'checkbox',
                    'default' => 'on',
                    'class'=>'woolentor_table_row',
                ),

                array(
                    'name'  => 'wb_product_rating',
                    'label'  => __( 'Product Rating', 'woolentor' ),
                    'type'  => 'checkbox',
                    'default' => 'on',
                    'class'=>'woolentor_table_row',
                ),

                array(
                    'name'  => 'wb_product_reviews',
                    'label'  => __( 'Product Reviews', 'woolentor' ),
                    'type'  => 'checkbox',
                    'default' => 'on',
                    'class'=>'woolentor_table_row',
                ),

                array(
                    'name'  => 'wb_product_image',
                    'label'  => __( 'Product Image', 'woolentor' ),
                    'type'  => 'checkbox',
                    'default' => 'on',
                    'class'=>'woolentor_table_row',
                ),

                array(
                    'name'  => 'wl_product_video_gallery',
                    'label'  => __( 'Product Video Gallery', 'woolentor' ),
                    'type'  => 'checkbox',
                    'default' => 'on',
                    'class'=>'woolentor_table_row',
                ),

                array(
                    'name'  => 'wb_product_upsell',
                    'label'  => __( 'Product Upsell', 'woolentor' ),
                    'type'  => 'checkbox',
                    'default' => 'on',
                    'class'=>'woolentor_table_row',
                ),

                array(
                    'name'  => 'wb_product_stock',
                    'label'  => __( 'Product Stock Status', 'woolentor' ),
                    'type'  => 'checkbox',
                    'default' => 'on',
                    'class'=>'woolentor_table_row',
                ),

                array(
                    'name'  => 'wb_product_meta',
                    'label'  => __( 'Product Meta Info', 'woolentor' ),
                    'type'  => 'checkbox',
                    'default' => 'on',
                    'class'=>'woolentor_table_row',
                ),

                array(
                    'name'  => 'wb_product_call_for_price',
                    'label'  => __( 'Call For Price', 'woolentor' ),
                    'type'  => 'checkbox',
                    'default' => 'on',
                    'class'=>'woolentor_table_row',
                ),

                array(
                    'name'  => 'wb_product_suggest_price',
                    'label'  => __( 'Suggest Price', 'woolentor' ),
                    'type'  => 'checkbox',
                    'default' => 'on',
                    'class'=>'woolentor_table_row',
                ),

                array(
                    'name'  => 'wl_custom_archive_layoutp',
                    'label'  => __( 'Product Archive Layout <span>( Pro )</span>', 'woolentor' ),
                    'type'  => 'checkbox',
                    'default' => 'off',
                    'class'=>'woolentor_table_row pro',
                ),

                array(
                    'name'  => 'wl_cart_tablep',
                    'label'  => __( 'Product Cart Table <span>( Pro )</span>', 'woolentor' ),
                    'type'  => 'checkbox',
                    'default' => 'off',
                    'class'=>'woolentor_table_row pro',
                ),

                array(
                    'name'  => 'wl_cart_totalp',
                    'label'  => __( 'Product Cart Total <span>( Pro )</span>', 'woolentor' ),
                    'type'  => 'checkbox',
                    'default' => 'off',
                    'class'=>'woolentor_table_row pro',
                ),

                array(
                    'name'  => 'wl_cartempty_messagep',
                    'label'  => __( 'Empty Cart Mes..<span>( Pro )</span>', 'woolentor' ),
                    'type'  => 'checkbox',
                    'default' => 'off',
                    'class'=>'woolentor_table_row pro',
                ),

                array(
                    'name'  => 'wl_cartempty_shopredirectp',
                    'label'  => __( 'Empty Cart Re.. Button <span>( Pro )</span>', 'woolentor' ),
                    'type'  => 'checkbox',
                    'default' => 'off',
                    'class'=>'woolentor_table_row pro',
                ),

                array(
                    'name'  => 'wl_cross_sellp',
                    'label'  => __( 'Product Cross Sell <span>( Pro )</span>', 'woolentor' ),
                    'type'  => 'checkbox',
                    'default' => 'off',
                    'class'=>'woolentor_table_row pro',
                ),

                array(
                    'name'  => 'wl_cross_sell_customp',
                    'label'  => __( 'Cross Sell ..( Custom ) <span>( Pro )</span>', 'woolentor' ),
                    'type'  => 'checkbox',
                    'default' => 'off',
                    'class'=>'woolentor_table_row pro',
                ),

                array(
                    'name'  => 'wl_checkout_additional_formp',
                    'label'  => __( 'Checkout Additional.. <span>( Pro )</span>', 'woolentor' ),
                    'type'  => 'checkbox',
                    'default' => 'off',
                    'class'=>'woolentor_table_row pro',
                ),

                array(
                    'name'  => 'wl_checkout_billingp',
                    'label'  => __( 'Checkout Billing Form <span>( Pro )</span>', 'woolentor' ),
                    'type'  => 'checkbox',
                    'default' => 'off',
                    'class'=>'woolentor_table_row pro',
                ),

                array(
                    'name'  => 'wl_checkout_shipping_formp',
                    'label'  => __( 'Checkout Shipping Form <span>( Pro )</span>', 'woolentor' ),
                    'type'  => 'checkbox',
                    'default' => 'off',
                    'class'=>'woolentor_table_row pro',
                ),

                array(
                    'name'  => 'wl_checkout_paymentp',
                    'label'  => __( 'Checkout Payment <span>( Pro )</span>', 'woolentor' ),
                    'type'  => 'checkbox',
                    'default' => 'off',
                    'class'=>'woolentor_table_row pro',
                ),

                array(
                    'name'  => 'wl_checkout_coupon_formp',
                    'label'  => __( 'Checkout Co.. Form <span>( Pro )</span>', 'woolentor-pro' ),
                    'type'  => 'checkbox',
                    'default' => 'off',
                    'class'=>'woolentor_table_row pro',
                ),

                array(
                    'name'  => 'wl_checkout_login_formp',
                    'label'  => __( 'Checkout lo.. Form <span>( Pro )</span>', 'woolentor-pro' ),
                    'type'  => 'checkbox',
                    'default' => 'off',
                    'class'=>'woolentor_table_row pro',
                ),

                array(
                    'name'  => 'wl_order_reviewp',
                    'label'  => __( 'Checkout Order Review <span>( Pro )</span>', 'woolentor' ),
                    'type'  => 'checkbox',
                    'default' => 'off',
                    'class'=>'woolentor_table_row pro',
                ),

                array(
                    'name'  => 'wl_myaccount_accountp',
                    'label'  => __( 'My Account <span>( Pro )</span>', 'woolentor-pro' ),
                    'type'  => 'checkbox',
                    'default' => 'off',
                    'class'=>'woolentor_table_row pro',
                ),

                array(
                    'name'  => 'wl_myaccount_dashboardp',
                    'label'  => __( 'My Account Dashboard <span>( Pro )</span>', 'woolentor' ),
                    'type'  => 'checkbox',
                    'default' => 'off',
                    'class'=>'woolentor_table_row pro',
                ),

                array(
                    'name'  => 'wl_myaccount_downloadp',
                    'label'  => __( 'My Account Download <span>( Pro )</span>', 'woolentor' ),
                    'type'  => 'checkbox',
                    'default' => 'off',
                    'class'=>'woolentor_table_row pro',
                ),

                array(
                    'name'  => 'wl_myaccount_edit_accountp',
                    'label'  => __( 'My Account Edit<span>( Pro )</span>', 'woolentor' ),
                    'type'  => 'checkbox',
                    'default' => 'off',
                    'class'=>'woolentor_table_row pro',
                ),

                array(
                    'name'  => 'wl_myaccount_addressp',
                    'label'  => __( 'My Account Address <span>( Pro )</span>', 'woolentor' ),
                    'type'  => 'checkbox',
                    'default' => 'off',
                    'class'=>'woolentor_table_row pro',
                ),

                array(
                    'name'  => 'wl_myaccount_login_formp',
                    'label'  => __( 'Login Form <span>( Pro )</span>', 'woolentor' ),
                    'type'  => 'checkbox',
                    'default' => 'off',
                    'class'=>'woolentor_table_row pro',
                ),

                array(
                    'name'  => 'wl_myaccount_register_formp',
                    'label'  => __( 'Registration Form <span>( Pro )</span>', 'woolentor' ),
                    'type'  => 'checkbox',
                    'default' => 'off',
                    'class'=>'woolentor_table_row pro',
                ),

                array(
                    'name'  => 'wl_myaccount_logoutp',
                    'label'  => __( 'My Account Logout <span>( Pro )</span>', 'woolentor' ),
                    'type'  => 'checkbox',
                    'default' => 'off',
                    'class'=>'woolentor_table_row pro',
                ),

                array(
                    'name'  => 'wl_myaccount_orderp',
                    'label'  => __( 'My Account Order <span>( Pro )</span>', 'woolentor' ),
                    'type'  => 'checkbox',
                    'default' => 'off',
                    'class'=>'woolentor_table_row pro',
                ),

                array(
                    'name'  => 'wl_thankyou_orderp',
                    'label'  => __( 'Thank You Order <span>( Pro )</span>', 'woolentor' ),
                    'type'  => 'checkbox',
                    'default' => 'off',
                    'class'=>'woolentor_table_row pro',
                ),

                array(
                    'name'  => 'wl_thankyou_customer_address_detailsp',
                    'label'  => __( 'Thank You Cus.. Address <span>( Pro )</span>', 'woolentor' ),
                    'type'  => 'checkbox',
                    'default' => 'off',
                    'class'=>'woolentor_table_row pro',
                ),

                array(
                    'name'  => 'wl_thankyou_order_detailsp',
                    'label'  => __( 'Thank You Order Details <span>( Pro )</span>', 'woolentor' ),
                    'type'  => 'checkbox',
                    'default' => 'off',
                    'class'=>'woolentor_table_row pro',
                ),

                array(
                    'name'  => 'wl_product_advance_thumbnailsp',
                    'label'  => __( 'Advance Product Image <span>( Pro )</span>', 'woolentor' ),
                    'type'  => 'checkbox',
                    'default' => 'off',
                    'class'=>'woolentor_table_row pro',
                ),

                array(
                    'name'  => 'wl_social_sherep',
                    'label'  => __( 'Product Social Share <span>( Pro )</span>', 'woolentor' ),
                    'type'  => 'checkbox',
                    'default' => 'off',
                    'class'=>'woolentor_table_row pro',
                ),

                array(
                    'name'  => 'wl_stock_progress_barp',
                    'label'  => __( 'Stock Progressbar <span>( Pro )</span>', 'woolentor' ),
                    'type'  => 'checkbox',
                    'default' => 'off',
                    'class'=>'woolentor_table_row pro',
                ),
                array(
                    'name'  => 'wl_single_product_sale_schedulep',
                    'label'  => __( 'Product Sale Schedule <span>( Pro )</span>', 'woolentor-pro' ),
                    'type'  => 'checkbox',
                    'default' => 'off',
                    'class'=>'woolentor_table_row pro',
                ),

                array(
                    'name'  => 'wl_related_productp',
                    'label'  => __( 'Related Pro..( Custom ) <span>( Pro )</span>', 'woolentor' ),
                    'type'  => 'checkbox',
                    'default' => 'off',
                    'class'=>'woolentor_table_row pro',
                ),

                array(
                    'name'  => 'wl_product_upsell_customp',
                    'label'  => __( 'Upsell Pro..( Custom ) <span>( Pro )</span>', 'woolentor' ),
                    'type'  => 'checkbox',
                    'default' => 'off',
                    'class'=>'woolentor_table_row pro',
                ),

            ),

            'woolentor_themes_library_tabs' => array(),
            'woolentor_rename_label_tabs' => array(
                
                array(
                    'name'  => 'enablerenamelabel',
                    'label'  => __( 'Enable / Disable Rename Label', 'woolentor' ),
                    'desc'  => __( 'Enable', 'woolentor' ),
                    'type'  => 'checkbox',
                    'default' => 'off',
                    'class'=>'woolentor_table_row',
                ),

                array(
                    'name'      => 'shop_page_heading',
                    'headding'  => __( 'Shop Page', 'woolentor' ),
                    'type'      => 'title',
                ),
                
                array(
                    'name'        => 'wl_shop_add_to_cart_txt',
                    'label'       => __( 'Add to Cart Button Text', 'woolentor' ),
                    'desc'        => __( 'You Can change the Add to Cart button text.', 'woolentor' ),
                    'type'        => 'text',
                    'placeholder' => __( 'Add to Cart', 'woolentor' )
                ),

                array(
                    'name'      => 'product_details_page_heading',
                    'headding'  => __( 'Product Details Page', 'woolentor' ),
                    'type'      => 'title',
                ),

                array(
                    'name'        => 'wl_add_to_cart_txt',
                    'label'       => __( 'Add to Cart Button Text', 'woolentor' ),
                    'desc'        => __( 'You Can change the Add to Cart button text.', 'woolentor' ),
                    'type'        => 'text',
                    'placeholder' => __( 'Add to Cart', 'woolentor' )
                ),

                array(
                    'name'        => 'wl_description_tab_menu_titlep',
                    'label'       => __( 'Description', 'woolentor' ),
                    'desc'        => __( 'You Can change the description tab title. <span>( Pro )</span>', 'woolentor' ),
                    'type'        => 'text',
                    'placeholder' => __( 'Description', 'woolentor' ),
                    'class'=>'proelement',
                ),
                
                array(
                    'name'        => 'wl_additional_information_tab_menu_titlep',
                    'label'       => __( 'Additional Information', 'woolentor' ),
                    'desc'        => __( 'You Can change the additional information tab title. <span>( Pro )</span>', 'woolentor' ),
                    'type'        => 'text',
                    'placeholder' => __( 'Additiona information', 'woolentor' ),
                    'class'=>'proelement',
                ),
                
                array(
                    'name'        => 'wl_reviews_tab_menu_titlep',
                    'label'       => __( 'Reviews', 'woolentor' ),
                    'desc'        => __( 'You Can change the review tab title. <span>( Pro )</span>', 'woolentor' ),
                    'type'        => 'text',
                    'placeholder' => __( 'Reviews', 'woolentor' ),
                    'class'=>'proelement',
                ),

                array(
                    'name'      => 'checkout_page_headingp',
                    'headding'  => __( 'Checkout Page', 'woolentor' ),
                    'type'      => 'title',
                ),

                array(
                    'name'        => 'wl_checkout_firstname_labelp',
                    'label'       => __( 'First name', 'woolentor' ),
                    'desc'        => __( 'You can change the First name field label. <span>( Pro )</span>', 'woolentor' ),
                    'type'        => 'text',
                    'placeholder' => __( 'First name', 'woolentor' ),
                    'class'=>'proelement',
                ),

                array(
                    'name'        => 'wl_checkout_lastname_labelp',
                    'label'       => __( 'Last name', 'woolentor' ),
                    'desc'        => __( 'You can change the Last name field label. <span>( Pro )</span>', 'woolentor' ),
                    'type'        => 'text',
                    'placeholder' => __( 'Last name', 'woolentor' ),
                    'class'=>'proelement',
                ),

                array(
                    'name'        => 'wl_checkout_company_labelp',
                    'label'       => __( 'Company name', 'woolentor' ),
                    'desc'        => __( 'You can change the company field label. <span>( Pro )</span>', 'woolentor' ),
                    'type'        => 'text',
                    'placeholder' => __( 'Company name', 'woolentor' ),
                    'class'=>'proelement',
                ),

                array(
                    'name'        => 'wl_checkout_address_1_labelp',
                    'label'       => __( 'Street address', 'woolentor' ),
                    'desc'        => __( 'You can change the Street address field label. <span>( Pro )</span>', 'woolentor' ),
                    'type'        => 'text',
                    'placeholder' => __( 'Street address', 'woolentor' ),
                    'class'=>'proelement',
                ),

                array(
                    'name'        => 'wl_checkout_address_2_labelp',
                    'label'       => __( 'Address Optional', 'woolentor' ),
                    'desc'        => __( 'You can change the Address Optional field label. <span>( Pro )</span>', 'woolentor' ),
                    'type'        => 'text',
                    'placeholder' => __( 'Address Optional', 'woolentor' ),
                    'class'=>'proelement',
                ),

                array(
                    'name'        => 'wl_checkout_city_labelp',
                    'label'       => __( 'Town / City', 'woolentor' ),
                    'desc'        => __( 'You can change the City field label. <span>( Pro )</span>', 'woolentor' ),
                    'type'        => 'text',
                    'placeholder' => __( 'Town / City', 'woolentor' ),
                    'class'=>'proelement',
                ),

                array(
                    'name'        => 'wl_checkout_postcode_labelp',
                    'label'       => __( 'Postcode / ZIP', 'woolentor' ),
                    'desc'        => __( 'You can change the Postcode / ZIP field label. <span>( Pro )</span>', 'woolentor' ),
                    'type'        => 'text',
                    'placeholder' => __( 'Postcode / ZIP', 'woolentor' ),
                    'class'=>'proelement',
                ),

                array(
                    'name'        => 'wl_checkout_state_labelp',
                    'label'       => __( 'State', 'woolentor' ),
                    'desc'        => __( 'You can change the state field label. <span>( Pro )</span>', 'woolentor' ),
                    'type'        => 'text',
                    'placeholder' => __( 'State', 'woolentor' ),
                    'class'=>'proelement',
                ),

                array(
                    'name'        => 'wl_checkout_phone_labelp',
                    'label'       => __( 'Phone', 'woolentor' ),
                    'desc'        => __( 'You can change the phone field label. <span>( Pro )</span>', 'woolentor' ),
                    'type'        => 'text',
                    'placeholder' => __( 'Phone', 'woolentor' ),
                    'class'=>'proelement',
                ),

                array(
                    'name'        => 'wl_checkout_email_labelp',
                    'label'       => __( 'Email address', 'woolentor' ),
                    'desc'        => __( 'You can change the email address field label. <span>( Pro )</span>', 'woolentor' ),
                    'type'        => 'text',
                    'placeholder' => __( 'Email address', 'woolentor' ),
                    'class'=>'proelement',
                ),

                array(
                    'name'        => 'wl_checkout_country_labelp',
                    'label'       => __( 'Country', 'woolentor' ),
                    'desc'        => __( 'You can change the Country field label. <span>( Pro )</span>', 'woolentor' ),
                    'type'        => 'text',
                    'placeholder' => __( 'Country', 'woolentor' ),
                    'class'       => 'proelement',
                ),

                array(
                    'name'        => 'wl_checkout_ordernote_labelp',
                    'label'       => __( 'Order Note', 'woolentor' ),
                    'desc'        => __( 'You can change the Order notes field label. <span>( Pro )</span>', 'woolentor' ),
                    'type'        => 'text',
                    'placeholder' => __( 'Order notes', 'woolentor' ),
                    'class'       => 'proelement',
                ),

                array(
                    'name'        => 'wl_checkout_placeorder_btn_txtp',
                    'label'       => __( 'Place order', 'woolentor' ),
                    'desc'        => __( 'You can change the Place order field label. <span>( Pro )</span>', 'woolentor' ),
                    'type'        => 'text',
                    'placeholder' => __( 'Place order', 'woolentor' ),
                    'class'       => 'proelement',
                ),

            ),
            
            'woolentor_sales_notification_tabs'=>array(

                array(
                    'name'     => 'enableresalenotification',
                    'label'    => __( 'Enable / Disable Sales Notification', 'woolentor' ),
                    'desc'     => __( 'Enable', 'woolentor' ),
                    'type'     => 'checkbox',
                    'default'  => 'off',
                    'class'    => 'woolentor_table_row',
                ),

                array(
                    'name'    => 'notification_content_typep',
                    'label'   => __( 'Notification Content Type', 'woolentor' ),
                    'desc'    => __( 'Select Content Type <span>( Pro )</span>', 'woolentor' ),
                    'type'    => 'radio',
                    'default' => 'actual',
                    'options' => array(
                        'actual' => __('Real','woolentor'),
                        'fakes'  => __('Fakes','woolentor'),
                    ),
                    'class'=>'proelement',
                ),

                array(
                    'name'    => 'notification_posp',
                    'label'   => __( 'Position', 'woolentor' ),
                    'desc'    => __( 'Sale Notification Position on frontend.( Top Left, Top Right, Bottom Right option are pro features ) <span>( Pro )</span>', 'woolentor' ),
                    'type'    => 'select',
                    'default' => 'bottomleft',
                    'options' => array(
                        'bottomleft' =>__( 'Bottom Left','woolentor' ),
                    ),
                    'class'=>'proelement',
                ),

                array(
                    'name'    => 'notification_layoutp',
                    'label'   => __( 'Image Position', 'woolentor' ),
                    'desc'    => __( 'Notification Layout. <span>( Pro )</span>', 'woolentor' ),
                    'type'    => 'select',
                    'default' => 'imageleft',
                    'options' => array(
                        'imageleft' =>__( 'Image Left','woolentor' ),
                    ),
                    'class'         => 'notification_real proelement'
                ),

                array(
                    'name'    => 'notification_loadduration',
                    'label'   => __( 'Loading Time', 'woolentor' ),
                    'desc'    => __( 'Notification Loading duration.', 'woolentor' ),
                    'type'    => 'select',
                    'default' => '3',
                    'options' => array(
                        '2'       =>__( '2 seconds','woolentor' ),
                        '3'       =>__( '3 seconds','woolentor' ),
                        '4'       =>__( '4 seconds','woolentor' ),
                        '5'       =>__( '5 seconds','woolentor' ),
                        '6'       =>__( '6 seconds','woolentor' ),
                        '7'       =>__( '7 seconds','woolentor' ),
                        '8'       =>__( '8 seconds','woolentor' ),
                        '9'       =>__( '9 seconds','woolentor' ),
                        '10'      =>__( '10 seconds','woolentor' ),
                        '20'      =>__( '20 seconds','woolentor' ),
                        '30'      =>__( '30 seconds','woolentor' ),
                        '40'      =>__( '40 seconds','woolentor' ),
                        '50'      =>__( '50 seconds','woolentor' ),
                        '60'      =>__( '1 minute','woolentor' ),
                        '90'      =>__( '1.5 minutes','woolentor' ),
                        '120'     =>__( '2 minutes','woolentor' ),
                    ),
                ),

                array(
                    'name'    => 'notification_time_intp',
                    'label'   => __( 'Time Interval', 'woolentor' ),
                    'desc'    => __( 'Time between notifications. <span>( Pro )</span>', 'woolentor' ),
                    'type'    => 'select',
                    'default' => '4',
                    'options' => array(
                        '2'       =>__( '2 seconds','woolentor' ),
                        '4'       =>__( '4 seconds','woolentor' ),
                        '5'       =>__( '5 seconds','woolentor' ),
                        '6'       =>__( '6 seconds','woolentor' ),
                        '7'       =>__( '7 seconds','woolentor' ),
                        '8'       =>__( '8 seconds','woolentor' ),
                        '9'       =>__( '9 seconds','woolentor' ),
                        '10'      =>__( '10 seconds','woolentor' ),
                        '20'      =>__( '20 seconds','woolentor' ),
                        '30'      =>__( '30 seconds','woolentor' ),
                        '40'      =>__( '40 seconds','woolentor' ),
                        '50'      =>__( '50 seconds','woolentor' ),
                        '60'      =>__( '1 minute','woolentor' ),
                        '90'      =>__( '1.5 minutes','woolentor' ),
                        '120'     =>__( '2 minutes','woolentor' ),
                    ),
                    'class' => 'proelement',
                ),

                array(
                    'name'              => 'notification_limit',
                    'label'             => __( 'Limit', 'woolentor' ),
                    'desc'              => __( 'Order Limit for notification.', 'woolentor' ),
                    'min'               => 1,
                    'max'               => 100,
                    'default'           => '5',
                    'step'              => '1',
                    'type'              => 'number',
                    'sanitize_callback' => 'number',
                    'class'             => 'notification_real',
                ),

                array(
                    'name'    => 'notification_uptodatep',
                    'label'   => __( 'Order Upto', 'woolentor' ),
                    'desc'    => __( 'Do not show purchases older than.( More Options are Pro features ) <span>( Pro )</span>', 'woolentor' ),
                    'type'    => 'select',
                    'default' => '7',
                    'options' => array(
                        '7'   =>__( '1 week','woolentor' ),
                    ),
                    'class'   => 'notification_real',
                ),

                array(
                    'name'    => 'notification_inanimationp',
                    'label'   => __( 'Animation In', 'woolentor' ),
                    'desc'    => __( 'Notification Enter Animation. <span>( Pro )</span>', 'woolentor' ),
                    'type'    => 'select',
                    'default' => 'fadeInLeft',
                    'options' => array(
                        'fadeInLeft'  =>__( 'fadeInLeft','woolentor' ),
                    ),
                    'class' => 'proelement',
                ),

                array(
                    'name'    => 'notification_outanimationp',
                    'label'   => __( 'Animation Out', 'woolentor' ),
                    'desc'    => __( 'Notification Out Animation. <span>( Pro )</span>', 'woolentor' ),
                    'type'    => 'select',
                    'default' => 'fadeOutRight',
                    'options' => array(
                        'fadeOutRight'  =>__( 'fadeOutRight','woolentor' ),
                    ),
                    'class'   => 'proelement',
                ),
                
                array(
                    'name'  => 'background_colorp',
                    'label' => __( 'Background Color', 'woolentor' ),
                    'desc'  => wp_kses_post( 'Notification Background Color. <span>( Pro )</span>', 'woolentor' ),
                    'type'  => 'color',
                    'class' => 'notification_real proelement',
                ),

                array(
                    'name'  => 'heading_colorp',
                    'label' => __( 'Heading Color', 'woolentor' ),
                    'desc'  => wp_kses_post( 'Notification Heading Color. <span>( Pro )</span>', 'woolentor' ),
                    'type'  => 'color',
                    'class' => 'notification_real proelement',
                ),

                array(
                    'name'  => 'content_colorp',
                    'label' => __( 'Content Color', 'woolentor' ),
                    'desc'  => wp_kses_post( 'Notification Content Color. <span>( Pro )</span>', 'woolentor' ),
                    'type'  => 'color',
                    'class' => 'notification_real proelement',
                ),

                array(
                    'name'  => 'cross_colorp',
                    'label' => __( 'Cross Icon Color', 'woolentor' ),
                    'desc'  => wp_kses_post( 'Notification Cross Icon Color. <span>( Pro )</span>', 'woolentor' ),
                    'type'  => 'color',
                    'class' => 'proelement',
                ),

            ),

            'woolentor_others_tabs'=>array(

                array(
                    'name'  => 'loadproductlimit',
                    'label' => __( 'Load Products in Elementor Addons', 'woolentor' ),
                    'desc'  => wp_kses_post( 'Load Products in Elementor Addons', 'woolentor' ),
                    'min'               => 1,
                    'max'               => 100,
                    'step'              => '1',
                    'type'              => 'number',
                    'default'           => '20',
                    'sanitize_callback' => 'floatval',
                    'class'             => 'woolentor_table_row',
                ),

                array(
                    'name'      => 'ajaxsearch',
                    'label'     => __( 'Ajax Search Widget', 'woolentor' ),
                    'type'      => 'checkbox',
                    'default'   => 'off',
                    'class'     => 'woolentor_table_row',
                ),

                array(
                    'name'      => 'ajaxcart_singleproduct',
                    'label'     => __( 'Single Product Ajax Add To Cart', 'woolentor' ),
                    'type'      => 'checkbox',
                    'default'   => 'off',
                    'class'     => 'woolentor_table_row',
                ),
                
                array(
                    'name'      => 'single_product_sticky_add_to_cartp',
                    'label'     => __( 'Single Product Sticky Add To Cart <span>( Pro )</span>', 'woolentor' ),
                    'type'      => 'checkbox',
                    'default'   => 'off',
                    'class'     => 'woolentor_table_row pro',
                ),

            ),

            'woolentor_buy_pro_tabs' => array(),

        );
        
        return array_merge( $settings_fields );
    }


    function plugin_page() {

        echo '<div class="wrap">';
            echo '<h2>'.esc_html__( 'WooLentor Settings','woolentor' ).'</h2>';
            $this->save_message();
            $this->settings_api->show_navigation();
            $this->settings_api->show_forms();
        echo '</div>';

    }

    function save_message() {
        if( isset($_GET['settings-updated']) ) { ?>
            <div class="updated notice is-dismissible"> 
                <p><strong><?php esc_html_e('Successfully Settings Saved.', 'woolentor') ?></strong></p>
            </div>
            <?php
        }
    }

    // Custom Markup

    // General tab
    function woolentor_html_general_tabs(){
        ob_start();
        ?>
            <div class="woolentor-general-tabs">

                <div class="woolentor-document-section">
                    <div class="woolentor-column">
                        <a href="https://hasthemes.com/blog-category/woolentor/" target="_blank">
                            <img src="<?php echo WOOLENTOR_ADDONS_PL_URL; ?>/includes/admin/assets/images/video-tutorial.jpg" alt="<?php esc_attr_e( 'Video Tutorial', 'woolentor' ); ?>">
                        </a>
                    </div>
                    <div class="woolentor-column">
                        <a href="https://demo.hasthemes.com/doc/woolentor/index.html" target="_blank">
                            <img src="<?php echo WOOLENTOR_ADDONS_PL_URL; ?>/includes/admin/assets/images/online-documentation.jpg" alt="<?php esc_attr_e( 'Online Documentation', 'woolentor' ); ?>">
                        </a>
                    </div>
                    <div class="woolentor-column">
                        <a href="https://hasthemes.com/contact-us/" target="_blank">
                            <img src="<?php echo WOOLENTOR_ADDONS_PL_URL; ?>/includes/admin/assets/images/genral-contact-us.jpg" alt="<?php esc_attr_e( 'Contact Us', 'woolentor' ); ?>">
                        </a>
                    </div>
                </div>

                <div class="different-pro-free">
                    <h3 class="wooolentor-section-title"><?php echo esc_html__( 'WooLentor Free VS WooLentor Pro.', 'woolentor' ); ?></h3>

                    <div class="woolentor-admin-row">
                        <div class="features-list-area">
                            <h3><?php echo esc_html__( 'WooLentor Free', 'woolentor' ); ?></h3>
                            <ul>
                                <li><?php echo esc_html__( '18 Elements', 'woolentor' ); ?></li>
                                <li><?php echo esc_html__( 'Shop Page Builder ( Default Layout )', 'woolentor' ); ?></li>
                                <li class="wldel"><del><?php echo esc_html__( 'Shop Page Builder ( Custom Design )', 'woolentor' ); ?></del></li>
                                <li><?php echo esc_html__( '3 Product Custom Layout', 'woolentor' ); ?></li>
                                <li><?php echo esc_html__( 'Single Product Template Builder', 'woolentor' ); ?></li>
                                <li class="wldel"><del><?php echo esc_html__( 'Single Product Individual Layout', 'woolentor' ); ?></del></li>
                                <li class="wldel"><del><?php echo esc_html__( 'Product Archive Category Wise Individual layout', 'woolentor' ); ?></del></li>
                                <li class="wldel"><del><?php echo esc_html__( 'Cart Page Builder', 'woolentor' ); ?></del></li>
                                <li class="wldel"><del><?php echo esc_html__( 'Checkout Page Builder', 'woolentor' ); ?></del></li>
                                <li class="wldel"><del><?php echo esc_html__( 'Thank You Page Builder', 'woolentor' ); ?></del></li>
                                <li class="wldel"><del><?php echo esc_html__( 'My Account Page Builder', 'woolentor' ); ?></del></li>
                                <li class="wldel"><del><?php echo esc_html__( 'My Account Login page Builder', 'woolentor' ); ?></del></li>
                            </ul>
                            <a class="button button-primary" href="<?php echo esc_url( admin_url() ); ?>/plugin-install.php" target="_blank"><?php echo esc_html__( 'Install Now', 'woolenror' ); ?></a>
                        </div>
                        <div class="features-list-area">
                            <h3><?php echo esc_html__( 'WooLentor Pro', 'woolentor' ); ?></h3>
                            <ul>
                                <li><?php echo esc_html__( '41 Elements', 'woolentor' ); ?></li>
                                <li><?php echo esc_html__( 'Shop Page Builder ( Default Layout )', 'woolentor' ); ?></li>
                                <li><?php echo esc_html__( 'Shop Page Builder ( Custom Design )', 'woolentor' ); ?></li>
                                <li><?php echo esc_html__( '15 Product Custom Layout', 'woolentor' ); ?></li>
                                <li><?php echo esc_html__( 'Single Product Template Builder', 'woolentor' ); ?></li>
                                <li><?php echo esc_html__( 'Single Product Individual Layout', 'woolentor' ); ?></li>
                                <li><?php echo esc_html__( 'Product Archive Category Wise Individual layout', 'woolentor' ); ?></li>
                                <li><?php echo esc_html__( 'Cart Page Builder', 'woolentor' ); ?></li>
                                <li><?php echo esc_html__( 'Checkout Page Builder', 'woolentor' ); ?></li>
                                <li><?php echo esc_html__( 'Thank You Page Builder', 'woolentor' ); ?></li>
                                <li><?php echo esc_html__( 'My Account Page Builder', 'woolentor' ); ?></li>
                                <li><?php echo esc_html__( 'My Account Login page Builder', 'woolentor' ); ?></li>
                            </ul>
                            <a class="button button-primary" href="http://bit.ly/2HObEeB" target="_blank"><?php echo esc_html__( 'Buy Now', 'woolenror' ); ?></a>
                        </div>
                    </div>

                </div>

            </div>
        <?php
        echo ob_get_clean();
    }

    // Pop up Box
    function woolentor_html_popup_box(){
        ob_start();
        ?>
            <div id="woolentor-dialog" title="<?php esc_html_e( 'Go Premium', 'woolentor' ); ?>" style="display: none;">
                <div class="wldialog-content">
                    <span><i class="dashicons dashicons-warning"></i></span>
                    <p>
                        <?php
                            echo __('Purchase our','woolentor').' <strong><a href="'.esc_url( 'http://bit.ly/2HObEeB' ).'" target="_blank" rel="nofollow">'.__( 'premium version', 'woolentor' ).'</a></strong> '.__('to unlock these pro elements!','woolentor');
                        ?>
                    </p>
                </div>
            </div>

            <script>
                ( function( $ ) {
                    
                    $(function() {
                        $( '.woolentor_table_row.pro,.proelement label' ).click(function() {
                            $( "#woolentor-dialog" ).dialog({
                                modal: true,
                                minWidth: 500,
                                buttons: {
                                    Ok: function() {
                                      $( this ).dialog( "close" );
                                    }
                                }
                            });
                        });
                        $(".woolentor_table_row.pro input[type='checkbox'],.proelement select,.proelement input[type='text'],.proelement input[type='radio']").attr("disabled", true);
                    });

                } )( jQuery );
            </script>
        <?php
        echo ob_get_clean();
    }

    // Theme Library
    function woolentor_html_themes_library_tabs() {
        ob_start();
        ?>
        <div class="woolentor-themes-laibrary">
            <p><?php echo esc_html__( 'Use Our WooCommerce Theme for your online Store.', 'woolentor' ); ?></p>
            <div class="woolentor-themes-area">
                <div class="woolentor-themes-row">

                    <div class="woolentor-single-theme"><img src="<?php echo WOOLENTOR_ADDONS_PL_URL; ?>/includes/admin/assets/images/99fy.png" alt="">
                        <div class="woolentor-theme-content">
                            <h3><?php echo esc_html__( '99Fy - WooCommerce Theme', 'woolentor' ); ?></h3>
                            <a href="https://demo.hasthemes.com/99fy-preview/index.html" class="woolentor-button" target="_blank"><?php echo esc_html__( 'Preview', 'woolentor' ); ?></a>
                            <a href="https://downloads.wordpress.org/theme/99fy.3.1.2.zip" class="woolentor-button"><?php echo esc_html__( 'Download', 'woolentor' ); ?></a>
                        </div>
                    </div>
                    
                    <div class="woolentor-single-theme"><img src="<?php echo WOOLENTOR_ADDONS_PL_URL; ?>/includes/admin/assets/images/parlo.png" alt="">
                        <div class="woolentor-theme-content">
                            <h3><?php echo esc_html__( 'Parlo - WooCommerce Theme', 'woolentor' ); ?></h3>
                            <a href="http://demo.shrimpthemes.com/1/parlo/" class="woolentor-button" target="_blank"><?php echo esc_html__( 'Preview', 'woolentor' ); ?></a>
                            <a href="https://freethemescloud.com/item/parlo-free-woocommerce-theme/" class="woolentor-button"><?php echo esc_html__( 'Download', 'woolentor' ); ?></a>
                        </div>
                    </div>

                    <div class="woolentor-single-theme"><img src="<?php echo WOOLENTOR_ADDONS_PL_URL; ?>/includes/admin/assets/images/flone.png" alt="">
                        <div class="woolentor-theme-content">
                            <h3><?php echo esc_html__( 'Flone – Minimal WooCommerce Theme', 'woolentor' ); ?> <span><?php echo esc_html__( '( Pro )', 'woolentor' ); ?></span></h3>
                            <a href="http://demo.shrimpthemes.com/2/flone/" class="woolentor-button" target="_blank"><?php echo esc_html__( 'Preview', 'woolentor' ); ?></a>
                        </div>
                    </div>

                    <div class="woolentor-single-theme"><img src="<?php echo WOOLENTOR_ADDONS_PL_URL; ?>/includes/admin/assets/images/holmes.png" alt="">
                        <div class="woolentor-theme-content">
                            <h3><?php echo esc_html__( 'Homes - Multipurpose WooCommerce Theme', 'woolentor' ); ?> <span><?php echo esc_html__( '( Pro )', 'woolentor' ); ?></span></h3>
                            <a href="http://demo.shrimpthemes.com/1/holmes/" class="woolentor-button" target="_blank"><?php echo esc_html__( 'Preview', 'woolentor' ); ?></a>
                        </div>
                    </div>
                    
                    <div class="woolentor-single-theme"><img src="<?php echo WOOLENTOR_ADDONS_PL_URL; ?>/includes/admin/assets/images/daniel-home-1.png" alt="">
                        <div class="woolentor-theme-content">
                            <h3><?php echo esc_html__( 'Daniel - WooCommerce Theme', 'woolentor' ); ?> <span><?php echo esc_html__( '( Pro )', 'woolentor' ); ?></span></h3>
                            <a href="http://demo.shrimpthemes.com/2/daniel/" class="woolentor-button" target="_blank"><?php echo esc_html__( 'Preview', 'woolentor' ); ?></a>
                        </div>
                    </div>
                    
                    <div class="woolentor-single-theme"><img src="<?php echo WOOLENTOR_ADDONS_PL_URL; ?>/includes/admin/assets/images/hurst-home-1.png" alt="">
                        <div class="woolentor-theme-content">
                            <h3><?php echo esc_html__( 'Hurst - WooCommerce Theme', 'woolentor' ); ?> <span><?php echo esc_html__( '( Pro )', 'woolentor' ); ?></span></h3>
                            <a href="http://demo.shrimpthemes.com/4/hurstem/" class="woolentor-button" target="_blank"><?php echo esc_html__( 'Preview', 'woolentor' ); ?></a>
                        </div>
                    </div>

                </div>
            </div>
        </div>
        <?php
        echo ob_get_clean();
    }

    // Buy Pro
    function woolentor_html_buy_pro_tabs(){
        ob_start();
        ?>
            <div class="woolentor-admin-tab-area">
                <ul class="woolentor-admin-tabs">
                    <li><a href="#oneyear" class="wlactive"><?php echo esc_html__( 'One Year', 'woolentor' ); ?></a></li>
                    <li><a href="#lifetime"><?php echo esc_html__( 'Life Time', 'woolentor' ); ?></a></li>
                </ul>
            </div>
            
            <div id="oneyear" class="woolentor-admin-tab-pane wlactive">
                <div class="woolentor-admin-row">

                    <div class="woolentor-price-plan">
                        <a href="http://bit.ly/2HObEeB" target="_blank"><img src="https://demo.hasthemes.com/pricing-plan/one_year_single_website.png" alt="<?php echo esc_attr__( 'One Year Single Website','woolentor' );?>"></a>
                    </div>

                    <div class="woolentor-price-plan">
                        <a href="http://bit.ly/2HObEeB" target="_blank"><img src="https://demo.hasthemes.com/pricing-plan/one_year_elementor_guru.png" alt="<?php echo esc_attr__( 'One Year Unlimited Website','woolentor' );?>"></a>
                    </div>

                    <div class="woolentor-price-plan">
                        <a href="http://bit.ly/2HObEeB" target="_blank"><img src="https://demo.hasthemes.com/pricing-plan/one_year_wpbundle.png" alt="<?php echo esc_attr__( 'One Year Unlimited Websites','woolentor' );?>"></a>
                    </div>

                </div>
            </div>

            <div id="lifetime" class="woolentor-admin-tab-pane">
                
                <div class="woolentor-admin-row">
                    <div class="woolentor-price-plan">
                        <a href="http://bit.ly/2HObEeB" target="_blank"><img src="https://demo.hasthemes.com/pricing-plan/life_time_single_website.png" alt="<?php echo esc_attr__( 'Life Time Single Website','woolentor' );?>"></a>
                    </div>

                    <div class="woolentor-price-plan">
                        <a href="http://bit.ly/2HObEeB" target="_blank"><img src="https://demo.hasthemes.com/pricing-plan/life_time_elementor_guru.png" alt="<?php echo esc_attr__( 'Life time Unlimited Website','woolentor' );?>"></a>
                    </div>

                    <div class="woolentor-price-plan">
                        <a href="http://bit.ly/2HObEeB" target="_blank"><img src="https://demo.hasthemes.com/pricing-plan/life_time_wpbundle.png" alt="<?php echo esc_attr__( 'Life Time Unlimited Websites','woolentor' );?>"></a>
                    </div>
                </div>

            </div>

        <?php
        echo ob_get_clean();
    }
    

}

new Woolentor_Admin_Settings();
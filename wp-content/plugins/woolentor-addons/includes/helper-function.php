<?php

// Exit if accessed directly
if( ! defined( 'ABSPATH' ) ) exit();

/**
* Elementor Version check
* Return boolean value
*/
function woolentor_is_elementor_version( $operator = '<', $version = '2.6.0' ) {
    return defined( 'ELEMENTOR_VERSION' ) && version_compare( ELEMENTOR_VERSION, $version, $operator );
}


/**
 *  Taxonomy List
 * @return array
 */
function woolentor_taxonomy_list( $taxonomy = 'product_cat' ){
    $terms = get_terms( array(
        'taxonomy' => $taxonomy,
        'hide_empty' => true,
    ));
    if ( ! empty( $terms ) && ! is_wp_error( $terms ) ){
        foreach ( $terms as $term ) {
            $options[ $term->slug ] = $term->name;
        }
        return $options;
    }
}


/**
 * Get Post List
 * return array
 */
function woolentor_post_name( $post_type = 'post' ){
    $options = array();
    $options['0'] = __('Select','woolentor');
    $perpage = woolentor_get_option( 'loadproductlimit', 'woolentor_others_tabs', '20' );
    $all_post = array( 'posts_per_page' => $perpage, 'post_type'=> $post_type );
    $post_terms = get_posts( $all_post );
    if ( ! empty( $post_terms ) && ! is_wp_error( $post_terms ) ){
        foreach ( $post_terms as $term ) {
            $options[ $term->ID ] = $term->post_title;
        }
        return $options;
    }
}

/*
 * Elementor Templates List
 * return array
 */
function woolentor_elementor_template() {
    $templates = '';
    if( class_exists('\Elementor\Plugin') ){
        $templates = \Elementor\Plugin::instance()->templates_manager->get_source( 'local' )->get_items();
    }
    $types = array();
    if ( empty( $templates ) ) {
        $template_lists = [ '0' => __( 'Do not Saved Templates.', 'woolentor' ) ];
    } else {
        $template_lists = [ '0' => __( 'Select Template', 'woolentor' ) ];
        foreach ( $templates as $template ) {
            $template_lists[ $template['template_id'] ] = $template['title'] . ' (' . $template['type'] . ')';
        }
    }
    return $template_lists;
}

/*
 * Plugisn Options value
 * return on/off
 */
function woolentor_get_option( $option, $section, $default = '' ){
    $options = get_option( $section );
    if ( isset( $options[$option] ) ) {
        return $options[$option];
    }
    return $default;
}

function woolentor_get_option_label_text( $option, $section, $default = '' ){
    $options = get_option( $section );
    if ( isset( $options[$option] ) ) {
        if( !empty($options[$option]) ){
            return $options[$option];
        }
        return $default;
    }
    return $default;
}

/*
 * HTML Tag list
 * return array
 */
function woolentor_html_tag_lists() {
    $html_tag_list = [
        'h1'   => __( 'H1', 'woolentor' ),
        'h2'   => __( 'H2', 'woolentor' ),
        'h3'   => __( 'H3', 'woolentor' ),
        'h4'   => __( 'H4', 'woolentor' ),
        'h5'   => __( 'H5', 'woolentor' ),
        'h6'   => __( 'H6', 'woolentor' ),
        'p'    => __( 'p', 'woolentor' ),
        'div'  => __( 'div', 'woolentor' ),
        'span' => __( 'span', 'woolentor' ),
    ];
    return $html_tag_list;
}

/* 
* Category list
* return first one
*/
function woolentor_get_product_category_list( $id = null, $taxonomy = 'product_cat', $limit = 1 ) { 
    $terms = get_the_terms( $id, $taxonomy );
    $i = 0;
    if ( is_wp_error( $terms ) )
        return $terms;

    if ( empty( $terms ) )
        return false;

    foreach ( $terms as $term ) {
        $i++;
        $link = get_term_link( $term, $taxonomy );
        if ( is_wp_error( $link ) ) {
            return $link;
        }
        echo '<a href="' . esc_url( $link ) . '">' . $term->name . '</a>';
        if( $i == $limit ){
            break;
        }else{ continue; }
    }
}

/*
* If Active WooCommerce
*/
if( class_exists('WooCommerce') ){

    /* Custom product badge */
    function woolentor_custom_product_badge( $show = 'yes' ){
        global $product;
        $custom_saleflash_text = get_post_meta( get_the_ID(), '_saleflash_text', true );
        if( $show == 'yes' ){
            if( !empty( $custom_saleflash_text ) && $product->is_in_stock() ){
                if( $product->is_featured() ){
                    echo '<span class="ht-product-label ht-product-label-left hot">' . esc_html( $custom_saleflash_text ) . '</span>';
                }else{
                    echo '<span class="ht-product-label ht-product-label-left">' . esc_html( $custom_saleflash_text ) . '</span>';
                }
            }
        }
    }

    /* Sale badge */
    function woolentor_sale_flash( $offertype = 'default' ){
        global $product;
        if( $product->is_on_sale() && $product->is_in_stock() ){
            if( $offertype !='default' && $product->get_regular_price() > 0 ){
                $_off_percent = (1 - round($product->get_price() / $product->get_regular_price(), 2))*100;
                $_off_price = round($product->get_regular_price() - $product->get_price(), 0);
                $_price_symbol = get_woocommerce_currency_symbol();
                $symbol_pos = get_option('woocommerce_currency_pos', 'left');
                $price_display = '';
                switch( $symbol_pos ){
                    case 'left':
                        $price_display = '-'.$_price_symbol.$_off_price;
                    break;
                    case 'right':
                        $price_display = '-'.$_off_price.$_price_symbol;
                    break;
                    case 'left_space':
                        $price_display = '-'.$_price_symbol.' '.$_off_price;
                    break;
                    default: /* right_space */
                        $price_display = '-'.$_off_price.' '.$_price_symbol;
                    break;
                }
                if( $offertype == 'number' ){
                    echo '<span class="ht-product-label ht-product-label-right">'.$price_display.'</span>';
                }elseif( $offertype == 'percent'){
                    echo '<span class="ht-product-label ht-product-label-right">'.$_off_percent.'%</span>';
                }else{ echo ' '; }

            }else{
                echo '<span class="ht-product-label ht-product-label-right">'.esc_html__( 'Sale!', 'woolentor' ).'</span>';
            }
        }else{
            $out_of_stock = get_post_meta( get_the_ID(), '_stock_status', true );
            $out_of_stock_text = apply_filters( 'woolentor_shop_out_of_stock_text', __( 'Out of stock', 'woolentor' ) );
            if ( 'outofstock' === $out_of_stock ) {
                echo '<span class="ht-stockout ht-product-label ht-product-label-right">'.esc_html( $out_of_stock_text ).'</span>';
            }
        }

    }

    // Shop page header result count
    function woolentor_product_result_count( $total, $perpage, $paged ){
        wc_set_loop_prop( 'total', $total );
        wc_set_loop_prop( 'per_page', $perpage );
        wc_set_loop_prop( 'current_page', $paged );
        $geargs = array(
            'total'    => wc_get_loop_prop( 'total' ),
            'per_page' => wc_get_loop_prop( 'per_page' ),
            'current'  => wc_get_loop_prop( 'current_page' ),
        );
        wc_get_template( 'loop/result-count.php', $geargs );
    }

    // product shorting
    function woolentor_product_shorting( $getorderby ){
        ?>
        <div class="woolentor-custom-sorting">
            <form class="woocommerce-ordering" method="get">
                <select name="orderby" class="orderby">
                    <?php
                        $catalog_orderby = apply_filters( 'woocommerce_catalog_orderby', array(
                            'menu_order' => __( 'Default sorting', 'woolentor' ),
                            'popularity' => __( 'Sort by popularity', 'woolentor' ),
                            'rating'     => __( 'Sort by average rating', 'woolentor' ),
                            'date'       => __( 'Sort by latest', 'woolentor' ),
                            'price'      => __( 'Sort by price: low to high', 'woolentor' ),
                            'price-desc' => __( 'Sort by price: high to low', 'woolentor' ),
                        ) );
                        foreach ( $catalog_orderby as $id => $name ){
                            echo '<option value="' . esc_attr( $id ) . '" ' . selected( $getorderby, $id, false ) . '>' . esc_attr( $name ) . '</option>';
                        }
                    ?>
                </select>
                <?php
                    // Keep query string vars intact
                    foreach ( $_GET as $key => $val ) {
                        if ( 'orderby' === $key || 'submit' === $key )
                            continue;
                        if ( is_array( $val ) ) {
                            foreach( $val as $innerVal ) {
                                echo '<input type="hidden" name="' . esc_attr( $key ) . '[]" value="' . esc_attr( $innerVal ) . '" />';
                            }
                        } else {
                            echo '<input type="hidden" name="' . esc_attr( $key ) . '" value="' . esc_attr( $val ) . '" />';
                        }
                    }
                ?>
            </form>
        </div>
        <?php
    }

    // Custom page pagination
    function woolentor_custom_pagination( $totalpage ){
        echo '<div class="ht-row woocommerce"><div class="ht-col-xs-12"><nav class="woocommerce-pagination">';
            echo paginate_links( apply_filters(
                    'woocommerce_pagination_args', array(
                        'base'=> esc_url( str_replace( 999999999, '%#%', remove_query_arg( 'add-to-cart', get_pagenum_link( 999999999, false ) ) ) ), 
                        'format'    => '', 
                        'current'   => max( 1, get_query_var( 'paged' ) ), 
                        'total'     => $totalpage, 
                        'prev_text' => '&larr;', 
                        'next_text' => '&rarr;', 
                        'type'      => 'list', 
                        'end_size'  => 3, 
                        'mid_size'  => 3 
                    )
                )       
            );
        echo '</div></div></div>';
    }

    // Change Product Per page
    if( woolentor_get_option( 'enablecustomlayout', 'woolentor_woo_template_tabs', 'on' ) == 'on' ){
        function woolentor_custom_number_of_posts() {
            $limit = woolentor_get_option( 'shoppageproductlimit', 'woolentor_woo_template_tabs', 2 );
            $postsperpage = apply_filters( 'product_custom_limit', $limit );
            return $postsperpage;
        }
        add_filter( 'loop_shop_per_page', 'woolentor_custom_number_of_posts' );
    }

    // Customize rating html
    if( !function_exists('woolentor_wc_get_rating_html') ){
        function woolentor_wc_get_rating_html(){
            if ( get_option( 'woocommerce_enable_review_rating' ) === 'no' ) { return; }
            global $product;
            $rating_count = $product->get_rating_count();
            $review_count = $product->get_review_count();
            $average      = $product->get_average_rating();
            if ( $rating_count > 0 ) {
                $rating_whole = $average / 5*100;
                $wrapper_class = is_single() ? 'rating-number' : 'top-rated-rating';
                ob_start();
            ?>
            <div class="<?php echo esc_attr( $wrapper_class ); ?>">
                <span class="ht-product-ratting">
                    <span class="ht-product-user-ratting" style="width: <?php echo esc_attr( $rating_whole );?>%;">
                        <i class="sli sli-star"></i>
                        <i class="sli sli-star"></i>
                        <i class="sli sli-star"></i>
                        <i class="sli sli-star"></i>
                        <i class="sli sli-star"></i>
                    </span>
                    <i class="sli sli-star"></i>
                    <i class="sli sli-star"></i>
                    <i class="sli sli-star"></i>
                    <i class="sli sli-star"></i>
                    <i class="sli sli-star"></i>
                </span>
            </div>
            <?php
                $html = ob_get_clean();
            } else { $html  = ''; }
            return $html;
        }
    }

    // Quick View Markup
    function woolentor_quick_view_html(){
        echo '<div class="woocommerce" id="htwlquick-viewmodal"><div class="htwl-modal-dialog product"><div class="htwl-modal-content"><button type="button" class="htcloseqv"><span class="sli sli-close"></span></button><div class="htwl-modal-body"></div></div></div></div>';
    }
    add_action( 'woolentor_footer_render_content', 'woolentor_quick_view_html', 10 );

    // HTML Markup Render in footer
    function woolentor_html_render_infooter(){
        do_action( 'woolentor_footer_render_content' );
    }
    add_action( 'wp_footer', 'woolentor_html_render_infooter' );

    // Quick view Ajax Callback
    function woolentor_wc_quickview() {
        // Get product from request.
        if ( isset( $_POST['id'] ) && (int) $_POST['id'] ) {
            global $post, $product, $woocommerce;
            $id      = ( int ) $_POST['id'];
            $post    = get_post( $id );
            $product = get_product( $id );
            if ( $product ) { include WOOLENTOR_ADDONS_PL_PATH.'includes/quickview-content.php'; }
        }
        wp_die();
    }
    add_action( 'wp_ajax_woolentor_quickview', 'woolentor_wc_quickview' );
    add_action( 'wp_ajax_nopriv_woolentor_quickview', 'woolentor_wc_quickview' );

}

/**
* Usages: Compare button shortcode [yith_compare_button] From "YITH WooCommerce Compare" plugins.
* Plugins URL: https://wordpress.org/plugins/yith-woocommerce-compare/
* File Path: yith-woocommerce-compare/includes/class.yith-woocompare-frontend.php
* The Function "woolentor_compare_button" Depends on YITH WooCommerce Compare plugins. If YITH WooCommerce Compare is installed and actived, then it will work.
*/
function woolentor_compare_button( $buttonstyle = 1 ){
    if( !class_exists('YITH_Woocompare') ) return;
    global $product;
    $product_id = $product->get_id();
    $comp_link = home_url() . '?action=yith-woocompare-add-product';
    $comp_link = add_query_arg('id', $product_id, $comp_link);

    if( $buttonstyle == 1 ){
        echo do_shortcode('[yith_compare_button]');
    }else{
        echo '<a title="'. esc_attr__('Add to Compare', 'woolentor') .'" href="'. esc_url( $comp_link ) .'" class="woolentor-compare compare" data-product_id="'. esc_attr( $product_id ) .'" rel="nofollow">'.esc_html__( 'Compare', 'woolentor' ).'</a>';
    }

}

/**
* Usages: "woolentor_add_to_wishlist_button()" function is used  to modify the wishlist button from "YITH WooCommerce Wishlist" plugins.
* Plugins URL: https://wordpress.org/plugins/yith-woocommerce-wishlist/
* File Path: yith-woocommerce-wishlist/templates/add-to-wishlist.php
* The below Function depends on YITH WooCommerce Wishlist plugins. If YITH WooCommerce Wishlist is installed and actived, then it will work.
*/

function woolentor_add_to_wishlist_button( $normalicon = '<i class="fa fa-heart-o"></i>', $addedicon = '<i class="fa fa-heart"></i>', $tooltip = 'no' ) {
    global $product, $yith_wcwl;

    if ( ! class_exists( 'YITH_WCWL' ) || empty(get_option( 'yith_wcwl_wishlist_page_id' ))) return;

    $url          = YITH_WCWL()->get_wishlist_url();
    $product_type = $product->get_type();
    $exists       = $yith_wcwl->is_product_in_wishlist( $product->get_id() );
    $classes      = 'class="add_to_wishlist"';
    $add          = get_option( 'yith_wcwl_add_to_wishlist_text' );
    $browse       = get_option( 'yith_wcwl_browse_wishlist_text' );
    $added        = get_option( 'yith_wcwl_product_added_text' );

    $output = '';

    $output  .= '<div class="'.( $tooltip == 'yes' ? '' : 'tooltip_no' ).' wishlist button-default yith-wcwl-add-to-wishlist add-to-wishlist-' . esc_attr( $product->get_id() ) . '">';
        $output .= '<div class="yith-wcwl-add-button';
            $output .= $exists ? ' hide" style="display:none;"' : ' show"';
            $output .= '><a href="' . esc_url( htmlspecialchars( YITH_WCWL()->get_wishlist_url() ) ) . '" data-product-id="' . esc_attr( $product->get_id() ) . '" data-product-type="' . esc_attr( $product_type ) . '" ' . $classes . ' >'.$normalicon.'<span class="ht-product-action-tooltip">'.esc_html( $add ).'</span></a>';
            $output .= '<i class="fa fa-spinner fa-pulse ajax-loading" style="visibility:hidden"></i>';
        $output .= '</div>';

        $output .= '<div class="yith-wcwl-wishlistaddedbrowse hide" style="display:none;"><a class="" href="' . esc_url( $url ) . '">'.$addedicon.'<span class="ht-product-action-tooltip">'.esc_html( $browse ).'</span></a></div>';
        $output .= '<div class="yith-wcwl-wishlistexistsbrowse ' . ( $exists ? 'show' : 'hide' ) . '" style="display:' . ( $exists ? 'block' : 'none' ) . '"><a href="' . esc_url( $url ) . '" class="">'.$addedicon.'<span class="ht-product-action-tooltip">'.esc_html( $added ).'</span></a></div>';
    $output .= '</div>';
    return $output;
}
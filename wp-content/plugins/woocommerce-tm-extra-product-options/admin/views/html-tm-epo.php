<?php
/**
 *
 *   View for displaying saved TM EPOs
 *
 */

// Direct access security
if ( !defined( 'TM_EPO_PLUGIN_SECURITY' ) ) {
    die();
}
global $post_id, $tm_is_ajax, $woocommerce;
$attributes = tc_get_attributes($post_id);
$tm_meta_cpf=tc_get_post_meta( $post_id, 'tm_meta_cpf', true );

// Check for variations
$variation_attribute_found = false;
if ( $attributes ) {
    foreach ( $attributes as $attribute ) {
        if ( isset( $attribute['is_variation'] ) ) {
            $variation_attribute_found = true;
            break;
        }
    }
}

// Get variations
$args = array(
    'post_type'     => 'product_variation',
    'post_status'   => array( 'private', 'publish' ),
    'numberposts'   => -1,
    'orderby'       => 'menu_order',
    'order'         => 'asc',
    'post_parent'   => $post_id
);
$variations = get_posts( $args );

//  $variation_attribute_found && $variations
if ( ! ( $attributes  ) ) : ?>
        <div id="message" class="tm-inner inline woocommerce-message">
            <p><?php _e( 'Before adding Extra Options, add and save some attributes on the <strong>Attributes</strong> tab.', 'woocommerce-tm-extra-product-options' ); ?></p>
        </div>
<?php else : ?>
        <p class="toolbar">
            <a href="#" class="expand_all"><?php _e( 'Expand all', 'woocommerce-tm-extra-product-options' ); ?></a> / <a href="#" class="close_all"><?php _e( 'Close all', 'woocommerce-tm-extra-product-options' ); ?></a>
        </p>
        <div class="woocommerce_tm_epos wc-metaboxes">
<?php
// Get parent data
$parent_data = array(
    'id'            => $post_id,
    'attributes'    => $attributes
);

$args = array(
    'post_type'     => TM_EPO_LOCAL_POST_TYPE,
    'post_status'   => array( 'private', 'publish' ),
    'numberposts'   => -1,
    'orderby'       => 'menu_order',
    'order'         => 'asc',
    'post_parent'   => $post_id
);
$tmepos = get_posts( $args );

if ( $tmepos ) {
    $loop = 0;
    foreach ( $tmepos as $price ) {
        $tmcp_id                        = absint( $price->ID );
        $tmcp_post_status               = esc_attr( $price->post_status );
        $tmcp_data                      = get_post_meta( $tmcp_id );
        $variation_fields               = array(
            '_regular_price',
            'tmcp_required',
            'tmcp_hide_price',
            'tmcp_limit',
            '_regular_price_type'
        );
        foreach ( $variation_fields as $field ) {
            $$field                     = isset( $tmcp_data[ $field ][0] ) ? maybe_unserialize( $tmcp_data[ $field ][0] ) : '';

        }
        /*
        * $key = attirbute
        * $k = variation
        * $v = price
        */
        if ( isset($_regular_price) && is_array( $_regular_price ) ) {
            foreach ( $_regular_price as $key=>$value ) {
                foreach ( $value as $k=>$v ) {
                    $_regular_price[$key][$k]=wc_format_localized_price( $v );
                }

            }
        }

        include ('html-tm-epo-admin.php');

        $loop++;
    }
}
?>
        </div>
        <p class="toolbar">
            <button type="button" class="tc tc-button fr tm_add_epo" <?php disabled( ( count($attributes)>0 ), false ); ?>><?php _e( 'Add Extra Option', 'woocommerce-tm-extra-product-options' ); ?></button>
            <span class="fr">
<?php
/* Ouput Attributes List */
_e( 'Attribute:', 'woocommerce-tm-extra-product-options' );
echo ' <select class="tmcp_attr_list" name="tmcp_attr_list">';
foreach ( $attributes as $attribute ) {
    // Only deal with attributes that are not variations
    if (  $attribute['is_variation'] ) {
        continue;
    }

    echo '<option value="' . esc_attr( sanitize_title( $attribute['name'] ) ) . '">' . esc_html( wc_attribute_label( $attribute['name'] ) ) . '</option>';
}
echo '</select>';
?>
            </span>
        </p>
<?php 
endif;
?>
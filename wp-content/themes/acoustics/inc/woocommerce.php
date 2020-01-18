<?php
/**
 * WooCommerce Compatibility File
 *
 * @link https://woocommerce.com/
 *
 * @author      CodeGearThemes
 * @category    WordPress
 * @package     Acoustics
 * @version     1.0.0
 *
 */

/**
 * WooCommerce setup function.
 *
 * @link https://docs.woocommerce.com/document/third-party-custom-theme-compatibility/
 * @link https://github.com/woocommerce/woocommerce/wiki/Enabling-product-gallery-features-(zoom,-swipe,-lightbox)-in-3.0.0
 *
 * @return void
 */
function acoustics_woocommerce_setup() {
  add_theme_support( 'woocommerce' );
	add_theme_support( 'wc-product-gallery-zoom' );
	add_theme_support( 'wc-product-gallery-slider' );
}

add_action( 'after_setup_theme', 'acoustics_woocommerce_setup' );

/**
 * WooCommerce specific scripts & stylesheets.
 *
 * @return void
 */
function acoustics_woocommerce_scripts() {
    wp_enqueue_style( 'acoustics-woocommerce-style', get_template_directory_uri() . '/assets/frontend/css/woocommerce.css' );

	$font_path   = WC()->plugin_url() . '/assets/fonts/';
	$inline_font = '@font-face {
			font-family: "star";
			src: url("' . $font_path . 'star.eot");
			src: url("' . $font_path . 'star.eot?#iefix") format("embedded-opentype"),
				url("' . $font_path . 'star.woff") format("woff"),
				url("' . $font_path . 'star.ttf") format("truetype"),
				url("' . $font_path . 'star.svg#star") format("svg");
			font-weight: normal;
			font-style: normal;
		}';

	wp_add_inline_style( 'acoustics-woocommerce-style', $inline_font );
}

add_action( 'wp_enqueue_scripts', 'acoustics_woocommerce_scripts' );

/**
 * Disable the default WooCommerce stylesheet.
 *
 * Removing the default WooCommerce stylesheet and enqueing your own will
 * protect you during WooCommerce core updates.
 *
 * @link https://docs.woocommerce.com/document/disable-the-default-stylesheet/
 */
add_filter( 'woocommerce_enqueue_styles', '__return_empty_array' );

/**
 * Add 'woocommerce-active' class to the body tag.
 *
 * @param  array $classes CSS classes applied to the body tag.
 * @return array $classes modified to include 'woocommerce-active' class.
 */
function acoustics_woocommerce_active_body_class( $classes ) {
  $classes[] = 'woocommerce-active';

	return $classes;
}

add_filter( 'body_class', 'acoustics_woocommerce_active_body_class' );

/**
 * Products per page.
 *
 * @return integer number of products.
 */
function acoustics_woocommerce_products_per_page() {
  return 12;
}

add_filter( 'loop_shop_per_page', 'acoustics_woocommerce_products_per_page' );

/**
 * Product gallery thumnbail columns.
 *
 * @return integer number of columns.
 */
function acoustics_woocommerce_thumbnail_columns() {
  return 4;
}

add_filter( 'woocommerce_product_thumbnails_columns', 'acoustics_woocommerce_thumbnail_columns' );

/**
 * Default loop columns on product archives.
 *
 * @return integer products per row.
 */
function acoustics_woocommerce_loop_columns() {
  $column = get_theme_mod( 'acoustics_archive_layout', 'left-sidebar' );
  if( $column == 'no-sidebar'){
	  return 4;
  }else{
  	return 3;
  }
}

add_filter( 'loop_shop_columns', 'acoustics_woocommerce_loop_columns' );

/**
 * Related Products Args.
 *
 * @param array $args related products args.
 * @return array $args related products args.
 */
function acoustics_woocommerce_related_products_args( $args ) {
  $defaults = array(
		'posts_per_page' => 3,
		'columns'        => 3,
	);

	$args = wp_parse_args( $defaults, $args );

	return $args;
}

add_filter( 'woocommerce_output_related_products_args', 'acoustics_woocommerce_related_products_args' );

if ( ! function_exists( 'acoustics_woocommerce_product_columns_wrapper' ) ) {
	/**
	 * Product columns wrapper.
	 *
	 * @return  void
	 */
  function acoustics_woocommerce_product_columns_wrapper() {
		$columns = acoustics_woocommerce_loop_columns();
		echo '<div class="columns-' . absint( $columns ) . '">';
}
}

add_action( 'woocommerce_before_shop_loop', 'acoustics_woocommerce_product_columns_wrapper', 40 );

if ( ! function_exists( 'acoustics_woocommerce_product_columns_wrapper_close' ) ) {
	/**
	 * Product columns wrapper close.
	 *
	 * @return  void
	 */
  function acoustics_woocommerce_product_columns_wrapper_close() {
		echo '</div>';
}
}

add_action( 'woocommerce_after_shop_loop', 'acoustics_woocommerce_product_columns_wrapper_close', 40 );

/**
 * Remove default WooCommerce wrapper.
 */
remove_action( 'woocommerce_before_main_content', 'woocommerce_output_content_wrapper', 10 );
remove_action( 'woocommerce_after_main_content', 'woocommerce_output_content_wrapper_end', 10 );
remove_action( 'woocommerce_sidebar', 'woocommerce_get_sidebar', 10 );

if ( ! function_exists( 'acoustics_woocommerce_wrapper_before' ) ) {
	/**
	 * Before Content.
	 *
	 * Wraps all WooCommerce content in wrappers which match the theme markup.
	 *
	 * @return void
	 */
  function acoustics_woocommerce_wrapper_before() {
		$layout = get_theme_mod( 'acoustics_archive_layout', 'left-sidebar' );
		$class = 'col-md-12 col-sm-12 col-xs-12';
		if( is_shop() || is_product_category() ):
			switch ( $layout ) {
			    case 'left-sidebar':
			       	$class = 'col-md-9 col-sm-9 col-xs-12 pull-right';
  					break;
			    case 'no-sidebar': $class = 'col-md-12 col-sm-12 col-xs-12';
  					break;
			    case 'right-sidebar': $class = 'col-md-9 col-sm-9 col-xs-12 pull-left';
  					break;
			    default: $class = 'col-md-12 col-sm-12 col-xs-12';
			}

		endif;
		?>
		<div class="section-default section--woocommerce-template">
			<div class="container">
				<div class="row">
					<section id="primary" class="content-area <?php echo esc_attr( $class ); ?>" >
						<main id="main" class="site-main" role="main">
			<?php
}
}

add_action( 'woocommerce_before_main_content', 'acoustics_woocommerce_wrapper_before' );

if ( ! function_exists( 'acoustics_woocommerce_wrapper_after' ) ) {
	/**
	 * After Content.
	 *
	 * Closes the wrapping divs.
	 *
	 * @return void
	 */
function acoustics_woocommerce_wrapper_after() {
						?>
						</main>
					</section>
					<?php
					if( is_shop() || is_product_category() ){
						$layout = get_theme_mod( 'acoustics_archive_layout', 'left-sidebar' );
						if( $layout == 'left-sidebar' || $layout == 'right-sidebar' ): get_sidebar( 'shop' );
endif;
}
					?>
				</div>
			</div>
		</div>
		<?php
}
}

add_action( 'woocommerce_after_main_content', 'acoustics_woocommerce_wrapper_after' );

remove_action( 'woocommerce_before_main_content', 'woocommerce_breadcrumb', 20 );
remove_action( 'woocommerce_after_single_product_summary', 'woocommerce_upsell_display', 15 );
remove_action( 'woocommerce_after_single_product_summary', 'woocommerce_output_related_products', 20 );

add_action( 'acoustics_breadcrumb', 'woocommerce_breadcrumb', 0 );
add_action( 'woocommerce_after_single_product' , 'woocommerce_upsell_display' , 5);
add_action( 'woocommerce_after_single_product' , 'woocommerce_output_related_products' , 10);
add_action( 'woocommerce_before_shop_loop', 'acoustics_woocommerce_result_start', 19 );
add_action( 'woocommerce_before_shop_loop', 'acoustics_woocommerce_result_end', 31 );


if( ! function_exists( 'acoustics_woocommerce_result_start' ) ) {
	function acoustics_woocommerce_result_start(){
		if( woocommerce_products_will_display() ):
		?>
			<div class="section-result section-action-result clearfix">
		<?php
	endif;
	}
}

if( ! function_exists( 'acoustics_woocommerce_result_end' ) ) {
	function acoustics_woocommerce_result_end(){
		if( woocommerce_products_will_display() ):
		?>
			</div>
		<?php
	endif;
	}
}

if ( ! function_exists( 'acoustics_woocommerce_cart_link_fragment' ) ) {
	/**
	 * Cart Fragments.
	 *
	 * Ensure cart contents update when products are added to the cart via AJAX.
	 *
	 * @param array $fragments Fragments to refresh via AJAX.
	 * @return array Fragments to refresh via AJAX.
	 */
	function acoustics_woocommerce_cart_link_fragment( $fragments ) {
			ob_start();
			acoustics_woocommerce_cart_link();
			$fragments['a.cart-contents'] = ob_get_clean();

			return $fragments;
	}
}

add_filter( 'woocommerce_add_to_cart_fragments', 'acoustics_woocommerce_cart_link_fragment' );

if ( ! function_exists( 'acoustics_woocommerce_cart_link' ) ) {
	/**
	 * Cart Link.
	 *
	 * Displayed a link to the cart including the number of items present and the cart total.
	 *
	 * @return void
	 */
function acoustics_woocommerce_cart_link() {
		?>
		<a class="cart-contents" href="<?php echo esc_url( wc_get_cart_url() ); ?>" title="<?php esc_attr_e( 'View your shopping cart', 'acoustics' ); ?>">
			<?php
			$item_count_text = sprintf(
				/* translators: number of items in the mini cart. */
				_n( '%d item', '%d items', WC()->cart->get_cart_contents_count(), 'acoustics' ),
				WC()->cart->get_cart_contents_count()
			);
			?>
			<img class="cart-icon" width="24" height="24" src="<?php echo get_stylesheet_directory_uri().'/assets/src/icon-cart.svg'; ?>"/>
			<span class="count"><?php echo absint ( WC()->cart->get_cart_contents_count() ); ?></span>
			<span class="amount"><?php echo wp_kses_data( WC()->cart->get_cart_subtotal() ); ?></span>
		</a>
		<?php
}
}

if ( ! function_exists( 'acoustics_woocommerce_header_cart' ) ) {
	/**
	 * Display Header Cart.
	 *
	 * @return void
	 */
function acoustics_woocommerce_header_cart() {
		if ( is_cart() ) {
			$class = 'current-menu-item';
}

else {
$class = '';
}
		?>
		<div class="header-cart widget-html mini-cart">
			<ul id="site-header-cart" class="site-header-cart">
				<li class="cart--link <?php echo esc_attr( $class ); ?>">
					<?php acoustics_woocommerce_cart_link(); ?>
				</li>
				<li class="cart--meta">
					<?php
					$instance = array(
						'title' => '',
					);

					the_widget( 'WC_Widget_Cart', $instance );
					?>
				</li>
			</ul>
		</div>
		<?php
}
}

if( ! function_exists('acoustics_product_categories')):
	function acoustics_product_categories() {
		$category_list = array();
	    $categories = get_categories(
	            array(
	                'hide_empty' => 0,
	                'exclude' => 1,
	                'taxonomy'=> 'product_cat'
	            )
	    );
	    $category_list[0] = esc_html__('Select Category', 'acoustics');
	    foreach ($categories as $category):
			$category_list[$category->term_id] = $category->name;
		endforeach;
	    return $category_list;
}
endif;

add_filter('woocommerce_sale_flash', 'acoustics_change_sale_content', 10, 3);
function acoustics_change_sale_content($content, $post, $product) {
$content = '<span class="onsale">'.__( 'Sale', 'acoustics' ).'</span>';
   return $content;
}

add_action( 'woocommerce_shop_loop_item_title' , 'acoustics_product_meta_start' , 5 );
function acoustics_product_meta_start() {
echo '<div class="product-meta">';
}

add_action( 'woocommerce_after_shop_loop_item_title' , 'acoustics_product_meta_end', 15 );
function acoustics_product_meta_end() {
echo '</div>';
}

add_filter( 'woocommerce_breadcrumb_defaults', 'acoustics_change_breadcrumb_delimiter' );
function acoustics_change_breadcrumb_delimiter( $defaults ) {
// Change the breadcrumb delimeter from '/' to '>'
	$defaults['delimiter'] = '';
	return $defaults;
}

add_filter( 'woocommerce_single_product_carousel_options', 'acoustic_single_product_carousel_options' );
function acoustic_single_product_carousel_options( $options ) {
	$options['directionNav'] = false;
	  	$options['direction'] = "vertical";
		return $options;
}

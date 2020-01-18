<?php
/**
 *
 * Product grid section
 *
 * @author      CodeGearThemes
 * @category    WordPress
 * @package     Acoustics
 * @version     1.0.0
 *
 */
if(! class_exists('Woocommerce')) {
	return;
}

$acoustics_product_collection = absint( get_theme_mod( 'acoustics_bestseller_collection' , 0 ) );
if( $acoustics_product_collection > 0 ): ?>
	<div id="section_bestseller_grid" class="section-products section--products-grid" type="bestseller">
		<div class = "container">
			<div class="section-heading">
				<h3 class="section-title h4">
					<span><?php esc_html_e( 'Best Sellers', 'acoustics' ); ?></span>
				</h3>
			</div>
			<?php
			if( $acoustics_product_collection){
	            $args = array(
	                'post_type' => 'product',
	                'posts_per_page' => 9,
	                'tax_query' => array(
						array(
		                    'taxonomy' => 'product_cat',
		                    'field' => 'term_id',
		                    'terms' => $acoustics_product_collection
						)
	                  )
	                );
					$acoustics_product_query = new WP_Query( $args );
					if( $acoustics_product_query->have_posts() ) { ?>
						<div class="woocommerce">
							<div class="row products products-grid columns clearfix">
								<?php
								while( $acoustics_product_query->have_posts() ) {
									$acoustics_product_query->the_post();
									wc_get_template_part( 'content', 'product' );
								} ?>
							</div>
							<div class="owl-nav owl-nav-bestseller">
								<span role="presentation" class="owl-prev-bestseller">
									<svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" xml:space="preserve" preserveAspectRatio="none" width="15" height="6.031" viewBox="0 0 15 6.031">
										<path d="M-0.000,2.389 L12.660,2.389 L11.229,1.075 C10.962,0.831 10.962,0.432 11.229,0.187 C11.497,-0.058 11.931,-0.058 12.199,0.187 L14.796,2.569 C14.808,2.580 14.821,2.592 14.832,2.605 C14.924,2.701 14.978,2.816 14.995,2.935 C15.000,2.977 15.002,3.020 14.998,3.062 C14.988,3.205 14.922,3.346 14.803,3.459 L14.803,3.459 C14.802,3.459 14.802,3.459 14.802,3.460 C14.802,3.460 14.801,3.462 14.800,3.462 L12.200,5.846 C11.932,6.091 11.498,6.091 11.230,5.846 C11.097,5.723 11.030,5.563 11.030,5.402 C11.030,5.240 11.096,5.080 11.230,4.956 L12.661,3.645 L-0.000,3.645 " class="previous"></path>
									</svg>
								</span>
								<span role="presentation" class="owl-next-bestseller">
									<svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" xml:space="preserve" preserveAspectRatio="none" width="15" height="6" viewBox="0 0 15 6">
										<path d="M15.000,2.376 L2.340,2.376 L3.771,1.070 C4.038,0.826 4.038,0.431 3.771,0.187 C3.502,-0.057 3.069,-0.057 2.801,0.187 L0.204,2.556 C0.192,2.567 0.179,2.579 0.168,2.592 C0.076,2.688 0.022,2.802 0.005,2.921 C0.000,2.962 -0.002,3.005 0.002,3.046 C0.012,3.189 0.078,3.330 0.197,3.442 L0.197,3.442 C0.198,3.442 0.198,3.442 0.198,3.443 C0.198,3.443 0.199,3.444 0.200,3.444 L2.800,5.816 C3.068,6.060 3.502,6.060 3.770,5.816 C3.903,5.693 3.970,5.534 3.970,5.375 C3.970,5.213 3.904,5.054 3.770,4.931 L2.339,3.627 L15.000,3.627 " class="next"></path>
									</svg>
								</span>
							</div>
						</div>
				   	<?php }
					wp_reset_postdata();
				}else{ ?>
					<div class="no-product"><?php esc_html_e( 'No Category Selected','acoustics' ); ?> </div>
				<?php
			} ?>
		</div>
	</div>
	<?php
endif;

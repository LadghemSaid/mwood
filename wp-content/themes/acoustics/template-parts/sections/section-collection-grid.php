<?php
/**
 *
 * Product collection grid section
 *
 * @author      CodeGearThemes
 * @category    WordPress
 * @package     Acoustics
 * @version     1.0.0
 *
 */
if(! class_exists('Woocommerce')) {
	return;
}?>
<div id="section_collection_grid" class="section--collection-grid section--product-collection">
	<div class = "container">
		<div class="section-heading">
			<h2 class="heading"><?php esc_html_e( 'Shop By Category', 'acoustics' ); ?></h2>
		</div>
		<div class="row">
		<?php
			$count = 0;
			$width = 165;
			for(  $i = 0; $i < 6; $i++ ){
				$acoustics_collection = get_theme_mod( 'acoustics_product_categories_grid_'.$i, 0 );
				if( $acoustics_collection > 0 ){
					$count++;
				}
			}

			switch ($count) {
				case 2:
				   $classes = 'col-md-6 col-sm-6 col-xs-6';
				   $width = 555;
				   break;
			    case 3:
				   $classes = 'col-md-4 col-sm-4 col-xs-12';
				    $width = 360;
				   break;
			    case 4:
				   $classes = 'col-md-3 col-sm-6 col-xs-6';
				   $width = 265;
				   break;
			    case 6:
				   $classes = 'col-md-2 col-sm-4 col-xs-6';
				   $width = 165;
				   break;
				default:
				  $classes = 'col-md-2 col-sm-4 col-xs-6';
				  $width = 165;
				  break;
			}

			for( $i = 0; $i < $count; $i++ ){

		     	$acoustics_product_category = get_theme_mod( 'acoustics_product_categories_grid_'.$i, '0' );

	     		$acoustics_term = get_term_by( 'id', $acoustics_product_category, 'product_cat' );
				$acoustics_thumbnail_id = get_term_meta( $acoustics_product_category, 'thumbnail_id', true );
				$acoustics_image = wp_get_attachment_url( $acoustics_thumbnail_id );
				$acoustics_link = get_category_link( $acoustics_product_category );

				if( $acoustics_product_category > 0 ){
					if( $acoustics_term && ! empty( $acoustics_image ) ){
					?>
						<div class="<?php echo esc_attr( $classes ); ?> text-center">
							<figure class="collection-image">
								<a href="<?php echo esc_url( $acoustics_link ); ?>">
			                        <img width="<?php echo esc_attr( $width ); ?>" src="<?php echo esc_url( $acoustics_image ); ?>" title="<?php echo esc_html( $acoustics_term->name ); ?>" alt="<?php echo esc_html( $acoustics_term->name ); ?>" />
			                    </a>
								<figcaption class="caption">
									<h2 class="h4 title"><?php echo esc_html( $acoustics_term->name ); ?></h2>
								</figcaption>
							</figure>
						</div>
					<?php
					}
				}
		    }
		?>
		</div>
	</div>
</div>

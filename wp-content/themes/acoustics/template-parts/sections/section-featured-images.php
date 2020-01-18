<?php
/**
 *
 * Featured section
 *
 * @author      CodeGearThemes
 * @category    WordPress
 * @package     Acoustics
 * @version     1.0.0
 *
 */
 ?>
 <div id="section_featured" class="section-featured section--featured-image">
	 <div class="container">
		 <div class="row">
			 <?php
			 $acoustics_featured_category = get_theme_mod( 'acoustics_featured_categories_0', '0' );

			 $acoustics_term = get_term_by( 'id', $acoustics_featured_category, 'product_cat' );
			 $acoustics_thumbnail_id = get_term_meta( $acoustics_featured_category, 'thumbnail_id', true );
			 $acoustics_image = wp_get_attachment_url( $acoustics_thumbnail_id );
			 $acoustics_link = get_category_link( $acoustics_featured_category );

			 if( $acoustics_featured_category ): ?>
				 	<div class="col-md-7 col-sm-6 col-xs-12">
				   	    <figure class="block-featured-main">
							<?php if( !empty( $acoustics_image ) ): ?>
							<img width="660"
								src="<?php echo esc_url( $acoustics_image ); ?>"
								alt="<?php echo esc_html( $acoustics_term->name ); ?>"
								title="<?php echo esc_html( $acoustics_term->name ); ?>"
								alt="<?php echo esc_html( $acoustics_term->name ); ?>">
							<?php endif; ?>
							<figcaption>
								<h2 class="h1 title"><?php echo esc_html( $acoustics_term->name ); ?></h2>
								<?php if( !empty( $acoustics_term->description ) ): ?>
									<div class="rte rte-settings">
										<?php echo esc_html( $acoustics_term->description ); ?>
									</div>
								<?php endif; ?>
								<a href="<?php esc_url( $acoustics_link ); ?>" class="btn btn-primary"><?php esc_html_e( 'Shop Now', 'acoustics' ); ?></a>
							</figcaption>
						 </figure>
					 </div>
			 <?php endif; ?>


			<div class="col-md-5 col-sm-6 col-xs-12">
				<?php
					for( $i=1; $i<3; $i++){
						$acoustics_featured_category = get_theme_mod( 'acoustics_featured_categories_'.$i, '0' );

			   			 $acoustics_term = get_term_by( 'id', $acoustics_featured_category, 'product_cat' );
			   			 $acoustics_thumbnail_id = get_term_meta( $acoustics_featured_category, 'thumbnail_id', true );
			   			 $acoustics_image = wp_get_attachment_url( $acoustics_thumbnail_id );
			   			 $acoustics_link = get_category_link( $acoustics_featured_category );

						if( $acoustics_featured_category ): ?>
								<figure class="block-featured-item">
									<a href="<?php echo esc_url( $acoustics_link ); ?>" class="block--featured-item-link">
										<?php if( !empty( $acoustics_image ) ): ?>
										<img width="660"
											src="<?php echo esc_url( $acoustics_image ); ?>"
											alt="<?php echo esc_html( $acoustics_term->name ); ?>"
											title="<?php echo esc_html( $acoustics_term->name ); ?>"
											alt="<?php echo esc_html( $acoustics_term->name ); ?>">
										<?php endif; ?>
										<figcaption>
											<h3 class="h3 title"><?php echo esc_html( $acoustics_term->name ); ?></h3>
											<?php if( !empty( $acoustics_term->description ) ): ?>
												<div class="rte rte-settings">
													<?php echo esc_html( $acoustics_term->description ); ?>
												</div>
											<?php endif; ?>
										</figcaption>
									</a>
								</figure>
							<?php
						endif;
					}
				?>
			</div>
		</div>
	</div>
 </div>

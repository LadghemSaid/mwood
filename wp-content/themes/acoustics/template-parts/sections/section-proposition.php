<?php
/**
 *
 * Proposition section
 *
 * @author      CodeGearThemes
 * @category    WordPress
 * @package     Acoustics
 * @version     1.0.0
 *
 */
 $valuesCategory = get_theme_mod( 'acoustics_values_category', '0' );
?>
<div id="section_porposition" class="section-proposition section--porposition-imagetext" style="background: #000;">
    <div class="container">
      <div class="row">
		  	<?php
			if( $valuesCategory ):
              $value_args = array(
                'cat' => $valuesCategory,
                'posts_per_page' => 4,
                'post_status'=>'publish',
              );
              $value_query = new WP_Query( $value_args );
              if( $value_query->have_posts() ) {
                  while( $value_query->have_posts() ) {
                    $value_query->the_post();
                    $image_instance = wp_get_attachment_image_src( get_post_thumbnail_id(), 'full' );
                    $src = $image_instance[0];
                    ?>
					<div class="col-md-3 col-sm-6 col-xs-6 proposition-block">
						<div class="proposition-item">
							<?php if( !empty( $src ) ): ?>
								<div class="proposition--item-thumb">
								  <img width="40" class="proposition--item-icon" src="<?php echo esc_url( $src ); ?>" alt="<?php echo esc_html( get_the_title() ); ?>"/>
								</div>
							<?php endif; ?>
							<div class="proposition--item-caption">
							  <h5 class="proposition--item-title"><?php echo esc_html( get_the_title() ); ?></h5>
							  <?php
							   	$acoustic_content = get_the_content();
							  	if( ! empty( $acoustic_content ) ): ?>
							  <div class="proposition--item-desc"><?php echo get_the_content(); ?></div>
							  <?php endif; ?>
							</div>
						</div>
   				    </div>
                <?php
				  }
			  }
			  wp_reset_postdata();
			endif;
		?>
      </div>
    </div>
  </div>

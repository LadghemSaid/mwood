<?php
/**
 * @author      CodeGearThemes
 * @category    WordPress
 * @package     Acoustics
 * @version     1.0.0
 *
 */
if ( ! function_exists( 'acoustics_home_sections' ) ) {
  function acoustics_home_sections(){
		$acoustics_sections = array(
			'hero' => 'hero',
			'featured' => 'featured-images',
			'newarrival' => 'newarrival',
			'product_category' => 'collection',
			'bestseller' => 'bestseller',
			'product_category_grid' => 'collection-grid',
			'values' => 'proposition',
		);
		$enabled_section = array();
		foreach ( $acoustics_sections as $section => $block ):
			if( get_theme_mod( 'acoustics_'.$section.'_section_enable' , false ) ){
				$enabled_section[] = array(
				    'section' => $section,
					'parts'   => $block
				);
			}
		endforeach;
		return $enabled_section;
	}
}

if( ! function_exists( 'acoustics_footer_social' ) ):
	function acoustics_footer_social() {
		$acoustics_twitter_link = get_theme_mod( 'acoustics_twitter_url', '' );
	    $acoustics_facebook_link = get_theme_mod( 'acoustics_facebook_url', '' );
	    $acoustics_linkedin_link = get_theme_mod( 'acoustics_linkedin_url', '' );
	    $acoustics_instagram_link = get_theme_mod( 'acoustics_instagram_url', '' );
	    $acoustics_pinterest_link = get_theme_mod( 'acoustics_pinterest_url', '' );
	    $acoustics_youtube_link = get_theme_mod( 'acoustics_youtube_url', '' );
	    $classes = 'round-icon';
		?>
	   <ul class="social-icons clearfix <?php echo esc_attr( $classes ); ?>">
		   <?php
		    if( ! empty( $acoustics_twitter_link ) ): ?>
		        <li class="text-center"><a href="<?php echo esc_url( $acoustics_twitter_link ); ?>" target="_blank"> <span class="fa fa-twitter"></span></a></li>
				<?php
		    endif;
			if( ! empty( $acoustics_facebook_link ) ): ?>
		        <li class="text-center"><a href="<?php echo esc_url( $acoustics_facebook_link ); ?>" target="_blank"> <span class="fa fa-facebook"></span></a></li>
				<?php
		    endif;
			if( ! empty( $acoustics_linkedin_link ) ): ?>
		        <li class="text-center"><a href="<?php echo esc_url( $acoustics_linkedin_link ); ?>" target="_blank"> <span class="fa fa-linkedin"></span></a></li>
				<?php
		    endif;
			if( ! empty( $acoustics_instagram_link ) ): ?>
		        <li class="text-center"><a href="<?php echo esc_url( $acoustics_instagram_link ); ?>" target="_blank"> <span class="fa fa-instagram"></span></a></li>
				<?php
		    endif;
			if( ! empty( $acoustics_pinterest_link ) ): ?>
		       <li class="text-center"><a href="<?php echo esc_url( $acoustics_pinterest_link ); ?>" target="_blank"> <span class="fa fa-pinterest"></span></a></li>
			   <?php
		    endif;
			if( ! empty( $acoustics_youtube_link ) ): ?>
		        <li class="text-center"><a href="<?php echo esc_url( $acoustics_youtube_link ); ?>" target="_blank"> <span class="fa fa-youtube"></span></a></li>
				<?php
		    endif;
			?>
		<ul>
	<?php
}

endif;

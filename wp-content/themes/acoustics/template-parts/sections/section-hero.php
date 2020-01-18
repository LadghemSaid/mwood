<?php
/**
 *
 * Hero section
 *
 * @author      CodeGearThemes
 * @category    WordPress
 * @package     Acoustics
 * @version     1.0.0
 *
 */
$acoustics_hero_title = get_theme_mod( 'acoustics_hero_section_title', '' );
$acoustics_hero_details = get_theme_mod( 'acoustics_hero_section_details', '' );
$acoustics_hero_link = get_theme_mod( 'acoustics_hero_section_link' );
 ?>
 <div id="section_hero" class="section-hero section--hero-image">
	<figure>
		<?php if( get_header_image() ): ?>
			<img src="<?php header_image(); ?>" width="<?php echo absint( get_custom_header()->width ); ?>" height="<?php echo absint( get_custom_header()->height ); ?>" alt="<?php bloginfo( 'name' ); ?>">
		<?php endif; ?>
		<?php if( !empty( $acoustics_hero_title ) || !empty( $acoustics_hero_link ) ): ?>
			<figcaption>
				<div class="container">
					<?php if( !empty( $acoustics_hero_title ) ): ?>
						<h2 class="h1 title"><?php echo esc_html( $acoustics_hero_title ); ?></h2>
					<?php endif; ?>
					<?php if( !empty( $acoustics_hero_details ) ): ?>
						<div class="rte rte-settings">
							<?php echo esc_html( $acoustics_hero_details ); ?>
							<?php
								if( !empty( $acoustics_hero_link ) ){ ?>
									<a class="btn btn-primary" href="<?php echo esc_url_raw( $acoustics_hero_link ); ?>"><?php esc_html_e( 'Shop Now', 'acoustics' ); ?></a>
								<?php
								}
							?>
						</div>
					<?php endif; ?>
				</div>
			</figcaption>
		<?php endif; ?>
	</figure>
 </div>

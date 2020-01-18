<?php
/**
 *
 * Header Default Layout
 *
 * @author      CodeGearThemes
 * @category    WordPress
 * @package     Acoustics
 * @version     1.0.0
 *
 */
?>
<div class="header--default navbar navbar-sticky navbar-ghost">
	<div class="header-inner">
		<div class="row">
			<div class="col-md-3 col-sm-12 col-xs-12">
				<?php $acoustics_description = get_bloginfo( 'description', 'display' ); ?>
				<div class="site-branding">
			 		 <?php
			 		 the_custom_logo();
			 		 if ( is_front_page() || is_home() ) : ?>
			 		   <h1 class="site-title <?php if( ! $acoustics_description ){ echo 'no-tagline'; } ?>">
			 			 <a href="<?php echo esc_url( home_url( '/' ) ); ?>" rel="home"><?php esc_attr( bloginfo( 'name' ) ); ?></a>
			 		   </h1>
			 		 <?php
			 		 else : ?>
			 		   <div class="site-title <?php if( ! $acoustics_description ){ echo 'no-tagline'; } ?>">
			 			 <a href="<?php echo esc_url( home_url( '/' ) ); ?>" rel="home"><?php esc_attr( bloginfo( 'name' ) ); ?></a>
			 		   </div>
			 		 <?php
			 		 endif;
			 		 if ( $acoustics_description || is_customize_preview() ) :
			 		   ?>
			 		   <p class="site-description"><?php echo $acoustics_description; /* WPCS: xss ok. */ ?></p>
			 		 <?php endif; ?>
			 	</div><!-- .site-branding -->
			</div>
			<div class="col-md-9 col-sm-12 col-xs-12">
				<div class="header-navigation">
					<nav id="site-navigation" class="main-navigation">
						<button class="menu-toggle" aria-controls="primary-menu" aria-expanded="false">
							<span class="icon-bar"></span>
							<span class="icon-bar middle"></span>
							<span class="icon-bar"></span>
							<small class="sr-only"><?php esc_html_e( 'Primary Menu', 'acoustics' ); ?></small>
						</button>
						<div class="navigation--mobile">
							<?php
							wp_nav_menu( array(
							  'theme_location' => 'main-menu',
							  'menu_id'        => 'primary-menu',
							) );
							?>
						</div>
					</nav><!-- #site-navigation -->
					<?php
					   /**
						* Hook: Functions hooked into acoustics_navigation action
						*
						*  @hooked acoustics_woocommerce_header_cart                  - 10
						*/
						do_action( 'acoustics_navigation' );
					?>
				</div>
			</div>
		</div>
	</div>
</div>

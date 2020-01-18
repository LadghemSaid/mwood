<?php
/**
 *
 * Footer copyright
 *
 * @author      CodeGearThemes
 * @category    WordPress
 * @package     Acoustics
 * @version     1.0.0
 *
 */

$acoustics_copyright = get_theme_mod( 'acoustics_footer_copyright', '')
?>
 <div class="section-copyright text-center">
	 <div class="container">
		 <div class="site-info">
		 	 <?php if( !empty( $acoustics_copyright ) ): ?>
				<small><?php echo wp_kses_post( $acoustics_copyright ); ?></small>
			 <?php else: ?>
				 <small><?php printf( __( '&copy; %1$s %2$s', 'acoustics' ), esc_attr( date_i18n( __( 'Y', 'acoustics' ) ) ), esc_attr( get_bloginfo( 'name' ) ) ); ?></small>
			 <?php endif; ?>
			<small class="copyright-credit">
				<span><?php esc_html_e( 'Designed by', 'acoustics' ); ?></span>
				<a  title="<?php esc_html_e('Free WordPress Theme','acoustics'); ?>" href="<?php echo esc_url( AUTHOR_URI ); ?>">
					<?php esc_html_e('CodeGearThemes','acoustics');?>
				</a>
			</small>
		 </div><!-- .site-info -->
	 </div>
 </div>

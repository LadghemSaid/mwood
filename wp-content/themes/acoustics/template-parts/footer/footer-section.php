<?php
/**
 *
 * Footer Widget
 *
 * @author      CodeGearThemes
 * @category    WordPress
 * @package     Acoustics
 * @version     1.0.0
 *
 */

if(is_active_sidebar('footer-column-1') ||
    is_active_sidebar('footer-column-2') ||
    is_active_sidebar('footer-column-3') ||
    is_active_sidebar('footer-column-4') ):
?>
<div class="section-footer section--footer-widget">
	<div class="container">
		<div class="row">
			<?php if( is_active_sidebar('footer-column-1') ): ?>
				<div class="footer-block footer--block-contact col-md-4 col-sm-6 col-xs-12">
					<?php
						dynamic_sidebar('footer-column-1');
						do_action( 'footer_social' );
					?>
				</div>
			<?php endif; ?>
			<?php if( is_active_sidebar('footer-column-2') ): ?>
				<div class="footer-block col-md-2 col-sm-3 col-xs-6">
					<?php dynamic_sidebar('footer-column-2'); ?>
				</div>
			<?php endif; ?>
			<?php if( is_active_sidebar('footer-column-3') ): ?>
				<div class="footer-block col-md-2 col-sm-3 col-xs-6">
					<?php dynamic_sidebar('footer-column-3'); ?>
				</div>
			<?php endif; ?>
			<?php if( is_active_sidebar('footer-column-4') ): ?>
				<div class="footer-block col-md-2 col-sm-3 col-xs-6">
					<?php dynamic_sidebar('footer-column-4'); ?>
				</div>
			<?php endif; ?>
			<?php if( is_active_sidebar('footer-column-5') ): ?>
				<div class="footer-block col-md-2 col-sm-3 col-xs-6">
					<?php dynamic_sidebar('footer-column-5'); ?>
				</div>
			<?php endif; ?>
		</div>
	</div>
</div>
<?php endif;

<?php
/**
 * The sidebar containing the main widget area
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @author      CodeGearThemes
 * @category    WordPress
 * @package     Acoustics
 * @version     1.0.0
 *
 */

if ( ! is_active_sidebar( 'sidebar' ) ) {
  return;
}
?>

<aside id="secondary" class="sidebar-widget widget-area col-md-3 col-sm-12 col-xs-12" role="complementary">
	<?php dynamic_sidebar( 'sidebar' ); ?>
</aside><!-- #secondary -->

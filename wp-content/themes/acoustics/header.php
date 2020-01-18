<?php
/**
 * The header for our theme
 *
 * This is the template that displays all of the <head> section and everything up until <div id="content">
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @author      CodeGearThemes
 * @category    WordPress
 * @package     Acoustics
 * @version     1.0.0
 *
 */

?>
<!doctype html>
<html <?php language_attributes(); ?>>
<head>
	<meta charset="<?php bloginfo( 'charset' ); ?>">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="profile" href="https://gmpg.org/xfn/11">

	<?php wp_head(); ?>
</head>

<body <?php body_class(); ?>>
<div id="page" class="site">
	<a class="skip-link screen-reader-text" href="#content"><?php esc_html_e( 'Skip to content', 'acoustics' ); ?></a>
    <?php do_action( 'acoustics_before_header' ); ?>

	<header id="masthead" class="site-header">
	  <div class="container">
		   <?php get_template_part( 'template-parts/header/header', 'default' ); ?>
	  </div>
 	</header><!-- #masthead -->

	<?php do_action( 'acoustics_after_header' ); ?>

	<div id="content" class="site-content">

<?php
/**
 * The template for displaying the footer
 *
 * Contains the closing of the #content div and all content after.
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
	</div><!-- #content -->

	<footer id="colophon" class="site-footer">
		<?php
			get_template_part( 'template-parts/footer/footer', 'section' );
			get_template_part( 'template-parts/footer/footer', 'copyright' );
		?>
	 </footer><!-- #colophon -->
</div><!-- #page -->

<?php wp_footer(); ?>

</body>
</html>

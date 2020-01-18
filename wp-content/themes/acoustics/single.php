<?php
/**
 * The template for displaying all single posts
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/#single-post
 *
 * @author      CodeGearThemes
 * @category    WordPress
 * @package     Acoustics
 * @version     1.0.0
 *
 */

get_header();
$acoustics_layout = get_theme_mod( 'acoustics_post_layout', 'right-sidebar' );
$acoustics_class = acoustics_layout_classes( $acoustics_layout );
?>
<div class="section-default section--single-template">
	<div class="container">
		<div class="row">
			<section id="primary" class="section-primary <?php echo esc_attr( $acoustics_class ); ?>  col-xs-12 content-area">
				<main id="main" class="site-main">
				<?php
					while ( have_posts() ) :
						the_post();

						get_template_part( 'template-parts/content', 'single' );

						the_post_navigation();

						// If comments are open or we have at least one comment, load up the comment template.
						if ( comments_open() || get_comments_number() ) :
							comments_template();
						endif;

					endwhile; // End of the loop.
				?>
				</main>
			</section>

			<?php
				if( $acoustics_layout != 'no-sidebar' ):
					get_sidebar();
				endif;
			?>
		</div>
	</div>
</div>
<?php
get_footer();

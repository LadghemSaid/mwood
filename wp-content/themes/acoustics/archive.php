<?php
/**
 * The template for displaying archive pages
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @author      CodeGearThemes
 * @category    WordPress
 * @package     Acoustics
 * @version     1.0.0
 *
 */

get_header();
$acoustics_layout = get_theme_mod( 'acoustics_archive_layout', 'left-sidebar' );
$acoustics_class = acoustics_layout_classes( $acoustics_layout );
?>
<header class="section-page-header">
	<div class="container">
		<div class="row">
			<div class="col-md-6 text-left">
				<?php the_archive_title( '<h1 class="page-title">', '</h1>' );?>
			</div>
			<div class="col-md-6 text-right">
				<?php acoustics_breadcrumb(); ?>
			</div>
		</div>
	</div>
</header>
<div class="section-default section--archive-template">
	<div class="container">
		<div class="row">
			<section id="primary" class="section-primary <?php echo esc_attr( $acoustics_class ); ?>  col-xs-12 content-area">
				<main id="main" class="site-main">
					<?php if ( have_posts() ) : ?>

						<div class="rte rte-settings">
							<?php the_archive_description( '<div class="archive-description">', '</div>' ); ?>
						</div><!-- .page-header -->

						<?php
						/* Start the Loop */
						while ( have_posts() ) :
							the_post();

							/*
							 * Include the Post-Type-specific template for the content.
							 * If you want to override this in a child theme, then include a file
							 * called content-___.php (where ___ is the Post Type name) and that will be used instead.
							 */
							get_template_part( 'template-parts/content', get_post_type() );

						endwhile;

						the_posts_navigation();

					else :

						get_template_part( 'template-parts/content', 'none' );

					endif;
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

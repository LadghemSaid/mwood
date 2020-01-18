<?php
/**
 * The template for displaying all pages
 *
 * This is the template that displays all pages by default.
 * Please note that this is the WordPress construct of pages
 * and that other 'pages' on your WordPress site may use a
 * different template.
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
$acoustics_layout = get_theme_mod( 'acoustics_page_layout', 'no-sidebar' );
$acoustics_class = acoustics_layout_classes( $acoustics_layout );
?>
<header class="section-page-header">
	<div class="container">
		<div class="row">
			<div class="col-md-6 text-left">
				<?php the_title( '<h1 class="entry-title">', '</h1>' ); ?>
			</div>
			<div class="col-md-6 text-right">
				<?php acoustics_breadcrumb(); ?>
			</div>
		</div>
	</div>
</header>
<div class="section-default section--page-template">
	<div class="container">
		<div class="row">
			<section id="primary" class="section-primary <?php echo esc_attr( $acoustics_class ); ?>  col-xs-12 content-area">
				<main id="main" class="site-main">
					<?php
						while ( have_posts() ) :
							the_post();

							get_template_part( 'template-parts/content', 'page' );

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

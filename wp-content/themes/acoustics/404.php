<?php
/**
 * The template for displaying 404 pages (not found)
 *
 * @link https://codex.wordpress.org/Creating_an_Error_404_Page
 *
 * @author      CodeGearThemes
 * @category    WordPress
 * @package     Acoustics
 * @version     1.0.0
 *
 */

get_header();
?>
<div class="section-default section--error-template">
	<div class="container">
		<div class="row">
			<div id="primary" class="col-md-8 col-md-push-2 content-area">
				<main id="main" class="site-main">

					<section class="error-404 not-found">
						<div class="page-large-text">
							<h2 class="page-heading-large"><?php esc_html_e( '404', 'acoustics' ); ?></h2>
						</div>
						<div class="page-content">
							<header class="page-header">
								<h1 class="page-title"><?php esc_html_e( 'Error!', 'acoustics' ); ?></h1>
							</header><!-- .page-header -->
							<p class="content"><?php esc_html_e( 'Sorry! Page not Found', 'acoustics' ); ?></p>
							<a href="<?php echo esc_url( home_url( '/' ) ); ?>" class="btn btn-primary"><?php esc_html_e( 'Return to Home', 'acoustics' ); ?></a>
						</div><!-- .page-content -->
					</section><!-- .error-404 -->
					<?php get_search_form(); ?>

				</main><!-- #main -->
			</div><!-- #primary -->
		</div>
	</div>
</div>
<?php
get_footer();

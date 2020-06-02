<?php
// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$advSettings = DGWT_WCAS()->settings->canSeeAdvSettings();

$classes = array();
if ( dgoraAsfwFs()->is_premium() ) {
	$classes[] = 'dgwt-wcas-settings-pro';
}
if ( DGWT_WCAS()->themeCompatibility->isCurrentThemeSupported() ) {
	$classes[] = 'dgwt-wcas-settings-theme-supported';
}
?>
<div class="wrap dgwt-wcas-settings <?php echo implode( ' ', $classes ); ?>">


	<h2 class="dgwt-wcas-settings__head">
		<img class="dgwt-wcas-settings__logo" src="<?php echo DGWT_WCAS_URL; ?>assets/img/logo-nobg-50.png"/>
		<span class="dgwt-wcas-settings__title">
        <?php
        if ( dgoraAsfwFs()->is_premium() ) {
	        _e( 'AJAX Search for WooCommerce (PRO) Settings', 'ajax-search-for-woocommerce' );
        } else {
	        _e( 'AJAX Search for WooCommerce Settings', 'ajax-search-for-woocommerce' );
        }
        ?>
        </span>
		<span class="dgwt-wcas-settings__advanced js-dgwt-wcas-settings__advanced">
            <span class="js-dgwt-wcas-adv-settings-toggle woocommerce-input-toggle woocommerce-input-toggle--<?php echo $advSettings ? 'enabled' : 'disabled'; ?>"><?php _e( 'Show advanced settings', 'ajax-search-for-woocommerce' ); ?></span>
            <span class="dgwt-wcas-adv-settings-label"><?php _e( 'Show advanced settings', 'ajax-search-for-woocommerce' ); ?></span>
         </span>
	</h2>

	<?php $settings->show_navigation(); ?>

	<div class="dgwt-wcas-settings-body js-dgwt-wcas-settings-body">
		<?php
		require DGWT_WCAS_DIR . 'partials/admin/search-preview.php';
		$settings->show_forms();
		?>
	</div>

</div>

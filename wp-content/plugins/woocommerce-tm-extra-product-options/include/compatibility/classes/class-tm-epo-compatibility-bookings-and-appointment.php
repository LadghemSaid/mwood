<?php
// Direct access security
if ( !defined( 'TM_EPO_PLUGIN_SECURITY' ) ) {
	die();
}

// Bookings and Appointment Plugin for WooCommerce
final class TM_EPO_COMPATIBILITY_bookings_and_appointment {

	protected static $_instance = NULL;

	public static function instance() {
		if ( is_null( self::$_instance ) ) {
			self::$_instance = new self();
		}

		return self::$_instance;
	}

	public function __construct() {

		add_action( 'plugins_loaded', array( $this, 'add_compatibility' ), 2 );
	}

	public function init() {

	}

	public function add_compatibility() {

		add_action( 'bkap_before_booking_form', array( $this, 'bkap_before_booking_form' ), 4 );

	}

	public function bkap_before_booking_form() {
		echo '<input type="hidden" id="product-addons-total" />';
	}

}



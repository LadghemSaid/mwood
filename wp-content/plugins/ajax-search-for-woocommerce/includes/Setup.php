<?php

namespace DgoraWcas;

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class Setup {

	public function init() {
		add_action( 'init', array( $this, 'setImageSize' ) );
		add_filter( 'body_class', array( $this, 'addMobileDeviceInfo' ) );
	}

	/**
	 * Register custom image size
	 * @return void
	 */
	public function setImageSize() {
		add_image_size( 'dgwt-wcas-product-suggestion', 64, 0, false );
	}

	public function addMobileDeviceInfo( $classes ) {

		if ( DGWT_WCAS()->mobileDetect->isMobile() ) {
			$classes[] = 'dgwt-wcas-is-mobile';
		}
		if ( DGWT_WCAS()->mobileDetect->isiOS() ) {
			$classes[] = 'dgwt-wcas-is-ios';
		}

		return $classes;

	}
}

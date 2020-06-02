<?php

namespace DgoraWcas\Integrations\Themes\Astra;

use DgoraWcas\Helpers;

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class Astra {

	private $themeSlug = 'astra';

	private $themeName = 'Astra';

	public function __construct() {
		if ( defined( 'ASTRA_EXT_VER' ) ) {
			add_filter( 'dgwt/wcas/suggestion_details/show_quantity', '__return_false' );
		}

		add_action( 'admin_head', function () {
			?>
			<style>
				#dgwt_wcas_basic .submit {
					display: none !important;
				}
			</style>
			<?php
		} );

	}

}

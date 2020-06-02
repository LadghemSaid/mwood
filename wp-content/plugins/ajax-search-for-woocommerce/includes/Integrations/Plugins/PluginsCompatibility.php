<?php

namespace DgoraWcas\Integrations\Plugins;

class PluginsCompatibility {

	public function __construct() {
		$this->loadCompatibilities();
	}

	/**
	 * Load class with compatibilities logic for current theme
	 *
	 * @return void
	 */
	private function loadCompatibilities() {

		$direcories = glob( DGWT_WCAS_DIR . 'includes/Integrations/Plugins/*', GLOB_ONLYDIR );

		if ( ! empty( $direcories ) && is_array( $direcories ) ) {
			foreach ( $direcories as $dir ) {
				$name     = str_replace( DGWT_WCAS_DIR . 'includes/Integrations/Plugins/', '', $dir );
				$filename = $name . '.php';

				$file  = $dir . '/' . $filename;
				$class = '\\DgoraWcas\\Integrations\\Plugins\\' . $name . "\\" . $name;

				if ( file_exists( $file ) && class_exists( $class ) ) {
					$tmp = new $class;
					$tmp->init();
				}
			}
		}

	}
}

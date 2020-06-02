<?php


namespace DgoraWcas\Integrations\Plugins\BoosterIO;

use DgoraWcas\Engines\TNTSearchMySQL\Config;

class Filters {
	const PLUGIN_NAME = 'booster-plus-for-woocommerce/booster-plus-for-woocommerce.php';

	private $country = '';

	public function init() {

		if ( ! Config::isPluginActive( self::PLUGIN_NAME ) ) {
			return;
		}

		$this->setCurrentCountry();

		// For module: Product Visibility by Country
		if ( $this->isModuleEnabled( 'product_by_country' ) ) {
			$this->excludeProductByCountry();
		}

		// Module: Prices and Currencies by Country
		if ( $this->isModuleEnabled( 'price_by_country' ) ) {
			$this->filterPrices();
		}

	}

	/**
	 * Module: [Product Visibility by Country]
	 * Set current country code based on PHP Session
	 *
	 * @return void
	 */
	private function setCurrentCountry() {
		if ( ! session_id() ) {
			session_start();
		}

		if ( ! empty( $_SESSION['wcj-country'] ) ) {
			$this->country = $_SESSION['wcj-country'];
		}

		if ( ! empty( $_SESSION['dgwt-wcas-boosterio-current-language'] ) ) {
			$this->country = $_SESSION['dgwt-wcas-boosterio-current-language'];
		}

	}

	/**
	 * Module: [Product Visibility by Country]
	 * Exlude all product by based on country
	 */
	private function excludeProductByCountry() {

		add_filter( 'dgwt/wcas/tnt/search_results/ids', function ( $ids ) {

			global $wpdb;

			if ( empty( $this->country ) ) {
				return $ids;
			}

			$filteredIds = $ids;

			$i = 0;
			foreach ( $ids as $id ) {

				$sql = $wpdb->prepare( "SELECT meta_value
                                       FROM $wpdb->postmeta
                                       WHERE post_id = %d
                                       AND meta_key = '_wcj_product_by_country_visible'
                                      ", $id );

				$rawCountries = $wpdb->get_var( $sql );

				if ( ! empty( $rawCountries ) && strpos( $rawCountries, 'a:' ) === 0 ) {
					$allowedCountries = unserialize( $rawCountries );
					if ( ! empty( $allowedCountries ) && ! in_array( $this->country, $allowedCountries ) ) {
						unset( $filteredIds[ $i ] );
					}
				}

				$i ++;
			}

			return $filteredIds;
		} );
	}

	/**
	 * Module: Prices and Currencies by Country
	 *
	 * Format prices on output
	 *
	 * @return void
	 */
	private function filterPrices() {

		add_filter( 'dgwt/wcas/tnt/search_results/output', function ( $output ) {

			if ( ! empty( $output['suggestions'] ) ) {
				$i = 0;
				foreach ( $output['suggestions'] as $suggestion ) {

					if ( isset( $suggestion['price'] ) ) {
						$output['suggestions'][ $i ]['price'] = $this->formatPrice( $suggestion['price'], $this->country );
					}

					$i ++;
				}
			}

			return $output;
		} );

	}

	/**
	 * Module: Prices and Currencies by Country
	 *
	 * Format price
	 *
	 * @param string $html
	 * @param string $location
	 *
	 * @return string
	 */
	public function formatPrice( $html, $location ) {

		$currentLocation = empty( $location ) || strlen( $location ) !== 2 ? '' : strtoupper( $location );

		if ( strpos( $html, 'booster.ioPricesByCountry:default' ) !== false ) {
			$groups = array();

			if ( empty( $currentLocation ) ) {
				return $this->getLocationPrice( $html, 'default' );
			}


			preg_match_all( '/(?<=\[booster\.ioPricesByCountry\:group_).+?(?=\])/m', $html, $matches, PREG_SET_ORDER, 0 );

			if ( ! empty( $matches ) && is_array( $matches ) ) {
				foreach ( $matches as $group ) {
					if ( ! empty( $group[0] ) ) {
						$groups[ $group[0] ] = explode( '_', $group[0] );
					}
				}
			}


			foreach ( $groups as $group => $countries ) {
				if ( ! empty( $countries ) ) {
					foreach ( $countries as $country ) {
						if ( $country === $currentLocation ) {
							return $this->getLocationPrice( $html, $group );
						}
					}
				}
			}


			return $this->getLocationPrice( $html, 'default' );
		}

		return $html;
	}

	/**
	 * Module: Prices and Currencies by Country
	 *
	 * Finds a substring between two strings
	 *
	 * @return string
	 */
	private function getLocationPrice( $html, $location ) {

		$key = empty( $location ) || $location === 'default' ? 'default' : 'group_' . $location;

		// Empty default price
		if ( $key === 'default' && strpos( $html, '[booster.ioPricesByCountry:default][booster.ioPricesByCountry:end]' ) !== false ) {
			return '';
		}

		preg_match( '/(?<=\[booster\.ioPricesByCountry\:' . $key . '\]).+?(?=\[booster\.ioPricesByCountry\:end\])/m', $html, $matches );

		if ( ! empty( $matches ) && is_array( $matches ) ) {
			$html = $matches[0];
		}

		return $html;
	}


	/**
	 * Module: [Product Visibility by Country]
	 * Check if module product by country is enabled
	 *
	 * @param string $moduleName
	 *
	 * @return bool
	 */
	private function isModuleEnabled( $moduleName ) {
		global $wpdb;
		$enabled   = false;
		$optionKey = 'wcj_' . $moduleName . '_enabled';

		$val = $wpdb->get_var( $wpdb->prepare( "SELECT option_value FROM $wpdb->options WHERE option_name = %s", $optionKey ) );

		if ( ! empty( $val ) && $val === 'yes' ) {
			$enabled = true;
		}

		return $enabled;

	}

}

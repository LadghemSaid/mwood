<?php


namespace DgoraWcas;


class Multilingual {

	public static $currentCurrency = '';

	/**
	 * Check if the website is multilingual
	 *
	 * @return bool
	 */
	public static function isMultilingual() {

		$isMultilingual = false;

		if ( defined( 'DGWT_WCAS_DISABLE_MULTILINGUAL' ) && DGWT_WCAS_DISABLE_MULTILINGUAL ) {
			return false;
		}

		if (
			count( self::getLanguages() ) > 1
			&& ( self::isWPML() || self::isPolylang() )
		) {
			$isMultilingual = true;
		}

		return $isMultilingual;
	}

	/**
	 * Check if WPMl is active
	 *
	 * @return bool
	 */
	public static function isWPML() {
		return class_exists( 'SitePress' );
	}

	/**
	 * Check if Polylang is active
	 *
	 * @return bool
	 */
	public static function isPolylang() {
		return class_exists( 'Polylang' ) && class_exists( 'Polylang_Woocommerce' );
	}

	/**
	 * Get Provider
	 *
	 * @return bool
	 */
	public static function getProvider() {
		$provider = 'not set';

		if(self::isWPML()){
			$provider = 'WPML';
		}

		if(self::isPolylang()){
			$provider = 'Polylang';
		}

		return $provider;
	}

	/**
	 * Check if language code has 2 leters
	 *
	 * @param $lang
	 *
	 * @return bool
	 */
	public static function isLangCode( $lang ) {
		return (bool) preg_match( '/^[A-Za-z]{2}$/', $lang );
	}

	/**
	 * Get default language
	 *
	 * @return string
	 */
	public static function getDefaultLanguage() {
		$defaultLang = 'en';

		if ( self::isWPML() ) {
			$defaultLang = apply_filters( 'wpml_default_language', null );
		}

		if ( self::isPolylang() ) {
			$defaultLang = pll_default_language( 'slug' );
		}

		if ( empty( $defaultLang ) ) {
			$locale      = get_locale();
			$defaultLang = substr( $locale, 0, 2 );
		}

		return $defaultLang;
	}

	/**
	 * Current language
	 *
	 * @return string
	 */
	public static function getCurrentLanguage() {
		$currentLang = self::getDefaultLanguage();

		if ( self::isWPML() ) {
			$currentLang = apply_filters( 'wpml_current_language', null );
		}

		if ( self::isPolylang() ) {
			$lang = pll_current_language( 'slug' );

			if ( $lang ) {
				$currentLang = $lang;
			} else {
				$currentLang = pll_default_language( 'slug' );
			}
		}

		return $currentLang;
	}

	/**
	 * Get Language of post or product
	 *
	 * @param int $postID
	 *
	 * @return string
	 */
	public static function getPostLang( $postID, $postType = 'product' ) {

		$lang = self::getDefaultLanguage();

		if ( self::isWPML() ) {
			global $wpdb;

			$postType = 'post_' . $postType;

			$tranlationsTable = $wpdb->prefix . 'icl_translations';
			$sql              = $wpdb->prepare( "SELECT language_code
                                          FROM $tranlationsTable
                                          WHERE element_type=%s
                                          AND element_id=%d", sanitize_key( $postType ), $postID );
			$query            = $wpdb->get_var( $sql );

			if ( ! empty( $query ) && strlen( $query ) === 2 ) {
				$lang = $query;
			}
		}

		if ( self::isPolylang() ) {
			$lang = pll_get_post_language( $postID, 'slug' );
		}

		return $lang;
	}

	/**
	 * Get term lang
	 *
	 * @param int $term ID
	 * @param string $taxonomy
	 *
	 * @return string
	 */
	public static function getTermLang( $termID, $taxonomy ) {
		$lang = self::getDefaultLanguage();

		if ( self::isWPML() ) {
			global $wpdb;

			$elementType      = 'tax_' . sanitize_key( $taxonomy );
			$tranlationsTable = $wpdb->prefix . 'icl_translations';

			$sql = $wpdb->prepare( "SELECT language_code
                                          FROM $tranlationsTable
                                          WHERE element_type = %s
                                          AND element_id=%d",
				$elementType, $termID );

			$query = $wpdb->get_var( $sql );

			if ( ! empty( $query ) && strlen( $query ) == 2 ) {
				$lang = $query;
			}
		}

		if ( self::isPolylang() ) {
			$lang = pll_get_term_language($termID, 'slug');
		}

		return $lang;
	}

	/**
	 * Get permalink
	 *
	 * @param string $postID
	 * @param string $url
	 * @param string $lang
	 *
	 * @return string
	 */
	public static function getPermalink( $postID, $url = '', $lang = '' ) {
		$permalink = $url;

		if ( self::isWPML() && self::getDefaultLanguage() !== $lang ) {
			/**
			 *  1 if the option is *Different languages in directories*
			 *  2 if the option is *A different domain per language*
			 *  3 if the option is *Language name added as a parameter*.
			 */
			$urlType = apply_filters( 'wpml_setting', 0, 'language_negotiation_type' );

			if ( $urlType == 3 ) {
				$permalink = apply_filters( 'wpml_permalink', $url, $lang );
			} else {
				$permalink = apply_filters( 'wpml_permalink', $url, $lang, true );
			}

		}

		return $permalink;
	}

	/**
	 * Active languages
	 *
	 * @return langs
	 */
	public static function getLanguages() {

		$langs = array();

		if ( self::isWPML() ) {
			$wpmlLangs = apply_filters( 'wpml_active_languages', null, array( 'skip_missing' => 0 ) );

			if ( is_array( $wpmlLangs ) ) {
				foreach ( $wpmlLangs as $langCode => $details ) {
					if ( self::isLangCode( $langCode ) ) {
						$langs[] = strtolower( $langCode );
					}
				}
			}

			$hiddenLangs = apply_filters( 'wpml_setting', array(), 'hidden_languages' );
			if ( ! empty( $hiddenLangs ) && is_array( $hiddenLangs ) ) {
				$langs = array_unique( array_merge( $langs, $hiddenLangs ) );
			}
		}

		if ( self::isPolylang() ) {
			$langs = pll_languages_list( array(
				'hide_empty' => false,
				'fields'     => 'slug'
			) );
		}


		if ( empty( $langs ) ) {
			$langs[] = self::getDefaultLanguage();
		}

		return $langs;

	}

	/**
	 * Get all terms in one taxonomy for all languages
	 *
	 * @param string $taxonomy
	 *
	 * @return array of WP_Term objects
	 */
	public static function getTermsInAllLangs( $taxonomy ) {
		$terms   = array();

		if ( self::isWPML() ) {
			$currentLang = self::getCurrentLanguage();
			$usedIds = array();

			foreach ( self::getLanguages() as $lang ) {
				do_action( 'wpml_switch_language', $lang );
				$args        = array(
					'taxonomy'         => $taxonomy,
					'hide_empty'       => true,
					'suppress_filters' => false
				);
				$termsInLang = get_terms( apply_filters( 'dgwt/wcas/search/' . $taxonomy . '/args', $args ) );

				if ( ! empty( $termsInLang ) && is_array( $termsInLang ) ) {
					foreach ( $termsInLang as $termInLang ) {

						if ( ! in_array( $termInLang->term_id, $usedIds ) ) {
							$terms[]   = $termInLang;
							$usedIds[] = $termInLang->term_id;
						}
					}
				}

			}

			do_action( 'wpml_switch_language', $currentLang );
		}

		if ( self::isPolylang() ) {

			$terms = get_terms( array(
				'taxonomy' => $taxonomy,
				'hide_empty' => true,
				'lang' => '', // query terms in all languages
			) );

		}

		return $terms;
	}

	/**
	 * Get term in specific language
	 *
	 * @param int $termID
	 * @param string $taxonomy
	 * @param string $lang
	 *
	 * @return object WP_Term
	 */
	public static function getTerm( $termID, $taxonomy, $lang ) {
		$term = null;

		if ( self::isWPML() ) {
			$currentLang = self::getCurrentLanguage();
			do_action( 'wpml_switch_language', $lang );

			$term = get_term( $termID, $taxonomy );

			do_action( 'wpml_switch_language', $currentLang );
		}

		if ( self::isPolylang() ) {

			$termID = pll_get_term( $termID, $lang );

			if ( $termID ) {
				$term = get_term( $termID, $taxonomy );
			}

		}

		return $term;
	}

	/**
	 * Check if multicurrency module is enabled
	 *
	 * @return bool
	 */
	public static function isMultiCurrency() {

		$multiCurrency = false;

		if ( self::isWPML() && function_exists( 'wcml_is_multi_currency_on' ) && wcml_is_multi_currency_on() ) {
			$multiCurrency = true;
		}


		return $multiCurrency;
	}

	/**
	 * Get currency code assigned to language
	 *
	 * @param string $lang
	 *
	 * @return string
	 */
	public static function getCurrencyForLang( $lang ) {
		$currencyCode = '';

		if ( self::isWPML() ) {
			global $woocommerce_wpml;
			if ( ! empty( $woocommerce_wpml ) && is_object( $woocommerce_wpml ) && ! empty( $lang ) ) {

				if ( ! empty( $woocommerce_wpml->settings['default_currencies'][ $lang ] ) ) {
					$currencyCode = $woocommerce_wpml->settings['default_currencies'][ $lang ];
				}
			}

		}

		return $currencyCode;
	}

	/**
	 * Set currenct currency
	 *
	 * @return void
	 */
	public static function setCurrentCurrency( $currency ) {
		self::$currentCurrency = $currency;
	}

	/**
	 * Get currenct currency
	 *
	 * @return string
	 */
	public static function getCurrentCurrency() {
		return self::$currentCurrency;
	}

	/**
	 * Switch language
	 *
	 * @param $lang
	 */
	public static function switchLanguage( $lang ) {
		if ( self::isWPML() && ! empty( $lang ) ) {
			do_action( 'wpml_switch_language', $lang );
		}
	}

}

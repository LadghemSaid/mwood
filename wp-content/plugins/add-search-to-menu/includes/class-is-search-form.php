<?php

class IS_Search_Form {

	const post_type = 'is_search_form';

	private static $found_items = 0;
	private static $current = null;

	private $id;
	private $name;
	private $title;
	private $is_locale;
	private $properties = array();
	private $unit_tag;

	private function __construct( $post = null ) {
		$post = get_post( $post );

		if ( $post && self::post_type == get_post_type( $post ) ) {
			$this->id = $post->ID;
			$this->name = $post->post_name;
			$this->title = $post->post_title;
			$this->is_locale = get_post_meta( $post->ID, '_is_locale', true );

			$properties = $this->get_properties();

			foreach ( $properties as $key => $value ) {
				if ( metadata_exists( 'post', $post->ID, $key ) ) {
					$properties[$key] = get_post_meta( $post->ID, $key, true );
				}
			}

			$this->properties = $properties;
		}
	}

	public static function get_instance( $post ) {
		$post = get_post( $post );

		if ( ! $post || self::post_type != get_post_type( $post ) ) {
			return false;
		}

		return self::$current = new self( $post );
	}

	public static function count() {
		return self::$found_items;
	}

	public static function get_current() {
		return self::$current;
	}

	public static function register_post_type() {
		register_post_type( self::post_type, array(
			'labels' => array(
				'name'			=> __( 'Search Forms', 'add-search-to-menu' ),
				'singular_name' => __( 'Search Form', 'add-search-to-menu' ),
			),
			'rewrite'   => false,
			'query_var' => false,
		) );
	}

	public static function find( $args = '' ) {
		$defaults = array(
			'post_status'	 => 'any',
			'posts_per_page' => -1,
			'offset'		 => 0,
			'orderby'		 => 'ID',
			'order'			 => 'ASC',
		);

		$args = wp_parse_args( $args, $defaults );

		$args['post_type'] = self::post_type;

		$q = new WP_Query();
		$posts = $q->query( $args );

		self::$found_items = $q->found_posts;

		$objs = array();

		foreach ( (array) $posts as $post ) {
			$objs[] = new self( $post );
		}

		return $objs;
	}

	public static function get_template( $args = '' ) {
		global $l10n;

		$defaults = array( 'locale' => null, 'title' => '' );
		$args = wp_parse_args( $args, $defaults );

		$locale = $args['locale'];
		$title = $args['title'];

		if ( $locale ) {
			$mo_orig = $l10n['add-search-to-menu'];
			$is_i18n = IS_I18n::getInstance();
			$is_i18n->load_is_textdomain( $locale );
		}

		self::$current = $search_form = new self;
		$search_form->title =
			( $title ? $title : __( 'Untitled', 'add-search-to-menu' ) );
		$search_form->locale = ( $locale ? $locale : get_locale() );

		$properties = $search_form->get_properties();

		$search_form->properties = $properties;

		$search_form = apply_filters( 'is_search_form_default_pack',
			$search_form, $args );

		if ( isset( $mo_orig ) ) {
			$l10n['add-search-to-menu'] = $mo_orig;
		}

		return $search_form;
	}

	public function __get( $name ) {
		$message = __( '<code>%1$s</code> property of a <code>IS_Search_Form</code> object is <strong>no longer accessible</strong>. Use <code>%2$s</code> method instead.', 'add-search-to-menu' );

		if ( 'id' == $name ) {
			if ( WP_DEBUG ) {
				trigger_error( sprintf( $message, 'id', 'id()' ) );
			}

			return $this->id;
		} elseif ( 'title' == $name ) {
			if ( WP_DEBUG ) {
				trigger_error( sprintf( $message, 'title', 'title()' ) );
			}

			return $this->title;
		} elseif ( $prop = $this->prop( $name ) ) {
			if ( WP_DEBUG ) {
				trigger_error(
					sprintf( $message, $name, 'prop(\'' . $name . '\')' ) );
			}

			return $prop;
		}
	}

	public function initial() {
		return empty( $this->id );
	}

	public function prop( $name ) {
		$props = $this->get_properties();
		return isset( $props[$name] ) ? $props[$name] : null;
	}

	public function get_properties() {
		$properties = (array) $this->properties;

		$properties = wp_parse_args( $properties, array(
			'_is_includes' => '',
			'_is_excludes' => '',
			'_is_settings' => '',
			'_is_ajax' => '',
			'_is_customize' => '',
		) );

		$properties = (array) apply_filters( 'is_search_form_properties',
			$properties, $this );

		return $properties;
	}

	public function set_properties( $properties ) {
		$defaults = $this->get_properties();

		$properties = wp_parse_args( $properties, $defaults );
		$properties = array_intersect_key( $properties, $defaults );

		$this->properties = $properties;
	}

	public function id() {
		return $this->id;
	}

	public function name() {
		return $this->name;
	}

	public function title() {
		return $this->title;
	}

	public function set_title( $title ) {
		$title = strip_tags( $title );
		$title = trim( $title );

		if ( '' === $title ) {
			$title = __( 'Untitled', 'add-search-to-menu' );
		}

		$this->title = $title;
	}

	public function locale() {
		if ( $this->is_valid_locale( $this->locale ) ) {
			return $this->locale;
		} else {
			return '';
		}
	}

	public function set_locale( $locale ) {
		$locale = trim( $locale );

		if ( $this->is_valid_locale( $locale ) ) {
			$this->locale = $locale;
		} else {
			$this->locale = 'en_US';
		}
	}

	// Return true if this form is the same one as currently POSTed.
	public function is_posted() {

		if ( empty( $_POST['_is_unit_tag'] ) ) {
			return false;
		}

		return $this->unit_tag == $_POST['_is_unit_tag'];
	}

	/**
	 * Get Customizer Generated CSS
	 *
	 * @since 4.3
	 * 
	 * @param  int $post_id     Post ID.
	 * @return mixed
	 */
	function get_css( $post_id ) {

		$settings = get_option( 'is_search_' . $post_id );
		$search_form = IS_Search_Form::get_instance( $post_id );
		$css = '';

		// AJAX customizer fields.
		$_ajax = $search_form->prop( '_is_ajax' );
		if ( isset( $_ajax['enable_ajax'] ) ) {
			
			// Suggestion Box.
			$suggestion_box_bg_color       = isset( $settings['search-results-bg'] ) ? $settings['search-results-bg'] : '';
			$suggestion_box_selected_color = isset( $settings['search-results-hover'] ) ? $settings['search-results-hover'] : '';
			$suggestion_box_text_color     = isset( $settings['search-results-text'] ) ? $settings['search-results-text'] : '';
			$suggestion_box_link_color     = isset( $settings['search-results-link'] ) ? $settings['search-results-link'] : '';
			$suggestion_box_border_color   = isset( $settings['search-results-border'] ) ? $settings['search-results-border'] : '';
			ob_start();
			if ( '' !== $suggestion_box_bg_color ) { ?>
			#is-ajax-search-result-<?php echo esc_attr( $post_id ); ?> .is-ajax-search-post,                        
                        #is-ajax-search-result-<?php echo esc_attr( $post_id ); ?> .is-show-more-results,
                        #is-ajax-search-details-<?php echo esc_attr( $post_id ); ?> .mCSB_container > div {
				background-color: <?php echo esc_html( $suggestion_box_bg_color ); ?> !important;
			}
                        <?php
                        }
                        if ( '' !== $suggestion_box_selected_color ) {
                        ?>
			#is-ajax-search-result-<?php echo esc_attr( $post_id ); ?> .is-ajax-search-post:hover,
                        #is-ajax-search-result-<?php echo esc_attr( $post_id ); ?> .is-show-more-results:hover,
                        #is-ajax-search-details-<?php echo esc_attr( $post_id ); ?> .is-ajax-search-tags-details > div:hover,
                        #is-ajax-search-details-<?php echo esc_attr( $post_id ); ?> .is-ajax-search-categories-details > div:hover {
				background-color: <?php echo esc_html( $suggestion_box_selected_color ); ?> !important;
			}
                        <?php
                        }
                        if ( '' !== $suggestion_box_text_color ) {
                        ?>
			#is-ajax-search-result-<?php echo esc_attr( $post_id ); ?>,
                        #is-ajax-search-details-<?php echo esc_attr( $post_id ); ?> {
				color: <?php echo esc_html( $suggestion_box_text_color ); ?> !important;
			}
                        <?php
                        }
                        if ( '' !== $suggestion_box_link_color ) {
                        ?>
			#is-ajax-search-result-<?php echo esc_attr( $post_id ); ?> a,
                        #is-ajax-search-details-<?php echo esc_attr( $post_id ); ?> a:not(.button) {
				color: <?php echo esc_html( $suggestion_box_link_color ); ?> !important;
			}
                        #is-ajax-search-details-<?php echo esc_attr( $post_id ); ?> .is-ajax-woocommerce-actions a.button {
                            background-color: <?php echo esc_html( $suggestion_box_link_color ); ?> !important;
                        }
                        <?php
                        }
                        if ( '' !== $suggestion_box_border_color ) {
                        ?>
			#is-ajax-search-result-<?php echo esc_attr( $post_id ); ?> .is-ajax-search-post,
			#is-ajax-search-details-<?php echo esc_attr( $post_id ); ?> .is-ajax-search-post-details {
			    border-color: <?php echo esc_html( $suggestion_box_border_color ); ?> !important;
			}
                        #is-ajax-search-result-<?php echo esc_attr( $post_id ); ?>,
                        #is-ajax-search-details-<?php echo esc_attr( $post_id ); ?> {
                            background-color: <?php echo esc_html( $suggestion_box_border_color ); ?> !important;
                        }
			<?php
                        }
			$css .= ob_get_clean();
		}

		// Customize options.
		$_customize = $search_form->prop('_is_customize');
		if( isset( $_customize['enable_customize'] ) ) {
			// Input.
			$search_input_color        = isset( $settings['text-box-text'] ) ? $settings['text-box-text'] : '';
			$search_input_bg_color     = isset( $settings['text-box-bg'] ) ? $settings['text-box-bg'] : '';
			$search_input_border_color = isset( $settings['text-box-border'] ) ? $settings['text-box-border'] : '';

			// Submit.
			$search_submit_color    = isset( $settings['submit-button-text'] ) ? $settings['submit-button-text'] : '';
			$search_submit_bg_color = isset( $settings['submit-button-bg'] ) ? $settings['submit-button-bg'] : '';
			ob_start();
			if ( '' !== $search_submit_color || '' !== $search_submit_bg_color ) { ?>
			.is-form-id-<?php echo esc_attr( $post_id ); ?> .is-search-submit:focus,
			.is-form-id-<?php echo esc_attr( $post_id ); ?> .is-search-submit:hover,
			.is-form-id-<?php echo esc_attr( $post_id ); ?> .is-search-submit,
                        .is-form-id-<?php echo esc_attr( $post_id ); ?> .is-search-icon {
				<?php echo ( '' !== $search_submit_color  ) ? 'color: ' . esc_html( $search_submit_color ).' !important;':''; ?>
                                <?php echo ( '' !== $search_submit_bg_color  ) ? 'background-color: ' . esc_html( $search_submit_bg_color ).' !important;':''; ?>
			}
                        <?php
                        }
                        if ( '' !== $search_input_color ) {
                        ?>
			.is-form-id-<?php echo esc_attr( $post_id ); ?> .is-search-input::-webkit-input-placeholder {
			    color: <?php echo esc_html( $search_input_color ); ?> !important;
			}
			.is-form-id-<?php echo esc_attr( $post_id ); ?> .is-search-input:-moz-placeholder {
			    color: <?php echo esc_html( $search_input_color ); ?> !important;
			    opacity: 1;
			}
			.is-form-id-<?php echo esc_attr( $post_id ); ?> .is-search-input::-moz-placeholder {
			    color: <?php echo esc_html( $search_input_color ); ?> !important;
			    opacity: 1;
			}
			.is-form-id-<?php echo esc_attr( $post_id ); ?> .is-search-input:-ms-input-placeholder {
			    color: <?php echo esc_html( $search_input_color ); ?> !important;
			}
                        <?php
                        }
                        if ( '' !== $search_input_color || '' !== $search_input_border_color || '' !== $search_input_bg_color ) {
                        ?>
			.is-form-style-1.is-form-id-<?php echo esc_attr( $post_id ); ?> .is-search-input:focus,
			.is-form-style-1.is-form-id-<?php echo esc_attr( $post_id ); ?> .is-search-input:hover,
			.is-form-style-1.is-form-id-<?php echo esc_attr( $post_id ); ?> .is-search-input,
			.is-form-style-2.is-form-id-<?php echo esc_attr( $post_id ); ?> .is-search-input:focus,
			.is-form-style-2.is-form-id-<?php echo esc_attr( $post_id ); ?> .is-search-input:hover,
			.is-form-style-2.is-form-id-<?php echo esc_attr( $post_id ); ?> .is-search-input,
			.is-form-style-3.is-form-id-<?php echo esc_attr( $post_id ); ?> .is-search-input:focus,
			.is-form-style-3.is-form-id-<?php echo esc_attr( $post_id ); ?> .is-search-input:hover,
			.is-form-style-3.is-form-id-<?php echo esc_attr( $post_id ); ?> .is-search-input,
			.is-form-id-<?php echo esc_attr( $post_id ); ?> .is-search-input:focus,
			.is-form-id-<?php echo esc_attr( $post_id ); ?> .is-search-input:hover,
			.is-form-id-<?php echo esc_attr( $post_id ); ?> .is-search-input {
                                <?php echo ( '' !== $search_input_color  ) ? 'color: ' . esc_html( $search_input_color ).' !important;':''; ?>
                                <?php echo ( '' !== $search_input_border_color  ) ? 'border-color: ' . esc_html( $search_input_border_color ).' !important;':''; ?>
                                <?php echo ( '' !== $search_input_bg_color  ) ? 'background-color: ' . esc_html( $search_input_bg_color ).' !important;':''; ?>
			}
                        <?php if ( '' !== $search_input_border_color  ) { ?>
                        .is-form-id-<?php echo esc_attr( $post_id ); ?> .is-search-icon {
                                border-color: <?php echo esc_html( $search_input_border_color ); ?> !important;
                        }
			<?php
                        }
                        }
			$css .= ob_get_clean();
		}

		return $css;
	}

	/* Generating Form HTML */
	public function form_html( $args = '', $display_id = '' ) {

		do_action( 'pre_is_get_search_form' );

                if ( ! isset( $args['id'] ) ) {
			return '';
		}

                $search_form = IS_Search_Form::get_instance( $args['id'] );

                if ( ! $search_form ) {
                    return __( 'Invalid search form.', 'add-search-to-menu' );
                }

                $_ajax = $this->prop('_is_ajax');
                $_customize = $this->prop('_is_customize');
                $_includes = $this->prop( '_is_includes' );
                $_settings = $this->prop('_is_settings');
                $result = '';
                $is = Ivory_Search::getInstance();

                if ( ! isset( $is->opt['not_load_files']['css'] ) ) {
                        wp_enqueue_style( 'ivory-search-styles', plugins_url( '/public/css/ivory-search.css', IS_PLUGIN_FILE ), array(), IS_VERSION );
                }

                if ( isset( $_customize['enable_customize'] ) || isset( $_ajax['enable_ajax'] ) ) {
                        wp_add_inline_style( 'ivory-search-styles', $this->get_css( $args['id'] ) );
                }

                if ( isset( $_settings['disable'] ) ) {
                        return '';
                }
                if ( isset( $_settings['demo'] ) && ! current_user_can( 'administrator' ) ) {
                        return '';
                }

                if ( ! isset( $_ajax['enable_ajax'] ) && ! isset( $_customize['enable_customize'] ) ) {

                    remove_filter( 'get_search_form', array( IS_Public::getInstance(), 'get_search_form' ), 9999999 );
                    $result = get_search_form( false );
                    add_filter( 'get_search_form', array( IS_Public::getInstance(), 'get_search_form' ), 9999999 );

                    if ( 'n' !== $display_id ) {
                        $result = preg_replace('/<\/form>/', '<input type="hidden" name="id" value="' . $args['id'] . '" /></form>', $result );
                    }
                    if ( ! isset( $_includes['post_type_url']  ) && isset( $_includes['post_type'] ) && count( $_includes['post_type'] ) < 2 ) {
                            $result = preg_replace('/<\/form>/', '<input type="hidden" name="post_type" value="' . reset( $_includes['post_type'] ) . '" /></form>', $result );
                    }
                    if ( isset( $_GET['lang'] ) ) {
                        $result = preg_replace('/<\/form>/', '<input type="hidden" name="lang" value="' . $_GET['lang'] . '" /></form>', $result );
                    }

                    $result = apply_filters( 'is_default_search_form', $result );

                } else {

                $view_search_result_class = ( isset( $_ajax['search_results'] ) && 'ajax_results' === $_ajax['search_results'] ) ? 'is-disable-submit ' : '';
            	$settings = get_option( 'is_search_' . $args['id'] );

                $is_ajax_search = '';
                $data_attrs = '';
                $placeholder_text = __( 'Search...', 'add-search-to-menu');
                $search_btn_text = __( 'Search', 'add-search-to-menu');
                $form_style = '';

                if ( isset( $_customize['enable_customize'] ) ) {
                    $placeholder_text = isset( $settings['placeholder-text'] ) ? $settings['placeholder-text'] : $placeholder_text;
                    $search_btn_text = isset( $settings['search-btn-text'] ) ? $settings['search-btn-text'] : $search_btn_text;
                    $form_style = ( isset( $settings['form-style'] ) && 'is-form-style-default' !== $settings['form-style'] ) ? $settings['form-style'] : '';
                }

                if ( isset( $_ajax['enable_ajax'] ) ) {
                    $is_ajax_search = 'is-ajax-search';

                    // Enqueue scripts.
                    wp_enqueue_script( 'ivory-ajax-search-scripts' );

                    $min_no_for_search  = isset( $_ajax['min_no_for_search'] ) ? $_ajax['min_no_for_search'] : '1';
                    $result_box_max_height = isset( $_ajax['result_box_max_height'] ) ? $_ajax['result_box_max_height'] : '400';

                    // Add data AJAX attributes.
                    $data_attrs = 'data-min-no-for-search="'.esc_attr( $min_no_for_search ).'"';
                    $data_attrs .= ' data-result-box-max-height="'.$result_box_max_height.'"';
                    $data_attrs .= ' data-form-id="'.$args['id'].'"';
                }

                $temp = ( '' !== $form_style ) ? 'is-form-style ' : '';
                $classes = $view_search_result_class . $temp . $form_style . ' is-form-id-' . $args['id'].' '.$is_ajax_search;
                $classes = apply_filters( 'is_search_form_classes', $classes );

                $result = '<form '.$data_attrs.' class="is-search-form '. $classes .'" action="' . home_url('/') . '" method="get" role="search" >';
                $autocomplete = apply_filters( 'is_search_form_autocomplete', 'autocomplete="off"' );
                $result .= '<label><input  type="text" name="s" value="' . get_search_query() . '" class="is-search-input" placeholder="' . esc_attr( $placeholder_text ) . '" '.$autocomplete.' />';
                // AJAX Loader.
                if ( isset( $_ajax['enable_ajax'] ) ) {
                    $loader_image = isset( $settings['loader-image'] ) ? $settings['loader-image'] : IS_PLUGIN_URI . 'public/images/spinner.gif';
                    if ( $loader_image ) {
                            $result .= '<img class="is-loader-image" alt="'. esc_attr__( "Loader Image", 'add-search-to-menu' ) .'" style="display: none;" src="'.$loader_image.'" />';
                    }
                }
                $result .= '</label>';
                if ( 'is-form-style-3' === $form_style ) {
                    $result .= '<button type="submit" class="is-search-submit"><span class="is-search-icon"><svg focusable="false" aria-label="' . __( "Search", "ivory-search" ) . '" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="24px"><path d="M15.5 14h-.79l-.28-.27C15.41 12.59 16 11.11 16 9.5 16 5.91 13.09 3 9.5 3S3 5.91 3 9.5 5.91 16 9.5 16c1.61 0 3.09-.59 4.23-1.57l.27.28v.79l5 4.99L20.49 19l-4.99-5zm-6 0C7.01 14 5 11.99 5 9.5S7.01 5 9.5 5 14 7.01 14 9.5 11.99 14 9.5 14z"></path></svg></span></button>';
		} else if ( 'is-form-style-2' !== $form_style ) {
                    $result .= '<input type="submit" value="' . esc_html( $search_btn_text ) . '" class="is-search-submit" />';
                }

                if ( 'n' !== $display_id ) {
                    $result .= '<input type="hidden" name="id" value="' . $args['id'] . '" />';
                }

                if ( ! isset( $_includes['post_type_url']  ) && isset( $_includes['post_type'] ) && count( $_includes['post_type'] ) < 2 ) {
                        $result .= '<input type="hidden" name="post_type" value="' . reset( $_includes['post_type'] ) . '" />';
                }

                if ( isset( $_GET['lang'] ) ) {
                    $result .=  '<input type="hidden" name="lang" value="' . $_GET['lang'] . '" />';
                }
                $result .= '</form>';

                $result = apply_filters( 'is_custom_search_form', $result );
		}

                if ( is_user_logged_in() && current_user_can( 'administrator' ) ) {
                    $result .= '<div class="is-link-container"><div><a class="is-edit-link" target="_blank" href="'.admin_url( 'admin.php?page=ivory-search&post='.$args['id'].'&action=edit' ) . '">'.__( "Edit", "ivory-search") .'</a>';

                    if ( ! is_customize_preview() ) {
                        if ( isset( $_customize['enable_customize'] ) || isset( $_ajax['enable_ajax'] ) ) {
                                $result .= ' <a class="is-customize-link" target="_blank" href="'.admin_url( 'customize.php?autofocus[section]=is_section_'.$args['id'].'&url=' . get_the_permalink( get_the_ID() ) ) .'">'.__( "Customizer", "ivory-search") .'</a>';
                        }
                    }
                    $result .= '</div></div>';
                }

                $result = apply_filters( 'is_after_search_form', $result );

		return $result;
	}

	/* Settings */

	public function setting( $name, $max = 1 ) {
		$settings = (array) explode( "\n", $this->prop( 'settings' ) );

		$pattern = '/^([a-zA-Z0-9_]+)[\t ]*:(.*)$/';
		$count = 0;
		$values = array();

		foreach ( $settings as $setting ) {
			if ( preg_match( $pattern, $setting, $matches ) ) {
				if ( $matches[1] != $name ) {
					continue;
				}

				if ( ! $max || $count < (int) $max ) {
					$values[] = trim( $matches[2] );
					$count += 1;
				}
			}
		}

		return $values;
	}

	public function is_true( $name ) {
		$settings = $this->setting( $name, false );

		foreach ( $settings as $setting ) {
			if ( in_array( $setting, array( 'on', 'true', '1' ) ) ) {
				return true;
			}
		}

		return false;
	}

	/* Save */

	public function save() {
		$props = $this->get_properties();

		$post_content = implode( "\n", $this->array_flatten( $props ) );

		if ( $this->initial() ) {
			$post_id = wp_insert_post( array(
				'post_type' => self::post_type,
				'post_status' => 'publish',
				'post_title' => $this->title,
				'post_content' => trim( $post_content ),
			) );
		} else {
			$post_id = wp_update_post( array(
				'ID' => (int) $this->id,
				'post_status' => 'publish',
				'post_title' => $this->title,
				'post_content' => trim( $post_content ),
			) );
		}

		if ( $post_id ) {
			foreach ( $props as $prop => $value ) {
				update_post_meta( $post_id, $prop, $this->normalize_newline_deep( $value ) );
			}

			if ( $this->is_valid_locale( $this->locale ) ) {
				update_post_meta( $post_id, '_is_locale', $this->locale );
			}

			if ( $this->initial() ) {
				$this->id = $post_id;
				do_action( 'is_after_create', $this );
			} else {
				do_action( 'is_after_update', $this );
			}

			do_action( 'is_after_save', $this );
		}

		return $post_id;
	}

	public function copy() {
		$new = new self;
		$new->title = $this->title . '_copy';
		$new->locale = $this->locale;
		$new->properties = $this->properties;

		return apply_filters( 'is_copy', $new, $this );
	}

	public function delete() {
		if ( $this->initial() ) {
			return;
		}

		if ( wp_delete_post( $this->id, true ) ) {
			$this->id = 0;
			return true;
		}

		return false;
	}

	public function shortcode( $args = '' ) {
		$args = wp_parse_args( $args );

		$title = str_replace( array( '"', '[', ']' ), '', $this->title );

		$shortcode = sprintf( '[ivory-search id="%1$d" title="%2$s"]',
			$this->id, $title );

		return apply_filters( 'is_search_form_shortcode', $shortcode, $args, $this );
	}

	function is_valid_locale( $locale ) {
		$pattern = '/^[a-z]{2,3}(?:_[a-zA-Z_]{2,})?$/';
		return (bool) preg_match( $pattern, $locale );
	}

	function normalize_newline( $text, $to = "\n" ) {
		if ( ! is_string( $text ) ) {
			return $text;
		}

		$nls = array( "\r\n", "\r", "\n" );

		if ( ! in_array( $to, $nls ) ) {
			return $text;
		}

		return str_replace( $nls, $to, $text );
	}

	function normalize_newline_deep( $arr, $to = "\n" ) {

		if ( is_array( $arr ) ) {
			$result = array();

			foreach ( $arr as $key => $text ) {
				$result[$key] = $this->normalize_newline_deep( $text, $to );
			}

			return $result;
		}

		return $this->normalize_newline( $arr, $to );
	}

	function array_flatten( $input ) {
		if ( ! is_array( $input ) ) {
			return array( $input );
		}

		$output = array();

		foreach ( $input as $value ) {
			$output = array_merge( $output, $this->array_flatten( $value ) );
		}

		return $output;
	}

}

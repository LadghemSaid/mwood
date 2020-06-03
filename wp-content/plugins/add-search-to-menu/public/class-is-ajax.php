<?php
/**
 * This class defines all plugin AJAX functionality for the site front end.
 *
 * @since      4.3
 * @package    IS
 * @subpackage IS/public
 * @author     Ivory Search <admin@ivorysearch.com>
 */

class IS_Ajax {

	/**
	 * Core singleton class
	 * @var self
	 */
	private static $_instance;

	/**
	 * Initializes this class
	 */
	public function __construct() {
        }

	/**
	 * Gets the instance of this class.
	 *
	 * @return self
	 */
	public static function getInstance() {
		if ( ! ( self::$_instance instanceof self ) ) {
			self::$_instance = new self();
		}

		return self::$_instance;
	}

	/**
	 * Load AJAX posts.
	 *
	 * @since 4.3
	 * 
	 * @return void
	 */
	function ajax_load_posts() {

                check_ajax_referer( 'is_ajax_nonce', 'security' );

		$search_post_id = isset( $_POST['id'] ) ? sanitize_text_field( absint( $_POST['id'] ) ) : '';
		$page = isset( $_POST['page'] ) ? sanitize_text_field( absint( $_POST['page'] ) ) : 1;
		$search_term = isset( $_POST['s'] ) ? sanitize_text_field( $_POST['s'] ) : '';

                $search_form = IS_Search_Form::get_instance( $search_post_id );
		$stored_field = $search_form->prop( '_is_ajax' );
                $is_settings = $search_form->prop( '_is_settings' );
                $posts_per_page = isset( $is_settings['posts_per_page'] ) ? $is_settings['posts_per_page'] : 10;

		$defaults = array(
			'show_description'           => 0,
			'description_source'         => 'content',
			'description_length'         => 20,
			'show_image'                 => 0,
			'show_categories'            => 0,
			'show_tags'                  => 0,
                        'show_author'                => 0,
                        'show_date'                  => 0,
                        'nothing_found_text'         => __( 'Nothing found', 'add-search-to-menu' ),
			'show_more_result'           => 0,
			'more_result_text'           => __( 'More results', 'add-search-to-menu' ),
			'show_price'                 => 0,
			'hide_price_out_of_stock'    => 0,
			'show_sale_badge'            => 0,
			'show_sku'                   => 0,
			'show_stock_status'          => 0,
			'show_featured_icon'         => 0,
			'show_matching_categories'   => 0,
			'show_matching_tags'         => 0,
                        'show_details_box'           => 0,
		);

		$field = wp_parse_args( $stored_field, $defaults );
                $posts_class = 'is-show-details-disabled';

		if ( isset( $field['show_details_box'] ) && $field['show_details_box'] ) {
			$posts_class = 'is-show-details-enabled';
		}
		?>
		<?php if( 1 == $page ) { ?>
			<div class="is-ajax-search-items <?php echo esc_attr( $posts_class ); ?>">
		<?php } ?>
			<?php
			// Show matching tags.
			if( isset( $field['show_matching_tags'] ) && $field['show_matching_tags'] && 1 == $page ) {
				$this->term_title_markup( array(
					'taxonomy'      => 'product_tag',
					'search_term'   => $search_term,
					'title'         => __( 'Tag', 'add-search-to-menu' ),
					'wrapper_class' => 'is-ajax-search-tags',
				) );
			}

			// Show matching categories.
			if( isset( $field['show_matching_categories'] ) && $field['show_matching_categories'] && 1 == $page ) {
				$this->term_title_markup( array(
					'taxonomy'      => 'product_cat',
					'search_term'   => $search_term,
					'title'         => __( 'Category', 'add-search-to-menu' ),
					'wrapper_class' => 'is-ajax-search-categories',
				) );
			}

			$post_args = array(
                                'suppress_filters' => false,
                                'post_type' => '',
                                'numberposts' => $posts_per_page,
				's'     => $search_term,
				'paged' => $page,
			);

			$posts = get_posts( $post_args );

			if ( $posts ) {

				if( 1 == $page ) { ?>
					<div class="is-ajax-search-posts">
				<?php }

			    foreach ( $posts as $post ) :
			        setup_postdata( $post );

			        $product = '';
			        $product_class = '';
			        if( 'product' === $post->post_type && function_exists( 'wc_get_product' ) ) {
			        	$product = wc_get_product( $post->ID );
			        	$product_class = 'is-product';
                                    if ( isset( $field['show_sale_badge'] ) && $field['show_sale_badge'] ) {
                                        $on_sale = ( $product->is_in_stock() ) ? $product->is_on_sale() : '';
                                        if ( $on_sale ) {
                                            $product_class .= ' is-has-badge';
                                        }
                                    }
			        }
			        ?>
			        <div data-id="<?php echo esc_attr( $post->ID ); ?>" class="is-ajax-search-post is-ajax-search-post-<?php echo esc_attr( $post->ID ) .' '. esc_attr( $product_class ); ?>">
			        	<!-- Header -->
			        	<div class="is-search-sections">

			        		<?php $this->image_markup( $field, $post ); ?>

			        		<div class="right-section">

                                                            <?php
                                                            $this->title_markup( $field, $post, $product ); 
                                                            ?>

					        	<div class="meta">
                                                            <div>
                                                                <?php $this->product_price_markup( $field, $product ); ?>
								<?php $this->product_stock_status_markup( $field, $product ); ?>
                                                            	<?php $this->product_sku_markup( $field, $product ); ?>
                                                            </div>
                                                                <?php $this->date_markup( $field, $post ); ?>
				        			<?php $this->author_markup( $field ); ?>
				        			<?php $this->tags_markup( $field, $post ); ?>
				        			<?php $this->categories_markup( $field, $post ); ?>
					        	</div><!-- .meta -->

					        	<!-- Content -->
					        	<div class="is-search-content">
					        		<?php $this->description_markup( $field, $post ); ?>
                                                        </div>

								<!-- WooCommerce Contents -->
								<?php
								$this->product_sale_badge_markup( $field, $product );
				                ?>
			        		</div>
			        	</div>

		        	</div>
			    <?php endforeach;
			    wp_reset_postdata();?>

			    <?php if( 1 == $page ) {?>
			    	</div>
				<?php } ?>


			    <?php
			} else if( empty( $tags ) && empty( $categories )) {
				?>
				<div class="is-ajax-search-no-result">
					<?php echo html_entity_decode( $field['nothing_found_text'] ); ?>
				</div>
				<?php
			}
			?>

		<?php if( 1 == $page ) { ?>
			</div>
		<?php } ?>
	            <?php
                    if ( isset( $field['show_more_result'] ) && $field['show_more_result'] && ( count( $posts ) >= $posts_per_page ) ) {
			    $next_page = $page + 1; ?>
			    	<div class="is-show-more-results" data-page="<?php echo $next_page; ?>">
			    		<div class="is-show-more-results-text"><?php echo $field['more_result_text']; ?></div>
				    	<?php
				    	// AAJX Loader.
                                        $settings = get_option( 'is_search_' . $search_post_id );
                                        $loader_image = isset( $settings['loader-image'] ) ? $settings['loader-image'] : IS_PLUGIN_URI . 'public/images/spinner.gif';
                                        if( $loader_image ) {
                                                echo '<img class="is-load-more-image" alt="'. esc_attr__( "Loader Image", 'add-search-to-menu' ) .'" src="'.esc_attr( $loader_image ).'" style="display: none;" />';
                                        }
				    	?>
			    	</div>
			    <?php } ?>		
		<?php if( isset( $field['show_details_box'] ) && $field['show_details_box'] ) { ?>
		    <div id="is-ajax-search-details-<?php echo $search_post_id; ?>" class="is-ajax-search-details">
                        <div class="is-ajax-search-items">
			    <?php
                            if ( 1 == $page ) {
			    // Show product details by "tags".
				if( isset( $field['show_matching_tags'] ) && $field['show_matching_tags'] ) {
					$this->product_details_markup( array(
						'taxonomy'      => 'product_tag',
						'search_term'   => $search_term,
						'field'         => $field,
						'wrapper_class' => 'is-ajax-search-tags-details',
					) );
				}
				?>

			    <?php
			    // Show product details by "categories".
				if( isset( $field['show_matching_categories'] ) && $field['show_matching_categories'] ) {
					$this->product_details_markup( array(
						'taxonomy'      => 'product_cat',
						'search_term'   => $search_term,
						'field'         => $field,
						'wrapper_class' => 'is-ajax-search-categories-details',
					) );
				}
                            }
				if ( $posts ) { ?>
					<div class="is-ajax-search-posts-details">
						<?php
					    foreach ( $posts as $post ) :
					        setup_postdata( $post );
					        $product = '';
                                                $product_class = '';
					        if( 'product' === $post->post_type && function_exists( 'wc_get_product' ) ) {
					        	$product = wc_get_product( $post->ID );
                                                    if ( isset( $field['show_sale_badge'] ) && $field['show_sale_badge'] ) {
                                                        $on_sale = ( $product->is_in_stock() ) ? $product->is_on_sale() : '';
                                                        if ( $on_sale ) {
                                                            $product_class .= ' is-has-badge';
                                                        }
                                                    }
					        }

					        // Is WooCommerce product then show details box.
					        if( $product ) { ?>
					            <div data-id="<?php echo esc_attr( $post->ID ); ?>" class="is-ajax-search-post-details is-ajax-search-post-details-<?php echo esc_attr( $post->ID ).' '. esc_attr( $product_class ); ?>">
					            	<div class="is-search-sections">
										<?php $this->image_markup( $field, $post ); ?>
					            		<div class="right-section">
                                                                    <?php
                                                                    $this->title_markup( $field, $post, $product );
                                                                    ?> 
                                                                    <div class="meta">
                                                                        <div>
                                                                            <?php $this->product_price_markup( $field, $product ); ?>
                                                                            <?php $this->product_stock_status_markup( $field, $product ); ?>
                                                                            <?php $this->product_sku_markup( $field, $product ); ?>
                                                                        </div>
                                                                            <?php $this->date_markup( $field, $post ); ?>
                                                                            <?php $this->author_markup( $field ); ?>
                                                                            <?php $this->tags_markup( $field, $post ); ?>
                                                                            <?php $this->categories_markup( $field, $post ); ?>
                                                                    </div><!-- .meta -->
                                                          <!-- Content -->
					        	<div class="is-search-content">
					        		<?php $this->description_markup( $field, $post, true ); ?>
                                                        </div>
							<?php $this->product_sale_badge_markup( $field, $product );

											if( $product ) { ?>
												<div class="is-ajax-woocommerce-actions">
													<?php
                                                                                                        if ( function_exists( 'woocommerce_quantity_input' ) ) {
													woocommerce_quantity_input( array(
                                                                                                            'input_name'  => 'is-ajax-search-product-quantity',
                                                                                                        ), $product, true );
													echo WC_Shortcodes::product_add_to_cart( array(
															'id'		 => $post->ID,
															'show_price' => false,
															'style'		 => '',
														) );
                                                                                                        } ?>
												</div>
											<?php } ?>
					            		</div>
									</div>
								</div>
							<?php
					        }
				        endforeach;
				   		 wp_reset_postdata();?>
					</div>
				<?php } ?>
                        </div>
		    </div>
		    <?php
	    }
		wp_die();
	}

	/**
	 * Get Taxonomies by Search Term
	 *
	 * @since 4.3
	 * 
	 * @param  string $taxonomy     Taxonomy Slug.
	 * @param  string $search_term  Search Term.
	 * @return array
	 */
        function get_taxonomies( $taxonomy, $search_term ) {

            $result = array();

            $all_terms = get_terms( $taxonomy, array(
                'taxonomy' => $taxonomy,
            ) );

            foreach ( $all_terms as $term ) {

                    // Used strtolower() because, If search term is 'product' and actual taxonomy title is 'Product',
                    // Then, it does not match due to its case sensitive test.
                    // See https://i.imgur.com/F8ag5cB.png
                if ( strpos( strtolower($term->name), strtolower($search_term) ) !== false ) {
                    $result[] = array(
                                            'term_id'  => $term->term_id,
                                            'name'     => $term->name,
                                            'slug'     => $term->slug,
                                            'taxonomy' => $term->taxonomy,
                                            'count'    => $term->count,
                                            'url'      => get_term_link( $term, $taxonomy ),
                    );
                }
            }

            return $result;
        }

	/**
	 * Term Title
	 *
	 * @since 4.3
	 *
	 * @param  array $args      Term Arguments.
	 * @return void
	 */
	function term_title_markup( $args = array() ) {
		$taxonomy      = $args['taxonomy'];
		$search_term   = $args['search_term'];
		$term_title    = $args['title'];
		$wrapper_class = $args['wrapper_class'];

		$tags = $this->get_taxonomies( $taxonomy, $search_term );
		if( $tags ) { ?>
			<div class="<?php echo $wrapper_class; ?>">
			<?php foreach ($tags as $key => $tag) { ?>
				<div data-id="<?php echo esc_attr( $tag['term_id'] ); ?>" class="is-ajax-search-post">
					<span class="is-ajax-term-label"><?php echo esc_html( $term_title ); ?></span>
                                        <div class="is-title">
					<a href="<?php echo esc_url( $tag['url'] ); ?>" data-id="<?php echo esc_attr( $tag['term_id'] ); ?>" data-slug="<?php echo esc_attr( $tag['slug'] ); ?>"><?php echo esc_attr( $tag['name'] ); ?> (<span class="is-term-count"><?php echo esc_attr( $tag['count'] ); ?></span>)</a>
                                        </div>
				</div>
			<?php } ?>
			</div>
			<?php
		}
	}

	/**
	 * Term Details Markup
	 *
	 * @since 4.3
	 *
	 * @param  array $args      Term Arguments.
	 * @return void
	 */
	function product_details_markup( $args = array() ) {
		$taxonomy      = $args['taxonomy'];
		$search_term   = $args['search_term'];
		$field         = $args['field'];
		$wrapper_class = $args['wrapper_class'];

		$terms = $this->get_taxonomies( $taxonomy, $search_term );
		if( $terms ) {
			?>
			<div class="<?php echo $wrapper_class; ?>">
				<?php
				foreach ($terms as $key => $term) {
					$this->get_product_by_tax_id( $field, $term['term_id'], $taxonomy );
				}
				?>
			</div>
			<?php
		}
	}

	/**
	 * Get products by taxonomy ID.
	 *
	 * @since 4.3
	 *
	 * @param  array $field      Current stored values.
	 * @param  int $cat_id       Term ID.
	 * @param  string $taxonomy  Taxonomy ID.
	 * @return void
	 */
	function get_product_by_tax_id( $field, $cat_id, $taxonomy ) {

                if ( ! class_exists( 'WooCommerce' ) ) {
                    return;
                }
            
		$product_list = isset( $field['product_list'] ) ? $field['product_list'] : 'all';
		$order_by     = isset( $field['order_by'] ) ? $field['order_by'] : 'date';
		$order        = isset( $field['order'] ) ? $field['order'] : 'desc';

		$query_args = apply_filters( 'is_get_product_by_tax_id', array(
			'posts_per_page' => 4,
			'post_status'	 => 'publish',
			'post_type'	 => 'product',
			'no_found_rows'	 => 1,
			'order'		 => $order,
			'meta_query'	 => array(),
			'tax_query'      => array(
                                'relation'       => 'AND',
                            )
		) );

		switch ( $product_list ) {
			case 'featured' :
				$query_args[ 'tax_query' ][] = array(
					'taxonomy'         => 'product_visibility',
					'field'            => 'name',
					'terms'            => 'featured',
				);
				break;
			case 'onsale' :
				$query_args[ 'post__in' ] = wc_get_product_ids_on_sale();
				break;
		}

		switch ( $order_by ) {
			case 'price' :
				$query_args[ 'meta_key' ]	 = '_price';
				$query_args[ 'orderby' ]	 = 'meta_value_num';
				break;
			case 'rand' :
				$query_args[ 'orderby' ]	 = 'rand';
				break;
			case 'sales' :
				$query_args[ 'meta_key' ]	 = 'total_sales';
				$query_args[ 'orderby' ]	 = 'meta_value_num';
				break;
			default :
				$query_args[ 'orderby' ]	 = 'date';
		}

		$query_args[ 'tax_query' ][] = array(
			'taxonomy'		 => $taxonomy,
			'field'			 => 'id',
			'terms'			 => $cat_id,
			'include_children'	 => true,
		);

		$products = new WP_Query( $query_args );

		if ( $products->have_posts() ) {

			$product_count = 0;
			while ( $products->have_posts() ) {
				$products->the_post();
				$product_count++;
				?>
				<div data-id="<?php echo esc_attr( $cat_id ); ?>" class="is-ajax-search-post-details is-ajax-search-post-details-<?php echo esc_attr( $cat_id ); ?>">

				<?php if( 1 === $product_count ) { ?>
					<div class="is-ajax-term-wrap">
						<?php
						if( 'product_cat' === $taxonomy ) {
							echo '<span class="is-ajax-term-label">'.__('Category', 'add-search-to-menu').':</span> ';
						} else {
							echo '<span class="is-ajax-term-label">'.__('Tag', 'add-search-to-menu').':</span> ';
						}
						$term = get_term( $cat_id, $taxonomy );
						echo '<span class="is-ajax-term-name">'.esc_html( $term->name ).'</span>';
						?>
					</div>
				<?php } ?>

					<div class="is-search-sections">
						<?php
                                                $product = wc_get_product( get_the_ID() );
                                                global $post;
						$this->image_markup( $field, $product ); ?>

	            		<div class="right-section">
                                                            <?php
                                                            $this->title_markup( $field, $post, $product ); 
                                                            ?>

					        	<div class="meta">
                                                            <div>
                                                                <?php $this->product_price_markup( $field, $product ); ?>
								<?php $this->product_stock_status_markup( $field, $product ); ?>
                                                            	<?php $this->product_sku_markup( $field, $product ); ?>
                                                            </div>
                                                                <?php $this->date_markup( $field, $post ); ?>
				        			<?php $this->author_markup( $field ); ?>
				        			<?php $this->tags_markup( $field, $post ); ?>
				        			<?php $this->categories_markup( $field, $post ); ?>
					        	</div><!-- .meta -->

					        	<!-- Content -->
					        	<div class="is-search-content">
					        		<?php $this->description_markup( $field, $post ); ?>
                                                        </div>
							<?php $this->product_sale_badge_markup( $field, $product );

							if( $product ) { ?>
								<div class="is-ajax-woocommerce-actions">
									<?php
                                                                        if ( function_exists( 'woocommerce_quantity_input' ) ) {
									woocommerce_quantity_input( array(
                                                                            'input_name'  => 'is-ajax-search-product-quantity',
                                                                        ), $product, true );
									echo WC_Shortcodes::product_add_to_cart( array(
											'id'		 => get_the_ID(),
											'show_price' => false,
											'style'		 => '',
										) ); 
                                                                        } ?>
								</div>
							<?php } ?>
	            		</div>
					</div>
				</div>
				<?php
			}

		}

		wp_reset_postdata();
	}

	/**
	 * Image Markup
	 *
	 * @since 4.3
	 *
	 * @param  array $field      Current stored values.
	 * @param  object $post      Post object.
	 * @return void
	 */
	function image_markup( $field, $post ) {
		$image = '';
                $image_size = apply_filters( 'is_ajax_image_size', 'thumbnail' );
		if( 'attachment' === $post->post_type ) {
			$image = wp_get_attachment_image( $post->ID, $image_size );
		} else if( has_post_thumbnail( $post->ID ) ) {
			$image = get_the_post_thumbnail( $post->ID, $image_size );
		}
		if ( isset( $field['show_image'] ) && $field['show_image'] ) { ?>
                    <div class="left-section">
                        <div class="thumbnail">
                            <a href="<?php echo get_the_permalink( $post->ID ); ?>"><?php echo $image; ?></a>
                        </div>
                    </div>
		<?php }
	}

	/**
	 * Title Markup
	 *
	 * @since 4.3
	 *
	 * @param  array $field      Current stored values.
	 * @param  object $post      Post object.
	 * @param  mixed $product    Product or Empty.
	 * @return void
	 */
	function title_markup( $field, $post, $product ) {
                if ( '' !== get_the_title( $post->ID ) ) {
		?>
                <div class="is-title">
                        <a href="<?php echo get_the_permalink( $post->ID ); ?>">
                                <?php if( $product && isset( $field['show_featured_icon'] ) && $field['show_featured_icon'] && $product->is_featured() ) { ?>
                                <svg class="is-featured-icon" focusable="false" aria-label="<?php _e( "Featured Icon", "ivory-search" ); ?>" version="1.1" viewBox="0 0 20 21" xmlns="http://www.w3.org/2000/svg" xmlns:sketch="http://www.bohemiancoding.com/sketch/ns" xmlns:xlink="http://www.w3.org/1999/xlink">
                                        <g fill-rule="evenodd" stroke="none" stroke-width="1"><g transform="translate(-296.000000, -422.000000)"><g transform="translate(296.000000, 422.500000)"><path d="M10,15.273 L16.18,19 L14.545,11.971 L20,7.244 L12.809,6.627 L10,0 L7.191,6.627 L0,7.244 L5.455,11.971 L3.82,19 L10,15.273 Z"></path></g></g></g>
                                </svg>
                                <?php } ?>
                                <?php echo get_the_title( $post->ID ); ?>
                        </a>
                </div>
    	<?php
                }
	}

	/**
	 * Author Markup
	 *
	 * @since 4.3
	 *
	 * @param  array $field      Current stored values.
	 * @return void
	 */
	function author_markup( $field ) {
		if ( isset( $field['show_author'] ) && $field['show_author'] ) { ?>
		    <span class="author vcard">
		        <?php _ex( '<i>By</i> ', 'Article written by', 'add-search-to-menu' ); ?>
		        <a class="url fn n" href="<?php echo esc_url( get_author_posts_url( get_the_author_meta( 'ID' ) ) ); ?>">
		            <?php echo esc_html( get_the_author() ); ?>
		        </a>
		    </span>
		<?php }
	}

	/**
	 * Date Markup
	 *
	 * @since 4.3
	 *
	 * @param  array $field      Current stored values.
	 * @param  object $post      Post object.
	 * @return void
	 */
	function date_markup( $field, $post ) {
		if ( isset( $field['show_date'] ) && $field['show_date'] ) { ?>
		<span class="meta-date">
			<span class="posted-on">
				<?php
				$time_string = '<time class="entry-date published updated" datetime="%1$s">%2$s</time>';
				if ( get_the_time( 'U' ) !== get_the_modified_time( 'U' ) ) {
				    $time_string = '<time class="entry-date published" datetime="%1$s">%2$s</time><time class="updated" datetime="%3$s">%4$s</time>';
				}
				$time_string = sprintf( $time_string,
				    esc_attr( get_the_date( 'c', $post->ID ) ),
				    esc_html( get_the_date( '', $post->ID ) ),
				    esc_attr( get_the_modified_date( 'c', $post->ID ) ),
				    esc_html( get_the_modified_date( '', $post->ID ) )
				);
				echo $time_string; ?>
			</span>
		</span>
		<?php }
	}

	/**
	 * Tags Markup
	 *
	 * @since 4.3
	 *
	 * @param  array $field      Current stored values.
	 * @param  object $post      Post object.
	 * @return void
	 */
	function tags_markup( $field, $post ) {
            if ( isset( $field['show_tags'] ) && $field['show_tags'] ) { ?>
                <?php $terms = get_the_terms( $post->ID, $post->post_type.'_tag' );
                if ( $terms && ! is_wp_error( $terms ) ) { ?>
                <span class="is-meta-tag">
                    <?php _e( '<i>Tagged with:</i> ', 'add-search-to-menu' ); ?>
                    <span class="is-tags-links">
                    <?php foreach ( $terms as $key => $term ) { if ( $key ) { echo ', '; }?><a href="<?php echo get_term_link( $term->term_id, $post->post_type.'_tag' ); ?> " rel="tag"><?php echo $term->name; ?></a><?php } ?>
                    </span>
                </span>
                <?php }
            }
        }

       /**
        * Categories Markup
        *
        * @since 4.3
        *
        * @param  array $field      Current stored values.
        * @param  object $post      Post object.
        * @return void
        */
	function categories_markup( $field, $post ) {
            if ( isset( $field['show_categories'] ) && $field['show_categories'] ) { ?>
                <?php 
                $tax_name = ( 'post' === $post->post_type ) ? 'category' : $post->post_type.'_cat';
                $terms = get_the_terms( $post->ID, $tax_name );
                if ( $terms && ! is_wp_error( $terms ) ) { ?>
                <span class="is-meta-category">
                    <?php _e( '<i>Categories:</i> ', 'add-search-to-menu' ); ?>
                    <span class="is-cat-links">
                    <?php foreach ( $terms as $key => $term ) { if ( $key ) { echo ', '; } ?><a href="<?php echo get_term_link( $term->term_id, $tax_name ); ?> " rel="tag"><?php echo $term->name; ?></a><?php } ?>
                    </span>
                </span>
                <?php }
            }
        }

	/**
	 * Description Markup
	 *
	 * @since 4.3
	 *
	 * @param  array $field      Current stored values.
	 * @param  mixed $single     Single product or not.
	 * @return void
	 */
	function description_markup( $field, $post, $single = false ) {

		// Description either content or excerpt.
		if ( isset( $field['show_description'] ) && $field['show_description'] ) {

    		$excerpt_length = ( isset( $field['description_length'] ) && $field['description_length'] ) ? absint( $field['description_length'] ) : 20;

                $content = strip_tags( strip_shortcodes( $post->post_content ) );

    		if ( $single ) {
                    $excerpt_length = 100;
                } else if ( isset( $field['description_source'] ) && 'excerpt' === $field['description_source'] ) {
                    $content = get_the_excerpt( $post->ID );
    		}

                // Removes all shortcodes
                $patterns = "/\[[\/]?[\s\S][^\]]*\]/";
                $replacements = "";
                $content = preg_replace( $patterns, $replacements, $content, -1 );
    		$content = wp_trim_words( $content, $excerpt_length, '...' );
    		?>
    		<div class="is-ajax-result-description">
    			<?php echo $content; ?>
    		</div>
    		<?php
		}
	}

	/**
	 * Product Stock Status Markup
	 *
	 * @since 4.3
	 *
	 * @param  array $field      Current stored values.
	 * @param  mixed $product    Product or Empty.
	 * @return void
	 */
	function product_stock_status_markup( $field, $product ) {
		
		if( $product ) {
			// Show stock status.
			if( isset( $field['show_stock_status'] ) && $field['show_stock_status'] ) {
				$stock_status = ( $product->is_in_stock() ) ? 'in-stock' : 'out-of-stock';
				$stock_status_text = ( 'in-stock' == $stock_status ) ? __( 'In stock', 'add-search-to-menu' ) : __( 'Out of stock', 'add-search-to-menu' );
				echo '<span class="stock-status is-'.$stock_status.'">'.$stock_status_text.'</span>';
			}
		}
	}

	/**
	 * Product SKU Markup
	 *
	 * @since 4.3
	 *
	 * @param  array $field      Current stored values.
	 * @param  mixed $product    Product or Empty.
	 * @return void
	 */
	function product_sku_markup( $field, $product ) {
		if ( $product ) {
			// Show SKU.
			if( isset( $field['show_sku'] ) && $field['show_sku'] ) {
				$sku = $product->get_sku();
				echo '<span class="sku"><i>SKU:</i> '.esc_html( $sku ).'</span>';
			}
		}
	}

	/**
	 * Product Price Markup
	 *
	 * @since 4.3
	 *
	 * @param  array $field      Current stored values.
	 * @param  mixed $product    Product or Empty.
	 * @return void
	 */
	function product_price_markup( $field, $product ) {

		$hide_price_out_of_stock = isset( $field['hide_price_out_of_stock'] ) && $field['hide_price_out_of_stock'] ? $field['hide_price_out_of_stock'] : false;

		if ( $product ) {
			if ( isset( $field['show_price'] ) && $field['show_price'] ) { 
					if ( $product->is_in_stock() || false === $hide_price_out_of_stock ) {?>
                                        <span class="is-prices">
					<?php
						echo $product->get_price_html();
                                                ?>
                                        </span>
                                        <?php
					} 
			}
		}
	}

	/**
	 * Product Sale Badge Markup
	 *
	 * @since 4.3
	 *
	 * @param  array $field      Current stored values.
	 * @param  mixed $product    Product or Empty.
	 * @return void
	 */
	function product_sale_badge_markup( $field, $product ) {
		if ( $product ) {
			// Show sale badge.
			if ( isset( $field['show_sale_badge'] ) && $field['show_sale_badge'] ) {
				$on_sale = ( $product->is_in_stock() ) ? $product->is_on_sale() : '';
				if( $on_sale ) {
					echo '<div class="is-sale-badge">'.__( 'Sale!', 'add-search-to-menu' ) .'</div>';
				}
			}
		}
	}
}
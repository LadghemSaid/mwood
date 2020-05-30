<div class="httemplates-templates-area">
    <div class="httemplate-row">

        <!-- PopUp Content Start -->
        <div id="httemplate-popup-area" style="display: none;">
            <div class="httemplate-popupcontent">
                <div class='htspinner'></div>
                <div class="htmessage" style="display: none;">
                    <p></p>
                    <span class="httemplate-edit"></span>
                </div>
                <div class="htpopupcontent">
                    <p><?php esc_html_e( 'Import template to your Library', 'woolentor' );?></p>
                    <span class="htimport-button-dynamic"></span>
                    <div class="htpageimportarea">
                        <p> <?php esc_html_e( 'Create a new page from this template', 'woolentor' ); ?></p>
                        <input id="htpagetitle" type="text" name="htpagetitle" placeholder="<?php echo esc_attr_x( 'Enter a Page Name', 'placeholder', 'woolentor' ); ?>">
                        <span class="htimport-button-dynamic-page"></span>
                    </div>
                </div>
            </div>
        </div>
        <!-- PopUp Content End -->

        <!-- Top banner area Start -->
        <div class="httemplate-top-banner-area">
            <div class="htbanner-content">
                <div class="htbanner-desc">
                    <h3><?php esc_html_e( 'WooLentor Templates Library', 'woolentor' ); ?></h3>
                    <?php
                        $alltemplates = sizeof( Woolentor_Template_Library::instance()->get_templates_info( true )['templates'] ) ? sizeof( Woolentor_Template_Library::instance()->get_templates_info( true )['templates'] ) : 0;
                    ?>
                    <?php if( !is_plugin_active('woolentor-addons-pro/woolentor_addons_pro.php') ){ ?>
                        <p><?php esc_html_e( '7 Templates are Free and 27 Templates are Premium', 'woolentor' ); ?></p>
                    <?php } else{ ?>
                        <p><?php esc_html_e( $alltemplates, 'woolentor' ); esc_html_e( ' Templates', 'woolentor' ); ?></p>
                    <?php } ?>
                </div>
                <?php if( !is_plugin_active('woolentor-addons-pro/woolentor_addons_pro.php') ){ ?>
                    <a href="http://bit.ly/2HObEeB" target="_blank"><?php esc_html_e( 'Buy WooLentor Pro Version', 'woolentor' );?></a>
                <?php } ?>
            </div>
        </div>
        <!-- Top banner area end -->

        <?php if( Woolentor_Template_Library::instance()->get_templates_info( true )['templates'] ): ?>
            
            <div class="htmega-topbar">
                <span id="htmegaclose">&larr; <?php esc_html_e( 'Back to Library', 'woolentor' ); ?></span>
                <h3 id="htmega-tmp-name"></h3>
            </div>

            <ul id="tp-grid" class="tp-grid">

                <?php foreach ( Woolentor_Template_Library::instance()->get_templates_info( true )['templates'] as $httemplate ): 
                    
                    $allcat = explode( ' ', $httemplate['category'] );

                    $htimp_btn_atr = [
                        'templpateid' => $httemplate['id'],
                        'templpattitle' => $httemplate['title'],
                        'message' => esc_html__( 'Successfully '.$httemplate['title'].' has been imported.', 'woolentor' ),
                        'htbtnlibrary' => esc_html__( 'Import to Library', 'woolentor' ),
                        'htbtnpage' => esc_html__( 'Import to Page', 'woolentor' ),
                        'fullimage' => esc_url( $httemplate['thumbnail'] ),
                    ];

                ?>

                    <li data-pile="<?php echo esc_attr( implode(' ', $allcat ) ); ?>">

                        <!-- Preview PopUp Start -->
                        <div id="httemplate-popup-prev-<?php echo $httemplate['id']; ?>" style="display: none;">
                            <img src="<?php echo esc_url( $httemplate['thumbnail'] ); ?>" alt="<?php $httemplate['title']; ?>" style="width:100%;"/>
                        </div>
                        <!-- Preview PopUp End -->

                        <div class="htsingle-templates-laibrary">
                            <div class="httemplate-thumbnails">
                                <img data-preview='<?php echo wp_json_encode( $htimp_btn_atr );?>' src="<?php echo esc_url( $httemplate['thumbnail'] ); ?>" src="<?php echo esc_url( $httemplate['thumbnail'] ); ?>" alt="<?php echo esc_attr( $httemplate['title'] ); ?>">
                                <div class="httemplate-action">
                                    <?php if( $httemplate['is_pro'] == 1 ):?>
                                        <a href="http://bit.ly/2HObEeB" target="_blank">
                                            <?php esc_html_e( 'Buy Now', 'woolentor' ); ?>
                                        </a>
                                    <?php else:?>
                                        <a href="#" class="wltemplateimp" data-templpateopt='<?php echo wp_json_encode( $htimp_btn_atr );?>' >
                                            <?php esc_html_e( 'Import', 'woolentor' ); ?>
                                        </a>
                                    <?php endif; ?>
                                    <a href="<?php echo esc_url( $httemplate['demourl'] ); ?>" target="_blank"><?php esc_html_e( 'Preview', 'woolentor' ); ?></a>
                                </div>
                            </div>
                            <div class="httemplate-content">
                                <h3><?php echo esc_html__( $httemplate['title'], 'woolentor' ); if( $httemplate['is_pro'] == 1 ){ echo ' <span>( '.esc_html__('Pro','woolentor').' )</span>'; } ?></h3>
                                <div class="httemplate-tags">
                                    <?php echo implode( ' / ', explode( ',', $httemplate['tags'] ) ); ?>
                                </div>
                            </div>
                        </div>
                    </li>

                <?php endforeach; ?>

            </ul>

            <script type="text/javascript">
                jQuery(document).ready(function($) {

                    $(function() {
                        var $grid = $( '#tp-grid' ),
                            $name = $( '#htmega-tmp-name' ),
                            $close = $( '#htmegaclose' ),
                            $loaderimg = '<?php echo WOOLENTOR_ADDONS_PL_URL . 'includes/admin/assets/images/ajax-loader.gif'; ?>',
                            $loader = $( '<div class="htmega-loader"><span><img src="'+$loaderimg+'" alt="" /></span></div>' ).insertBefore( $grid ),
                            stapel = $grid.stapel( {
                                onLoad : function() {
                                    $loader.remove();
                                },
                                onBeforeOpen : function( pileName ) {
                                    $( '.htmega-topbar,.httemplate-action' ).css('display','flex');
                                    $( '.httemplate-content span' ).css('display','inline-block');
                                    $close.show();
                                    $name.html( pileName );
                                },
                                onAfterOpen : function( pileName ) {
                                    $close.show();
                                }
                            } );
                        $close.on( 'click', function() {
                            $close.hide();
                            $name.empty();
                            $( '.htmega-topbar,.httemplate-action,.httemplate-content span' ).css('display','none');
                            stapel.closePile();
                        } );
                    } );

                });
            </script>
        <?php endif; ?>

    </div>
</div>
<?php
class Oneline_Lite_Popup {

  function  __construct(){
             if (shortcode_exists('themehunk-customizer-oneline-lite')!=true):
                $this->active();
            endif;
    }
  function active(){
    if(!get_option( "thunk_customizer_disable_popup")):
    add_action('customize_controls_print_styles', array($this,'popup_styles'));
    add_action( 'customize_controls_enqueue_scripts', array($this,'popup_scripts'));
    endif;
  }

function active_plugin(){
        $plugin_slug = 'themehunk-customizer';
        $active_file_name =  $plugin_slug.'/'.$plugin_slug.'.php';
        $button_class = 'install-now button button-primary button-large';

                $button_txt = esc_html__( 'Install Plugin & Setup Homepage', 'oneline-lite' );
                $status     = is_dir( WP_PLUGIN_DIR . '/'.$plugin_slug );

                if ( ! $status ) {
                    $install_url = wp_nonce_url(
                        add_query_arg(
                            array(
                                'action' => 'install-plugin',
                                'plugin' => $plugin_slug
                            ),
                            network_admin_url( 'update.php' )
                        ),
                        'install-plugin_'.$plugin_slug
                    );

                } else {
                    $install_url = add_query_arg(array(
                        'action' => 'activate',
                        'plugin' => rawurlencode( $active_file_name ),
                        'plugin_status' => 'all',
                        'paged' => '1',
                        '_wpnonce' => wp_create_nonce('activate-plugin_' . $active_file_name ),
                    ), network_admin_url('plugins.php'));
                    $button_class = 'activate-now button-primary button-large';
                    $button_txt = esc_html__( 'Setup Homepage', 'oneline-lite' );
                }

        $url = esc_url($install_url);
    return "<a href='javascript:void' onclick=\"oneline_lite_install('{$url}'); return false;\"  data-slug='".esc_attr($plugin_slug)."' class='".esc_attr( $button_class )."'>{$button_txt}</a>";

}

function popup_styles() {
    wp_enqueue_style('oneline_lite_customizer_popup', get_template_directory_uri() . '/inc/theme-setup/customizer-popup-styles.css');
}

function popup_scripts() {
    wp_enqueue_script( 'oneline_lite_customizer_popup', get_template_directory_uri() . '/inc/theme-setup/customizer-popup.js', array("jquery"), '', true  );
}
}


// home page setup 

function active_plugin(){
       $plugin_slug = 'themehunk-customizer';
            $active_file_name =  $plugin_slug.'/'.$plugin_slug.'.php';
            $button_class = 'install-now button button-primary button-large';
      $install_url = add_query_arg(array(
                            'action' => 'activate',
                            'plugin' => rawurlencode( $active_file_name ),
                            'plugin_status' => 'all',
                            'paged' => '1',
                            '_wpnonce' => wp_create_nonce('activate-plugin_' . $active_file_name ),
                        ), network_admin_url('plugins.php'));
                        $button_class = 'activate-now button-primary button-large';
                        $button_txt = esc_html__( 'Setup Homepage', 'oneline-lite' );
    if ( is_plugin_active( $active_file_name ) ) {
      echo false;
    }else{
      echo $install_url;

} 
        
}

add_action( 'wp_ajax_oneline_lite_default_home', 'oneline_lite_default_home' );
function oneline_lite_default_home() {

 $pages = get_pages(array(
        'meta_key' => '_wp_page_template',
        'meta_value' => 'home-template.php'
    ));
    $post_id = isset($pages[0]->ID)?$pages[0]->ID:false;



if(empty($pages)){
      $post_id = wp_insert_post(array (
       'post_type'    => 'page',
       'post_title'   => 'Home',
       'post_content' => '',
       'post_name'    => 'oneline-home',
       'post_status'  => 'publish',
       'comment_status' => 'closed',   // if you prefer
       'ping_status'   => 'closed',      // if you prefer
       'page_template' =>'home-template.php', //Sets the template for the page.
    ));
  }
      if($post_id){
        update_option( 'page_on_front', $post_id );
        update_option( 'show_on_front', 'page' );
    }
 active_plugin();

    wp_die(); // this is required to terminate immediately and return a proper response
}





function customizer_disable_popup(){
      $value = intval(@$_POST['value']);
      update_option( "thunk_customizer_disable_popup", $value );
      die();
  }
add_action('wp_ajax_customizer_disable_popup', 'customizer_disable_popup');

/*
 *  online about us feature
 *
 */
function oneline_lite_tab_config($theme_data){
    $config = array(
        'theme_brand' => __('ThemeHunk','oneline-lite'),
        'theme_brand_url' => esc_url($theme_data->get( 'AuthorURI' )),
        'welcome'=>sprintf(esc_html__('Welcome to OneLine Lite - Version %1s', 'oneline-lite'), $theme_data->get( 'Version' ) ),
        'welcome_desc' => esc_html__( ' Oneline is a versatile one page theme for creating beautiful websites. This theme comes with powerful features which will help you in designing a wonderful website for any type of niche (Business, Landing page, E-commerce, Local business, Personal website).', 'oneline-lite' ),
        'tab_one' =>esc_html__('Get Started with OneLine Lite', 'oneline-lite' ),
        'tab_two' =>esc_html__( 'Recommended Actions', 'oneline-lite' ),
        'tab_three' =>esc_html__( 'Free VS Pro', 'oneline-lite' ),

        'plugin_title' => esc_html__( 'Step 1 - Do recommended actions', 'oneline-lite' ),
        'plugin_link' => '?page=th_oneline_lite&tab=actions_required',
        'plugin_text' => sprintf(esc_html__('Firstly install recommended plugin Themehunk Customizer. It will activate homepage sections ( Like : Header, Ribbon, Service, About Us, Team, WooCommerce, Testimonial and Contact Us ).', 'oneline-lite'), $theme_data->get( 'Name' )),
        'plugin_button' => esc_html__('Go To Recommended Action', 'oneline-lite'),


        'docs_title' => esc_html__( 'Step 2 - Configure Homepage Layout', 'oneline-lite' ),
        'video_link' => esc_url('//www.youtube.com/watch?v=pHCoxwYCZGQ'),
        'docs_button' => esc_html__('Configuration Instructions (with video)', 'oneline-lite'),
		
		'customizer_title' => esc_html__( 'Step 3 - Customize Your Website', 'oneline-lite' ),
        'customizer_text' =>  sprintf(esc_html__('%s theme support live customizer for home page set up. Everything visible at home page can be changed through customize panel', 'oneline-lite'), $theme_data->Name),
        'customizer_button' => sprintf( esc_html__('Start Customize', 'oneline-lite')),

        'support_title' => esc_html__( 'Step 4 - Theme Support', 'oneline-lite' ),
        'support_link' => esc_url('//www.themehunk.com/support/'),
        'support_forum' => sprintf(esc_html__('Support Forum', 'oneline-lite'), $theme_data->get( 'Name' )),
        'doc_link' => esc_url('//www.themehunk.com/docs/oneline-lite-theme/'),
        'doc_link_text' => sprintf(esc_html__('Theme Documentation', 'oneline-lite'), $theme_data->get( 'Name' )),

        'support_text' => sprintf(esc_html__('If you need any help you can contact to our support team, our team is always ready to help you.', 'oneline-lite'), $theme_data->get( 'Name' )),
        'support_button' => sprintf( esc_html__('Create a support ticket', 'oneline-lite'), $theme_data->get( 'Name' )),

        'demo_title' => esc_html__( 'Step 5 - Import Demo Content', 'oneline-lite' ),
        'demo_link' => esc_url('//www.themehunk.com/demo/'),
        'demo_text' => sprintf(esc_html__('You can import demo from here.', 'oneline-lite'), $theme_data->get( 'Name' )),
        'demo_button' => sprintf( esc_html__('Import the Demo', 'oneline-lite'), $theme_data->get( 'Name' )),
        );
    return $config;
}


if ( ! function_exists( 'oneline_lite_admin_scripts' ) ) :
    /**
     * Enqueue scripts for admin page only: Theme info page
     */
    function oneline_lite_admin_scripts( $hook ) {
        if ($hook === 'appearance_page_th_oneline_lite'  ) {
            wp_enqueue_style( 'oneline-lite-admin-css', get_template_directory_uri() . '/css/admin.css' );
            // Add recommend plugin css
            wp_enqueue_style( 'plugin-install' );
            wp_enqueue_script( 'plugin-install' );
            wp_enqueue_script( 'updates' );
            add_thickbox();
        }
    }
endif;
add_action( 'admin_enqueue_scripts', 'oneline_lite_admin_scripts' );

function online_lite_count_active_plugins() {
   $i = 3;
       if ( shortcode_exists( 'themehunk-customizer-oneline-lite' ) ):
           $i--;
       endif;
        if(class_exists( 'woocommerce' )) :
           $i--;
       endif;
       if ( shortcode_exists( 'lead-form' ) ):
           $i--;
       endif;

       return $i;
}

function oneline_lite_tab_count(){
   $count = '';
       $number_count = online_lite_count_active_plugins();
           if( $number_count >0):
           $count = "<span class='update-plugins count-".esc_attr( $number_count )."' title='".esc_attr( $number_count )."'><span class='update-count'>" . number_format_i18n($number_count) . "</span></span>";
           endif;
           return $count;
}


 /**
    * Menu tab
    */
function oneline_lite_tab() {
               $number_count = online_lite_count_active_plugins();
               $menu_title = esc_html__('Get Started with OneLine Lite', 'oneline-lite');
           if( $number_count >0):
           $count = "<span class='update-plugins count-".esc_attr( $number_count )."' title='".esc_attr( $number_count )."'><span class='update-count'>" . number_format_i18n($number_count) . "</span></span>";
               $menu_title = sprintf( esc_html__('Get Started with OneLine Lite %s', 'oneline-lite'), $count );
           endif;


   add_theme_page( esc_html__( 'OneLine Lite', 'oneline-lite' ), $menu_title, 'edit_theme_options', 'th_oneline_lite', 'oneline_lite_tab_page');
}
add_action('admin_menu', 'oneline_lite_tab');


function oneline_lite_pro_theme(){ ?>
<div class="freeevspro-img">
<img src="<?php echo get_template_directory_uri(); ?>/images/freevspro.png" alt="free vs pro" />
<p>
 <a href="https://www.themehunk.com/product/oneline-single-page-wordpress-theme/" target="_blank" class="button button-primary">Check Pro version for more features</a>
                           </p></div>
<?php }


function oneline_lite_tab_page() {
    $theme_data = wp_get_theme();
    $theme_config = oneline_lite_tab_config($theme_data);


    // Check for current viewing tab
    $tab = null;
    if ( isset( $_GET['tab'] ) ) {
        $tab = $_GET['tab'];
    } else {
        $tab = null;
    }

    $actions_r = oneline_lite_get_actions_required();
    $actions = $actions_r['actions'];

    $current_action_link =  admin_url( 'themes.php?page=th_oneline_lite&tab=actions_required' );

    $recommend_plugins = get_theme_support( 'recommend-plugins' );
    if ( is_array( $recommend_plugins ) && isset( $recommend_plugins[0] ) ){
        $recommend_plugins = $recommend_plugins[0];
    } else {
        $recommend_plugins[] = array();
    }
    ?>
    <div class="wrap about-wrap theme_info_wrapper">
        <h1><?php  echo $theme_config['welcome']; ?></h1>
        <div class="about-text"><?php echo $theme_config['welcome_desc']; ?></div>

        <a target="_blank" href="<?php echo $theme_config['theme_brand_url']; ?>/?wp=oneline-lite" class="themehunkhemes-badge wp-badge"><span><?php echo $theme_config['theme_brand']; ?></span></a>
        <h2 class="nav-tab-wrapper">
            <a href="?page=th_oneline_lite" class="nav-tab<?php echo is_null($tab) ? ' nav-tab-active' : null; ?>"><?php  echo $theme_config['tab_one']; ?></a>
            <a href="?page=th_oneline_lite&tab=actions_required" class="nav-tab<?php echo $tab == 'actions_required' ? ' nav-tab-active' : null; ?>"><?php echo $theme_config['tab_two'];  echo oneline_lite_tab_count();?></a>
            <a href="?page=th_oneline_lite&tab=theme-pro" class="nav-tab<?php echo $tab == 'theme-pro' ? ' nav-tab-active' : null; ?>"><?php echo $theme_config['tab_three']; ?></span></a>
        </h2>

        <?php if ( is_null( $tab ) ) { ?>
            <div class="theme_info info-tab-content">
                <div class="theme_info_column clearfix">
                    <div class="theme_info_left">
                    <div class="theme_link">
                            <h3><?php echo $theme_config['plugin_title']; ?></h3>
                            <p class="about"><?php echo $theme_config['plugin_text']; ?></p>
                            <p>
                                <a href="<?php echo esc_url($theme_config['plugin_link'] ); ?>" class="button button-secondary"><?php echo $theme_config['plugin_button']; ?></a>
                            </p>
                        </div>
                        <div class="theme_link">
                            <h3><?php echo $theme_config['docs_title']; ?></h3>
                            <p class="about"><ol>
                                <li><p><?php esc_html_e(' 
Go to your dashboard. Create a new page and select “Home Page Layout” from page attribute.','oneline-lite')?> </p></li>
                                <li><p><?php esc_html_e('After this go to Dashboard > Settings > Reading and set newly created page as a static page under "Front page displays" and save your changes.','oneline-lite')?></p></li>
                                </ol></p>
                            <p>
                                <a href="<?php echo esc_url($theme_config['video_link'] ); ?>" target="_blank" class="button button-secondary"><?php echo $theme_config['docs_button']; ?></a>
                            </p>
                        </div>
						  <div class="theme_link">
                            <h3><?php echo $theme_config['customizer_title']; ?></h3>
                            <p class="about"><?php  echo $theme_config['customizer_text']; ?></p>
                            <p>
                                <a href="<?php echo admin_url('customize.php'); ?>" class="button button-primary"><?php echo $theme_config['customizer_button']; ?></a>
                            </p>
                        </div>
                        <div class="theme_link">
                            <h3><?php echo $theme_config['support_title']; ?></h3>

                            <p class="about"><?php  echo $theme_config['support_text']; ?></p>
                            <p>
            <a target="_blank" href="<?php echo $theme_config['support_link']; ?>"><?php echo $theme_config['support_forum']; ?></a>
            </p>
            <p><a target="_blank" href="<?php echo $theme_config['doc_link']; ?>"><?php  echo $theme_config['doc_link_text']; ?></a></p>
                            <p>
                                <a href="<?php echo $theme_config['support_link']; ?>" target="_blank" class="button button-secondary"><?php echo $theme_config['support_button']; ?></a>
                            </p>

                        </div>
                        <div class="theme_link theme-demo">
                        <h3><?php echo esc_html($theme_config['demo_title']); ?></h3>
                        <p class="about"><ol>
                                <li><p><?php esc_html_e(' 
                                Install recommended plugins','oneline-lite')?> </p></li>
                                <li><p><?php esc_html_e('Click this button and import desired demo.','oneline-lite')?></p></li>
                                </ol></p>
                        <p>
                               <?php
            // Sita Sites - Installed but Inactive.
            // Sita Premium Sites - Inactive.
            if ( file_exists( WP_PLUGIN_DIR . '/one-click-demo-import/one-click-demo-import.php' ) && is_plugin_inactive( 'one-click-demo-import/one-click-demo-import.php' )){

              $class       = 'button zta-sites-inactive';
              $button_text = __( 'Activate Importer Plugin', 'oneline-lite' );
              $data_slug   = 'one-click-demo-import';
              $data_init   = '/one-click-demo-import/one-click-demo-import.php';

              // Sita Sites - Not Installed.
              // Sita Premium Sites - Inactive.
            } elseif ( ! file_exists( WP_PLUGIN_DIR . '/one-click-demo-import/one-click-demo-import.php' ) ) {

              $class       = 'button zta-sites-notinstalled';
              $button_text = __( 'Install Importer Plugin', 'oneline-lite' );
              $data_slug   = 'one-click-demo-import';
              $data_init   = '/one-click-demo-import/one-click-demo-import.php';

            }
            else {
              $class       = 'active';
              $button_text = __( 'See Library', 'oneline-lite' );
              $link        = admin_url( 'themes.php?page=pt-one-click-demo-import' );
            }

            printf(
              '<a class="ztabtn %1$s" %2$s %3$s %4$s> %5$s </a>',
              esc_attr( $class ),
              isset( $link ) ? 'href="' . esc_url( $link ) . '"' : '',
              isset( $data_slug ) ? 'data-slug="' . esc_attr( $data_slug ) . '"' : '',
              isset( $data_init ) ? 'data-init="' . esc_attr( $data_init ) . '"' : '',
              esc_html( $button_text )
            );
            ?>
                            </p>
                        </div>

                    </div>
                </div>
            </div>
        <?php } ?>

        <?php if ( $tab == 'actions_required' ) { ?>
            <div class="action-required-tab info-tab-content">

                <?php if ( is_child_theme() ){
                    $child_theme = wp_get_theme();
                    ?>
                    <form method="post" action="<?php echo esc_attr( $current_action_link ); ?>" class="demo-import-boxed copy-settings-form">
                        <p>
                           <strong> <?php printf( esc_html__(  'You\'re using %1$s theme, It\'s a child theme', 'oneline-lite' ) ,  $child_theme->Name ); ?></strong>
                        </p>
                        <p><?php printf( esc_html__(  'Child theme uses it’s own theme setting name, would you like to copy setting data from parent theme to this child theme?', 'oneline-lite' ) ); ?></p>
                        <p>

                        <?php

                        $select = '<select name="copy_from">';
                        $select .= '<option value="">'.esc_html__( 'From Theme', 'oneline-lite' ).'</option>';
                        $select .= '<option value="onelinelite">OnelineLite</option>';
                        $select .= '<option value="'.esc_attr( $child_theme->get_stylesheet() ).'">'.( $child_theme->Name ).'</option>';
                        $select .='</select>';

                        $select_2 = '<select name="copy_to">';
                        $select_2 .= '<option value="">'.esc_html__( 'To Theme', 'oneline-lite' ).'</option>';
                        $select_2 .= '<option value="onelinelite">OnelineLite</option>';
                        $select_2 .= '<option value="'.esc_attr( $child_theme->get_stylesheet() ).'">'.( $child_theme->Name ).'</option>';
                        $select_2 .='</select>';

                        echo $select . ' to '. $select_2;

                        ?>
                        <input type="submit" class="button button-secondary" value="<?php esc_attr_e( 'Copy now', 'oneline-lite' ); ?>">
                        </p>
                        <?php if ( isset( $_GET['copied'] ) && $_GET['copied'] == 1 ) { ?>
                            <p><?php esc_html_e( 'Your settings copied.', 'oneline-lite' ); ?></p>
                        <?php } ?>
                    </form>

                <?php } ?>
      
                    <?php if ( isset($actions['recommend_plugins']) && $actions['recommend_plugins'] == 'active' ) {  ?>
                        <div id="plugin-filter" class="recommend-plugins action-required">
                        <h3><?php esc_html_e( 'Recommend Plugins', 'oneline-lite' ); ?></h3>
                            <?php oneline_lite_plugin_api(); ?>
                        </div>
                    <?php } ?>                            
            </div>
        <?php } ?>

        <?php if ( $tab == 'theme-pro' ) { ?>

            <?php oneline_lite_pro_theme(); ?>

        <?php } ?>

    </div> <!-- END .theme_info -->
    <?php

}

 function oneline_lite_plugin_api() {
        include_once( ABSPATH . 'wp-admin/includes/plugin-install.php' );
                        network_admin_url( 'plugin-install.php' );


        $recommend_plugins = get_theme_support( 'recommend-plugins' );
    if ( is_array( $recommend_plugins ) && isset( $recommend_plugins[0] ) ){

        foreach($recommend_plugins[0] as $slug=>$plugin){
            
            $plugin_info = plugins_api( 'plugin_information', array(
                    'slug' => $slug,
                    'fields' => array(
                        'downloaded'        => false,
                        'sections'          => true,
                        'homepage'          => true,
                        'added'             => false,
                        'compatibility'     => false,
                        'requires'          => false,
                        'downloadlink'      => false,
                        'icons'             => true,
                    )
                ) );
                //foreach($plugin_info as $plugin_info){
                    $plugin_name = $plugin_info->name;
                    $plugin_slug = $plugin_info->slug;
                    $version = $plugin_info->version;
                    $author = $plugin_info->author;
                    $download_link = $plugin_info->download_link;
                    $icons = (isset($plugin_info->icons['1x']))?$plugin_info->icons['1x']:$plugin_info->icons['default'];
            

            $status = is_dir( WP_PLUGIN_DIR . '/' . $plugin_slug );
            $active_file_name = $plugin_slug . '/' . $plugin_slug . '.php';
            $button_class = 'install-now button';

            if ( ! is_plugin_active( $active_file_name ) ) {
                $button_txt = esc_html__( 'Install Now', 'oneline-lite' );
                if ( ! $status ) {
                    $install_url = wp_nonce_url(
                        add_query_arg(
                            array(
                                'action' => 'install-plugin',
                                'plugin' => $plugin_slug
                            ),
                            network_admin_url( 'update.php' )
                        ),
                        'install-plugin_'.$plugin_slug
                    );

                } else {
                    $install_url = add_query_arg(array(
                        'action' => 'activate',
                        'plugin' => rawurlencode( $active_file_name ),
                        'plugin_status' => 'all',
                        'paged' => '1',
                        '_wpnonce' => wp_create_nonce('activate-plugin_' . $active_file_name ),
                    ), network_admin_url('plugins.php'));
                    $button_class = 'activate-now button-primary';
                    $button_txt = esc_html__( 'Active Now', 'oneline-lite' );
                }


                    $detail_link = add_query_arg(
                    array(
                        'tab' => 'plugin-information',
                        'plugin' => $plugin_slug,
                        'TB_iframe' => 'true',
                        'width' => '772',
                        'height' => '349',

                    ),
                    network_admin_url( 'plugin-install.php' )
                );
				$detail = '';
                echo '<div class="rcp">';
                echo '<h4 class="rcp-name">';
                echo esc_html( $plugin_name );
                echo '</h4>';
                echo '<img src="'.$icons.'" />';
				if($plugin_slug=='lead-form-builder'){
		$detail='Lead form builder is a contact form as well as lead generator plugin. This plugin will allow you create
unlimited contact forms and to generate unlimited leads on your site.';
} elseif($plugin_slug=='themehunk-customizer'){
		$detail= 'ThemeHunk customizer – 
ThemeHunk customizer plugin will allow you to add  unlimited number of columns for services, Testimonial, and Team with widget support. It will add slider section, Ribbon section, latest post, Contact us and Woocommerce section. These will be visible on front page of your site.';

} elseif($plugin_slug=='woocommerce'){
$detail='WooCommerce is a free eCommerce plugin that allows you to sell anything, beautifully. Built to integrate seamlessly with WordPress, WooCommerce is the eCommerce solution that gives both store owners and developers complete control.';
}
			echo '<p class="rcp-detail">'.$detail.' </p>';

                echo '<p class="action-btn plugin-card-'.esc_attr( $plugin_slug ).'">
                        <span>Version:'.$version.'</span>
                        '.$author.'
                <a href="'.esc_url( $install_url ).'" data-slug="'.esc_attr( $plugin_slug ).'" class="'.esc_attr( $button_class ).'">'.$button_txt.'</a>
                </p>';
                echo '<a class="plugin-detail thickbox open-plugin-details-modal" href="'.esc_url( $detail_link ).'">'.esc_html__( 'Details', 'oneline-lite' ).'</a>';
                echo '</div>';


            }

        }
    }
}


function oneline_lite_get_actions_required( ) {

    $actions = array();

    $recommend_plugins = get_theme_support( 'recommend-plugins' );
    if ( is_array( $recommend_plugins ) && isset( $recommend_plugins[0] ) ){
        $recommend_plugins = $recommend_plugins[0];
    } else {
        $recommend_plugins[] = array();
    }

    if ( ! empty( $recommend_plugins ) ) {

        foreach ( $recommend_plugins as $plugin_slug => $plugin_info ) {
            $plugin_info = wp_parse_args( $plugin_info, array(
                'name' => '',
                'active_filename' => '',
            ) );
            if ( $plugin_info['active_filename'] ) {
                $active_file_name = $plugin_info['active_filename'] ;
            } else {
                $active_file_name = $plugin_slug . '/' . $plugin_slug . '.php';
            }
            if ( ! is_plugin_active( $active_file_name ) ) {
                $actions['recommend_plugins'] = 'active';
            }
        }

    }

    $actions = apply_filters( 'oneline_lite_get_actions_required', $actions );

    $return = array(
        'actions' => $actions,
        'number_actions' => count( $actions ),
    );

    return $return;
}
// AJAX.
add_action( 'wp_ajax_onelinelite-sites-plugin-activate','required_plugin_activate' );
function required_plugin_activate() {

      if ( ! current_user_can( 'install_plugins' ) || ! isset( $_POST['init'] ) || ! $_POST['init'] ) {
        wp_send_json_error(
          array(
            'success' => false,
            'message' => __( 'No plugin specified', 'oneline-lite' ),
          )
        );
      }

      $plugin_init = ( isset( $_POST['init'] ) ) ? esc_attr( $_POST['init'] ) : '';

      $activate = activate_plugin( $plugin_init, '', false, true );

      if ( is_wp_error( $activate ) ) {
        wp_send_json_error(
          array(
            'success' => false,
            'message' => $activate->get_error_message(),
          )
        );
      }

      wp_send_json_success(
        array(
          'success' => true,
          'message' => __( 'Plugin Successfully Activated', 'oneline-lite' ),
        )
      );

    }
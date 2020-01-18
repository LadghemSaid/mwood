<?php
/**
 * tgm plugin installation and activation
 *
 * @param  
 * @return mixed|string
 */

require_once( get_template_directory() . '/class-tgm-plugin-activation.php' );
add_action( 'tgmpa_register', 'oneline_lite_register_required_plugins' );
function oneline_lite_register_required_plugins()
{
	$wp_version_nr = get_bloginfo('version');
	
		$plugins = array(
             array(
                'name' => __('Themehunk Customizer','oneline-lite'),
                'slug' => 'themehunk-customizer', 
                'required' => false,            ),
            array(
                'name' => __('Lead Form Builder','oneline-lite'),
                'slug' => 'lead-form-builder', 
                'required' => false 
            ),
             array(
                'name' => __('Woocommerce','oneline-lite'),
                'slug' => 'woocommerce', 
                'required' => false,  )
        );

    $config = array(
        'id'       => 'tgmpa-onelinelite',
        'default_path' => '',
        'menu' => 'tgmpa-install-plugins',
        'has_notices' => true,
        'dismissable' => true,
        'dismiss_msg' => '',
        'is_automatic' => false,
        'message' => '',
        'strings' => array(
            'page_title' => __('Install Plugins', 'oneline-lite'),
            'menu_title' => __('Install Plugins', 'oneline-lite'),
            'installing' => __('Installing Plugin: %s', 'oneline-lite'),
            'oops' => __('Something went wrong with the plugin API.', 'oneline-lite'),
            'notice_can_install_required' => _n_noop('This theme requires the following plugin: %1$s.', 'This theme requires the following plugins: %1$s.','oneline-lite'),
            'notice_can_install_recommended' => _n_noop('This theme recommends the following plugin: %1$s.', 'This theme recommends the following plugins: %1$s.','oneline-lite'),
            'notice_cannot_install' => _n_noop('Sorry, but you do not have the correct permissions to install the %s plugin. Contact the administrator of this site for help on getting the plugin installed.', 'Sorry, but you do not have the correct permissions to install the %s plugins. Contact the administrator of this site for help on getting the plugins installed.','oneline-lite'),
            'notice_can_activate_required' => _n_noop('The following required plugin is currently inactive: %1$s.', 'The following required plugins are currently inactive: %1$s.','oneline-lite'),
            'notice_can_activate_recommended' => _n_noop('The following recommended plugin is currently inactive: %1$s.', 'The following recommended plugins are currently inactive: %1$s.','oneline-lite'),
            'notice_cannot_activate' => _n_noop('Sorry, but you do not have the correct permissions to activate the %s plugin. Contact the administrator of this site for help on getting the plugin activated.', 'Sorry, but you do not have the correct permissions to activate the %s plugins. Contact the administrator of this site for help on getting the plugins activated.','oneline-lite'),
            'notice_ask_to_update' => _n_noop('The following plugin needs to be updated to its latest version to ensure maximum compatibility with this theme: %1$s.', 'The following plugins need to be updated to their latest version to ensure maximum compatibility with this theme: %1$s.','oneline-lite'),
            'notice_cannot_update' => _n_noop('Sorry, but you do not have the correct permissions to update the %s plugin. Contact the administrator of this site for help on getting the plugin updated.', 'Sorry, but you do not have the correct permissions to update the %s plugins. Contact the administrator of this site for help on getting the plugins updated.','oneline-lite'),
            'install_link' => _n_noop('Begin installing plugin', 'Begin installing plugins','oneline-lite'),
            'activate_link' => _n_noop('Begin activating plugin', 'Begin activating plugins','oneline-lite'),
            'return' => __('Return to Required Plugins Installer', 'oneline-lite'),
            'plugin_activated' => __('Plugin activated successfully.', 'oneline-lite'),
            'complete' => __('All plugins installed and activated successfully. %s', 'oneline-lite'),
            'nag_type' => 'updated'
        )
    );
    tgmpa($plugins, $config);
}

?>
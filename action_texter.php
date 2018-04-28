<?php

/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @link              Codewalker Institute
 * @since             1.0.0
 * @package           Action_texter
 *
 * @wordpress-plugin
 * Plugin Name:       Action Texter
 * Plugin URI:        Codewalker Institute
 * Description:       This is a short description of what the plugin does. It's displayed in the WordPress admin area.
 * Version:           1.0.0
 * Author:            CodeWalker Institute
 * Author URI:        Codewalker Institute
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       action_texter
 * Domain Path:       /languages
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * Currently plugin version.
 * Start at version 1.0.0 and use SemVer - https://semver.org
 * Rename this for your plugin and update it as you release new versions.
 */
define( 'PLUGIN_NAME_VERSION', '1.0.0' );

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-action_texter-activator.php
 */
 error_log('made it top');

function activate_action_texter() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-action_texter-activator.php';
	Action_texter_Activator::activate();

	if (!is_plugin_active('advanced-custom-fields/acf.php')) {
        // Deactivate the plugin
		deactivate_plugins(__FILE__);

		// Throw an error in the wordpress admin console
		$error_message = __('This plugin requires the <a href="https://www.advancedcustomfields.com/">Advanced Custom Fields</a> plugin to be active!', 'advanced_custom_fields');
		die($error_message);
    } else {
        add_role( 'action_texter', 'Action Texter', array( 'read' => true, 'level_0' => true ) );
        setupACF();
  }
	 // add_action("admin_menu", "texter");
	 // function texter() {
		//  error_log('inside texter');
	 //
		// 	 add_menu_page("Action Texts", "Action Texter", "manage_options", "texter_settings_page", "texter_form");
	 // };
	 // function texter_form() {
	 //
		// 	 if (!current_user_can( 'manage_options' )) {
		// 			 wp_die( "Sorry. You don't have access to use this plugin." );
		// 	 }
		// 	 error_log('made it');
		// 	 echo "test worked";
	 // };
}

function setupACF() {
	if(function_exists("register_field_group"))
	{
			register_field_group(array (
					'id' => 'acf_action-texter-fields',
					'title' => 'Action Texter Fields',
					'fields' => array (
							array (
									'key' => 'field_5a9dab6243415',
									'label' => 'Action Network API Key',
									'name' => 'action_network_api_key',
									'type' => 'text',
									'instructions' => 'This is the API key you should have received from Action Network. If you do not have one you can request one.',
									'required' => 1,
									'default_value' => '',
									'placeholder' => '',
									'prepend' => '',
									'append' => '',
									'formatting' => 'html',
									'maxlength' => '',
							),
							array (
									'key' => 'field_5a9dabe843416',
									'label' => 'Twilio Account SID',
									'name' => 'twilio_account_sid',
									'type' => 'text',
									'instructions' => 'This is your Twilio Account SID you should be able to retrieve this value from the Twilio Dashboard.',
									'required' => 1,
									'default_value' => '',
									'placeholder' => '',
									'prepend' => '',
									'append' => '',
									'formatting' => 'html',
									'maxlength' => '',
							),
							array (
									'key' => 'field_5a9dac3d43417',
									'label' => 'Twilio Auth Token',
									'name' => 'twilio_auth_token',
									'type' => 'text',
									'instructions' => 'This is your Twilio Auth Token, you should be able to retrieve this value from the Twilio Dashboard.',
									'required' => 1,
									'default_value' => '',
									'placeholder' => '',
									'prepend' => '',
									'append' => '',
									'formatting' => 'html',
									'maxlength' => '',
							),
							array (
									'key' => 'field_5a9dac6043418',
									'label' => 'Twilio From Number',
									'name' => 'twilio_from_number',
									'type' => 'text',
									'instructions' => 'This is the number you provisioned in Twilio',
									'default_value' => '',
									'placeholder' => '',
									'prepend' => '',
									'append' => '',
									'formatting' => 'html',
									'maxlength' => '',
							),
							array (
									'key' => 'field_5a9dac7e43419',
									'label' => 'Twilio Service ID',
									'name' => 'twilio_service_id',
									'type' => 'text',
									'instructions' => 'If you set up a service in Twilio you can set the service Id here instead of a From Number.',
									'default_value' => '',
									'placeholder' => '',
									'prepend' => '',
									'append' => '',
									'formatting' => 'html',
									'maxlength' => '',
							),
							array (
									'key' => 'field_5a9dce18fb22d',
									'label' => 'Action Texts API Key',
									'name' => 'action_texts_api_key',
									'type' => 'text',
									'instructions' => 'This is your API issued by us!',
									'required' => 1,
									'default_value' => '',
									'placeholder' => '',
									'prepend' => '',
									'append' => '',
									'formatting' => 'html',
									'maxlength' => '',
							),
					),
					'location' => array (
							array (
									array (
											'param' => 'ef_user',
											'operator' => '==',
											'value' => 'administrator',
											'order_no' => 0,
											'group_no' => 0,
									),
							),
							array (
									array (
											'param' => 'ef_user',
											'operator' => '==',
											'value' => 'action_texter',
											'order_no' => 0,
											'group_no' => 1,
									),
							),
					),
					'options' => array (
							'position' => 'normal',
							'layout' => 'no_box',
							'hide_on_screen' => array (
							),
					),
					'menu_order' => 0,
			));
	}
}
/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-action_texter-deactivator.php
 */
function deactivate_action_texter() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-action_texter-deactivator.php';
	Action_texter_Deactivator::deactivate();

	//check if role exist before removing it
  if( get_role('action_texter') ) {
      remove_role( 'action_texter' );
  }
}

register_activation_hook( __FILE__, 'activate_action_texter' );
register_deactivation_hook( __FILE__, 'deactivate_action_texter' );

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path( __FILE__ ) . 'includes/class-action_texter.php';

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function run_action_texter() {

	$plugin = new Action_texter();
	$plugin->run();

}
run_action_texter();

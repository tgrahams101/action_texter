<?php

/**
 * The file that defines the core plugin class
 *
 * A class definition that includes attributes and functions used across both the
 * public-facing side of the site and the admin area.
 *
 * @link       Codewalker Institute
 * @since      1.0.0
 *
 * @package    Action_texter
 * @subpackage Action_texter/includes
 */

/**
 * The core plugin class.
 *
 * This is used to define internationalization, admin-specific hooks, and
 * public-facing site hooks.
 *
 * Also maintains the unique identifier of this plugin as well as the current
 * version of the plugin.
 *
 * @since      1.0.0
 * @package    Action_texter
 * @subpackage Action_texter/includes
 * @author     CodeWalker Institute <zeke@codewalker.institute>
 */
class Action_texter {

	/**
	 * The loader that's responsible for maintaining and registering all hooks that power
	 * the plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      Action_texter_Loader    $loader    Maintains and registers all hooks for the plugin.
	 */
	protected $loader;

	/**
	 * The unique identifier of this plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      string    $plugin_name    The string used to uniquely identify this plugin.
	 */
	protected $plugin_name;

	/**
	 * The current version of the plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      string    $version    The current version of the plugin.
	 */
	protected $version;

	/**
	 * Define the core functionality of the plugin.
	 *
	 * Set the plugin name and the plugin version that can be used throughout the plugin.
	 * Load the dependencies, define the locale, and set the hooks for the admin area and
	 * the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function __construct() {
		if ( defined( 'PLUGIN_NAME_VERSION' ) ) {
			$this->version = PLUGIN_NAME_VERSION;
		} else {
			$this->version = '1.0.0';
		}
		$this->plugin_name = 'action_texter';

		$this->load_dependencies();
		$this->set_locale();
		$this->define_admin_hooks();
		$this->define_public_hooks();

	}

	/**
	 * Load the required dependencies for this plugin.
	 *
	 * Include the following files that make up the plugin:
	 *
	 * - Action_texter_Loader. Orchestrates the hooks of the plugin.
	 * - Action_texter_i18n. Defines internationalization functionality.
	 * - Action_texter_Admin. Defines all hooks for the admin area.
	 * - Action_texter_Public. Defines all hooks for the public side of the site.
	 *
	 * Create an instance of the loader which will be used to register the hooks
	 * with WordPress.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function load_dependencies() {

		/**
		 * The class responsible for orchestrating the actions and filters of the
		 * core plugin.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-action_texter-loader.php';

		/**
		 * The class responsible for defining internationalization functionality
		 * of the plugin.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-action_texter-i18n.php';

		/**
		 * The class responsible for defining all actions that occur in the admin area.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/class-action_texter-admin.php';

		/**
		 * The class responsible for defining all actions that occur in the public-facing
		 * side of the site.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'public/class-action_texter-public.php';


		/**
		 * The class that has the core php methods for Action Texter
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-action_texter-methods.php';
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-plugin-name-alert.php';

		$this->loader = new Action_texter_Loader();

	}

	/**
	 * Define the locale for this plugin for internationalization.
	 *
	 * Uses the Action_texter_i18n class in order to set the domain and to register the hook
	 * with WordPress.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function set_locale() {

		$plugin_i18n = new Action_texter_i18n();

		$this->loader->add_action( 'plugins_loaded', $plugin_i18n, 'load_plugin_textdomain' );

	}

	/**
	 * Register all of the hooks related to the admin area functionality
	 * of the plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function define_admin_hooks() {

		$plugin_admin = new Action_texter_Admin( $this->get_plugin_name(), $this->get_version() );

		$this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'enqueue_styles' );
		$this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'enqueue_scripts' );

	}

	/**
	 * Register all of the hooks related to the public-facing functionality
	 * of the plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function define_public_hooks() {

		$plugin_public = new Action_texter_Public( $this->get_plugin_name(), $this->get_version() );

		$this->loader->add_action( 'wp_enqueue_scripts', $plugin_public, 'enqueue_styles' );
		$this->loader->add_action( 'wp_enqueue_scripts', $plugin_public, 'enqueue_scripts' );

		$antexter_methods = new ANTexterMethods();

		$this->loader->add_action( 'wp_ajax_send_test_text', $antexter_methods, 'send_test_text' );
		$this->loader->add_action( 'wp_ajax_fetch_forms', $antexter_methods, 'fetch_forms' );
		$this->loader->add_action( 'wp_ajax_get_flows', $antexter_methods, 'get_flows' );
		$this->loader->add_action( 'wp_ajax_post_flow', $antexter_methods, 'post_flow' );
		$this->loader->add_action( 'wp_ajax_put_flow', $antexter_methods, 'put_flow' );
		$this->loader->add_action( 'wp_ajax_send_bulk_text', $antexter_methods, 'send_bulk_text' );
		$this->loader->add_action( 'wp_ajax_fetch_batches', $antexter_methods, 'fetch_batches' );
		$this->loader->add_action( 'wp_ajax_fetch_tags', $antexter_methods, 'fetch_tags' );
		$this->loader->add_action( 'wp_ajax_check_progress', $antexter_methods, 'check_progress' );

		$plugin_alert = new Plugin_Name_Alert();
		$this->loader->add_action( 'init', $plugin_alert, 'my_alert_function' );

		$this->loader->add_action("admin_menu",$this,  "texter");

	}

	function texter() {
		error_log('inside texter');
		add_menu_page("Action Texts", "Action Texter", "manage_options", "texter_settings_page", "texter_form");
	}


	/**
	 * Run the loader to execute all of the hooks with WordPress.
	 *
	 * @since    1.0.0
	 */
	public function run() {
		$this->loader->run();
	}

	/**
	 * The name of the plugin used to uniquely identify it within the context of
	 * WordPress and to define internationalization functionality.
	 *
	 * @since     1.0.0
	 * @return    string    The name of the plugin.
	 */
	public function get_plugin_name() {
		return $this->plugin_name;
	}

	/**
	 * The reference to the class that orchestrates the hooks with the plugin.
	 *
	 * @since     1.0.0
	 * @return    Action_texter_Loader    Orchestrates the hooks of the plugin.
	 */
	public function get_loader() {
		return $this->loader;
	}

	/**
	 * Retrieve the version number of the plugin.
	 *
	 * @since     1.0.0
	 * @return    string    The version number of the plugin.
	 */
	public function get_version() {
		return $this->version;
	}

}

function texter_form() {
	error_log('inside texter_form');

	if (!current_user_can( 'manage_options' )) {
		wp_die( "Sorry. You don't have access to use this plugin." );
	}
	error_log('made it');
	echo include(dirname( __FILE__ ) . './../public/partials/action_texter-public-display.php');
}

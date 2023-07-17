<?php

/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @link              https://https://codepixelzmedia.com/
 * @since             1.0.0
 * @package           Learndash_Addon
 *
 * @wordpress-plugin
 * Plugin Name:       LearnDash Addon
 * Plugin URI:        
 * Description:       Additional features for LearnDash Plugin.
 * Version:           1.0.0
 * Author:            Codepixelz Media
 * Author URI:        https://https://codepixelzmedia.com/
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       learndash-addon
 * Domain Path:       /languages
 */


// If this file is called directly, abort.
if (!defined('WPINC')) {
	die;
}


/**
 * Currently plugin version.
 * Start at version 1.0.0 and use SemVer - https://semver.org
 * Rename this for your plugin and update it as you release new versions.
 */
define('LEARNDASH_ADDON_VERSION', '1.0.0');

// Hook into the 'plugins_loaded' action
// add_action('plugins_loaded', 'check_learndash_plugin_active');

// function check_learndash_plugin_active()
// {
// 	// Check if the required plugin is active
// 	if (is_plugin_active('sfwd-lms/sfwd_lms.php')) {
// 		// The required plugin is active, so you can run your plugin here
// 		add_action('init', 'learndash_addon_functions');
// 	}
// }

// function learndash_addon_functions()
// {
	// Your plugin code goes here
	// This function will only be executed if the required plugin is active

	/**
	 * The code that runs during plugin activation.
	 * This action is documented in includes/class-learndash-addon-activator.php
	 */
	function activate_learndash_addon()
	{
		require_once plugin_dir_path(__FILE__) . 'includes/class-learndash-addon-activator.php';
		Learndash_Addon_Activator::activate();
	}

	/**
	 * The code that runs during plugin deactivation.
	 * This action is documented in includes/class-learndash-addon-deactivator.php
	 */
	function deactivate_learndash_addon()
	{
		require_once plugin_dir_path(__FILE__) . 'includes/class-learndash-addon-deactivator.php';
		Learndash_Addon_Deactivator::deactivate();
	}

	register_activation_hook(__FILE__, 'activate_learndash_addon');
	register_deactivation_hook(__FILE__, 'deactivate_learndash_addon');

	/**
	 * The core plugin class that is used to define internationalization,
	 * admin-specific hooks, and public-facing site hooks.
	 */
	require plugin_dir_path(__FILE__) . 'includes/class-learndash-addon.php';

	/**
	 * Begins execution of the plugin.
	 *
	 * Since everything within the plugin is registered via hooks,
	 * then kicking off the plugin from this point in the file does
	 * not affect the page life cycle.
	 *
	 * @since    1.0.0
	 */
	function run_learndash_addon()
	{

		$plugin = new Learndash_Addon();
		$plugin->run();
	}
	run_learndash_addon();
// }

<?php

/**
 * Define the internationalization functionality
 *
 * Loads and defines the internationalization files for this plugin
 * so that it is ready for translation.
 *
 * @link       https://https://codepixelzmedia.com/
 * @since      1.0.0
 *
 * @package    Learndash_Addon
 * @subpackage Learndash_Addon/includes
 */

/**
 * Define the internationalization functionality.
 *
 * Loads and defines the internationalization files for this plugin
 * so that it is ready for translation.
 *
 * @since      1.0.0
 * @package    Learndash_Addon
 * @subpackage Learndash_Addon/includes
 * @author     Codepixelz Media <wordpress.enthusiast@gmail.com>
 */
class Learndash_Addon_i18n {


	/**
	 * Load the plugin text domain for translation.
	 *
	 * @since    1.0.0
	 */
	public function load_plugin_textdomain() {

		load_plugin_textdomain(
			'learndash-addon',
			false,
			dirname( dirname( plugin_basename( __FILE__ ) ) ) . '/languages/'
		);

	}



}

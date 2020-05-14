<?php

/**
 * Define the internationalization functionality
 *
 * Loads and defines the internationalization files for this plugin
 * so that it is ready for translation.
 *
 * @link       https://github.com/f2re/ampopuplearn
 * @since      1.0.0
 *
 * @package    Ampopuplearn
 * @subpackage Ampopuplearn/includes
 */

/**
 * Define the internationalization functionality.
 *
 * Loads and defines the internationalization files for this plugin
 * so that it is ready for translation.
 *
 * @since      1.0.0
 * @package    Ampopuplearn
 * @subpackage Ampopuplearn/includes
 * @author     F2re <lendingad@gmail.com>
 */
namespace AmpopupLearn\Inc;

class I18n {


	/**
	 * Load the plugin text domain for translation.
	 *
	 * @since    1.0.0
	 */
	public function load_plugin_textdomain() {

		load_plugin_textdomain(
			'ampopuplearn',
			false,
			dirname( dirname( plugin_basename( __FILE__ ) ) ) . '/languages/'
		);

	}



}

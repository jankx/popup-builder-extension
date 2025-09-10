<?php

namespace App\PopupBuilder\Libs;

defined( 'ABSPATH' ) || exit;

use PopupKitScopedDependencies\Wpmet\UtilityPackage;
use App\PopupBuilder\Helpers\Utils;

class UtilityPackages {

	/**
	 * UtilityPackages class constructor.
	 *
	 * @return void
	 * @since 1.0.0
	 */
	public function __construct() {
		// To prevent the "doing_it_wrong" notice from being displayed
		// when the "_load_textdomain_just_in_time" function is called.
		add_filter(
			'doing_it_wrong_trigger_error',
			function ( $doing_it_wrong, $function_name ) {
				if ( '_load_textdomain_just_in_time' === $function_name ) {
					return false;
				}

				return $doing_it_wrong;
			},
			10,
			2
		);


		/**
		 * Checks if the 'user_consent' setting is enabled.
		 * If it is disabled, the function returns without performing any further actions.
		 *
		 * @return void
		 */
		if ( ! Utils::get_settings( 'user_consent' ) ) {
			return;
		}

		/**
		 * Show WPMET stories widget in the dashboard
		 */
		$filter_string = '';

		/**
		 * Initializes the Notice utility package.
		 *
		 * This function initializes the Notice utility package, allowing you to display notices in your WordPress plugin or theme.
		 * It is recommended to call this function during the initialization phase of your plugin or theme.
		 *
		 * @since 1.0.0
		 */
		UtilityPackage\Notice\Notice::init();

		/**
		 * UtilityPackages.php
		 *
		 * This file contains the code for the UtilityPackages class, which is responsible for setting up and configuring the utility packages for the Popup Builder Block plugin.
		 *
		 * @package Popup_Builder_Block
		 * @subpackage Includes\Libs
		 */

		UtilityPackage\Stories\Stories::instance( 'popup-builder-block' )   # @plugin_slug
		// ->is_test(true)                                                      # @check_interval
		->set_filter( $filter_string )                                          # @active_plugins
		->set_plugin( 'Popupkit', 'https://wpmet.com/plugin/popupkit/' )  # @plugin_name  @plugin_url
		->set_api_url( 'https://api.wpmet.com/public/stories/' )                # @api_url_for_stories
		->call();

		/**
		 * Show WPMET banner (codename: jhanda)
		 *
		 * This code snippet is responsible for displaying the WPMET banner, also known as codename "jhanda".
		 * It initializes the UtilityPackage\Banner\Banner class and sets various properties and options.
		 * The banner is associated with the 'testplugin' plugin slug and is set to run in test mode.
		 * The active plugins are filtered based on the provided filter string.
		 * The API URL for the banners is set to 'https://api.wpmet.com/public/jhanda'.
		 * The allowed screen for the banner is set to 'toplevel_page_popupkit'.
		 * Finally, the `call()` method is invoked to display the banner.
		 *
		 * @package popup_builder_block
		 * @subpackage Libs
		 * @since 1.0.0
		 */
		UtilityPackage\Banner\Banner::instance( 'popup-builder-block' )     // @plugin_slug
		// ->is_test(true)                                                      # @check_interval
		->set_filter( ltrim( $filter_string, ',' ) )                            // @active_plugins
		->set_api_url( 'https://api.wpmet.com/public/jhanda' )                  // @api_url_for_banners
		->set_plugin_screens( 'toplevel_page_popupkit' )                     // @set_allowed_screen
		->call();

		/**
		 * Ask for Ratings
		 *
		 * This code initializes the utility package for asking users to rate the Popup Builder Block plugin.
		 * It sets various properties such as the plugin logo, plugin name and URL, allowed screens, priority,
		 * time interval, and conditions for displaying the rating prompt.
		 *
		 * @package popup_builder_block
		 * @subpackage Libs
		 */
		UtilityPackage\Rating\Rating::instance( 'popup-builder-block' )                    // @plugin_slug
		->set_plugin_logo( 'https://ps.w.org/popup-builder-block/assets/icon-256x256.png?rev=3316844' )       // @plugin_logo_url
		->set_plugin( 'Popupkit', 'https://wpmet.com/wordpress.org/rating/popup-builder-block' )   // @plugin_name  @plugin_url
		->set_allowed_screens( 'toplevel_page_popupkit' )                      // @set_allowed_screen
		->set_priority( 30 )                                                          // @priority
		->set_first_appear_day( 7 )                                                   // @time_interval_days
		->set_condition( true )                                                       // @check_conditions
		->set_support_url( 'https://wpmet.com/support-ticket-form/' )                 // @support_url
		->call();
	}

}

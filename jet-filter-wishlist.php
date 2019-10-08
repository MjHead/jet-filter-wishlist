<?php
/**
 * Plugin Name: JetSmartFilters & Wishlist
 * Plugin URI:
 * Description: JetSmartFilters & Wishlist compatibility
 * Version:     1.0.0
 * Author:      Zemez
 * Author URI:
 * License:     GPL-3.0+
 * License URI: http://www.gnu.org/licenses/gpl-3.0.txt
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die();
}

define( 'JET_FW_PATH', plugin_dir_path( __FILE__ ) );

// If class `Jet_Filter_Wishlist` doesn't exists yet.
if ( ! class_exists( 'Jet_Filter_Wishlist' ) ) {

	/**
	 * Sets up and initializes the plugin.
	 */
	class Jet_Filter_Wishlist {

		/**
		 * Sets up needed actions/filters for the plugin to initialize.
		 *
		 * @since 1.0.0
		 * @access public
		 * @return void
		 */
		public function __construct() {

			add_action( 'jet-smart-filters/providers/register', function( $manager ) {
				$manager->register_provider(
					'Jet_Filter_Wishlist_Provider',
					JET_FW_PATH . '/inc/provider.php'
				);
			} );

		}

	}
}

new Jet_Filter_Wishlist;

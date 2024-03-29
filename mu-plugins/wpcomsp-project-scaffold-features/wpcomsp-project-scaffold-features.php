<?php
/**
 * The WPCOMSP Project Scaffold features plugin bootstrap file.
 *
 * @since       0.1.0
 * @version     0.1.0
 * @author      WordPress.com Special Projects
 * @license     GPL-3.0-or-later
 *
 * @noinspection    ALL
 *
 * @wordpress-plugin
 * Plugin Name:             WPCOMSP Project Scaffold Features
 * Description:             A features plugin for a custom theme. Could include post types, metaboxes etc. Do not use for blocks!
 * Version:                 0.1.0
 * Requires at least:       6.1
 * Requires PHP:            8.1
 * Author:                  WordPress.com Special Projects
 * Author URI:              https://wpspecialprojects.com
 * License:                 GPL-3.0-or-later
 * License URI:             https://www.gnu.org/licenses/gpl-3.0.html
 * Text Domain:             wpcomsp-project-scaffold-features
 * Domain Path:             /languages
 */

defined( 'ABSPATH' ) || exit;

// Define plugin constants.
function_exists( 'get_plugin_data' ) || require_once ABSPATH . 'wp-admin/includes/plugin.php';
define( 'WPCOMSP_FEATURES_METADATA', get_plugin_data( __FILE__, false, false ) );

define( 'WPCOMSP_FEATURES_DIR', plugin_dir_path( __FILE__ ) );
define( 'WPCOMSP_FEATURES_URL', plugin_dir_url( __FILE__ ) );

// Include the rest of the features plugin's files if system requirements check out.
if ( is_php_version_compatible( WPCOMSP_FEATURES_METADATA['RequiresPHP'] ) && is_wp_version_compatible( WPCOMSP_FEATURES_METADATA['RequiresWP'] ) ) {
	require_once 'functions.php';

	foreach ( glob( __DIR__ . '/includes/*.php' ) as $wpcomsp_features_filename ) {
		if ( preg_match( '#/includes/_#i', $wpcomsp_features_filename ) ) {
			continue; // Ignore files prefixed with an underscore.
		}

		include $wpcomsp_features_filename;
	}
}

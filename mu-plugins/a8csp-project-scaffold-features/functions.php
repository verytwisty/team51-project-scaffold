<?php

defined( 'ABSPATH' ) || exit;

/**
 * Returns the plugin's slug.
 *
 * @return  string
 */
function a8csp_features_get_slug(): string {
	return 'a8csp-project-scaffold-features';
}

/**
 * Returns an array with meta information for a given asset path. First, it checks for an .asset.php file in the same directory
 * as the given asset file whose contents are returns if it exists. If not, it returns an array with the file's last modified
 * time as the version and the main stylesheet + any extra dependencies passed in as the dependencies.
 *
 * @param   string     $asset_path         The path to the asset file.
 * @param   array|null $extra_dependencies Any extra dependencies to include in the returned meta.
 *
 * @return  array|null
 */
function a8csp_features_get_asset_meta( string $asset_path, ?array $extra_dependencies = null ): ?array {
	if ( ! file_exists( $asset_path ) || ! str_starts_with( $asset_path, A8CSP_FEATURES_DIR ) ) {
		return null;
	}

	$asset_path_info = pathinfo( $asset_path );
	if ( file_exists( $asset_path_info['dirname'] . '/' . $asset_path_info['filename'] . '.asset.php' ) ) {
		$asset_meta  = require $asset_path_info['dirname'] . '/' . $asset_path_info['filename'] . '.asset.php';
		$asset_meta += array( 'dependencies' => array() ); // Ensure 'dependencies' key exists.
	} else {
		$asset_meta = array(
			'dependencies' => array(),
			'version'      => filemtime( $asset_path ),
		);
		if ( false === $asset_meta['version'] ) { // Safeguard against filemtime() returning false.
			$asset_meta['version'] = A8CSP_FEATURES_METADATA['Version'];
		}
	}

	if ( is_array( $extra_dependencies ) ) {
		$asset_meta['dependencies'] = array_merge( $asset_meta['dependencies'], $extra_dependencies );
		$asset_meta['dependencies'] = array_unique( $asset_meta['dependencies'] );
	}

	return $asset_meta;
}

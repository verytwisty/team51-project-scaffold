<?php

defined( 'ABSPATH' ) || exit;

/**
 * Loads the features plugin's translated strings.
 *
 * @since   0.1.0
 * @version 0.1.0
 *
 * @return  void
 */
function wpcomsp_features_load_textdomain(): void {
	load_muplugin_textdomain( WPCOMSP_FEATURES_METADATA['TextDomain'], dirname( plugin_basename( WPCOMSP_FEATURES_DIR ) ) . WPCOMSP_FEATURES_METADATA['DomainPath'] );
}
add_action( 'init', 'wpcomsp_features_load_textdomain' );

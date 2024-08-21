<?php

defined( 'ABSPATH' ) || exit;

/**
 * Loads the features plugin's translated strings.
 *
 * @version 0.1.0
 *
 * @return  void
 */
function a8csp_features_load_textdomain(): void {
	load_muplugin_textdomain( A8CSP_FEATURES_METADATA['TextDomain'], dirname( plugin_basename( A8CSP_FEATURES_DIR ) ) . A8CSP_FEATURES_METADATA['DomainPath'] );
}
add_action( 'init', 'a8csp_features_load_textdomain' );

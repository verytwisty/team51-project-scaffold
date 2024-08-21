<?php

defined( 'ABSPATH' ) || exit;

/**
 * Simple shortcode to output a string.
 *
 * @return string
 */
function wpcomsp_sc_translatable_string(): string {
	return __( 'This string can be translated!', 'wpcomsp-project-scaffold' );
}
add_shortcode( 'translate-string', 'wpcomsp_sc_translatable_string' );

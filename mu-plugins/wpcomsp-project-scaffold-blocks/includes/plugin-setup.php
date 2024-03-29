<?php

defined( 'ABSPATH' ) || exit;

/**
 * Registers the block using the metadata loaded from the `block.json` file.
 * Behind the scenes, it registers also all assets, so they can be enqueued
 * through the block editor in the corresponding context.
 *
 * @since   0.1.0
 * @version 0.1.0
 *
 * @return  void
 */
function wpcomsp_blocks_init(): void {
	register_block_type( WPCOMSP_BLOCKS_DIR . 'build/foobar' );
	register_block_type( WPCOMSP_BLOCKS_DIR . 'build/spamham' );
}
add_action( 'init', 'wpcomsp_blocks_init' );

/**
 * Loads the blocks plugin's translated strings.
 *
 * @since   0.1.0
 * @version 0.1.0
 *
 * @return  void
 */
function wpcomsp_blocks_load_textdomain(): void {
	load_muplugin_textdomain( WPCOMSP_BLOCKS_METADATA['TextDomain'], dirname( plugin_basename( WPCOMSP_BLOCKS_DIR ) ) . WPCOMSP_BLOCKS_METADATA['DomainPath'] );

	foreach ( glob( WPCOMSP_BLOCKS_DIR . 'build/*', GLOB_ONLYDIR ) as $wpcomsp_block_dir ) {
		$wpcomsp_block_name = basename( $wpcomsp_block_dir );
		wp_set_script_translations(
			generate_block_asset_handle( "wpcomsp/$wpcomsp_block_name", 'editorScript' ),
			WPCOMSP_BLOCKS_METADATA['TextDomain'],
			untrailingslashit( WPCOMSP_BLOCKS_DIR ) . WPCOMSP_BLOCKS_METADATA['DomainPath']
		);
	}
}
add_action( 'init', 'wpcomsp_blocks_load_textdomain', 11 );

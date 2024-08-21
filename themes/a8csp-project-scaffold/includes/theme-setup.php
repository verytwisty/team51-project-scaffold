<?php

defined( 'ABSPATH' ) || exit;

/**
 * Loads the theme's translated strings.
 *
 * @since   1.0.0
 * @version 1.0.0
 *
 * @return  void
 */
function wpcomsp_load_theme_textdomain(): void {
	wp_get_theme()->load_textdomain();
}
add_action( 'init', 'wpcomsp_load_theme_textdomain' );

/**
 * Registers one or more stylesheets to enqueue in the editor (both Gutenberg and TinyMCE).
 *
 * @since   1.0.0
 * @version 1.0.0
 *
 * @return  void
 */
function wpcomsp_enqueue_editor_style(): void {
	add_editor_style( 'style-editor.css' );
}
add_action( 'admin_init', 'wpcomsp_enqueue_editor_style' );

/**
 * Registers and/or enqueues theme scripts and stylesheets.
 *
 * @since   1.0.0
 * @version 1.0.0
 *
 * @return  void
 */
function wpcomsp_enqueue_frontend_assets(): void {
	$theme_slug = wpcomsp_get_theme_slug();

	$asset_meta = wpcomsp_get_theme_asset_meta( get_theme_file_path( 'style.css' ), array( /* parent theme style, if applicable */ ) );
	wp_enqueue_style(
		"$theme_slug-style",
		get_stylesheet_uri(),
		$asset_meta['dependencies'],
		$asset_meta['version']
	);
	wp_style_add_data( "$theme_slug-style", 'rtl', 'replace' );

	$asset_meta = wpcomsp_get_theme_asset_meta( get_theme_file_path( 'assets/js/build/index.js' ) );
	wp_enqueue_script(
		"$theme_slug-script",
		get_theme_file_uri( 'assets/js/build/index.js' ),
		$asset_meta['dependencies'],
		$asset_meta['version'],
		true
	);
}
add_action( 'wp_enqueue_scripts', 'wpcomsp_enqueue_frontend_assets' );

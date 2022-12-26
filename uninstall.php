<?php
/**
 * Fired when the plugin is uninstalled.
 */

// If uninstall not called from WordPress, then exit.
if ( ! defined( 'WP_UNINSTALL_PLUGIN' ) ) {
	exit;
}

// Perform security checks.
$is_plugin_valid = ( isset( $_REQUEST['plugin'] ) && strpos( sanitize_text_field( wp_unslash( $_REQUEST['plugin'] ) ), 'work-with-us' ) !== false ) ? true : false;
$is_slug_valid   = ( isset( $_REQUEST['slug'] ) && strpos( sanitize_text_field( wp_unslash( $_REQUEST['slug'] ) ), 'work-with-us' ) !== false ) ? true : false;
$is_user_allowed = current_user_can( 'delete_plugins' );

if ( ! $is_plugin_valid || ! $is_slug_valid || ! $is_user_allowed ) {
	exit;
}

// Check if plugin settings and data must be removed.
$option_delete_data_on_uninstall = get_option( 'wwu_delete_data_on_uninstall' );

if ( '1' === $option_delete_data_on_uninstall ) {
	// Delete options.
	$options = array(
		'wwu_cta',
		'wwu_paragraphs_number',
		'wwu_post_tag',
		'wwu_delete_data_on_uninstall',
	);

	foreach ( $options as $option ) {
		if ( get_option( $option ) ) {
			delete_option( $option );
		}
	}
}

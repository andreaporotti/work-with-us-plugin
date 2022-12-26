<?php
/**
 * Plugin Name:       ilPost - Work with us
 * Plugin URI:        https://github.com/andreaporotti/work-with-us-plugin
 * Description:       Allows users to add a call to action to post content.
 * Version:           1.0.0
 * Requires at least: 6.0
 * Requires PHP:      7.4
 * Author:            Andrea Porotti
 * Author URI:        https://www.andreaporotti.it/
 * License:           GPL v2 or later
 * License URI:       https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain:       wwu
 * Domain Path:       /languages
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

// Get plugin details from file header.
$plugin_data = get_file_data(
	__FILE__,
	array(
		'name'        => 'Plugin Name',
		'version'     => 'Version',
		'text_domain' => 'Text Domain',
	)
);
define( 'WWU_PLUGIN_VERSION', ( isset( $plugin_data['version'] ) ) ? $plugin_data['version'] : '1.0.0' );
define( 'WWU_PLUGIN_NAME', ( isset( $plugin_data['name'] ) ) ? $plugin_data['name'] : 'ilPost - Work with us' );
define( 'WWU_PLUGIN_SLUG', ( isset( $plugin_data['text_domain'] ) ) ? $plugin_data['text_domain'] : 'wwu' );

// Set default options values.
define( 'WWU_CTA', '<div class="wwu-cta"><a href="#">Abbonati</a></div>' );
define( 'WWU_PARAGRAPHS_NUMBER', 4 );
define( 'WWU_POST_TAG', 'governo' );

// Include plugin files.
require_once plugin_dir_path( __FILE__ ) . 'includes/class-wwu-plugin-hooks.php';
require_once plugin_dir_path( __FILE__ ) . 'includes/class-wwu-plugin-options.php';
require_once plugin_dir_path( __FILE__ ) . 'includes/class-wwu.php';

// Register plugin activate hook.
function wwu_activate() {
	Wwu_Plugin_Hooks::activate();
}
register_activation_hook( __FILE__, 'wwu_activate' );

// Register plugin deactivate hook.
function wwu_deactivate() {
	Wwu_Plugin_Hooks::deactivate();
}
register_deactivation_hook( __FILE__, 'wwu_deactivate' );

// Begin plugin execution.
$wwu_plugin = new Wwu();
$wwu_plugin->run();

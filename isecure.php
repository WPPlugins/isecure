<?php

/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @link              http://odude.com/
 * @package           iSecure
 *
 * @wordpress-plugin
 * Plugin Name:       iSecure - WordPress Scanner
 * Plugin URI:        http://www.odude.com
 * Description:       Keep eyes on your site activity.
 * Version:           1.0.5
 * Author:            ODude Web Solutions
 * Author URI:        http://odude.com/
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       isecure
 * Domain Path:       /languages
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-isecure-activator.php
 */
function activate_isecure() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-isecure-activator.php';
	ISecure_Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-isecure-deactivator.php
 */
function deactivate_isecure() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-isecure-deactivator.php';
	ISecure_Deactivator::deactivate();
}

register_activation_hook( __FILE__, 'activate_isecure' );
register_deactivation_hook( __FILE__, 'deactivate_isecure' );

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path( __FILE__ ) . 'includes/class-isecure.php';


/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function run_isecure() {

	$plugin = new ISecure();
	$plugin->run();

}
run_isecure();

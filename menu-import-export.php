<?php

/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @link              https://www.upwork.com/fl/rayhan1
 * @since             1.0.0
 * @package           Menu_Import_Export
 *
 * @wordpress-plugin
 * Plugin Name:       Menu Import and Export
 * Plugin URI:        https://myrecorp.com/plugins/menu-import-export
 * Description:       Export or Import your all menus within a click. It will help you to backup or restore your menus easily.
 * Version:           1.0.0
 * Author:            ReCorp
 * Author URI:        https://www.upwork.com/fl/rayhan1
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       menu-import-export
 * Domain Path:       /languages
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * Currently plugin version.
 * Start at version 1.0.0 and use SemVer - https://semver.org
 * Rename this for your plugin and update it as you release new versions.
 */
define( 'MENU_IMPORT_EXPORT_VERSION', '1.0.0' );

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-menu-import-export-activator.php
 */
function activate_menu_import_export() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-menu-import-export-activator.php';
	Menu_Import_Export_Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-menu-import-export-deactivator.php
 */
function deactivate_menu_import_export() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-menu-import-export-deactivator.php';
	Menu_Import_Export_Deactivator::deactivate();
}

register_activation_hook( __FILE__, 'activate_menu_import_export' );
register_deactivation_hook( __FILE__, 'deactivate_menu_import_export' );



register_activation_hook(__FILE__, 'recorp_menu_import_export_page_activate');
add_action('admin_init', 'recorp_menu_import_export_plugin_redirect');


/*Redirect to Nav Menu page when plugin will active*/
function recorp_menu_import_export_page_activate() {
    add_option('recorp_menu_import_export_activation_check', true);
}


function recorp_menu_import_export_plugin_redirect() {
    if (get_option('recorp_menu_import_export_activation_check', false)) {
        delete_option('recorp_menu_import_export_activation_check');
         exit( wp_redirect("nav-menus.php?success=true") );
    }
}


/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path( __FILE__ ) . 'includes/class-menu-import-export.php';

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function run_menu_import_export() {

	$plugin = new Menu_Import_Export();
	$plugin->run();

}
run_menu_import_export();

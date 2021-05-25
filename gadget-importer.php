<?php

/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @link              https://iammarcogiannini.github.io
 * @since             1.0.0
 * @package           Gadget_Importer
 *
 * @wordpress-plugin
 * Plugin Name:       Gadget Importer
 * Plugin URI:        https://iammarcogiannini.github.io
 * Description:       This is a short description of what the plugin does. It's displayed in the WordPress admin area.
 * Version:           1.0.0
 * Author:            Marco Giannini
 * Author URI:        https://iammarcogiannini.github.io
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       gadget-importer
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
define( 'GADGET_IMPORTER_VERSION', '1.0.0' );

/**
 * Plugin Path and URL
 * Downloaded XML path
 */
define( 'GADGET_PATH', plugin_dir_path( __FILE__ ));
define( 'GADGET_URL', plugin_dir_url(__FILE__));
define( 'XML_PATH', GADGET_PATH . 'xml/');
define( 'CSV_PATH', GADGET_PATH . 'csv/');

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require GADGET_PATH . 'vendor/autoload.php';
require GADGET_PATH . 'helpers/upload-image.php';
require GADGET_PATH . 'helpers/snippets.php';
require GADGET_PATH . 'helpers/products.php';
require GADGET_PATH . 'includes/class-gadget-importer.php';
require GADGET_PATH . 'helpers/csv-headings.php';
require GADGET_PATH . 'controllers/DownloadController.php';
require GADGET_PATH . 'controllers/ImportController.php';

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function run_gadget_importer() {

	//$plugin = new Gadget_Importer();
	//$plugin->run();

}

add_action('init', function(){
	$files = [
		[
			'name' => 'prodinfo_it_v1.1.xml',
			'payload' => 'prodinfo'
		]
	];
	switch ($_GET['payload']) {
		case 'run':
			foreach ($files as $file) {
				$downloader = new DownloadController();
				$downloader->download_xml($file['name']);	
				$importer = new ImportController($file['name']);

				switch ($file['payload']) {
					case 'prodinfo':
						$importer->readProdInfo();
						break;
					
					default:
						# code...
						break;
				}
			}
			break;
		
		default:
			# code...
			break;
	}
}, 6);
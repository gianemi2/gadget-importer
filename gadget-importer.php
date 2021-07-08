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
define( 'OUTPUT_PATH', GADGET_PATH . 'output/');

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require GADGET_PATH . 'vendor/autoload.php';
require GADGET_PATH . 'helpers/products.php';
require GADGET_PATH . 'helpers/csv-headings.php';
require GADGET_PATH . 'helpers/printing-id-converter.php';
require GADGET_PATH . 'helpers/create-prodextras.php';
require GADGET_PATH . 'controllers/ReaderController.php';
require GADGET_PATH . 'controllers/DownloadController.php';
require GADGET_PATH . 'controllers/ImportController.php';
require GADGET_PATH . 'controllers/ConvertController.php';
require GADGET_PATH . 'controllers/ProdExtraController.php';

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
			'name' => 'stock.xml',
			'payload' => 'compare',
			'sku_property' => 'ID'
		],
		[
			'name' => 'stock_textile.xml',
			'payload' => 'compare',
			'sku_property' => 'ID'
		],
		[
			'name' => 'printinfo.xml',
			'payload' => 'prodextras',
			'check' => 'PRODUCT_BASE_NUMBER',
			'sku_property' => 'PRODUCT_BASE_NUMBER'
		],
		[
			'name' => 'printinfo_TEXTILE.xml',
			'payload' => 'prodextras',
			'check' => 'PRODUCT_BASE_NUMBER',
			'sku_property' => 'PRODUCT_BASE_NUMBER'
		]
		, 
		[
			'name' => 'prodinfo_it_v1.1.xml',
			'payload' => 'prodinfo',
			'compare' => 'stock.json'
		],
		[
			'name' => 'prodinfo_TEXTILE_IT.xml',
			'payload' => 'prodinfo',
			'compare' => 'stock_textile.json'
		]/*,
		[
			'name' => 'USBprodinfo.xml',
			'payload' => 'usb',
			'compare' => 'USBpricelist.json'
		],
		[
			'name' => 'USBpricelist.xml',
			'payload' => 'usbprice',
			'remote_folder' => 'usbpricelist/'
		], */
	];
	switch ($_GET['payload']) {
		case 'run':
			foreach ($files as $file) {
				//$downloader = new DownloadController();
				//$downloader->download_xml($file);
				switch ($file['payload']) {
					case 'compare': 
						$check = $file['check'] ? $file['check'] : false;
						$converter = new ConvertController($file, 2);
						$converter->run($file['sku_property'], $check);
						break;
					case 'prodextras':
						$check = $file['check'] ? $file['check'] : false;
						$converter = new ProdExtraController($file, 2);
						$converter->run($file['sku_property'], $check);
						break;
					case 'prodinfo':
						$importer = new ImportController($file, 3, $file['compare'], 'prodinfo', $file['attributes']);
						$importer->run();
						break;
					case 'usbprice':
						$converter = new ConvertController($file, 3);
						$converter->run('CODE');
						break;
					case 'usb':
						$importer = new ImportController($file, 3, $file['compare'], 'usb');
						$importer->run('code');
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

add_filter('before_map_products', function($prod_string){
	if(!$prod_string['id']){
		$product_id = wc_get_product_id_by_sku( $product['sku'] );
		$product = wc_get_product( $product_id );
		if($product){
			$prod_string['title'] = $product->get_name();
			$prod_string['id'] = $product_id;
		} else {
			return false;
		}
	}
	return $prod_string;
});
add_filter('before_create_table_products', function($group_row){
	$prod_string = unserialize($group_row['group_id']);
	if(!$prod_string['id']){
		$product_id = wc_get_product_id_by_sku( $prod_string['sku'] );
		$product = wc_get_product( $product_id );
		if($product){
			$prod_string['title'] = $product->get_name();
			$prod_string['id'] = $product_id;
		} else {
			return false;
		}
	}
	$group_row['group_id'] = serialize($prod_string);
	return $group_row;
});
add_filter( 'wc_product_has_unique_sku', '__return_false' );
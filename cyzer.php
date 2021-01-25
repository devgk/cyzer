<?php
/**
 * Plugin Name:     WP Cyzer Engine
 * Plugin URI:      https://cyzer.dev/
 * Description:     WP Cyzer Engine extends the functionalities of WordPress, 
 *                  improves security, and adds several tools for developers. 
 *                  It comes with built-in analytics, log management, form 
 *                  sanitization and validation, and access control management. 
 *                  It also has a WP control panel to customize the
 *                  default functionalities of WordPress.
 * 
 * Text Domain:     wp_cyzer_engine
 * Version:         1.0
 * 
 * Author:          Cyzer
 * Author URI:      https://cyzer.dev/
 * 
 * License:         GPL v3
 * License URI:     https://www.gnu.org/licenses/gpl-3.0.en.html
 * 
 * @package         wp_cyzer_engine
 * @copyright       2016 2021 cyzer.dev
 */

 // Make sure we don't expose any info if called directly
if(!function_exists('add_action')){
	header('Status: 403 Forbidden');
	header('HTTP/1.1 403 Forbidden');
	exit();
}

// General Definitions
define('PLUGIN_PATH', plugin_dir_path(__FILE__));
define('PLUGIN_URL', plugin_dir_url(__FILE__));

// Core Settings
require_once PLUGIN_PATH.'/functions/core/default-settings.php';
require_once PLUGIN_PATH.'/functions/core/base64-mod.php';

// Security Settings
require_once PLUGIN_PATH.'/functions/security/default-settings.php';

// Developer Tool Kits
require_once PLUGIN_PATH.'/includes/wp-management/page-creator.php';

// Form Security
require_once PLUGIN_PATH.'/includes/form/form-security.php';

// Developer Tool Kits
require_once PLUGIN_PATH.'/libraries/stag-input-validator/stag_input.php';
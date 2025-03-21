<?php
/**
* Plugin Name: Popup Builder - Create highly converting, mobile friendly marketing popups.
* Plugin URI: https://popup-builder.com
* Description: The most complete popup plugin. Html, image, iframe, shortcode, video and many other popup types. Manage popup dimensions, effects, themes and more.
* Version: 4.3.6
* Author: Looking Forward Software Incorporated.
* Author URI: https://popup-builder.com
* License: GPLv2
* Text Domain:  popup-builder
* Domain Path:  /languages/
*/

/*If this file is called directly, abort.*/
if (!defined('WPINC')) {
	die;
}

if (class_exists('SgpbPopupConfig')) {
	wp_die('Please, deactivate the FREE version of Popup Builder plugin before upgrading to PRO.');
}

if (!defined('SGPB_POPUP_FILE_NAME')) {
	define('SGPB_POPUP_FILE_NAME', plugin_basename(__FILE__));
}

if (!defined('SGPB_POPUP_FOLDER_NAME')) {
	define('SGPB_POPUP_FOLDER_NAME', plugin_basename(dirname(__FILE__)));
}

require_once(plugin_dir_path(__FILE__).'com/boot.php');
require_once(plugin_dir_path(__FILE__).'PopupBuilderInit.php');

<?php
/**
 * @package wp_mail return-path
 */
/*/*
Plugin Name: wp_mail return-path
Version: 1.0.3
Plugin URI: 
Description: Simple plugin that sets the PHPMailer->Sender variable so that the return-path is correctly set when using wp_mail.
Author: Barnaby Puttick
Author URI: http://www.umis.net/
*/

if (!function_exists('wp_mail_returnpath_phpmailer_init')) :
function wp_mail_returnpath_phpmailer_init($phpmailer) {
	// Set the Sender (return-path) if it is not already set
	if(filter_var($params->Sender, FILTER_VALIDATE_EMAIL) !== true) :
		$phpmailer->Sender=$phpmailer->From;
	endif;
}
endif;

add_action('phpmailer_init','wp_mail_returnpath_phpmailer_init');
?>

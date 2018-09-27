=== Plugin Name ===
Contributors: barnz99
Tags: wp-mail, return-path, sender, phpmailer
Requires at least: 3.0.1
Tested up to: 4.9
Stable tag: 4.9
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Simple plugin that sets the PHPMailer->Sender variable so that the return-path is correctly set when using wp_mail.

== Description ==

This plugin sets the PHPMailer Sender (return-path) the same as the From address if it's not correctly set.

== Installation ==

1. Unzip all files to the `/wp-content/plugins/` directory
2. Log into Wordpress admin and activate the 'Latest Tweets' plugin through the 'Plugins' menu

== Changelog ==

= 1.0.3 =
* Tested with WordPress 4.9.4

= 1.0.2 =
* Now only sets the sender if it's not already set with a valid email address.

= 1.0.1 =
* Tested on 4.4 

= 1.0.0 =
* Inital Release

= 0.0.1 =
* Beta Release


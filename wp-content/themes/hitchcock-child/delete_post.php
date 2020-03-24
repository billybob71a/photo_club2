<?php
/**
 * Custom Page Template
 */
require( '/var/www/html/www.cpas-yyc.com/wp-load.php' );
$login_check = is_user_logged_in();
error_log("The user logged in is ". $login_check);
if ($login_check) {
	error_log("Arrived at the delete_post page created by Petery");
	if (!empty($_POST["delete_var"])) {
		error_log("The value from jquery is ". $_POST["delete_var"]);
		wp_delete_post( $_POST["delete_var"]);
		return 0;
		}
	else {
		error_log("nothing was sent over");
		return 1;
		}
}
else {
}


<?php
/**
 * Custom Page Template
 */
require( '/var/www/html/www.visorsourcing.com/wp-load.php' );
$login_check = is_user_logged_in();
error_log("The user logged in is ". $login_check);
if ($login_check) {
	error_log("Arrived at photo admin page");
	$current_user = wp_get_current_user();
	error_log("the user name is ". $current_user->user_login);
	error_log("The user first name last name is ". $current_user->user_firstname ." ". $current_user->user_lastname);
	error_log("The user division is ". $current_user->division_drop_down);
	$args = array( 'who' => 'subscribers', 
					'has_published_posts' => True );
	$user_query = new WP_User_Query( $args );
	$user_array = $user_query->get_results();
	echo '<ul>';
	foreach ( $user_array as $user ) {
		$user_info = get_userdata( $user->ID );
		echo '<li>'. $user_info->first_name . ' ' . $user_info->last_name .'</li>';
	}
	echo '</ul>';
}
else {
}


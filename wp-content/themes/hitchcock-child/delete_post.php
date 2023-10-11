<?php
/**
 * Custom Page Template
 */
global $wpdb;
require( '../../../wp-load.php' );
$login_check = is_user_logged_in();
$login_user = wp_get_current_user();
$login_email = $login_user->user_email;
error_log("The user logged in is ". $login_user->user_email);
if ($login_check) {
	error_log("Arrived at the delete_post page created by Petery");
	if (!empty($_POST["delete_var"])) {
		error_log("The value from jquery is goodbye ". $_POST["delete_var"]);
        $post_id = $_POST["delete_var"];
        $querystr = "SELECT DISTINCT post_title FROM wp_posts WHERE ID = '$post_id'";
        $post_title_array =  $wpdb->get_results($querystr, ARRAY_A);
        $post_title = $post_title_array[0]['post_title'];
		wp_delete_post( $_POST["delete_var"]);
        $blog_name    = get_bloginfo('name');
        $subject = "You have deleted $post_title";
        $message_to_user = '<html><head></head><body>';
        $message_to_user .= '<p>Dear '. $login_user->display_name .'</p>';
        $message_to_user .= '<p>You have deleted a photo called '. $post_title .'</p>';
        $message_to_user .= "Calgary Photographic Art Society";
        $message_to_user .= '</body></html>';
        $headers  = 'X-Mailer: User Submitted Posts'. "\n";
        $headers .= 'From: Photo Club <yungpetapps@gmail.com>'. "\n";
        $headers .= 'Reply-To:  Photo Club <yungpetapps@gmail.com>'. "\n";
        $headers .= 'Content-Type: '. 'text/html' .'; charset='. get_option('blog_charset', 'UTF-8')  ."\n";
        wp_mail($login_email, $subject, $message_to_user, $headers);
		return 0;
		}
	else {
		error_log("nothing was sent over");
		return 1;
		}
}
else {
}


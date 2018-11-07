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
	$user_id = $current_user->ID;
	error_log("the user name is ". $current_user->user_login);
	error_log("the user id is ". $user_id);
	error_log("The user first name last name is ". $current_user->user_firstname ." ". $current_user->user_lastname);
	error_log("The user division is ". $current_user->division_drop_down);
	//$list_of_posts = get_posts('author' => $user_id);
	$args = array( 'who' => 'subscribers', 
					'has_published_posts' => True );
	$user_query = new WP_User_Query( $args );
	$user_array = $user_query->get_results();
	$var_junior_array = array();
	$var_senior_array = array();
	echo '<ul>';
	foreach ( $user_array as $user ) {
		error_log("the user id from the array is ". $user->ID );
		if ($user->ID != '1') { 
			$user_info = get_userdata( $user->ID );
			$args2 = array( 'posts_per_page' => -1,
							'author' => $user->ID
							);
			$current_user_posts = get_posts( $args2 );
			error_log("the number of posts for this user is ". count( $current_user_posts ));
			foreach ( $current_user_posts as $post_item ) {
				$images = get_attached_media('image', $post_item->ID );
				error_log("here are the images ". $temp);
				foreach ( $images as $image_item ) {
					if ($user->division_drop_down == 'Junior') {
						echo('<li>'. $user_info->first_name . ' ' . $user_info->last_name .', '.
						$user->division_drop_down .', '. $post_item->post_title .', '.
						$image_item->guid .'</li>');
						$pattern = '/[^\/]+/';
						echo('<li>'. $image_item->guid .'</li>');
						$results = preg_match_all($pattern, $image_item->guid, $all_matches);
						$results2 = var_dump($all_matches);
						//echo('<li>'.$all_matches[0][2].'/'.$all_matches[0][3].'/'.$all_matches[0][4].'/'.$all_matches[0][5].'/'.$all_matches[0][6].'</li>');
						$source_location = $all_matches[0][2].'/'.$all_matches[0][3].'/'.$all_matches[0][4].'/'.$all_matches[0][5];
						$presenter_list_junior = $post_item->post_title.', '.$source_location.', '. $all_matches[0][6] .', '. $user->division_drop_down .','. $user_info->first_name .' '. $user_info->last_name;
						echo('<li> hihihihi '. $presenter_list_junior .'</li>');
						//array_push($var_junior_array, $presenter_list_junior);
						$var_junior_array[] = $presenter_list_junior;
						$new_name = $post_item->post_title;
						echo('<li>'.$new_name.'</li>');
						preg_match_all('/[^.]+/',$all_matches[0][6],$image_split); 
						//$temp = var_dump($image_split);
						$image_renamed = $new_name.'.'.$image_split[0][1];
						$dest_location = $all_matches[0][2].'/'.$all_matches[0][3].'/'.$all_matches[0][4].'/'.$all_matches[0][5].'/photo_random/junior/'.$image_renamed;
						echo('<li>'. $dest_location .'</li>');
						//copy($source_location, $dest_location);
					}
					else if ($user->division_drop_down == 'Senior') {
						echo('<li>'. $user_info->first_name . ' ' . $user_info->last_name .', '.
						$user->division_drop_down .', '. $post_item->post_title .', '.
						$image_item->guid .'</li>');
						$pattern = '/[^\/]+/';
						echo('<li>'. $image_item->guid .'</li>');
						$results = preg_match_all($pattern, $image_item->guid, $all_matches);
						$results2 = var_dump($all_matches);
						//echo('<li>'.$all_matches[0][2].'/'.$all_matches[0][3].'/'.$all_matches[0][4].'/'.$all_matches[0][5].'/'.$all_matches[0][6].'</li>');
						$source_location = $all_matches[0][2].'/'.$all_matches[0][3].'/'.$all_matches[0][4].'/'.$all_matches[0][5]; 
						echo('The source location is '. $source_location);
						$presenter_list_senior = $post_item->post_title.','.$source_location.','. $all_matches[0][6] .','. $user->division_drop_down .','. $user_info->first_name .' '. $user_info->last_name;
						$var_senior_array[] = $presenter_list_senior;
						$new_name = $post_item->post_title;
						echo('<li>'.$new_name.'</li>');
						preg_match_all('/[^.]+/',$all_matches[0][6],$image_split); 
						$temp = var_dump($image_split);
						$image_renamed = $new_name.'.'.$image_split[0][1];
						$dest_location = $all_matches[0][2].'/'.$all_matches[0][3].'/'.$all_matches[0][4].'/'.$all_matches[0][5].'/photo_random/senior/'.$image_renamed;
						echo('<li>'. $dest_location .'</li>');
						//copy($source_location, $dest_location);
					}
				}
			}
		}
	}
	echo '</ul>';
	echo '-------------------------------';
	$temp = var_dump($var_junior_array);
	shuffle($var_junior_array);
	echo '<br>';
	echo '---------------------------------';
	$temp2 = var_dump($var_junior_array);
	echo '<ul>';
	$index = 0;
	foreach ($var_junior_array as $item) {
		$index++;
		echo('<li>'. $index .', '.$item .'</li>');
		$pattern = '/[^,]+/';
		preg_match_all($pattern, $item, $item_delimited);
		preg_match_all('/(\..*)$/', $item_delimited[0][1], $file_extension);
		echo('<li>'.$index.'_'.$item_delimited[0][0] .'.'.$file_extension[1][0] .'</li>');
	}
	echo '</ul>';
	echo('-------------------------');
	echo('<br><br>');
	$index = 0;
	$senior_list_document = $source_location.'/photo_random/senior/senior_list.txt';
	shuffle($var_senior_array);
	$myfileSenior = fopen($senior_list_document, "w");
	echo($senior_list_document);
	foreach ($var_senior_array as $item) {
		$index++;
		$pattern = '/[^,]+/';
		//echo('<li>'. $index .', '.$item .'</li>');
		preg_match_all($pattern, $item, $item_delimited);
		//echo('<li>'.$index .', '.$item_delimited[0][0].', '.$item_delimited[0][3].', '. $item_delimited[0][4].'</li>');
		fwrite($myfileSenior, $index."__".$item_delimited[0][0]."__".$item_delimited[0][3]."__".$item_delimited[0][4]." ".$item_delimited[0][5]."\r\n");
		preg_match_all('/([^\.]+)$/', $item_delimited[0][2], $file_extension);
		echo('<li>'.$file_extension[1][0].'</li>');
		echo('<li>'.$index.'_'.$item_delimited[0][0] .'.'.$file_extension[1][0] .','.$item_delimited[0][1].'</li>');
		$dest_location = $item_delimited[0][1] .'/photo_random/senior/'.$index.'_'. $item_delimited[0][0] .'.'. $file_extension[1][0];
		$source_location = $item_delimited[0][1].'/'.$item_delimited[0][2];
		//$dest_location = $item_delimited[0][1].'/'.$item_delimited[0][0];
		echo($source_location.' copy to '. $dest_location);
		copy($source_location, $dest_location);
	}
	fclose($myfileSenior);
}
else {
}


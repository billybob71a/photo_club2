<?php
/**
 * Custom Page Template
 */
require( '/var/www/html/www.visorsourcing.com/wp-load.php' );
$login_check = is_user_logged_in();
error_log("The user logged in is ". $login_check);
function display_photos($file_location, $thumbnail_location) {
	// try to use this expression regex (?<=________)(.*)
	echo("Hi you all the file location is ".$file_location."<br>");
	echo("the thumbnail location is ".$thumbnail_location."<br>");
	if (file_exists($file_location)) {
		echo "The file $filename exists";
	}
	$file = fopen($file_location, "r");
	$d = dir($thumbnail_location);
	$dir_content = [];
	while (false !== ($f = $d->read())) {
		//echo("the file is". $f);
		array_push($dir_content, $f);
	}
	//echo("yoooooooooooooooooooooo");
	//print_r($dir_content);
	$counter = 0;
	while(! feof($file)) {
		$readline = fgets($file);
		if (preg_match("/(?<=________)(.*)/", $readline, $matches)) {
			echo("The matched item is ". $matches[0]."<br/>");
			preg_match_all('/.*(?=\.)/', $matches[0], $before_file_extension);
			//preg_match_all('/[^\.]/', $matches[0], $before_file_extension);
			$readline_items_array = preg_split("/__/",$readline);
			//echo("<br><br>");
			//$temp = var_dump($before_file_extension);
			/*below i am appending "-150" because the thumbnail is usually <image name>-150x150
			meaning width 150 pixel height 150 pixel
			i could have just appended the whole "-150x150", but I found that some thumbnails like
			<image name>-300 have a different height measurement depending on the proportion of the image, but
			the width seems to be a standard, so I decided to use width as a part of the search criteria instead
			of the width and height
			*/
			$thumbnail_search = $before_file_extension[0][0]."-150";
			$thumbnail_search_regex = '/^('.$thumbnail_search.')/';
			//echo("I am searching for the pattern ". $thumbnail_search_regex."");
			//echo("I will be searching for ". $thumbnail_search."<br/>");
			$fl_array = preg_grep($thumbnail_search_regex, $dir_content);
			$index_number = key($fl_array);
			//echo("I think I found it in the array index ". $dir_content[$index_number]."<br/>");
			//echo("the thumbnail location is ". $thumbnail_location ."<br/>");
			$thumbnail_location_file = 'https://www.visorsourcing.com/'.$thumbnail_location."/". $dir_content[$index_number];
			//echo($thumbnail_location_file."<br/>");
			echo("<img src='".$thumbnail_location_file."' /><br>");
			echo("$readline_items_array[1] <br><br>");
		}
	}
	fclose($file);
	
}
if (($login_check) && isset($_POST['randomize'])) {
	error_log("Arrived at photo admin page");
	$current_user = wp_get_current_user();
	$user_id = $current_user->ID;
	error_log("the user name is ". $current_user->user_login);
	error_log("the user id is ". $user_id);
	error_log("The user first name last name is ". $current_user->user_firstname ." ". $current_user->user_lastname);
	error_log("The user division is ". $current_user->division_drop_down);
	date_default_timezone_set('America/Edmonton');
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
						//echo('<li>'. $user_info->first_name . ' ' . $user_info->last_name .', '.
						//$user->division_drop_down .', '. $post_item->post_title .', '.
						//$image_item->guid .'</li>');
						$pattern = '/[^\/]+/';
						//echo('<li>'. $image_item->guid .'</li>');
						$results = preg_match_all($pattern, $image_item->guid, $all_matches);
						//$results2 = var_dump($all_matches);
						//echo('<li>'.$all_matches[0][2].'/'.$all_matches[0][3].'/'.$all_matches[0][4].'/'.$all_matches[0][5].'/'.$all_matches[0][6].'</li>');
						$source_location_junior = $all_matches[0][2].'/'.$all_matches[0][3].'/'.$all_matches[0][4].'/'.$all_matches[0][5];
						$presenter_list_junior = $post_item->post_title.','.$source_location_junior.','. $all_matches[0][6] .','. $user->division_drop_down .','. $user_info->first_name .' '. $user_info->last_name.','.$all_matches[0][6];
						//echo('<li> hihihihi '. $presenter_list_junior .'</li>');
						//array_push($var_junior_array, $presenter_list_junior);
						$var_junior_array[] = $presenter_list_junior;
						$new_name = $post_item->post_title;
						//echo('<li>'.$new_name.'</li>');
						preg_match_all('/[^.]+/',$all_matches[0][6],$image_split); 
						//$temp = var_dump($image_split);
						$image_renamed = $new_name.'.'.$image_split[0][1];
						$dest_location = $all_matches[0][2].'/'.$all_matches[0][3].'/'.$all_matches[0][4].'/'.$all_matches[0][5].'/photo_random/junior/'.$image_renamed;
						//echo('<li>'. $dest_location .'</li>');
						//copy($source_location, $dest_location);
					}
					else if ($user->division_drop_down == 'Senior') {
						//echo('<li>'. $user_info->first_name . ' ' . $user_info->last_name .', '.
						//$user->division_drop_down .', '. $post_item->post_title .', '.
						//$image_item->guid .'</li>');
						$pattern = '/[^\/]+/';
						//echo('<li>'. $image_item->guid .'</li>');
						$results = preg_match_all($pattern, $image_item->guid, $all_matches);
						//$results2 = var_dump($all_matches);
						//echo('<li>'.$all_matches[0][2].'/'.$all_matches[0][3].'/'.$all_matches[0][4].'/'.$all_matches[0][5].'/'.$all_matches[0][6].'</li>');
						$source_location_senior = $all_matches[0][2].'/'.$all_matches[0][3].'/'.$all_matches[0][4].'/'.$all_matches[0][5]; 
						//echo('The source location is '. $source_location);
						$presenter_list_senior = $post_item->post_title.','.$source_location_senior.','. $all_matches[0][6] .','. $user->division_drop_down .','. $user_info->first_name .' '. $user_info->last_name.','.$all_matches[0][6];
						$var_senior_array[] = $presenter_list_senior;
						$new_name = $post_item->post_title;
						//echo('<li>'.$new_name.'</li>');
						preg_match_all('/[^.]+/',$all_matches[0][6],$image_split); 
						//$temp = var_dump($image_split);
						$image_renamed = $new_name.'.'.$image_split[0][1];
						$dest_location = $all_matches[0][2].'/'.$all_matches[0][3].'/'.$all_matches[0][4].'/'.$all_matches[0][5].'/photo_random/senior/'.$image_renamed;
						//echo('<li>'. $dest_location .'</li>');
						//copy($source_location, $dest_location);
					}
				}
			}
		}
	}
	echo '</ul>';
	//echo '-------------------------------';
	$localtime_assoc = getdate();
	//print_r($localtime_assoc);
	$date_iso = ($localtime_assoc['year'].
		$localtime_assoc['mon'].
		$localtime_assoc['mday'].
		$localtime_assoc['hours'].
		$localtime_assoc['minutes'].
		$localtime_assoc['seconds']);
	//echo($date_iso);
	$dir_read = $source_location_junior.'/photo_random';
	$dir_contents = scandir($dir_read,1);
	foreach ($dir_contents as $item) {
		//echo($item);
		if ($item == 'junior') {
			$junior_folder = $dir_read.'/'.$item;
			$junior_folder_backup = $dir_read.'/'.$item.'_'.$date_iso;
			echo('the junior folder is '. $junior_folder);
			$result = rename($junior_folder, $junior_folder_backup);
			//echo('the result from renaming is '. $result);
			mkdir($junior_folder);
		}
		else if ($item == 'senior') {
			$senior_folder = $dir_read.'/'.$item;
			$senior_folder_backup = $dir_read.'/'.$item.'_'.$date_iso;
			rename($senior_folder, $senior_folder_backup);
			mkdir($senior_folder);
		}
	}
	//$temp = var_dump($var_junior_array);
	shuffle($var_junior_array);
	//echo '<br>';
	//echo '---------------------------------';
	//$temp2 = var_dump($var_junior_array);
	echo '<ul>';
	$index = 0;
	$junior_list_document = $source_location_junior.'/photo_random/junior/junior_list.txt';
	$myfileJunior = fopen($junior_list_document, "w");
	foreach ($var_junior_array as $item) {
		$index++;
		$pattern = '/[^,]+/';
		//echo('<li>'. $index .', '.$item .'</li>');
		preg_match_all($pattern, $item, $item_delimited);
		//echo('<li>'.$index .', '.$item_delimited[0][0].', '.$item_delimited[0][3].', '. $item_delimited[0][4].'</li>');
		fwrite($myfileJunior, $index."__".$item_delimited[0][0]."__".$item_delimited[0][3]."__".$item_delimited[0][4]."________".$item_delimited[0][5]."\r\n");
		preg_match_all('/([^\.]+)$/', $item_delimited[0][2], $file_extension);
		//echo('<li>'.$file_extension[1][0].'</li>');
		//echo('<li>'.$index.'_'.$item_delimited[0][0] .'.'.$file_extension[1][0] .','.$item_delimited[0][1].'</li>');
		$dest_location = $item_delimited[0][1] .'/photo_random/junior/'.$index.'_'. $item_delimited[0][0] .'.'. $file_extension[1][0];
		$source_dir = item_delimited[0][1];
		$source_location = $item_delimited[0][1].'/'.$item_delimited[0][2];
		//$dest_location = $item_delimited[0][1].'/'.$item_delimited[0][0];
		//echo($source_location.' copy to '. $dest_location);
		copy($source_location, $dest_location);
	}
	echo '</ul>';
	fclose($myfileJunior);
	display_photos($junior_list_document, $source_location_junior);
	//echo('-------------------------');
	echo('<br><br>');
	$index = 0;
	$senior_list_document = $source_location_senior.'/photo_random/senior/senior_list.txt';
	shuffle($var_senior_array);
	$myfileSenior = fopen($senior_list_document, "w");
	//echo("the senior_list_document is ".$senior_list_document);
	//echo(" finished");
	foreach ($var_senior_array as $item) {
		$index++;
		$pattern = '/[^,]+/';
		//echo('<li>'. $index .', '.$item .'</li>');
		preg_match_all($pattern, $item, $item_delimited);
		//echo('<li>'.$index .', '.$item_delimited[0][0].', '.$item_delimited[0][3].', '. $item_delimited[0][4].'</li>');
		fwrite($myfileSenior, $index."__".$item_delimited[0][0]."__".$item_delimited[0][3]."__".$item_delimited[0][4]."________".$item_delimited[0][5]."\r\n");
		preg_match_all('/([^\.]+)$/', $item_delimited[0][2], $file_extension);
		//echo('<li>'.$file_extension[1][0].'</li>');
		//echo('<li>'.$index.'_'.$item_delimited[0][0] .'.'.$file_extension[1][0] .','.$item_delimited[0][1].'</li>');
		$dest_location = $item_delimited[0][1] .'/photo_random/senior/'.$index.'_'. $item_delimited[0][0] .'.'. $file_extension[1][0];
		$source_location = $item_delimited[0][1].'/'.$item_delimited[0][2];
		//$dest_location = $item_delimited[0][1].'/'.$item_delimited[0][0];
		//echo($source_location.' copy to '. $dest_location);
		copy($source_location, $dest_location);
	}
	fclose($myfileSenior);
	display_photos($senior_list_document, $source_location_senior);
}
else {
}
echo("<br>");
echo("<link href='style.css' rel='stylesheet' type='text/css'>");
echo("<form method='POST' action='cpasadmin.php'>");
echo("<input type='hidden' value='randomize' name='randomize'>");
echo("<input type='submit' value='Randomize All Photos' class='submit_button'>");
echo("</form>");
	


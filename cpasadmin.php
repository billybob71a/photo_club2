<?php
/**
 * Custom Page Template
 */
require( '/var/www/html/www.visorsourcing.com/wp-load.php' );
$login_check = is_user_logged_in();
error_log("The user logged in is ". $login_check);
function display_photos($file_location, $thumbnail_location) {
	// try to use this expression regex (?<=________)(.*)
	//echo("Hi you all the file location is ".$file_location."<br>");
	//echo("the thumbnail location is ".$thumbnail_location."<br>");
	$junior_preg_match = 0;
	$senior_preg_match = 0;
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
	//echo("The file location is $file_location");
	if (file_exists("./download/junior.zip")) {
		echo("The last randomization of photos was at: " . date ("F d Y H:i:s.", filemtime('./download/junior.zip'))."<br><br>");
	}
	//if (strpos($file_location, 'junior') === true) {
	$junior_preg_match = preg_match('/junior/', $file_location, $matches);
	$senior_preg_match = preg_match('/senior/', $file_location, $matches);
	if ($junior_preg_match === 1) {
		echo("Junior Photos");
	}
	else if ($senior_preg_match === 1 ) {
		echo("Senior Photos");
	}
	// else {
	// }
	echo("<table>");
	while(! feof($file)) {
		$result = $counter % 3;
		$readline = fgets($file);
		if (preg_match("/(?<=________)(.*)/", $readline, $matches)) {
			//echo("The matched item is ". $matches[0]."<br/>");
			$counter++;
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
			$before_file_extension[0][0] = str_replace('%20', ' ', $before_file_extension[0][0]);
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
			if ($result == 0) {
				echo("<tr><td class='widecell'>");
				echo("<div class='cellwidener'> <img src='".$thumbnail_location_file."' /><br>$readline_items_array[1] </div>");
				echo("</td>");
			}
			else if ($result == 2) {
				echo("<td class='widecell'>");
				echo("<div class='cellwidener'> <img src='".$thumbnail_location_file."' /><br>$readline_items_array[1] </div>");
				echo("</td>");
				echo("</tr>");
			}
			else {
				echo("<td class='widecell'>");
				echo("<div class='cellwidener'> <img src='".$thumbnail_location_file."' /><br>$readline_items_array[1]<br> </div>");
				echo("</td>");
			}
		}
	}
	if ( $result == 2) {
		if ($junior_preg_match === 1) {
			echo("<td class='widecell'>&nbsp;</td></tr>");
			echo("</table><br><a href='https://www.visorsourcing.com/download/junior.zip'>Download Junior Photos</a><br><br>");
		}
		else if ($senior_preg_match === 1) {
			echo("<td class='widecell'>&nbsp;</td></tr>");
			echo("</table><br><a href='https://www.visorsourcing.com/download/senior.zip'>Download Senior Photos</a><br>");
		}
	}
	else if ( $result == 1 ) {
		if ($junior_preg_match === 1) {
			echo("<td class='widecell'>&nbsp;</td><td class='widecell'>&nbsp;</td></tr>");
			echo("</table><br><a href='https://www.visorsourcing.com/download/junior.zip'>Download Junior Photos</a><br><br>");
		}
		else if ($senior_preg_match === 1) {
			echo("<td class='widecell'>&nbsp;</td><td class='widecell'>&nbsp;</td></tr>");
			echo("</table><br><a href='https://www.visorsourcing.com/download/senior.zip'>Download Senior Photos</a><br>");
		}
	}
	else{
		if ($junior_preg_match === 1) {
			echo("</table><br><a href='https://www.visorsourcing.com/download/junior.zip'>Download Junior Photos</a><br><br>");
		}
		else if ($senior_preg_match === 1) {
			echo("</table><br><a href='https://www.visorsourcing.com/download/senior.zip'>Download Senior Photos</a><br>");
		}
	}
	fclose($file);
	
}
function get_source_folder() {
	$args = array( 'who' => 'subscribers', 
					'has_published_posts' => True,
					);
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
							'author' => $user->ID,
							'lang' => ''
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
$source_locations = [];
$source_locations = array($source_location_junior, $source_location_senior);
return $source_locations;
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
					'has_published_posts' => True,
					);
	$user_query = new WP_User_Query( $args );
	$user_array = $user_query->get_results();
	$var_junior_array = array();
	$var_senior_array = array();
	//below debugging level is very usefl
	error_reporting(E_ALL);
	ini_set('display_errors', '1');
	//if ($res === True) {
	//	echo("I did not find an archive");
		//$zip->addFile($_SERVER['DOCUMENT_ROOT']."/wp-content/upload/2018/11/IMG06802_defished.jpg");
	//	}
	//else if ($res === False) {
	//	echo("I found an archive");
	//	}
	echo '<ul>';
	foreach ( $user_array as $user ) {
		error_log("the user id from the array is ". $user->ID );
		if ($user->ID != '1') { 
			$user_info = get_userdata( $user->ID );
			$args2 = array( 'posts_per_page' => -1,
							'author' => $user->ID,
							'lang' => ''
							);
			$current_user_posts = get_posts( $args2 );
			error_log("the number of posts for this user is ". count( $current_user_posts ));
			foreach ( $current_user_posts as $post_item ) {
				$images = get_attached_media('image', $post_item->ID );
				//error_log("here are the images ". $temp);
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
			//echo('the junior folder is '. $junior_folder);
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
	$zip_junior = new ZipArchive();
	if(file_exists('./download/junior.zip')){
		unlink('./download/junior.zip');
	}
	if ($zip_junior->open("./download/junior.zip", ZIPARCHIVE::CREATE ) !== True) {
		die("Could not open file");
	}
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
		//$source_dir = item_delimited[0][1];
		$source_location = $item_delimited[0][1].'/'.$item_delimited[0][2];
		//$dest_location = $item_delimited[0][1].'/'.$item_delimited[0][0];
		//echo($source_location.' copy to '. $dest_location);
		if (file_exists($source_location)) {
			error_log("The file $source_location exists");
		}
		else {
			error_log("Hey Pete The file $source_location does not exist");
			$source_location2 = str_replace('%20', ' ', $source_location);
			error_log("changing the file name to $source_location2");
			if(file_exists($source_location2)) {
				error_log("The file replaced by source_location2 $source_location2 exists");
				$source_location = $source_location2;
			}
		}
		copy($source_location, $dest_location);
			$filename = '/var/www/html/www.visorsourcing.com/wp-content/uploads/2018/11/photo_random/junior/junior_list.txt';
		$zip_junior->addFile($dest_location, basename($dest_location));
	}
	echo '</ul>';
	fclose($myfileJunior);
	$zip_junior->addFile($junior_list_document, basename($junior_list_document));
	$zip_junior->close();
	display_photos($junior_list_document, $source_location_junior);
	//echo('-------------------------');
	echo('<br><br>');
	$index = 0;
	$zip_senior = new ZipArchive();
	if(file_exists('./download/senior.zip')){
		unlink('./download/senior.zip');
	}
	if ($zip_senior->open("./download/senior.zip", ZIPARCHIVE::CREATE ) !== True) {
		die("Could not open file");
	}
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
		if (file_exists($source_location)) {
			error_log("The file $source_location exists");
		}
		else {
			error_log("Hey Pete The file $source_location does not exist");
			$source_location2 = str_replace('%20', ' ', $source_location);
			error_log("changing the file name to $source_location2");
			if(file_exists($source_location2)) {
				error_log("The file replaced by source_location2 $source_location2 exists");
				$source_location = $source_location2;
			}
		}
		copy($source_location, $dest_location);
		$zip_senior->addFile($dest_location, basename($dest_location));
	}
	fclose($myfileSenior);
	$zip_senior->addFile($senior_list_document, basename($senior_list_document));
	$zip_senior->close();
	display_photos($senior_list_document, $source_location_senior);
}
else if ($login_check) {
	$locations = get_source_folder();
	$senior_file = $locations[0]."/photo_random/senior/senior_list.txt";
	$junior_file = $locations[1]."/photo_random/junior/junior_list.txt";
	display_photos($junior_file, $locations[0]);
	display_photos($senior_file, $locations[0]);
}
else {
}
echo("<br><br><br>");
echo("<link href='style.css' rel='stylesheet' type='text/css'>");
echo("<form method='POST' action='cpasadmin.php'>");
echo("<input type='hidden' value='randomize' name='randomize'>");
echo("<input type='submit' value='Randomize All Photos' class='submit_button'>");
echo("</form>");
	


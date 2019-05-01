<?php
/**
 * Custom Page Template
 */
require( '/var/www/html/www.visorsourcing.com/wp-load.php' );
$login_check = is_user_logged_in();
error_log("The user logged in is ". $login_check);

function display_photos_all($keys_of_division, $sub_folder_current) {
    foreach ($keys_of_division as $item) {
        echo("I am going to start readying the thumbnail files");
        $list_file_thumbnail = $_SERVER['DOCUMENT_ROOT'] . '/wp-content/uploads/' . $sub_folder_current . '/photo_random/' . $item . '/' . $item . '_list_thumbnail.txt';
        //echo("I am going to search this folder for the listing " . $list_file_thumbnail . "<br>");

        if (!file_exists($list_file_thumbnail)) {
            //throw new Exception('File not found.');
            return;
        }
        echo("<table>");
        echo("<tr>");
        echo("<td>".$item."</td>");
        echo("</tr>");
        echo("<tr>");
        $contents = fopen($list_file_thumbnail, "r");
        //$lines = explode("\r\n", $contents);
        $counter = 0;
        while (!feof($contents)) {
            $counter++;
            $result = fgets($contents);
            if (strlen($result)==0)
            {
                break;
            }
            //echo("I got the line as " . $result . "<br>");
            $line_split = explode('||', $result);
            preg_match_all('/([^\.]+)$/', $line_split[0], $file_extension);
            //echo("YO man!");
            //var_dump($file_extension);
            //echo("the count is ". count($file_extension));
            if (count($file_extension) == 2)
            {
                $file_extension[0][0] = strtolower($file_extension[0][0]);
                preg_match_all('/(.*\.)/', $line_split[0], $file_name);
                //echo("Hey dude");
                // var_dump($file_name);
                $modulus_counter = $counter % 3;
                //echo("modulus counter is ".$modulus_counter);
                if ($modulus_counter != 0) {
                    echo("<td class='widecell'>");
                    echo("<div class='cellwidener'> <img src='" . $file_name[0][0].$file_extension[0][0]."'><br>" . $line_split[1] . "</div>");
                    echo("</td>");
                } else {
                    echo("<td class='widecell'>");
                    echo("<div class='cellwidener'> <img src='" . $file_name[0][0].$file_extension[0][0]."'><br>" . $line_split[1] . "</div>");
                    echo("</td>");
                    echo("</tr>");
                    echo("<tr>");
                }
            }

        }
     if ($item != "") {
            echo("<tr><td colspan='3'><a href='https://www.visorsourcing.com/download/".$item.".zip'>Download ".$item."</a></td></tr>");
     }
    }
    echo("</table>");
    fclose($contents);
}
function get_current_date() {
    $localtime_assoc = getdate();
    //print_r($localtime_assoc);
    $date_iso = ($localtime_assoc['year'].
        $localtime_assoc['mon'].
        $localtime_assoc['mday'].
        $localtime_assoc['hours'].
        $localtime_assoc['minutes'].
        $localtime_assoc['seconds']);
    return $localtime_assoc;
    //echo($date_iso);
}

function get_current_date_array() {
    $current_date = get_current_date();
    $month_array = array("January" => 1,
        "February" => 2,
        "March" => 3,
        "April" => 4,
        "May" => 5,
        "June" => 6,
        "July" => 7,
        "August" => 8,
        "September" => 9,
        "October" => 10,
        "November" => 11,
        "December" => 12);
    $month_array_folder = array(1 => '01',
        2 => '02',
        3 => '03',
        4 => '04',
        5 => '05',
        6 => '06',
        7 => '07',
        8 => '08',
        9 => '09',
        10 => '10',
        11 => '11',
        12 => '12');
    $month_array_keys = array_keys($month_array);
    //the line below is to shift the whole array to start with index 1 so it is not confusing
    $month_array_keys = array_combine(range(1, count($month_array_keys)), $month_array_keys);

    $month_current_now = array_search($current_date["month"], $month_array_keys);

    $month_current_before = $month_current_now - 1;

    $folder_number_current = $month_array_folder[$month_current_now];
    $folder_number_before = $month_array_folder[$month_current_before];
    $folder_number_year = strval($current_date["year"]);
    return array($folder_number_year, $folder_number_before, $folder_number_current);
}


class GetDivisionAndFolders {

    private $currentfolder;
    private $previousfolder;
    private $division;

    function __construct()
    {
        $this->currentfolder;
        $this->previousfolder;
        $this->division;
    }

    function getCurrentFolder()
    {
        $folder_year_month_array = get_current_date_array();
        $currentfolder = $folder_year_month_array[0]."\/".$folder_year_month_array[2];
        $this->currentfolder = $currentfolder;
        return $this->currentfolder;
    }
    function getPreviousFolder()
    {
        $folder_year_month_array = get_current_date_array();
        $previousfolder = $folder_year_month_array[0]."\/".$folder_year_month_array[1];
        $this->previousfolder = $previousfolder;
        return $this->previousfolder;
    }
    function getDivision()
    {
        global $wpdb;
        $querystr = "SELECT DISTINCT meta_value FROM wp_usermeta WHERE meta_key = 'division_drop_down'";
        $division_competition =  $wpdb->get_results($querystr, ARRAY_A);
        $counter = 0;
        foreach ($division_competition as $division_item => $fields) {
            $var_division[$fields['meta_value']] = array();
            $counter = $counter++;
        }
        $this->division = $var_division;
        return $this->division;
    }
}

if (($login_check) && isset($_POST['randomize'])) {
	//initialize array
	error_log("Arrived at photo admin page");
   // folder
    $folder_year_month_array = get_current_date_array();
    $sub_folder_current = $folder_year_month_array[0]."\/".$folder_year_month_array[2];
    $sub_folder_previous = $folder_year_month_array[0]."\/".$folder_year_month_array[1];
    global $wpdb;
    $querystr = "SELECT DISTINCT meta_value FROM wp_usermeta WHERE meta_key = 'division_drop_down'";
    $division_competition =  $wpdb->get_results($querystr, ARRAY_A);
    $counter = 0;

    foreach ($division_competition as $division_item => $fields) {
        $var_division[$fields['meta_value']] = array();
        $counter = $counter++;
    }

	$current_user = wp_get_current_user();
	$user_id = $current_user->ID;

	date_default_timezone_set('America/Edmonton');
	//$list_of_posts = get_posts('author' => $user_id);
	$args = array( 'who' => 'subscribers',
					'has_published_posts' => True,
					);
	$user_query = new WP_User_Query( $args );
	$user_array = $user_query->get_results();
	/*$var_junior_array = array();
	$var_senior_array = array();*/
	//below debugging level is very usefl
	error_reporting(E_ALL);
	ini_set('display_errors', '1');
	echo '<ul>';
	foreach ( $user_array as $user ) {
		error_log("the user id from the array is ". $user->ID );
		if ($user->ID != '1') {
            $user_division = get_user_meta($user->ID, "division_drop_down", true);
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
                // in the next line we look for the index value of where the user division is (ie Junior, Senior)
                // in the $division_competition two dimensional array

                $division_index = array_search($user_division, array_column($division_competition,'meta_value'));
				foreach ( $images as $image_item ) {
                    $pattern = '/[^\/]+/';
                    // the next preg_match match everything but / from the location found of the folder
                    //echo('<li>'. $image_item->guid .'</li>');
                    $results = preg_match_all($pattern, $image_item->guid, $all_matches);
                    /*
                     The $all_matches array will come up with
                            [0] = https:
                            [1] = www.visorsourcing.com
                            [2] = "wp-content"
                            [3] = "uploads"
                            [4] = "2019"
                            [5] = "04"
                    */
                    $source_location = $all_matches[0][2].'/'.$all_matches[0][3].'/'.$all_matches[0][4].'/'.$all_matches[0][5];
                    $sub_folder_current = str_replace('\/', '/',$sub_folder_current);
                    $sub_folder_previous = str_replace('\/', '/',$sub_folder_previous);
                    if ( (strpos($source_location, $sub_folder_current)!== False) || (strpos($source_location, $sub_folder_previous) !== False))
                    {
                        //echo ("I found ". $sub_folder_current ." in $source_location");
                        //$presenter_list_junior will compose the title, file location, image file name, division, user, and image file name again
                        // instead of $presenter_list and $var_junior_arry[] we will use $var_division[$user_division] and a $presenter_list for all divisions
                        $presenter_list  = $post_item->post_title.'||'.$source_location.'||'. $all_matches[0][6] .'||'. $user_division .'||'. $user_info->first_name .' '. $user_info->last_name.'||'.$all_matches[0][6];
                        //echo('<li> hihihihi '. $presenter_list_junior .'</li>');
                        array_push($var_division[$user_division], $presenter_list);
                        //$var_junior_array[] = $presenter_list_junior;
                        //this part will use the post title
                        $new_name = $post_item->post_title;
                        //echo('<li>'.$new_name.'</li>');
                        preg_match_all('/[^.]+/',$all_matches[0][6],$image_split);
                        //$temp = var_dump($image_split);
                        // rename the image file name as the post title
                        $image_renamed = $new_name.'.'.$image_split[0][1];

                        //$dest_location will be the location of the destination photo of the "photo_random/<division>/<post title name>.jpg"
                        // if the location does not exist it will be created
                        $directory_base = $_SERVER['DOCUMENT_ROOT'].'/'.$all_matches[0][2].'/'.$all_matches[0][3];
                        $destination_directory = $_SERVER['DOCUMENT_ROOT'].'/'.$all_matches[0][2].'/'.$all_matches[0][3].'/'.$sub_folder_current.'/photo_random/'.$user_division.'/';
                        $destination_directory = str_replace('\/', '/', $destination_directory);
                        if ( !is_dir($destination_directory)) {
                            //mkdir($all_matches[0][2].'/'.$all_matches[0][3].'/'.$sub_folder_current.'/photo_random/'.$user_division);
                            mkdir($destination_directory, 0777, true);
                            error_log("Directory ".$user_division." created");
                        }

                        $dest_location = $destination_directory.$image_renamed;
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

    $keys_of_division = array_keys($var_division);
    /*the following loop is used to clear the Senior and Junior folders
    in order that we may put a new list file and image file(s)
    */
    foreach ($keys_of_division as $item)
    {
        $delete_any_files_here = $directory_base.'/'.$sub_folder_current.'/photo_random/'.$item.'/*';
        $delete_any_files_here = str_replace('\/', '/', $delete_any_files_here);
        $files = glob($delete_any_files_here);
        foreach( $files as $file )
        {
            if(is_file($file))
            {
                unlink($file);
            }
        }
    }
    foreach ($var_division as $var_division_item => $fields)
    {
        //randomize the photos in the presentation_list
        shuffle($fields);
        //echo("Hey ".var_dump($fields));
    }

	//echo '<br>';
	//echo '---------------------------------';
	//$temp2 = var_dump($var_junior_array);
	echo '<ul>';
	$index = 0;
	/*$zip_junior = new ZipArchive();
	if(file_exists('./download/junior.zip')){
		unlink('./download/junior.zip');
	}
	if ($zip_junior->open("./download/junior.zip", ZIPARCHIVE::CREATE ) !== True) {
		die("Could not open file");
	}*/
	// go through the divisions and copy it the destination
	foreach ($keys_of_division as $item) {
        $list_file = $_SERVER['DOCUMENT_ROOT'] . '/wp-content/uploads/' . $sub_folder_current . '/photo_random/' . $item . '/' . $item . '_list.txt';
        $list_file_thumbnail = $_SERVER['DOCUMENT_ROOT'] . '/wp-content/uploads/' . $sub_folder_current . '/photo_random/' . $item . '/' . $item . '_list_thumbnail.txt';
        $myfile = fopen($list_file, "w");
        $myfilethumbnail = fopen($list_file_thumbnail, "w");
        // echo("the file is " . $list_file . "<br>");
        $counter = 0;
        $zipfile = new ZipArchive();
        $destination_zip = $_SERVER['DOCUMENT_ROOT'] . '/download/' . $item . '.zip';
       // echo("the destination zip will be " . $destination_zip);
        if (file_exists($destination_zip)) {
            //throw new Exception('File not found.');
            unlink($destination_zip);
        }
        $zipfile->open($destination_zip, ZipArchive::CREATE);
        shuffle($var_division[$item]);
        //echo("the var dump for division");
        //var_dump($var_division[$item]);
        foreach ($var_division[$item] as $list_entries) {
            $counter++;
            // echo("in division " . $item . " I have " . $list_entries . "<br>");
            $array_source_location = explode("||", $list_entries);
            $original_location_file = $_SERVER['DOCUMENT_ROOT'] . '/' . $array_source_location[1] . '/' . $array_source_location[2];
            $original_location_file = str_replace('%20', ' ', $original_location_file);
            $original_location_file = str_replace('&amp;', '&', $original_location_file);
            preg_match_all('/([^\.]+)$/', $array_source_location[2], $image_split_extension);
            preg_match_all('/(.*\.)/', $array_source_location[2], $image_split_name);
            $image_split_name[1][0] = rtrim($image_split_name[1][0], '.');
            // var_dump($image_split_name);

            if ($array_source_location[0] != "")
            {
                $new_location_file = $_SERVER['DOCUMENT_ROOT'] . '/wp-content/uploads/' . $sub_folder_current . '/photo_random/' . $item . '/' . $counter . '_' . $array_source_location[0] . '.' . $image_split_extension[1][0];
                $new_location_file = str_replace('&amp;', '&', $new_location_file);
                $new_location_file = str_replace('%20', ' ', $new_location_file);
                $new_location_file = html_entity_decode($new_location_file);
                //echo ("the old file is " . $original_location_file) . "<br>";
                //echo("the new file is " . $new_location_file . "<br>");
                copy($original_location_file, $new_location_file);
                $array_source_location[0] = str_replace('&amp;', '&', $array_source_location[0]);
                $array_source_location[0] = str_replace('%20', ' ', $array_source_location[0]);
                fwrite($myfile, $counter . "__" . $array_source_location[0] . "__" . $array_source_location[3] . "__" . $array_source_location[4] . "________" . $counter . '_' . $array_source_location[0] . '.' . $image_split_extension[1][0] . "\r\n");
                fwrite($myfilethumbnail, 'https://' . $_SERVER['SERVER_NAME'] . '/' . $array_source_location[1] . '/' . $image_split_name[1][0] . "-150x150." . $image_split_extension[1][0] . '||' . $array_source_location[0] . "\r\n");
                $zipfile->addFile($new_location_file, basename($new_location_file));
            }

        }
        fclose($myfile);
        fclose($myfilethumbnail);
        $zipfile->addFile($list_file, basename($list_file));
        $zipfile->close();
    }
    display_photos_all($keys_of_division, $sub_folder_current);
}



else if ($login_check) {
    //first get the current month and then minus it one month so that we choose the first two months of photos
//    $month_beforeafter = get_current_date();
//	$locations = get_source_folder();
//	$locations_new = str_replace('02','03',$locations[0]);
//	error_log("The location that I will read from is " . $location_new);
//	$senior_file = $locations_new."/photo_random/senior/senior_list.txt";
//	$junior_file = $locations_new."/photo_random/junior/junior_list.txt";
//	error_log("New location " . $locations_new);
//	error_log("I will look in senior file " . $senior_file);
//	if (file_exists($junior_file)) {
//		display_photos($junior_file, $locations_new);
//	}
//	if (file_exists($senior_file)) {
//		display_photos($senior_file, $locations_new);
//	}
    error_reporting(E_ALL);
    $folders_and_division = new GetDivisionAndFolders();
    $sub_folder_current = $folders_and_division->getCurrentFolder();
    $sub_folder_previous = $folders_and_division->getPreviousFolder();
    $var_division = $folders_and_division->getDivision();
    $sub_folder_current = str_replace('\/', '/',$sub_folder_current);
    $sub_folder_previous = str_replace('\/', '/',$sub_folder_previous);
    echo("the current folder is ".$sub_folder_current);
    echo("the previous folder is ".$sub_folder_previous);
    $keys_of_division = array_keys($var_division);
    //var_dump($var_division);
    display_photos_all($keys_of_division, $sub_folder_current);
}
else {
}
echo("<br><br><br>");
echo("<link href='style.css' rel='stylesheet' type='text/css'>");
echo("<form method='POST' action='cpasadmin.php'>");
echo("<input type='hidden' value='randomize' name='randomize'>");
echo("<input type='submit' value='Get New Photos and Randomize' class='submit_button'>");
echo("</form>");
	


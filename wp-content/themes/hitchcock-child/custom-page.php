<?php  /* Template Name: Competition2020Mar */ ?>
<?php
require( '/var/www/html/www.cpas-yyc.com/wp-load.php' );
$login_check = is_user_logged_in();
error_log("The user logged in is ". $login_check);

function display_photos_all($keys_of_division_unique, $sub_folder_current) {
    foreach ($keys_of_division_unique as $item) {
        //echo("I am going to start readying the thumbnail files");
        $list_file_thumbnail = $_SERVER['DOCUMENT_ROOT'] . '/wp-content/uploads/' . $sub_folder_current . '/photo_random/' . $item . '/' . $item . '_list_thumbnail.txt';
        //echo("I am going to search this folder for the listing " . $list_file_thumbnail . "<br>");

        if (file_exists($list_file_thumbnail)) {
            //throw new Exception('File not found.');
            //return;
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
                    $small_photo =  $file_name[0][0];
                    $large_photo = preg_replace("/-150x150/", "", $small_photo);
                    $large_photo_with_extension = $large_photo.$file_extension[0][0];
                    echo("file name is ".$large_photo);
                    if ($modulus_counter != 0) {
                        echo("<td class='widecell'>");
                        echo("<div class='cellwidener'> <a href='" . $large_photo_with_extension ."' rel='prettyPhoto[Gallery1]'><img src='" . $file_name[0][0].$file_extension[0][0]."'></a><br>" . $line_split[1] . "</div>");
                        echo("<li><a href=\"http://63.142.254.125/wp-content/themes/hitchcock-child/images/fullscreen/3.jpg\" rel=\"prettyPhoto[gallery1]\"><img src=\"images/thumbnails/t_3.jpg\" width=\"60\" height=\"60\" alt=\"Fire!\" /></a></li>");
                        echo("</td>");
                    } else {
                        echo("<td class='widecell'>");
                        echo("<div class='cellwidener'> <a href='" . $large_photo_with_extension ."' rel='prettyPhoto[Gallery1]'><img src='" . $file_name[0][0].$file_extension[0][0]."'></a><br>" . $line_split[1] . "</div>");
                        echo("</td>");
                        echo("</tr>");
                        echo("<tr>");
                    }
                }

            }
            if ($item != "") {
                echo("<tr><td colspan='3'>&nbsp;</td></tr>");
            }
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
    $sub_folder_current = str_replace('\/', '/',$sub_folder_current);
    $sub_folder_previous = str_replace('\/', '/',$sub_folder_previous);
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
                            [1] = www.cpas-yyc.com
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

                        $presenter_list  = $post_item->post_title.'||'.$source_location.'||'. $all_matches[0][6] .'||'. $user_division .'||'. $user_info->first_name .' '. $user_info->last_name.'||'.$all_matches[0][6];
                        array_push($var_division[$user_division], $presenter_list);
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
    //echo("Petery the keys of division are ".var_dump($keys_of_division));
    /*the following loop is used to clear the division folders
    in order that we may put a new list file and image file(s)
    */
    foreach ($keys_of_division as $item)
    {
        $delete_any_files_here = $_SERVER['DOCUMENT_ROOT'].'/wp-content/uploads/'.$sub_folder_current.'/photo_random/'.$item.'/*';
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
    $keys_of_division_total = array();
    foreach ($var_division as $var_division_item => $fields)
    {
        //randomize the photos in the presentation_list
        shuffle($fields);
        if (sizeof($fields) != 0 ) {
            //echo("Hey " . var_dump($fields));
            $my_array_regex = preg_split("(\|\|)", $fields[0]);
            //echo("Yo " . $my_array_regex[3]);
            array_push($keys_of_division_total, $my_array_regex[3]);
            //echo("Yo end");
        }
    }

	echo '<ul>';
	$index = 0;
	$keys_of_division_unique = array_unique($keys_of_division_total);
	// echo("the keys are unique ".var_dump($keys_of_division_unique));

	// go through the divisions and copy it the destination
	foreach ($keys_of_division_unique as $item) {
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
    display_photos_all($keys_of_division_unique, $sub_folder_current);
}



else if ($login_check) {
    error_reporting(E_ALL);
    echo("<html>");
    echo("<head>");
    echo("<meta http-equiv='Content-Type' content='text/html; charset=utf-8'>");
    echo("<!--<script src='http://63.142.254.125/wp-content/themes/hitchcock-child/js/jquery-1.7.2.min.js'></script>-->");
    echo("<script src='https://ajax.googleapis.com/ajax/libs/jquery/1.9.0/jquery.min.js'></script>");
    echo("<script src='http://63.142.254.125/wp-content/themes/hitchcock-child/js/jquery.prettyPhoto.js' type='text/javascript' charset='utf-8'></script>");
    echo("<link rel='stylesheet' href='http://63.142.254.125/wp-content/themes/hitchcock-child/css/prettyPhoto.css' media='screen' title='prettyPhoto main stylesheet' type='text/css' />");
    echo("		<style type=\"text/css\" media=\"screen\">
			* { margin: 0; padding: 0; }
			
			body {
				background: #282828;
				font: 62.5%/1.2 Arial, Verdana, Sans-Serif;
				padding: 0 20px;
			}
			
			h1 { font-family: Georgia; font-style: italic; margin-bottom: 10px; }
			
			h2 {
				font-family: Georgia;
				font-style: italic;
				margin: 25px 0 5px 0;
			}
			
			p { font-size: 1.2em; }
			
			ul li { display: inline; }
			
			.wide {
				border-bottom: 1px #000 solid;
				width: 4000px;
			}
			
			.fleft { float: left; margin: 0 20px 0 0; }
			
			.cboth { clear: both; }
			
			#main {
				background: #fff;
				margin: 0 auto;
				padding: 30px;
				width: 1000px;
			}
		</style>");
    echo("</head><body>");
    $folders_and_division = new GetDivisionAndFolders();
    $sub_folder_current = $folders_and_division->getCurrentFolder();
    $sub_folder_previous = $folders_and_division->getPreviousFolder();
    $var_division = $folders_and_division->getDivision();
    $sub_folder_current = str_replace('\/', '/',$sub_folder_current);
    $sub_folder_previous = str_replace('\/', '/',$sub_folder_previous);
    echo("the current folder is ".$sub_folder_current);
    echo("the previous folder is ".$sub_folder_previous);
    $keys_of_division = array_keys($var_division);
    $result = scandir("./wp-content/uploads/".$sub_folder_current."/photo_random");
    //var_dump($var_division);
    //echo(var_dump($keys_of_division));
    $result_intersect = array_intersect($result, $keys_of_division);
    unset($result_intersect[0]);
    $folders_division = array_values($result_intersect);
    //echo(var_dump($folders_division));
    display_photos_all($folders_division, $sub_folder_current);
}
else {
}
echo("<br><br><br>");
// echo("<link href='http://63.142.254.125/wp-content/themes/hitchcock-child/css/style.css' rel='stylesheet' type='text/css'>");
?>

<?php get_footer(); ?>

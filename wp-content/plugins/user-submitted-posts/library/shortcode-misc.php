<?php // User Submitted Posts - Shortcodes misc.

/*
	Shortcode: Reset form button
	Returns the markup for a reset-form button
	Syntax: [usp-reset-button class="aaa,bbb,ccc" value="Reset form" url="https://example.com/usp-pro/submit/"]
	Attributes:
		class  = classes for the parent element (optional, default: none)
		value  = link text (optional, default: "Reset form")
		url    = the URL where your form is displayed (can use %%current%% for current URL)
	
*/
function usp_reset_button_shortcode($args) {
	
	extract(shortcode_atts(array(
		'class' => '',
		'value' => __('Reset form', 'usp'),
		'url'   => '#please-check-shortcode',
	), $args));
	
	$protocol = is_ssl() ? 'https://' : 'http://';
	
	$host = isset($_SERVER['HTTP_HOST']) ? $_SERVER['HTTP_HOST'] : '';
	
	$current = isset($_SERVER['REQUEST_URI']) ? $protocol . $host . $_SERVER['REQUEST_URI'] : '';
	
	$url = preg_replace('/%%current%%/', $current, $url);
	
	$url = remove_query_arg(array('usp_reset_form', 'post_id', 'success', 'usp-error'), $url);
	
	$href = get_option('permalink_structure') ? $url .'?usp_reset_form=true"' : $url .'&usp_reset_form=true';
	
	$class = empty($class) ? '' : ' class="'. esc_attr($class) .'"';
	
	$output = '<p'. $class .'><a href="'. esc_url($href) .'">'. esc_html($value) .'</a></p>';
	
	return $output;
	
}
add_shortcode('usp-reset-button', 'usp_reset_button_shortcode');



/*
	Displays a list of all user submitted posts
	Bonus: includes any posts submitted by the Pro version of USP :)
	Shortcode: 
		[usp_display_posts userid="current"]          : displays all submitted posts by current logged-in user
		[usp_display_posts userid="1"]                : displays all submitted posts by registered user with ID = 1
		[usp_display_posts userid="Pat Smith"]        : displays all submitted posts by author name "Pat Smith"
		[usp_display_posts userid="all"]              : displays all submitted posts by all users/authors
		[usp_display_posts userid="all" numposts="5"] : limit to 5 posts
		
	Note that the Pro version of USP provides many more options for the display-posts shortcode:
		
		https://plugin-planet.com/usp-pro-display-list-submitted-posts/
	
*/
function usp_display_posts($attr = array(), $content = null) {
	
	global $post;
	
	extract(shortcode_atts(array(
		
		'userid'    => 'all',
		'post_type' => 'post',
		'numposts'  => -1,
		
	), $attr));
	
	if (ctype_digit($userid)) {
		
		$args = array(
			'author'         => $userid,
			'posts_per_page' => $numposts,
			'post_type'      => $post_type,
			'meta_key'       => 'is_submission',
			'meta_value'     => '1'
		);
		
	} elseif ($userid === 'all') {
		
		$args = array(
			'posts_per_page' => $numposts,
			'post_type'      => $post_type,
			'meta_key'       => 'is_submission',
			'meta_value'     => '1'
		);
		
	} elseif ($userid === 'current') {
		
		$args = array(
			'author'         => get_current_user_id(),
			'posts_per_page' => $numposts,
			'post_type'      => $post_type,
			'meta_key'       => 'is_submission',
			'meta_value'     => '1'
		);
		
	} else {
		
		$args = array(
			'posts_per_page' => $numposts,
			'post_type'      => $post_type,
			
			'meta_query' => array(
				
				'relation' => 'AND',
				
				array(
					'key'    => 'is_submission',
					'value'  => '1'
				),
				array(
					'key'    => 'user_submit_name',
					'value'  => $userid
				)
			)
		);
		
	}
	
	$args = apply_filters('usp_display_posts_args', $args);
	
	$submitted_posts = get_posts($args);
	
	$display_posts = '<ul>';
	
	foreach ($submitted_posts as $post) {
		
		setup_postdata($post);
		
		$display_posts .= '<li><a href="'. get_the_permalink() .'" title="'. esc_attr__('View full post', 'usp') .'">'. get_the_title() .'</a></li>';
		
	}
	
	$display_posts .= '</ul>';
	
	wp_reset_postdata();
	
	return $display_posts;
	
}
add_shortcode('usp_display_posts', 'usp_display_posts');



/* 
	Shortcode: [usp_gallery]
	Displays a gallery of submitted images for the current post
	Syntax: [usp_gallery size="" format="" target="" class="" number="" id=""]
	Notes: See usp_get_images() for inline notes and more infos
*/
if (!function_exists('usp_gallery')) :

function usp_gallery($attr, $content = null) {
	
	extract(shortcode_atts(array(
		
		'size'    => 'thumbnail',
		'format'  => 'image',
		'target'  => 'blank',
		'class'   => '',
		'number'  => 100,
		'id'      => false,
		
	), $attr));

    // PeterY code starts here
    #error_log("the permalink is ". get_the_permalink());
    #$user_profile_data = get_userdata( 50 );
    #error_log(gettype( $user_profile_data ));
    #$user_division = $user_profile_data->__get( 'division_drop_down'  );
    #error_log("the user division is");
    #error_log( $user_division );
    $the_current_user_temp = wp_get_current_user();
    // echo( "the current user ID is " . $the_current_user_temp->ID . "<br>");
    $the_current_user_id = esc_html( $the_current_user_temp->ID );
    $the_current_user_login_id = get_the_author_meta( 'user_login', $the_current_user_id );
    //echo( "the current author meta id is " . $the_current_user_login_id . "<br>");
    $args = array(
        'author' 	=> $the_current_user_id,
        'orderby' 	=> 'post_date',
        'order'		=> 'ASC',
        'lang'		=> '',
        'posts_per_page'	=> 5
    );
    $current_user_posts = get_posts( $args );
    ob_start();
    var_dump($current_user_posts);
    $output = ob_get_clean();
    error_log($output);
    $total = count( $current_user_posts );
    // echo( "the total is photos submitted is  ". $total . "<br>" );
    // error_log($total);
    //error_log(var_export( $current_user_posts));
    //error_log(var_export($current_user_posts[1]->ID));
    $gallery = '';
    $pattern = '#^https?://[^/]+/wp-content#';
    $wp_content_var = WP_CONTENT_DIR;
    foreach ($current_user_posts as $item) {
        error_log("Logging item");
        error_log($item->ID);
        // echo( "the item post ID is " . $item->ID . "<br>");
        //petery code start
        $images = usp_get_images($size, $format, $target, $class, $number, $item->ID);
        //petery code finish
        //here is regex pattern to recognize any domain with the wp-content path

        foreach ($images as $image) {
            echo("The image is ". $image );
            //the following match will produce anything after 'wp-content' in this case it should be the year and month
            preg_match('#/wp-content/([^"]+)#', $image, $matches);

            if (isset($matches[1])) {
                /*the following attaches the absolute path of the 'wp-content' from the constant variable to the
                year/month/image name
                */
                $imagelocation =  $wp_content_var . '/' . $matches[1];
                //Regex: remove the "-digitsxdigits" for the file extension
                //the (?=\.\w+$) is a lookahead to ensure that the digit pattern such as -460x345.jpg is before the .jpg
                $imagelocation = preg_replace('/-\d+x\d+(?=\.\w+$)/', '', $imagelocation);
                //get the filename only without showing entire path
                //[^/]+ matches one or more characters that are not a slash
                //$ anaches the match at the end of string
                if (preg_match('#([^/]+)$#', $imagelocation, $matches)) {
                    $originalFileName = $matches[1];
                    // echo('The original file name is '. $matches[1] .'<br>');
                }
                // echo("The image location is ". $imagelocation);
                $filesize_var = filesize($imagelocation);
                $filesize_var_MB = $filesize_var / 1048576;
                // echo("File size is :". $filesize_var);
                // the filesize output should be complete here
            }
            // here I am replacing the URL path with contents from constant WP_CONTENT_DIR

            $gallery .= $image;
            // echo("The title is " . $item->post_title . "<br>");
            $gallery = $gallery ? '<div class="usp-image-gallery">'
                . $gallery
                . '</div>'
                . 'The original file name is <br>'
                . $originalFileName
                . '<br>'
                . 'The original size is <br>'
                . $filesize_var_MB
                . ' MB <br>'
                . '<H1>'
                . $item->post_title
                . '</H1><input type="button" name="'
                . $item->ID
                . '" id="delete_button" value="Delete" /><br><br>' : '';

            //code not used anymore
            //if (file_exists($image)) {
            //    $sizeInBytes = filesize($filename);
            //    echo("File size is :". $sizeInBytes);
            // }
        }
    }
    error_log($the_current_user_login_id);
    //PeterY code ends here
    //$images = usp_get_images('thumbnail');

    //foreach ($images as $image) $gallery .= $image;

    //$gallery = $gallery ? '<div class="usp-image-gallery">'. $gallery .'</div>' : '';
	
	return $gallery;
	
}
add_shortcode('usp_gallery', 'usp_gallery');

endif;
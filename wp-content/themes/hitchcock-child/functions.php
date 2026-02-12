<?php
/*Peter Y custom functions
custom function to make home page transparent*/
add_action( 'wp_enqueue_scripts', 'my_theme_enqueue_styles' );
function my_theme_enqueue_styles() {
 
    $parent_style = 'parent-style'; // For Hitchcock theme
 
    wp_enqueue_style( $parent_style, get_template_directory_uri() . '/style.css' );
    wp_enqueue_style( 'child-style',
        get_stylesheet_directory_uri() . '/style.css',
        array( $parent_style ),
        wp_get_theme()->get('Version')
    );
}
add_action( 'wp_enqueue_scripts', 'my_theme_enqueue_styles' );

	
/*********************************************************************
         *PeterY code
 /*********************************************************************/

        //PeterY code starts here
        add_action( 'register_form', 'myplugin_register_form' );
        function myplugin_register_form() {

            $first_name = ( ! empty( $_POST['first_name'] ) ) ? trim( $_POST['first_name'] ) : '';
            $last_name = ( ! empty( $_POST['last_name'] ) ) ? trim( $_POST['last_name'] ) : '';

            ?>
            <p>
                <label for="first_name"><?php _e( 'First Name', 'mydomain' ) ?><br />
                    <input type="text" name="first_name" id="first_name" class="input" value="<?php echo esc_attr( wp_unslash( $first_name ) ); ?>" size="25" /></label>
            </p>

            <p>
                <label for="last_name"><?php _e( 'Last Name', 'mydomain' ) ?><br />
                    <input type="text" name="last_name" id="last_name" class="input" value="<?php echo esc_attr( wp_unslash( $last_name ) ); ?>" size="25" /></label>
            </p>

            <?php
        }
        add_filter( 'registration_errors', 'myplugin_registration_errors', 10, 3 );
        function myplugin_registration_errors( $errors, $sanitized_user_login, $user_email ) {

            if ( empty( $_POST['first_name'] ) || ! empty( $_POST['first_name'] ) && trim( $_POST['first_name'] ) == '' ) {
                $errors->add( 'first_name_error', __( '<strong>ERROR</strong>: You must include a first name.', 'mydomain' ) );
            }
            if ( empty( $_POST['last_name'] ) || ! empty( $_POST['last_name'] ) && trim( $_POST['first_name'] ) == '' ) {
                $errors->add( 'last_name_error', __( '<strong>ERROR</strong>: You must include a first name.', 'mydomain' ) );
            }
            return $errors;
        }
        add_action( 'user_register', 'myplugin_user_register' );
        function myplugin_user_register( $user_id ) {
            if ( ! empty( $_POST['first_name'] ) ) {
                update_user_meta( $user_id, 'first_name', trim( $_POST['first_name'] ) );
                update_user_meta( $user_id, 'last_name', trim( $_POST['last_name'] ) );
            }
        }
        function extra_profile_fields( $current_user ) {
            global $wp;
            $the_url = $_SERVER['REQUEST_URI'];
            $current_user = wp_get_current_user();
// Uncomment the line below to dump the data of current user
//$temp_var = var_dump($current_user);
            $type_current_user = gettype( $current_user );
            error_log( 'The current user is ' . $type_current_user );
            $var_temp = $current_user->exists();
            error_log('the type of var_temp is ' . $var_temp );
            if ( $current_user->exists() ) {
                if ( strpos( $the_url, 'register') == false ) {
                    $login_current_user = $current_user->user_login;
                    $id_current_user = $current_user->ID;
                    error_log( 'The current user ID is ' . $id_current_user );
                    //error_log("The current_user_id_type is ". $current_user_id_type);
                    //error_log("The current_user_id is $current_user_id " );
                    $user_profile_data = get_userdata( $current_user->ID );
                    $user_division = $user_profile_data->__get( 'division_drop_down'  );
                    error_log("the division in the profile is");
                    error_log( $user_division );
                    error_log('the url is coming from ' . $the_url );
                    error_log('the url type is ' . gettype($the_url));
                }
                else
                {
                    $user_division = '';
                }
            }
            else {
                $user_division = "";
            }
            ?>
            <!--<h3> //commented out function below because committee does not want members to change their own level
            <?php /*_e('Photo Contest Division', 'frontendprofile');
                */?> </h3>
            <?php
/*            switch ($user_division) {
                case "Level 1":
                    error_log("in Level 1");
                    */?>
                    <select name="division_drop_down">
                        <option value="">Please select your division</option>
                        <option selected="Selected" value="Level_1">Level 1</option>
                        <option value="Level_2">Level 2</option>
			<option value="Level_3">Level 3</option>
                    </select>
                    <?php
/*                    break;
                case "Level 2":
                    error_log("in Level 2");
                    */?>
                    <select name="division_drop_down">
                        <option value="">Please select your division</option>
                        <option value="Level_1">Level 1</option>
                        <option selected="Selected" value="Level_2">Level 2</option>
 			<option value="Level_3">Level 3</option>
                    </select>
                    <?php
/*                    break;
                default:
                    error_log("in default");
                    */?>
                    <select name="division_drop_down">
                        <option selected="Selected" value="">Please select your division</option>
                        <option value="Level_1">Level 1</option>
                        <option value="Level_2">Level 2</option>
			<option value="Level_3">Level 3</option>
                    </select>
                    --><?php
/*                    break;
            }
            */?>
            <br>
            <br>
        <?php }
        function save_extra_profile_fields( $user_id ) {
            error_log( 'Hello the user_id is ' . $user_id );
//if ( !current_user_can( 'edit_user', $user_id ) )
//	return false;
            error_log( 'about to save data the user_id field is ' . $user_id );
            /* Edit the following lines according to your set fields */
            //error_log('I am going to save data for user ' . $user );
            //commented out line below because committee does not want members to change their own level
            //update_user_meta( $user_id, "division_drop_down", $_POST['division_drop_down'] );
        }
        //commented out function below because committee does not want members to change their own level
       /* function  validate_extra_profile_fields( $errors, $sanitized_user_login, $user_email ) {
            if ( $_POST['division_drop_down'] == "") {
                $errors->add( 'no_division_error', __('<strong>ERROR</Strong>:You must select Level 1 or Level 2 for Photo Contest Division', 'mydomain') );}
//
            return $errors;
        }*/
        //function save_extra_profile_fields( $user_id ) {
        //	if ( ! empty( $_POST['first_name'] )) {
        //		update_user_meta( $user_id, 'division_drop_down', $_POST['division_drop_down']);
        //	}
        //}
        //Function ends here
        //PeterY code ends here
        //comment out two add_filter line below
        // add_filter( 'wp_mail_from', 'wpb_sender_email' );
        //add_filter( 'wp_mail_from_name', 'wpb_sender_name');
        //end of comment out
        //PeterY code starts here
        //add_action( 'profile_personal_options', 'add_contact_fields');
        //add_action('show_user_profile', 'extra_profile_fields', 10 );
        //add_action('edit_user_profile', 'extra_profile_fields', 10 );
        //add_filter( 'user_contactmethods', 'extra_profile_fields');
        //the above adds it into Contact Info as the first item
        //add_action('profile_personal_options', function (){error_log("hello sugar"); $current_user = wp_get_current_user(); $gettype_val = gettype($current_user); error_log("the gettype_val is $gettype_val" );},5,1);
        add_action('profile_personal_options', 'extra_profile_fields');
        //the above adds below color scheme and above personal options
        add_action( 'edit_user_profile', 'custom_user_profile_fields', 10, 1 );
        //the above adds for non-current user near the top of profile page
        add_action( 'personal_options_update', 'save_extra_profile_fields' );
        add_action('register_form', 'add_hidden_field_to_registration_form');
        function add_hidden_field_to_registration_form() {
            ?>
            <input type="hidden" name="division_drop_down_ff" value="Level_0">
            <?php
        }
        add_action('user_register', 'save_hidden_field_value');

        function save_hidden_field_value($user_id) {
            if (isset($_POST['division_drop_down_ff'])) {
                update_user_meta($user_id, 'division_drop_down', sanitize_text_field($_POST['division_drop_down_ff']), $prev_value = '');

            }
        }
        //the above is for updating and saving to usermeta
        //add_action( 'register_form', 'extra_profile_fields');
        //add_filter( 'registration_errors', 'validate_extra_profile_fields', 10, 3);
        //add_action( 'user_register', 'save_extra_profile_fields');
        //add_action( 'login_init', 'my_wp_new_user_notification_init' );
        //function my_wp_new_user_notification_init() {
        //	add_filter( 'wp_new_user_notification_email', 'my_wp_new_user_notification_email', 10, 3 );
        //}
        //function my_wp_new_user_notification_email( $wp_new_user_notification_email, $user, $user_email ) {
        //	global $wpdb, $wp_hasher;
        //
        //    $key = wp_generate_password( 20, false );
        //
        /** This action is documented in wp-login.php */
        //    do_action( 'retrieve_password_key', $user->user_login, $key );
        //
        // Now insert the key, hashed, into the DB.
        //    if ( empty( $wp_hasher ) ) {
        //        require_once ABSPATH . WPINC . '/class-phpass.php';
        //        $wp_hasher = new PasswordHash( 8, true );
        //    }
        //    $hashed = time() . ':' . $wp_hasher->HashPassword( $key );
        //    $wpdb->update( $wpdb->users, array( 'user_activation_key' => $hashed ), array( 'user_login' => $user->user_login ) );
        //
        //    $switched_locale = switch_to_locale( get_user_locale( $user ) );
        //	$message = sprintf(__('Your new username is: %s'), $user->user_login) . "\r\n";
        //	$message .= __('To set your password and complete registration, visit the following URL:') . "\r\n\r\n";
        //	$message .= '<' .network_site_url("wp-login.php?action=rp&key=$key&login=" . rawurlencode($user->user_login), 'login') . ">\r\n\r\n";
        //	$message .= sprintf(__('你的新用戶名: %s'), $user->user_login) . "\r\n\r\n";
        //	$message .= __('請點撃以的網址，更改密碼，以便完成證記新用戶名：') . "\r\n\r\n";
        //	$message .= '<' .network_site_url("wp-login.php?action=rp&key=$key&login=" . rawurlencode($user->user_login), 'login') . ">\r\n\r\n";
        //	$wp_new_user_notification_email['subject'] = sprintf( ' Registration email', $blogname, $user->user_login );
        //	$wp_new_user_notification_email['message'] = $message;
        //	return $wp_new_user_notification_email;
        //	}
        add_filter( 'random_password', 'disable_random_password', 10, 2 );
        function disable_random_password( $password ) {
            $action = isset( $_GET['action'] ) ? $_GET['action'] : '';
            if ( 'wp-login.php' === $GLOBALS['pagenow'] && ( 'rp' == $action  || 'resetpass' == $action ) ) {
                return '';
            }
            return $password;
        }
        add_filter( 'password_hint', function( $hint )
        {
            return __( '' );
        } );
        //function rename_attachment($post_ID){
        //	$post = get_post($post_ID);
        //	$file = get_attached_file($post_ID);
        //	error_log("The file is called ". $file);
        //	$path = pathinfo($file);
        //	$oldfilename = $path['filename'];
        //	error_log("the old file name is ". $oldfilename);
        //	$newfilename = "l-".$oldfilename;
        //	$newuploadfile = $path['dirname']."/".$newfilename.".".$path['extension'];
        //	rename($file, $newuploadfile);
        //	update_attached_file( $post_ID, $newuploadfile);
        //	}
/* Peter Y custom functions
custom function to make home page transparent */
if ( wp_is_mobile() ) { function add_petery_script() {
    wp_register_script('petery_script_mobile', get_template_directory_uri() . '-child/js/petery_script_mobile.js', array( 'jquery' ),'1.10',true);
    wp_enqueue_script('petery_script_mobile');
}
}
else { function add_petery_script() {
    wp_register_script('petery_script', get_template_directory_uri() . '-child/js/petery_script.js', array( 'jquery' ),'1.6',true);
    wp_enqueue_script('petery_script');
}
}
add_action( 'wp_enqueue_scripts', 'add_petery_script');
//Adding some font awesome
add_action('wp_enqueue_scripts', 'enqueue_load_fa');
function enqueue_load_fa() {
	wp_enqueue_style('load_fa', 'https://use.fontawesome.com/releases/v5.7.2/css/all.css' );
}
//Adding Sidebar attempt
function petery_sidebar() {
    register_sidebar( array(
        'name' => __( 'Sidebar name', 'theme_hitchcock' ),
        'id' => 'sidebar-1',
        'description' => '',
        'before_widget' => '',
        'after_widget'  => '',
        'before_title'  => '',
        'after_title'   => '',
    ) );
}
add_action( 'widgets_init', 'petery_sidebar' );
//Petery code, the following will add a widgetized sidebar , maybe
function wpsites_before_post_widget( $content ) {
    if ( is_singular( array( 'post', 'page' ) ) && is_active_sidebar( 'sidebar-1' ) && is_main_query() ) {
        dynamic_sidebar('sidebar-1');
    }
    return $content;
}
add_filter( 'the_content', 'wpsites_before_post_widget' );
function wpse_19692_registration_redirect() {
    return home_url( '/post-registration-2' );
}

add_filter( 'registration_redirect', 'wpse_19692_registration_redirect' );

//this function is redirect function which I got from Gemini
function custom_login_redirect( $redirect_to, $request, $user ) {
    // Check if there is a valid user object
    if ( isset( $user->roles ) && is_array( $user->roles ) ) {
        // Check for specific roles
        if ( in_array( 'administrator', $user->roles ) ) {
            // Admins go to the standard dashboard
            return admin_url();
        } else {
            // Everyone else goes to a custom "Welcome" page
            return home_url( '/upload-photos-2/' );
        }
    }
    return $redirect_to;
}
add_filter( 'login_redirect', 'custom_login_redirect', 10, 3 );
//the following will add a role called subscriber_support
//it will be removed later
//*** code starts here for adding role***
/*function create_custom_subscriber_role() {
     //1. Get the existing Subscriber role object
    $subscriber = get_role( 'subscriber' );

     //2. Add your new role with a custom ID and Display Name
     //We pass the subscriber's capabilities array to the new role
    add_role(
        'subscriber_support',        // The unique ID (Role Slug)
        'Subscriber Support',        // The name shown in the Dashboard
        $subscriber->capabilities
    );
}*/

// Hook it into 'init' so it runs when WordPress loads
//add_action( 'init', 'create_custom_subscriber_role' );
//*** code ends here for adding role

?>

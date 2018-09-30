<?php if( ! defined( 'ABSPATH' ) ) exit;
/**
 * All Purpose functions and definitions
 */

/*******************************
Basic
********************************/

if ( ! function_exists( 'all_purpose_setup' ) ) :

function all_purpose_setup() {

	load_theme_textdomain( 'all-purpose', ALL_PURPOSE_THEME_URI . '/languages' );

	// Add default posts and comments RSS feed links to head.
	add_theme_support( 'automatic-feed-links' );
	add_theme_support( 'title-tag' );
	add_theme_support( 'post-thumbnails' );
	add_theme_support( 'woocommerce' );
	add_theme_support( 'post-formats', array( 'aside', 'image', 'link', 'quote', 'status' ) );			
	// This theme styles the visual editor with editor-style.css to match the theme style.
	add_editor_style();
	
	register_nav_menus( array(
		'primary' => esc_html__( 'Primary', 'all-purpose' ),
	) );

	/*
	 * Switch default core markup for search form, comment form, and comments
	 * to output valid HTML5.
	 */
	add_theme_support( 'html5', array(
		'search-form',
		'comment-form',
		'comment-list',
		'gallery',
		'caption',
	) );

	// Set up the WordPress core custom background feature.
	add_theme_support( 'custom-background', apply_filters( 'all_purpose_custom_background_args', array(
		'default-color' => 'ffffff',
		'default-image' => '',
	) ) );

	// Add theme support for selective refresh for widgets.
	add_theme_support( 'customize-selective-refresh-widgets' );
}
endif;
add_action( 'after_setup_theme', 'all_purpose_setup' );

/*******************************
$content_width
********************************/

function all_purpose_content_width() {
	$GLOBALS['content_width'] = apply_filters( 'all_purpose_content_width', 640 );
}
add_action( 'after_setup_theme', 'all_purpose_content_width', 0 );


/*******************************
* Register widget area.
********************************/


	function all_purpose_widgets_init() {
		register_sidebar( array(
			'name'          => esc_html__( 'Sidebar', 'all-purpose' ),
			'id'            => 'sidebar-1',
			'description'   => esc_html__( 'Add widgets here.', 'all-purpose' ),
			'before_widget' => '<section id="%1$s" class="widget %2$s">',
			'after_widget'  => '</section>',
			'before_title'  => '<h2 class="widget-title">',
			'after_title'   => '</h2>',
		) );
	
}
add_action( 'widgets_init', 'all_purpose_widgets_init' );


	// This theme supports a variety of post formats.
	add_theme_support( 'post-formats', array( 'aside', 'image', 'link', 'quote', 'status' ) );
	
/*******************************
* Enqueue scripts and styles.
********************************/
 
 
function all_purpose_scripts() {

		wp_enqueue_style( 'all-purpose-style', get_stylesheet_uri());
		wp_enqueue_style( 'all-purpose-animate', ALL_PURPOSE_THEME_URI . '/framework/css/animate.css');
		wp_enqueue_style( 'all-purpose-fontawesome', ALL_PURPOSE_THEME_URI . '/framework/css/font-awesome.css' );	
		wp_enqueue_style( 'all-purpose-genericons', ALL_PURPOSE_THEME_URI . '/framework/genericons/genericons.css', array(), '3.4.1' );	
		wp_enqueue_style( 'all-purpose-woocommerce', ALL_PURPOSE_THEME_URI . '/inc/woocommerce/woo-css.css' );

		
	    wp_enqueue_script('jquery');
		wp_enqueue_script( 'all-purpose-navigation', ALL_PURPOSE_THEME_URI . '/framework/js/navigation.js', array(), '20120206', true );
		wp_enqueue_script( 'all-purpose-skip-link-focus-fix', ALL_PURPOSE_THEME_URI . '/framework/js/skip-link-focus-fix.js', array(), '20130115', true );
		wp_enqueue_script( 'all-purpose-aniview', ALL_PURPOSE_THEME_URI . '/framework/js/jquery.aniview.js' );

		if ( is_singular() && wp_attachment_is_image() ) {
			wp_enqueue_script( 'all-purpose-keyboard-image-navigation', ALL_PURPOSE_THEME_SCRIPTS_URI . '/keyboard-image-navigation.js', array( 'jquery' ), '20151104' );
		}
		
		if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
			wp_enqueue_script( 'comment-reply' );
		}
}

add_action( 'wp_enqueue_scripts', 'all_purpose_scripts' );


function all_purpose_admin_scripts() {
	
		wp_enqueue_style( 'all-purpose-admin', ALL_PURPOSE_THEME_URI . '/inc/css/admin.css');
}		
add_action( 'admin_enqueue_scripts', 'all_purpose_admin_scripts' );


/*******************************
* Includes.
*******************************/

	require ALL_PURPOSE_THEME . '/inc/template-tags.php';
	require ALL_PURPOSE_THEME . '/inc/extras.php';
	require ALL_PURPOSE_THEME . '/inc/customizer.php';
	require ALL_PURPOSE_THEME . '/inc/jetpack.php';
	require ALL_PURPOSE_THEME . '/inc/custom-header.php';
	require ALL_PURPOSE_THEME . '/inc/premium-options.php';
	
/*********************************************************************************************************
* Excerpt Read More
**********************************************************************************************************/

function all_purpose_excerpt_more( $link ) {
	if ( is_admin() ) {
		return $link;
	}
	$link = sprintf( '<p class="link-more"><a href="%1$s" class="more-link">%2$s</a></p>',
		esc_url( get_permalink( get_the_ID() ) ),
		/* translators: %s: Name of current post */
		sprintf( __( 'Read More<span class="screen-reader-text"> "%s"</span>', 'all-purpose' ), get_the_title( get_the_ID() ) )
	);
	return ' &hellip; ' . $link;
}
add_filter( 'excerpt_more', 'all_purpose_excerpt_more' );
/*********************************************************************
*PeterY code
*********************************************************************/
	
//PeterY code starts here
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
		error_log("The current_user_id_type is $current_user_id_type" );
		error_log("The current_user_id is $current_user_id " );
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
<h3><?php _e('Photo Contest Division', 'frontendprofile'); 
?> </h3>
<?php
switch ($user_division) {
case "Junior":
error_log("in junior");
?>
<select name="division_drop_down">
<option value="">Please select your division</option>
<option selected="Selected" value="Junior">Junior</option>
<option value="Senior">Senior</option>
</select>
<?php
break;
case "Senior":
error_log("in senior");
?>
<select name="division_drop_down">
<option value="">Please select your division</option>
<option value="Junior">Junior</option>
<option selected="Selected" value="Senior">Senior</option>
</select>
<?php
break;
default:
error_log("in default");
?>
<select name="division_drop_down">
<option selected="Selected" value="">Please select your division</option>
<option value="Junior">Junior</option>
<option value="Senior">Senior</option>
</select>
<?php
break;
}
?>
<br>
<br>
<?php } 
function save_extra_profile_fields( $user_id ) {
error_log( 'Hello the user_id is ' . $user_id );
//if ( !current_user_can( 'edit_user', $user_id ) )
//	return false;
error_log( 'about to save data the user_id field is ' . $user_id );
/* Edit the following lines according to your set fields */
error_log('I am going to save data for user ' . $user );
update_user_meta( $user_id, "division_drop_down", $_POST['division_drop_down'] );
}
function  validate_extra_profile_fields( $errors, $sanitized_user_login, $user_email ) {
	if ( $_POST['division_drop_down'] == "") {
		$errors->add( 'no_division_error', __('<strong>ERROR</Strong>: Demo error. Registration', 'mydomain') );}
//
return $errors;
}
//function save_extra_profile_fields( $user_id ) {
//	if ( ! empty( $_POST['first_name'] )) {
//		update_user_meta( $user_id, 'division_drop_down', $_POST['division_drop_down']);	
//	}
//}
//Function ends here
//PeterY code ends here
add_filter( 'wp_mail_from', 'wpb_sender_email' );
add_filter( 'wp_mail_from_name', 'wpb_sender_name');
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
//the above is for updating and saving to usermeta
add_action( 'register_form', 'extra_profile_fields');
add_filter( 'registration_errors', 'validate_extra_profile_fields', 10, 3);
add_action( 'user_register', 'save_extra_profile_fields');
add_action( 'login_init', 'my_wp_new_user_notification_init' );
function my_wp_new_user_notification_init() {
	add_filter( 'wp_new_user_notification_email', 'my_wp_new_user_notification_email', 10, 3 );
}
function my_wp_new_user_notification_email( $wp_new_user_notification_email, $user_id, $blogname ) {
	global $wpdb, $wp_hasher;
    $user = get_userdata( $user_id );
	error_log('the user_id shows ' . $user_id);
	$key = wp_generate_password( 20, false );
	do_action( 'retrieve_password_key', $user->user_login, $key );
	// Now insert the key, hashed, into the DB.
    if ( empty( $wp_hasher ) ) {
        require_once ABSPATH . WPINC . '/class-phpass.php';
        $wp_hasher = new PasswordHash( 8, true );
    }
    $hashed = time() . ':' . $wp_hasher->HashPassword( $key );
    $wpdb->update( $wpdb->users, array( 'user_activation_key' => $hashed ), array( 'user_login' => $user->user_login ) );
	$message = sprintf(__('Your new username is: %s'), $user->user_login) . "\r\n";
	$message .= __('To set your password and complete registration, visit the following URL:') . "\r\n\r\n";
	$message .= '<' .network_site_url("wp-login.php?action=rp&key=$key&login=" . rawurlencode($user->user_login), 'login') . ">\r\n\r\n";
	$message .= sprintf(__('你的新用戶名: %s') . $user->user_login) . "\r\n\r\n";
	$message .= __('請點撃以的網址，更改密碼，以便完成證記新用戶名：') . "\r\n\r\n";
	$message .= '<' .network_site_url("wp-login.php?action=rp&key=$key&login=" . rawurlencode($user->user_login), 'login') . ">\r\n\r\n";
	$wp_new_user_notification_email['subject'] = sprintf( ' Registration email', $blogname, $user->user_login );
	$wp_new_user_notification_email['message'] = $message;
	return $wp_new_user_notification_email;
	}
//PeterY code ends here

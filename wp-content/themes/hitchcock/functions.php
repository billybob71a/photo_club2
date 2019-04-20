<?php


/* ---------------------------------------------------------------------------------------------
   THEME SETUP
   --------------------------------------------------------------------------------------------- */


if ( ! function_exists( 'hitchcock_setup' ) ) {

	function hitchcock_setup() {
		
		// Automatic feed
		add_theme_support( 'automatic-feed-links' );
		
		// Set content-width
		global $content_width;
		if ( ! isset( $content_width ) ) $content_width = 600;
		
		// Post thumbnails
		add_theme_support( 'post-thumbnails' );
		add_image_size( 'post-image', 1240, 9999 );
		add_image_size( 'post-thumb', 508, 9999 );
		
		// Title tag
		add_theme_support( 'title-tag' );

		// Custom logo
		add_theme_support( 'custom-logo' );
		
		// Custom header
		$args = array(
			'width'         => 1440,
			'height'        => 900,
			'default-image' => get_template_directory_uri() . '/images/bg.jpg',
			'uploads'       => true,
			'header-text'  	=> false
			
		);
		add_theme_support( 'custom-header', $args );
		
		// Post formats
		add_theme_support( 'post-formats', array( 'gallery' ) );
			
		// Jetpack infinite scroll
		add_theme_support( 'infinite-scroll', array(
			'type' 		=> 'click',
			'container'	=> 'posts',
			'wrapper'	=> false,
			'footer' 	=> false,
		) );
		
		// Add nav menu
		register_nav_menu( 'primary', __( 'Primary Menu', 'hitchcock' ) );
		register_nav_menu( 'social', __( 'Social Menu', 'hitchcock' ) );
		
		// Make the theme translation ready
		load_theme_textdomain( 'hitchcock', get_template_directory() . '/languages' );
		
		$locale = get_locale();
		$locale_file = get_template_directory() . "/languages/$locale.php";
		if ( is_readable($locale_file) ) {
			require_once($locale_file);
		}
		
	}
	add_action( 'after_setup_theme', 'hitchcock_setup' );

}


/* ---------------------------------------------------------------------------------------------
   ENQUEUE SCRIPTS
   --------------------------------------------------------------------------------------------- */


if ( ! function_exists( 'hitchcock_load_javascript_files' ) ) {

	function hitchcock_load_javascript_files() {

		if ( ! is_admin() ) {		
			wp_register_script( 'hitchcock_flexslider', get_template_directory_uri() . '/js/flexslider.js', '', true );
			wp_register_script( 'hitchcock_doubletaptogo', get_template_directory_uri() . '/js/doubletaptogo.js', '', true );

			wp_enqueue_script( 'hitchcock_global', get_template_directory_uri() . '/js/global.js', array( 'jquery', 'hitchcock_flexslider', 'hitchcock_doubletaptogo' ), '', true );
			
			if ( is_singular() ) wp_enqueue_script( 'comment-reply' );
			
		}
	}
	add_action( 'wp_enqueue_scripts', 'hitchcock_load_javascript_files' );

}


/* ---------------------------------------------------------------------------------------------
   ENQUEUE STYLES
   --------------------------------------------------------------------------------------------- */


if ( ! function_exists( 'hitchcock_load_style' ) ) {

	function hitchcock_load_style() {
		if ( ! is_admin() ) {

			$dependencies = array();

			/**
			 * Translators: If there are characters in your language that are not
			 * supported by the theme fonts, translate this to 'off'. Do not translate
			 * into your own language.
			 */
			$google_fonts = _x( 'on', 'Google Fonts: on or off', 'hitchcock' );

			if ( 'off' !== $google_fonts ) {

				// Register Google Fonts
				wp_register_style( 'hitchcock_google_fonts', '//fonts.googleapis.com/css?family=Montserrat:400,400italic,500,600,700,700italic|Droid+Serif:400,400italic,700,700italic', false, 1.0, 'all' );
				$dependencies[] = 'hitchcock_google_fonts';

			}

			wp_register_style( 'hitchcock_fontawesome', get_stylesheet_directory_uri() . '/fa/css/font-awesome.css' );
			$dependencies[] = 'hitchcock_fontawesome';
			
			wp_enqueue_style( 'hitchcock_style', get_stylesheet_uri(), $dependencies );
		}
	}
	add_action( 'wp_print_styles', 'hitchcock_load_style' );

}


/* ---------------------------------------------------------------------------------------------
   ADD EDITOR STYLES
   --------------------------------------------------------------------------------------------- */


if ( ! function_exists( 'hitchcock_add_editor_styles' ) ) {

	function hitchcock_add_editor_styles() {

		add_editor_style( 'hitchcock-editor-styles.css' );

		$dependencies = array();

		/**
		 * Translators: If there are characters in your language that are not
		 * supported by the theme fonts, translate this to 'off'. Do not translate
		 * into your own language.
		 */
		$google_fonts = _x( 'on', 'Google Fonts: on or off', 'hitchcock' );

		if ( 'off' !== $google_fonts ) {

			$font_url = '//fonts.googleapis.com/css?family=Montserrat:400,400italic,500,600,700,700italic|Droid+Serif:400,400italic,700,700italic';
			add_editor_style( str_replace( ', ', '%2C', $font_url ) );

		}
		
	}
	add_action( 'init', 'hitchcock_add_editor_styles' );

}


/* ---------------------------------------------------------------------------------------------
   CHECK JAVASCRIPT SUPPORT
   --------------------------------------------------------------------------------------------- */


if ( ! function_exists( 'hitchcock_html_js_class' ) ) {

	function hitchcock_html_js_class() {
		echo '<script>document.documentElement.className = document.documentElement.className.replace("no-js","js");</script>'. "\n";
	}
	add_action( 'wp_head', 'hitchcock_html_js_class', 1 );

}


/* ---------------------------------------------------------------------------------------------
   ARCHIVE NAVIGATION
   --------------------------------------------------------------------------------------------- */


if ( ! function_exists( 'hitchcock_archive_navigation' ) ) {

	function hitchcock_archive_navigation() {
		
		global $wp_query;
		
		if ( $wp_query->max_num_pages > 1 ) : ?>
					
			<div class="archive-nav">
				
				<?php 
				if ( get_previous_posts_link() ) {
					previous_posts_link( '<span class="fa fw fa-angle-left"></span>' );
				} else {
					echo '<span class="fa fw fa-angle-left"></span>';
				} 

				echo '<span class="sep">/</span>';

				if ( get_next_posts_link() ) {
					next_posts_link( '<span class="fa fw fa-angle-right"></span>' );
				} else {
					echo '<span class="fa fw fa-angle-right"></span>';
				} 
				?>
				
				<div class="clear"></div>
					
			</div><!-- .archive-nav-->
							
		<?php endif;
	}

}


/* ---------------------------------------------------------------------------------------------
   CUSTOM LOGO OUTPUT
   --------------------------------------------------------------------------------------------- */


if ( ! function_exists( 'hitchcock_custom_logo' ) ) {

	function hitchcock_custom_logo() {

		// Get the logo
		$logo = wp_get_attachment_image_src( get_theme_mod( 'custom_logo' ), 'full' );
		
		if ( $logo ) {

			// For clarity
			$logo_url = esc_url( $logo[0] );
			$logo_width = esc_attr( $logo[1] );
			$logo_height = esc_attr( $logo[2] );

			// If the retina logo setting is active, reduce the width/height by half
			if ( get_theme_mod( 'hitchcock_retina_logo' ) ) {
				$logo_width = floor( $logo_width / 2 );
				$logo_height = floor( $logo_height / 2 );
			}

			?>
			
			<a href="<?php echo esc_url( home_url() ); ?>" title="<?php bloginfo( 'name' ); ?>" class="custom-logo-link">
				<img src="<?php echo esc_url( $logo_url ); ?>" width="<?php echo esc_attr( $logo_width ); ?>" height="<?php echo esc_attr( $logo_height ); ?>" />
			</a>

			<?php
		}

	}

}


/* ---------------------------------------------------------------------------------------------
   ADMIN CSS
   --------------------------------------------------------------------------------------------- */


if ( ! function_exists( 'hitchcock_admin_css' ) ) {

	function hitchcock_admin_css() { ?>
		<style type="text/css">
			#postimagediv #set-post-thumbnail img {
				max-width: 100%;
				height: auto;
			}
		</style>
		<?php
	}
	add_action( 'admin_head', 'hitchcock_admin_css' );

}


/* ---------------------------------------------------------------------------------------------
   BODY CLASSES
   --------------------------------------------------------------------------------------------- */

 
if ( ! function_exists( 'hitchcock_body_classes' ) ) {

	function hitchcock_body_classes( $classes ) {
	
		// Check if we're on singular
		if ( is_singular() || is_404() || ( is_search() && ! have_posts() ) ) {
			$classes[] = 'post single';
		}

		// Check if we're in the WP customizer preview
		if ( is_customize_preview() ) {
			$classes[] = 'customizer-preview';
		}

		// Check whether we're always showing preview titles
		if ( get_theme_mod( 'hitchcock_show_titles' ) ) {
			$classes[] = 'show-preview-titles';
		}

		// Check if we're on mobile
		if ( wp_is_mobile() ) {
			$classes[] = 'wp-is-mobile';
		}
		
		return $classes;
	}
	add_filter( 'body_class', 'hitchcock_body_classes' );

}


/* ---------------------------------------------------------------------------------------------
   FLEXSLIDER FUNCTION
   --------------------------------------------------------------------------------------------- */


if ( ! function_exists( 'hitchcock_flexslider' ) ) {

	function hitchcock_flexslider( $size ) {

		$attachment_parent = is_page() ? $post->ID : get_the_ID();

		$images = get_posts( array(
			'numberposts'    => -1,
			'orderby'        => 'menu_order',
			'order'          => 'ASC',
			'post_parent'    => $attachment_parent,
			'post_type'      => 'attachment',
			'post_status'    => null,
			'post_mime_type' => 'image',
		) );

		if ( $images ) { ?>
		
			<div class="flexslider">
			
				<ul class="slides">
		
					<?php foreach( $images as $image ) { 
					
						$attimg = wp_get_attachment_image( $image->ID, $size ); ?>
						
						<li>
							<?php echo $attimg; ?>
						</li>
						
					<?php } ?>
			
				</ul>
				
			</div><?php
			
		}
	}

}


/* ---------------------------------------------------------------------------------------------
   RELATED POSTS FUNCTION
   --------------------------------------------------------------------------------------------- */


if ( ! function_exists( 'hitchcock_related_posts' ) ) {

	function hitchcock_related_posts( $number_of_posts = 3 ) { ?>
		
		<div class="related-posts posts section-inner">
					
			<?php

			global $post;

			// Base args, used for both the term query and random query
			$base_args = array(
				'ignore_sticky_posts'	=>	true,
				'meta_key'				=>	'_thumbnail_id',
				'posts_per_page'		=>	$number_of_posts,
				'post_status'			=>	'publish',
				'post__not_in'			=>	array( $post->ID ),	
			);

			// Create a query for posts in the same category as the ones for the current post
			$cat_ids = array();

			$categories = get_the_category();

			foreach( $categories as $category ) {
				$cat_ids[] = $category->cat_ID;
			}

			$term_posts_args = array_merge( $base_args, array( 'category__in' => $cat_ids ) );
			
			$related_posts = get_posts( $term_posts_args );

			// No results for the categories? Get random posts instead
			if ( ! $related_posts ) :

				$random_posts_args = array_merge( $base_args, array( 'orderby' => 'rand' ) );

				$related_posts = get_posts( $random_posts_args );

			endif;

			// If either the category query or random query hit pay dirt, output the posts
			if ( $related_posts ) :
				
				foreach( $related_posts as $post ) :
			
					setup_postdata( $post );

					get_template_part( 'content', get_post_format() );

				endforeach;

				wp_reset_postdata();
			
			endif;
			
			?>
					
			<div class="clear"></div>

		</div><!-- .related-posts --> 

		<?php
		
	}

}


/* ---------------------------------------------------------------------------------------------
   COMMENT FUNCTION
   --------------------------------------------------------------------------------------------- */


if ( ! function_exists( 'hitchcock_comment' ) ) {

	function hitchcock_comment( $comment, $args, $depth ) {
		switch ( $comment->comment_type ) :
			case 'pingback' :
			case 'trackback' :
		?>
		
		<li <?php comment_class(); ?> id="comment-<?php comment_ID(); ?>">
		
			<?php __( 'Pingback:', 'hitchcock' ); ?> <?php comment_author_link(); ?> <?php edit_comment_link( __( 'Edit', 'hitchcock' ), '<span class="edit-link">', '</span>' ); ?>
			
		</li>
		<?php
				break;
			default :
			global $post;
		?>
		<li <?php comment_class(); ?> id="li-comment-<?php comment_ID(); ?>">
		
			<div id="comment-<?php comment_ID(); ?>" class="comment">
				
				<h4 class="comment-title">
					<?php echo get_comment_author_link(); ?>
					<span><a class="comment-date-link" href="<?php echo esc_url( get_comment_link( $comment->comment_ID ) ) ?>" title="<?php echo get_comment_date() . ' at ' . get_comment_time(); ?>"><?php echo get_comment_date( get_option( 'date_format' ) ); ?></a>
					<?php
					if ( $post == get_post( $post->ID ) ) {
						if ( $comment->user_id === $post->post_author )
						echo ' &mdash; ' . __( 'Post Author', 'hitchcock' );
					}
					?>
					</span>
				</h4>
										
				<div class="comment-content post-content">
				
					<?php comment_text(); ?>
					
				</div><!-- .comment-content -->
				
				<div class="comment-actions">
								
					<?php 

					comment_reply_link( array( 
						'reply_text' 	=> __( 'Reply', 'hitchcock' ),
						'depth'			=> $depth, 
						'max_depth' 	=> $args['max_depth'],
						'before'		=> '',
						'after'			=> ''
					) ); 

					edit_comment_link( __( 'Edit', 'hitchcock' ), '', '' );
					
					if ( 0 == $comment->comment_approved ) : ?>
					
						<p class="comment-awaiting-moderation fright"><?php _e( 'Your comment is awaiting moderation.', 'hitchcock' ); ?></p>
						
					<?php endif; ?>
									
				</div><!-- .comment-actions -->
											
			</div><!-- .comment-## -->
					
		<?php
			break;
		endswitch;
	}

}


/* ---------------------------------------------------------------------------------------------
   THEME OPTIONS
   --------------------------------------------------------------------------------------------- */


class hitchcock_customize {

	public static function hitchcock_register( $wp_customize ) {

		// Hitchcock theme options section
		$wp_customize->add_section( 'hitchcock_options', array(
			'title' 		=> __( 'Theme Options', 'hitchcock' ),
			'priority' 		=> 35,
			'capability' 	=> 'edit_theme_options',
			'description' 	=> __( 'Customize the theme settings for Hitchcock.', 'hitchcock' ),
		) );


		/* 2X Header Logo ----------------------------- */


		$wp_customize->add_setting( 'hitchcock_retina_logo', array(
			'capability' 		=> 'edit_theme_options',
			'sanitize_callback' => 'hitchcock_sanitize_checkbox',
			'transport'			=> 'postMessage'
		) );

		$wp_customize->add_control( 'hitchcock_retina_logo', array(
			'type' 			=> 'checkbox',
			'section' 		=> 'title_tagline',
			'priority'		=> 9,
			'label' 		=> __( 'Retina logo', 'hitchcock' ),
			'description' 	=> __( 'Scales the logo to half its uploaded size, making it sharp on high-res screens.', 'hitchcock' ),
		) );


		/* Always show titles setting ----------------------------- */


		$wp_customize->add_setting( 'hitchcock_show_titles', array(
			'capability' 		=> 'edit_theme_options',
			'sanitize_callback' => 'hitchcock_sanitize_checkbox',
			'transport'			=> 'postMessage'
		) );

		$wp_customize->add_control( 'hitchcock_show_titles', array(
			'type' 			=> 'checkbox',
			'section' 		=> 'hitchcock_options', 
			'label' 		=> __( 'Show Preview Titles', 'hitchcock' ),
			'description' 	=> __( 'Check to always show the titles in the post previews.', 'hitchcock' ),
		) );


		/* Custom accent color ----------------------------- */


		$wp_customize->add_setting( 'hitchcock_accent_color', array(
			'default' 			=> '#3bc492', 
			'type' 				=> 'theme_mod', 
			'transport' 		=> 'postMessage', 
			'sanitize_callback' => 'sanitize_hex_color'
		) );

		$wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'hitchcock_accent_color', array(
			'label' 	=> __( 'Accent Color', 'hitchcock' ), 
			'section' 	=> 'hitchcock_options',
			'settings' 	=> 'hitchcock_accent_color', 
		) ) );

		// Make built-in controls use live-JS preview
		$wp_customize->get_setting( 'blogname' )->transport = 'postMessage';
		$wp_customize->get_setting( 'blogdescription' )->transport = 'postMessage';


		// SANITATION

		// Sanitize boolean for checkbox
		function hitchcock_sanitize_checkbox( $checked ) {
			return ( ( isset( $checked ) && true == $checked ) ? true : false );
		}

	}

	public static function hitchcock_header_output() {

		echo '<!-- Customizer CSS -->';

		echo '<style type="text/css">';
		
			self::hitchcock_generate_css( 'body a', 'color', 'hitchcock_accent_color' );
			self::hitchcock_generate_css( 'body a:hover', 'color', 'hitchcock_accent_color' );

			self::hitchcock_generate_css( '.blog-title a:hover', 'color', 'hitchcock_accent_color' );
			self::hitchcock_generate_css( '.social-menu a:hover', 'background', 'hitchcock_accent_color' );
			self::hitchcock_generate_css( '.post:hover .archive-post-title', 'color', 'hitchcock_accent_color' );

			self::hitchcock_generate_css( '.post-content a', 'color', 'hitchcock_accent_color' );
			self::hitchcock_generate_css( '.post-content a:hover', 'color', 'hitchcock_accent_color' );
			self::hitchcock_generate_css( '.post-content a:hover', 'border-bottom-color', 'hitchcock_accent_color' );
			self::hitchcock_generate_css( '.post-content p.pull', 'color', 'hitchcock_accent_color' );
			self::hitchcock_generate_css( '.post-content input[type="submit"]', 'background', 'hitchcock_accent_color' );
			self::hitchcock_generate_css( '.post-content input[type="button"]', 'background', 'hitchcock_accent_color' );
			self::hitchcock_generate_css( '.post-content input[type="reset"]', 'background', 'hitchcock_accent_color' );
			self::hitchcock_generate_css( '.post-content input:focus', 'border-color', 'hitchcock_accent_color' );
			self::hitchcock_generate_css( '.post-content textarea:focus', 'border-color', 'hitchcock_accent_color' );

			self::hitchcock_generate_css( '.post-content .has-accent-color', 'color', 'accent_color' );
			self::hitchcock_generate_css( '.post-content .has-accent-background-color', 'background-color', 'accent_color' );

			self::hitchcock_generate_css( '.button', 'background', 'hitchcock_accent_color' );
			self::hitchcock_generate_css( '.page-links a:hover', 'background', 'hitchcock_accent_color' );
			self::hitchcock_generate_css( '.comments .pingbacks li a:hover', 'color', 'hitchcock_accent_color' );
			self::hitchcock_generate_css( '.comment-header h4 a:hover', 'color', 'hitchcock_accent_color' );
			self::hitchcock_generate_css( '.comment-form input:focus', 'border-color', 'hitchcock_accent_color' );
			self::hitchcock_generate_css( '.comment-form textarea:focus', 'border-color', 'hitchcock_accent_color' );
			self::hitchcock_generate_css( '.form-submit #submit', 'background-color', 'hitchcock_accent_color' );
			self::hitchcock_generate_css( '.comment-title .url:hover', 'color', 'hitchcock_accent_color' );
			self::hitchcock_generate_css( '.comment-actions a', 'color', 'hitchcock_accent_color' );
			self::hitchcock_generate_css( '.comment-actions a:hover', 'color', 'hitchcock_accent_color' );
			self::hitchcock_generate_css( '.archive-nav a:hover', 'color', 'hitchcock_accent_color' );
			self::hitchcock_generate_css( '#infinite-handle:hover', 'background', 'hitchcock_accent_color' );
			self::hitchcock_generate_css( '.credits p:first-child a:hover', 'color', 'hitchcock_accent_color' );

			self::hitchcock_generate_css( '.nav-toggle.active .bar', 'background-color', 'hitchcock_accent_color' );
			self::hitchcock_generate_css( '.mobile-menu a:hover', 'color', 'hitchcock_accent_color' );

		echo '</style>';

		echo '<!-- /Customizer CSS -->';

	}

	public static function hitchcock_live_preview() {
		wp_enqueue_script( 'hitchcock-themecustomizer', get_template_directory_uri() . '/js/theme-customizer.js', array(  'jquery', 'customize-preview' ), '', true );
	}

	public static function hitchcock_generate_css( $selector, $style, $mod_name, $prefix='', $postfix='', $echo = true ) {
		$return = '';
		$mod = get_theme_mod( $mod_name );

		if ( ! empty( $mod ) ) { 

			$return = sprintf( '%s { %s:%s; }', $selector, $style, $prefix . $mod . $postfix );

			if ( $echo ) echo $return;

		}

		return $return;
	}
}

// Setup the Theme Customizer settings and controls...
add_action( 'customize_register', array( 'hitchcock_customize', 'hitchcock_register' ) );

// Output custom CSS to live site
add_action( 'wp_head', array( 'hitchcock_customize', 'hitchcock_header_output' ) );

// Enqueue live preview javascript in Theme Customizer admin screen
add_action( 'customize_preview_init', array( 'hitchcock_customize', 'hitchcock_live_preview' ) );


/* ---------------------------------------------------------------------------------------------
   SPECIFY GUTENBERG SUPPORT
------------------------------------------------------------------------------------------------ */


if ( ! function_exists( 'hitchcock_add_gutenberg_features' ) ) :

	function hitchcock_add_gutenberg_features() {

		/* Gutenberg Features --------------------------------------- */

		add_theme_support( 'align-wide' );

		/* Gutenberg Palette --------------------------------------- */

		$accent_color = get_theme_mod( 'accent_color' ) ? get_theme_mod( 'accent_color' ) : '#3bc492';

		add_theme_support( 'editor-color-palette', array(
			array(
				'name' 	=> _x( 'Accent', 'Name of the accent color in the Gutenberg palette', 'hitchcock' ),
				'slug' 	=> 'accent',
				'color' => $accent_color,
			),
			array(
				'name' 	=> _x( 'Black', 'Name of the black color in the Gutenberg palette', 'hitchcock' ),
				'slug' 	=> 'black',
				'color' => '#1d1d1d',
			),
			array(
				'name' 	=> _x( 'Dark Gray', 'Name of the dark gray color in the Gutenberg palette', 'hitchcock' ),
				'slug' 	=> 'dark-gray',
				'color' => '#555',
			),
			array(
				'name' 	=> _x( 'Medium Gray', 'Name of the medium gray color in the Gutenberg palette', 'hitchcock' ),
				'slug' 	=> 'medium-gray',
				'color' => '#777',
			),
			array(
				'name' 	=> _x( 'Light Gray', 'Name of the light gray color in the Gutenberg palette', 'hitchcock' ),
				'slug' 	=> 'light-gray',
				'color' => '#999',
			),
			array(
				'name' 	=> _x( 'White', 'Name of the white color in the Gutenberg palette', 'hitchcock' ),
				'slug' 	=> 'white',
				'color' => '#fff',
			),
		) );

		/* Gutenberg Font Sizes --------------------------------------- */

		add_theme_support( 'editor-font-sizes', array(
			array(
				'name' 		=> _x( 'Small', 'Name of the small font size in Gutenberg', 'hitchcock' ),
				'shortName' => _x( 'S', 'Short name of the small font size in the Gutenberg editor.', 'hitchcock' ),
				'size' 		=> 14,
				'slug' 		=> 'small',
			),
			array(
				'name' 		=> _x( 'Regular', 'Name of the regular font size in Gutenberg', 'hitchcock' ),
				'shortName' => _x( 'M', 'Short name of the regular font size in the Gutenberg editor.', 'hitchcock' ),
				'size' 		=> 16,
				'slug' 		=> 'regular',
			),
			array(
				'name' 		=> _x( 'Large', 'Name of the large font size in Gutenberg', 'hitchcock' ),
				'shortName' => _x( 'L', 'Short name of the large font size in the Gutenberg editor.', 'hitchcock' ),
				'size' 		=> 21,
				'slug' 		=> 'large',
			),
			array(
				'name' 		=> _x( 'Larger', 'Name of the larger font size in Gutenberg', 'hitchcock' ),
				'shortName' => _x( 'XL', 'Short name of the larger font size in the Gutenberg editor.', 'hitchcock' ),
				'size' 		=> 26,
				'slug' 		=> 'larger',
			),
		) );

	}
	add_action( 'after_setup_theme', 'hitchcock_add_gutenberg_features' );

endif;


/* ---------------------------------------------------------------------------------------------
   GUTENBERG EDITOR STYLES
   --------------------------------------------------------------------------------------------- */


if ( ! function_exists( 'hitchcock_block_editor_styles' ) ) :

	function hitchcock_block_editor_styles() {

		$dependencies = array();

		/**
		 * Translators: If there are characters in your language that are not
		 * supported by the theme fonts, translate this to 'off'. Do not translate
		 * into your own language.
		 */
		$google_fonts = _x( 'on', 'Google Fonts: on or off', 'hitchcock' );

		if ( 'off' !== $google_fonts ) {

			// Register Google Fonts
			wp_register_style( 'hitchcock-block-editor-styles-font', '//fonts.googleapis.com/css?family=Montserrat:400,400italic,50,500,600,700,700italic|Droid+Serif:400,400italic,700,700italic', false, 1.0, 'all' );
			$dependencies[] = 'hitchcock-block-editor-styles-font';

		}

		// Enqueue the editor styles
		wp_enqueue_style( 'hitchcock-block-editor-styles', get_theme_file_uri( '/hitchcock-gutenberg-editor-style.css' ), $dependencies, '1.0', 'all' );

	}
	add_action( 'enqueue_block_editor_assets', 'hitchcock_block_editor_styles', 1 );

endif;
/*Peter Y custom functions
custom function to make home page transparent*/
function add_petery_script() {
    wp_register_script('petery_script', home_url() . '/wp-content/themes/hitchcock/js/petery_script.js');
	wp_register_script('my-script', home_url() . '/wp-content/themes/hitchcock/js/my-script.js');
    wp_enqueue_script('petery_script');
	wp_enqueue_script('my-script');
}  
add_action( 'wp_enqueue_scripts', 'add_petery_script' );
//Adding a widget sidebar
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
/*********************************************************************
 *PeterY code
 *********************************************************************/

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
        $errors->add( 'no_division_error', __('<strong>ERROR</Strong>:You must select Junior or Senior for Photo Contest Division', 'mydomain') );}
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
?>
<?php
/**
 * Sundance functions and definitions
 *
 * Sets up the theme and provides some helper functions. Some helper functions
 * are used in the theme as custom template tags. Others are attached to action and
 * filter hooks in WordPress to change core functionality.
 *
 * The first function, sundance_setup(), sets up the theme by registering support
 * for various features in WordPress, such as post thumbnails, navigation menus, and the like.
 *
 * When using a child theme (see http://codex.wordpress.org/Theme_Development and
 * http://codex.wordpress.org/Child_Themes), you can override certain functions
 * (those wrapped in a function_exists() call) by defining them first in your child theme's
 * functions.php file. The child theme's functions.php file is included before the parent
 * theme's file, so the child theme functions would be used.
 *
 * Functions that are not pluggable (not wrapped in function_exists()) are instead attached
 * to a filter or action hook. The hook can be removed by using remove_action() or
 * remove_filter() and you can attach your own function to the hook.
 *
 * We can remove the parent theme's hook only after it is attached, which means we need to
 * wait until setting up the child theme:
 *
 * <code>
 * add_action( 'after_setup_theme', 'my_child_theme_setup' );
 * function my_child_theme_setup() {
 *     // We are providing our own filter for excerpt_length (or using the unfiltered value)
 *     remove_filter( 'excerpt_length', 'sundance_excerpt_length' );
 *     ...
 * }
 * </code>
 *
 * For more information on hooks, actions, and filters, see http://codex.wordpress.org/Plugin_API.
 *
 * @package Sundance
 * @subpackage Sundance
 * @since Sundance 2.0
 */

//if ( !session_id() )
//	add_action( 'init', 'session_start' );

/**
 * Set the content width based on the theme's design and stylesheet.
 *
 * Used to set the width of images and content. Should be equal to the width the theme
 * is designed for, generally via the style.css stylesheet.
 */
if ( ! isset( $content_width ) )
	$content_width = 640;

if ( ! isset( $tubcats ) )
	$tubcats = array();

/** Tell WordPress to run sundance_setup() when the 'after_setup_theme' hook is run. */
add_action( 'after_setup_theme', 'sundance_setup' );

if ( ! function_exists( 'sundance_setup' ) ):
/**
 * Sets up theme defaults and registers support for various WordPress features.
 *
 * Note that this function is hooked into the after_setup_theme hook, which runs
 * before the init hook. The init hook is too late for some features, such as indicating
 * support post thumbnails.
 *
 * To override sundance_setup() in a child theme, add your own sundance_setup to your child theme's
 * functions.php file.
 *
 * @uses add_theme_support() To add support for post thumbnails and automatic feed links.
 * @uses register_nav_menus() To add support for navigation menus.
 * @uses add_custom_background() To add support for a custom background.
 * @uses add_editor_style() To style the visual editor.
 * @uses load_theme_textdomain() For translation/localization support.
 * @uses add_custom_image_header() To add support for a custom header.
 * @uses register_default_headers() To register the default custom header images provided with the theme.
 * @uses set_post_thumbnail_size() To set a custom post thumbnail size.
 *
 * @since Sundance 2.0
 */
function sundance_setup() {

	// This theme uses post thumbnails
	add_theme_support( 'post-thumbnails' );
	add_image_size( 'slide-large', 728, 320, true );
	add_image_size( 'overhead-large', 307, 305, false );
	add_image_size( 'overhead-mid', 202, 201, false );
	add_image_size( 'overhead-small', 101, 103, true );
	add_image_size( 'swatch-large', 120, 120, false );
	add_image_size( 'swatch-small', 29, 29, false );
	add_image_size( 'blog-mid', 211, 159, true );
	add_image_size( 'blog-thm', 93, 70, true );
	add_image_size( 'accthm', 140, 125, true );
	add_image_size( 'banner-full', 960, 285, true );

	// This theme uses wp_nav_menu() in one location.
	register_nav_menus( array(
		'topres' => __( 'Header Top Line', 'sundance' ),
		'ft1' => __( 'Footer : Line 1', 'sundance' ),
		'ft2' => __( 'Footer : Line 2', 'sundance' ),
		'ftres' => __( 'Footer : Resources', 'sundance' ),
	) );
	
	// add custom actions
	add_action( 'init', 'sundance_init' );
	add_action( 'widgets_init', 'sundance_widgets_init' );
	add_action('save_post', 'sundance_meta_save');
	
	add_action('admin_menu', 'sundance_admin_menu_cleanup');
	add_action('wp_print_scripts', 'sundance_add_scripts');
	add_action('wp_print_styles', 'sundance_add_styles');
	add_action( 'login_head', 'sundance_custom_login_logo' );
	add_action( 'login_headerurl', 'sundance_custom_login_url' );
	
	// cleanup some
	remove_action( 'wp_head', 'feed_links_extra', 3 );
	$bye = array( 'rsd_link', 'wlwmanifest_link', 'wp_generator' );
	foreach ( $bye as $b ) remove_action( 'wp_head', $b );
	
	// add custom filters
	add_filter( 'wp_page_menu_args', 'sundance_page_menu_args' );
	add_filter( 'excerpt_length', 'sundance_excerpt_length' );
	add_filter( 'excerpt_more', 'sundance_auto_excerpt_more' );
	add_filter( 'get_the_excerpt', 'sundance_custom_excerpt_more' );	
	add_filter( 'body_class', 'sundance_body_class' );
	add_filter('embed_oembed_html', 'sundance_embed', 10, 3);
	
	global $tubcats;
	sundance_series_setup();
}
endif;

/**
 * Custom query vars to replace $_GET[] for SDS live
 */
function add_query_vars_filter( $vars ){
  $vars[] = "the_ip";
  return $vars;
}
add_filter( 'query_vars', 'add_query_vars_filter' );


/**
 * Sets the post excerpt length to 40 characters.
 *
 * To override this length in a child theme, remove the filter and add your own
 * function tied to the excerpt_length filter hook.
 *
 * @since Sundance 2.0
 * @return int
 */
function sundance_excerpt_length( $length ) {
	return 40;
}

/**
 * Returns a "Continue Reading" link for excerpts
 *
 * @since Sundance 2.0
 * @return string "Continue Reading" link
 */
function sundance_continue_reading_link() {
	return ' <a href="'. get_permalink() . '">' . __( 'Continue reading <span class="meta-nav">&rarr;</span>', 'sundance' ) . '</a>';
}

/**
 * Replaces "[...]" (appended to automatically generated excerpts) with an ellipsis and sundance_continue_reading_link().
 *
 * To override this in a child theme, remove the filter and add your own
 * function tied to the excerpt_more filter hook.
 *
 * @since Sundance 2.0
 * @return string An ellipsis
 */
function sundance_auto_excerpt_more( $more ) {
	return ' &hellip;' . sundance_continue_reading_link();
}

/**
 * Adds a pretty "Continue Reading" link to custom post excerpts.
 *
 * To override this link in a child theme, remove the filter and add your own
 * function tied to the get_the_excerpt filter hook.
 *
 * @since Sundance 2.0
 * @return string Excerpt with a pretty "Continue Reading" link
 */
function sundance_custom_excerpt_more( $output ) {
	if ( has_excerpt() && ! is_attachment() ) {
		$output .= sundance_continue_reading_link();
	}
	return $output;
}

/**
 * Get our wp_nav_menu() fallback, wp_page_menu(), to show a home link.
 *
 * To override this in a child theme, remove the filter and optionally add
 * your own function tied to the wp_page_menu_args filter hook.
 *
 * @since Sundance 1.0
 */
function sundance_page_menu_args( $args ) {
	$args['show_home'] = true;
	return $args;
}

function sundance_cleanclass( $classes, $ar = array('page'), $keepers = array('admin-bar') ) {
	foreach ( $classes as $k => $v ) {
		if ( !in_array($v, $keepers) ) unset($classes[$k]);
	}
	
	return array_merge($classes, $ar);
}

if ( ! function_exists( 'sundance_body_class' ) ):
/**
 * adds some additional classes to the <body> based on what page we're on
 * @param array of classes to add to the <body> tag
 * @since Sundance 1.0
 */
function sundance_body_class($classes) {
	
	global $wp_query;
	
	switch ( $wp_query->query_vars['post_type'] ) {
		case 's_spa':
			$classes = sundance_cleanclass( $classes, array('page', 'hot-tub-detail') );
			break;
		case 's_cat':
			if ( $wp_query->query_vars['s_cat'] == 'hot-tubs-and-spas' ) {
				$classes = sundance_cleanclass( $classes, array('page', 'hot-tub-landing') );
			} else {
				$classes = sundance_cleanclass( $classes, array('page', 'hot-tub-series') );
			}
			break;
        case 's_acc':
			$classes = sundance_cleanclass( $classes, array('page', 'accessories') );
			break;
		default:
			if ( is_page() ) {
				if ( is_page(array('accessories', 'features', 'colors', 'covers')) ) {
					$classes = sundance_cleanclass( $classes, array('page', 'accessories') );
				}
				if ( is_page(array('jets', 'lighting', 'stereos', 'controls')) ) {
					$classes = sundance_cleanclass( $classes, array('page', 'features') );
				}
				if ( is_page('site-map') ) {
					$classes = sundance_cleanclass( $classes, array('page', 'sitemap') );
				}
				if ( is_page('video-gallery') ) {
					$classes = sundance_cleanclass( $classes, array('page', 'video', 'blog') );
				}
				if ( is_page('backyard-ideas') ) {
					$classes = sundance_cleanclass( $classes, array('page', 'backyard-life') );
				}
				if ( is_page('installations') ) {
					$classes = sundance_cleanclass( $classes, array('page', 'backyard-life', 'backyard-life-installation') );
				}
				if ( is_page('faqs') ) {
					$classes = sundance_cleanclass( $classes, array('page', 'backyard-life', 'faq') );
				}
				if ( is_page('planning') ) {
					$classes = sundance_cleanclass( $classes, array('page', 'backyard-life-planning') );
				}
				if ( is_page('specials') ) {
					$classes = sundance_cleanclass( $classes, array('page', 'specials') );
				}
				if ( is_page_template('page-truckload.php') ) {
					$classes = sundance_cleanclass( $classes, array('page', 'truckload', 'conversion') );
				}
				if ( is_page_template('page-bigleft.php') || is_page_template('page-bigright.php') ) {
					$classes = sundance_cleanclass( $classes, array('page', 'health-benefits') );
				}
				if ( is_page_template('page-brochure1.php') ) {
					$classes = sundance_cleanclass( $classes, array('page', 'sundance-brochure', 'conversion', 'brochure1' ) );
				}
				if ( is_page_template('page-brochure2.php') ) {
					$classes = sundance_cleanclass( $classes, array('page', 'sundance-brochure', 'conversion', 'brochure2' ) );
				}
				if ( is_page_template('page-brochure3.php') || is_page_template('page-brochure4.php') ) {
					$classes = sundance_cleanclass( $classes, array('page', 'sundance-brochure', 'conversion', 'brochure3' ) );
				}
			}
			break;
	}
	
	if ( is_front_page() ) {
		$gone = array('page');
		foreach ( $classes as $k => $v ) {
			if ( in_array($v, $gone) ) {
				unset($classes[$k]);
			}
		}
	}
	
	if ( is_home() || is_category() || is_singular('post') ) {
		$classes = sundance_cleanclass( $classes, array('page', 'blog') );
	}
	
	if ( is_tax('s_acc_cat') ) {
		$classes = sundance_cleanclass( $classes, array('page', 'accessories') );
	}
	
	if ( is_404() ) {
		$classes = sundance_cleanclass( $classes, array('page', 'error404') );
	}
	
	if ( is_search() ) {
		$classes[] = 'page';
	}
	
	if ( sds_is_ca() ) {
		$classes[] = 'sds-canada';
	}
	
	return $classes;
}
endif;

if ( ! function_exists( 'sundance_comment' ) ) :
/**
 * Template for comments and pingbacks.
 *
 * To override this walker in a child theme without modifying the comments template
 * simply create your own sundance_comment(), and that function will be used instead.
 *
 * Used as a callback by wp_list_comments() for displaying the comments.
 *
 * @since Sundance 2.0
 */
function sundance_comment( $comment, $args, $depth ) {
	$GLOBALS['comment'] = $comment;
	switch ( $comment->comment_type ) :
		case '' :
	?>
	<li <?php comment_class(); ?> id="li-comment-<?php comment_ID(); ?>">
		<div id="comment-<?php comment_ID(); ?>">
		<div class="comment-author vcard">
			<?php echo get_avatar( $comment, 40 ); ?>
			<?php printf( __( '%s <span class="says">says:</span>', 'sundance' ), sprintf( '<cite class="fn">%s</cite>', get_comment_author_link() ) ); ?>
		</div><!-- .comment-author .vcard -->
		<?php if ( $comment->comment_approved == '0' ) : ?>
			<em class="comment-awaiting-moderation"><?php _e( 'Your comment is awaiting moderation.', 'sundance' ); ?></em>
			<br />
		<?php endif; ?>

		<div class="comment-meta commentmetadata"><a href="<?php echo esc_url( get_comment_link( $comment->comment_ID ) ); ?>">
			<?php
				/* translators: 1: date, 2: time */
				printf( __( '%1$s at %2$s', 'sundance' ), get_comment_date(),  get_comment_time() ); ?></a><?php edit_comment_link( __( '(Edit)', 'sundance' ), ' ' );
			?>
		</div><!-- .comment-meta .commentmetadata -->

		<div class="comment-body"><?php comment_text(); ?></div>

		<div class="reply">
			<?php comment_reply_link( array_merge( $args, array( 'depth' => $depth, 'max_depth' => $args['max_depth'] ) ) ); ?>
		</div><!-- .reply -->
	</div><!-- #comment-##  -->

	<?php
			break;
		case 'pingback'  :
		case 'trackback' :
	?>
	<li class="post pingback">
		<p><?php _e( 'Pingback:', 'sundance' ); ?> <?php comment_author_link(); ?><?php edit_comment_link( __( '(Edit)', 'sundance' ), ' ' ); ?></p>
	<?php
			break;
	endswitch;
}
endif;

/**
 * Register widgetized areas, including two sidebars and four widget-ready columns in the footer.
 *
 * To override sundance_widgets_init() in a child theme, remove the action hook and add your own
 * function tied to the init hook.
 *
 * @since Sundance 2.0
 * @uses register_sidebar
 */
function sundance_widgets_init() {
	// Area 1, located at the top of the sidebar.
	register_sidebar( array(
		'name' => __( 'Blog Sidebar', 'sundance' ),
		'id' => 'blog',
		'description' => __( 'Right Sidebar for the Blog area', 'sundance' ),
		'before_widget' => '<li id="%1$s" class="widget-container %2$s">',
		'after_widget' => '</li>',
		'before_title' => '<h3 class="widget-title">',
		'after_title' => '</h3>',
	) );
}
/** Register sidebars by running sundance_widgets_init() on the widgets_init hook. */
add_action( 'widgets_init', 'sundance_widgets_init' );

/**
 * Removes the default styles that are packaged with the Recent Comments widget.
 *
 * To override this in a child theme, remove the filter and optionally add your own
 * function tied to the widgets_init action hook.
 *
 * This function uses a filter (show_recent_comments_widget_style) new in WordPress 3.1
 * to remove the default style. Using Sundance 1.2 in WordPress 3.0 will show the styles,
 * but they won't have any effect on the widget in default Sundance styling.
 *
 * @since Sundance 2.0
 */
function sundance_remove_recent_comments_style() {
	add_filter( 'show_recent_comments_widget_style', '__return_false' );
}
add_action( 'widgets_init', 'sundance_remove_recent_comments_style' );

if ( ! function_exists( 'sundance_posted_on' ) ) :
/**
 * Prints HTML with meta information for the current post-date/time and author.
 *
 * @since Sundance 2.0
 */
function sundance_posted_on() {
	// Oct 5, 2011 &nbsp;|&nbsp; K. Lee
	printf( __( '%1$s &nbsp;|&nbsp; %2$s', 'sundance' ),
			get_the_date(),
			get_the_author()
	);
}
endif;

if ( ! function_exists( 'sundance_posted_in' ) ) :
/**
 * Prints HTML with meta information for the current post (category, tags and permalink).
 *
 * @since Sundance 2.0
 */
function sundance_posted_in() {
	// Retrieves tag list of current post, separated by commas.
	$tag_list = get_the_tag_list( '', ', ' );
	if ( $tag_list ) {
		$posted_in = __( 'This entry was posted in %1$s and tagged %2$s. Bookmark the <a href="%3$s" title="Permalink to %4$s" rel="bookmark">permalink</a>.', 'sundance' );
	} elseif ( is_object_in_taxonomy( get_post_type(), 'category' ) ) {
		$posted_in = __( 'This entry was posted in %1$s. Bookmark the <a href="%3$s" title="Permalink to %4$s" rel="bookmark">permalink</a>.', 'sundance' );
	} else {
		$posted_in = __( 'Bookmark the <a href="%3$s" title="Permalink to %4$s" rel="bookmark">permalink</a>.', 'sundance' );
	}
	// Prints the string, replacing the placeholders.
	printf(
		$posted_in,
		get_the_category_list( ', ' ),
		$tag_list,
		get_permalink(),
		the_title_attribute( 'echo=0' )
	);
}
endif;

function sundance_embed($oembvideo, $url, $attr) {
	if(strpos($url,'youtube.com') || strpos($url,'youtu.be') ) {
		if ( strpos( $oembedvideo, 'wmode' ) ) return $oembvideo;
		return str_replace('feature=oembed', 'feature=oembed&amp;wmode=opaque',$oembvideo);//
	}
	return $oembedvideo;
}

if ( ! function_exists( 'sundance_mbox_cleanup' ) ):
function sundance_mbox_cleanup() {
	global $wp_meta_boxes, $post_type, $post;
	
	switch($post_type) {
		case 's_color':
		case 's_cab':
			$wp_meta_boxes[$post_type]['side']['low']['postimagediv']['title'] = 'Featured (120x120 Swatch) Image';
			break;
		case 's_spa':
			$wp_meta_boxes[$post_type]['side']['low']['postimagediv']['title'] = 'Featured (728x320 Landing) Image';
			break;
		case 's_cat':
			$wp_meta_boxes[$post_type]['side']['low']['postimagediv']['title'] = 'Featured (728x206 Landing) Image';
			break;
			/*
		case 's_acc':
			$wp_meta_boxes[$post_type]['side']['low']['postimagediv']['title'] = '140x125 Landing Thumbnail';
			break;
			*/
		case 's_high':
		case 's_opt':
			$wp_meta_boxes[$post_type]['side']['low']['postimagediv']['title'] = 'Featured (140x137) Image';
			break;
		case 's_feat':
			$wp_meta_boxes[$post_type]['side']['low']['postimagediv']['title'] = 'Rollover (279x129) Image';
			break;
	}
}
endif;
add_action( 'do_meta_boxes', 'sundance_mbox_cleanup' );


function sundance_post_permalink( $permalink, $post, $leavename ) {
	global $wp_query, $wpsc_page_titles;
	$term_url = '';
	$rewritecode = array(
		'%series%',
		$leavename ? '' : '%postname%',
	);
	if ( is_object( $post ) ) {
		// In wordpress 2.9 we got a post object
		$post_id = $post->ID;
	} else {
		// In wordpress 3.0 we get a post ID
		$post_id = $post;
		$post = get_post( $post_id );
	}
	
	switch( $post->post_type ) {
		case 's_cat':
			$permalink = substr($permalink,0,strpos($permalink,'%s_none')) . $post->post_name .'/';
			break;
		case 's_spa':
			$custom = get_post_meta($post->ID,'s_cats');
			if ( is_array( $custom ) ) {
				if ( count( $custom ) > 0 ) {
					$tub_cats = $custom[0];
					if(is_array($tub_cats)) {
						$lastcat = get_post(absint( array_pop($tub_cats) ));
						$permalink = str_replace('%series%', $lastcat->post_name, $permalink);
					}
				}
			}
//			$permalink = str_replace('tubs', 'hot-tub', $permalink);
			break;
	}
	return $permalink;
}
add_filter( 'post_type_link', 'sundance_post_permalink', 1, 3 );


add_filter( 'post_updated_messages', 'sundance_updated_messages' );
function sundance_updated_messages( $messages ) {
	global $post, $post_ID;
	
	$messages['s_spa'] = array(
		0 => '', // Unused. Messages start at index 1.
		1 => sprintf( __('Spa updated. <a href="%s">View tub</a>'), esc_url( get_permalink($post_ID) ) ),
		2 => __('Custom field updated.'),
		3 => __('Custom field deleted.'),
		4 => __('Spa updated.'),
		// translators: %s: date and time of the revision
		5 => isset($_GET['revision']) ? sprintf( __('Spa restored to revision from %s'), wp_post_revision_title( (int) $_GET['revision'], false ) ) : false,
		6 => sprintf( __('Spa published. <a href="%s">View tub</a>'), esc_url( get_permalink($post_ID) ) ),
		7 => __('Spa saved.'),
		8 => sprintf( __('Spa submitted. <a target="_blank" href="%s">Preview tub</a>'), esc_url( add_query_arg( 'preview', 'true', get_permalink($post_ID) ) ) ),
		9 => sprintf( __('Spa scheduled for: <strong>%1$s</strong>. <a target="_blank" href="%2$s">Preview tub</a>'),
		// translators: Publish box date format, see http://php.net/date
		date_i18n( __( 'M j, Y @ G:i' ), strtotime( $post->post_date ) ), esc_url( get_permalink($post_ID) ) ),
		10 => sprintf( __('Spa draft updated. <a target="_blank" href="%s">Preview tub</a>'), esc_url( add_query_arg( 'preview', 'true', get_permalink($post_ID) ) ) ),
	);
	
	$messages['s_cat'] = array(
		0 => '', // Unused. Messages start at index 1.
		1 => sprintf( __('Series updated. <a href="%s">View Series</a>'), esc_url( get_permalink($post_ID) ) ),
		2 => __('Custom field updated.'),
		3 => __('Custom field deleted.'),
		4 => __('Series updated.'),
		// translators: %s: date and time of the revision
		5 => isset($_GET['revision']) ? sprintf( __('Series restored to revision from %s'), wp_post_revision_title( (int) $_GET['revision'], false ) ) : false,
		6 => sprintf( __('Series published. <a href="%s">View Series</a>'), esc_url( get_permalink($post_ID) ) ),
		7 => __('Series saved.'),
		8 => sprintf( __('Series submitted. <a target="_blank" href="%s">Preview Series</a>'), esc_url( add_query_arg( 'preview', 'true', get_permalink($post_ID) ) ) ),
		9 => sprintf( __('Series scheduled for: <strong>%1$s</strong>. <a target="_blank" href="%2$s">Preview Series</a>'),
		// translators: Publish box date format, see http://php.net/date
		date_i18n( __( 'M j, Y @ G:i' ), strtotime( $post->post_date ) ), esc_url( get_permalink($post_ID) ) ),
		10 => sprintf( __('Series draft updated. <a target="_blank" href="%s">Preview Series</a>'), esc_url( add_query_arg( 'preview', 'true', get_permalink($post_ID) ) ) ),
	);
	
	$messages['s_color'] = array(
		0 => '', // Unused. Messages start at index 1.
		1 => __('Color updated.'),
		2 => __('Custom field updated.'),
		3 => __('Custom field deleted.'),
		4 => __('Color updated.'),
		// translators: %s: date and time of the revision
		5 => isset($_GET['revision']) ? sprintf( __('Color restored to revision from %s'), wp_post_revision_title( (int) $_GET['revision'], false ) ) : false,
		6 => __('Color published.'),
		7 => __('Color saved.'),
		8 => __('Color submitted.'),
		9 => sprintf( __('Color scheduled for: <strong>%1$s</strong>.'),
		// translators: Publish box date format, see http://php.net/date
		date_i18n( __( 'M j, Y @ G:i' ), strtotime( $post->post_date ) ) ),
		10 => __('Color draft updated.'),
	);
	
	$messages['s_cab'] = array(
		0 => '', // Unused. Messages start at index 1.
		1 => __('Cabinet Color updated.'),
		2 => __('Custom field updated.'),
		3 => __('Custom field deleted.'),
		4 => __('Cabinet Color updated.'),
		// translators: %s: date and time of the revision
		5 => isset($_GET['revision']) ? sprintf( __('Cabinet Color restored to revision from %s'), wp_post_revision_title( (int) $_GET['revision'], false ) ) : false,
		6 => __('Cabinet Color published.'),
		7 => __('Cabinet Color saved.'),
		8 => __('Cabinet Color submitted.'),
		9 => sprintf( __('Cabinet Color scheduled for: <strong>%1$s</strong>.'),
		// translators: Publish box date format, see http://php.net/date
		date_i18n( __( 'M j, Y @ G:i' ), strtotime( $post->post_date ) ) ),
		10 => __('Cabinet Color draft updated.'),
	);
	
	$messages['s_high'] = array(
		0 => '', // Unused. Messages start at index 1.
		1 => __('Highlight updated.'),
		2 => __('Custom field updated.'),
		3 => __('Custom field deleted.'),
		4 => __('Highlight updated.'),
		// translators: %s: date and time of the revision
		5 => isset($_GET['revision']) ? sprintf( __('Highlight restored to revision from %s'), wp_post_revision_title( (int) $_GET['revision'], false ) ) : false,
		6 => __('Highlight published.'),
		7 => __('Highlight saved.'),
		8 => __('Highlight submitted.'),
		9 => sprintf( __('Highlight scheduled for: <strong>%1$s</strong>.'),
		// translators: Publish box date format, see http://php.net/date
		date_i18n( __( 'M j, Y @ G:i' ), strtotime( $post->post_date ) ) ),
		10 => __('Highlight draft updated.'),
	);
	
	$messages['s_opt'] = array(
		0 => '', // Unused. Messages start at index 1.
		1 => __('Option updated.'),
		2 => __('Custom field updated.'),
		3 => __('Custom field deleted.'),
		4 => __('Option updated.'),
		// translators: %s: date and time of the revision
		5 => isset($_GET['revision']) ? sprintf( __('Option restored to revision from %s'), wp_post_revision_title( (int) $_GET['revision'], false ) ) : false,
		6 => __('Option published.'),
		7 => __('Option saved.'),
		8 => __('Option submitted.'),
		9 => sprintf( __('Option scheduled for: <strong>%1$s</strong>.'),
		// translators: Publish box date format, see http://php.net/date
		date_i18n( __( 'M j, Y @ G:i' ), strtotime( $post->post_date ) ) ),
		10 => __('Option draft updated.'),
	);
	
	$messages['s_feat'] = array(
		0 => '', // Unused. Messages start at index 1.
		1 => __('Feature updated.'),
		2 => __('Custom field updated.'),
		3 => __('Custom field deleted.'),
		4 => __('Feature updated.'),
		// translators: %s: date and time of the revision
		5 => isset($_GET['revision']) ? sprintf( __('Feature restored to revision from %s'), wp_post_revision_title( (int) $_GET['revision'], false ) ) : false,
		6 => __('Feature published.'),
		7 => __('Feature saved.'),
		8 => __('Feature submitted.'),
		9 => sprintf( __('Feature scheduled for: <strong>%1$s</strong>.'),
		// translators: Publish box date format, see http://php.net/date
		date_i18n( __( 'M j, Y @ G:i' ), strtotime( $post->post_date ) ) ),
		10 => __('Feature draft updated.'),
	);
	
	$messages['s_jet'] = array(
		0 => '', // Unused. Messages start at index 1.
		1 => __('Jet updated.'),
		2 => __('Custom field updated.'),
		3 => __('Custom field deleted.'),
		4 => __('Jet updated.'),
		// translators: %s: date and time of the revision
		5 => isset($_GET['revision']) ? sprintf( __('Jet restored to revision from %s'), wp_post_revision_title( (int) $_GET['revision'], false ) ) : false,
		6 => __('Jet published.'),
		7 => __('Jet saved.'),
		8 => __('Jet submitted.'),
		9 => sprintf( __('Jet scheduled for: <strong>%1$s</strong>.'),
		// translators: Publish box date format, see http://php.net/date
		date_i18n( __( 'M j, Y @ G:i' ), strtotime( $post->post_date ) ) ),
		10 => __('Jet draft updated.'),
	);
	/*
	$messages['s_warranty'] = array(
		0 => '', // Unused. Messages start at index 1.
		1 => __('Warranty updated.'),
		2 => __('Custom field updated.'),
		3 => __('Custom field deleted.'),
		4 => __('Warranty updated.'),
		// translators: %s: date and time of the revision
		5 => isset($_GET['revision']) ? sprintf( __('Warranty restored to revision from %s'), wp_post_revision_title( (int) $_GET['revision'], false ) ) : false,
		6 => __('Warranty published.'),
		7 => __('Warranty saved.'),
		8 => __('Warranty submitted.'),
		9 => sprintf( __('Warranty scheduled for: <strong>%1$s</strong>.'),
		// translators: Publish box date format, see http://php.net/date
		date_i18n( __( 'M j, Y @ G:i' ), strtotime( $post->post_date ) ) ),
		10 => __('Warranty draft updated.'),
	);
	*/
	$messages['s_vid'] = array(
		0 => '', // Unused. Messages start at index 1.
		1 => __('Video updated.'),
		2 => __('Custom field updated.'),
		3 => __('Custom field deleted.'),
		4 => __('Video updated.'),
		// translators: %s: date and time of the revision
		5 => isset($_GET['revision']) ? sprintf( __('Video restored to revision from %s'), wp_post_revision_title( (int) $_GET['revision'], false ) ) : false,
		6 => __('Video published.'),
		7 => __('Video saved.'),
		8 => __('Video submitted.'),
		9 => sprintf( __('Video scheduled for: <strong>%1$s</strong>.'),
		// translators: Publish box date format, see http://php.net/date
		date_i18n( __( 'M j, Y @ G:i' ), strtotime( $post->post_date ) ) ),
		10 => __('Video draft updated.'),
	);
	
	$messages['s_acc'] = array(
		0 => '', // Unused. Messages start at index 1.
		1 => __('Accessory updated.'),
		2 => __('Custom field updated.'),
		3 => __('Custom field deleted.'),
		4 => __('Accessory updated.'),
		/* translators: %s: date and time of the revision */
		5 => isset($_GET['revision']) ? sprintf( __('Accessory restored to revision from %s'), wp_post_revision_title( (int) $_GET['revision'], false ) ) : false,
		6 => __('Accessory published.'),
		7 => __('Accessory saved.'),
		8 => __('Accessory submitted.'),
		9 => sprintf( __('Accessory scheduled for: <strong>%1$s</strong>.'),
		// translators: Publish box date format, see http://php.net/date
		date_i18n( __( 'M j, Y @ G:i' ), strtotime( $post->post_date ) ) ),
		10 => __('Accessory draft updated.'),
	);
	return $messages;
}

function sundance_init() {
	register_post_type( 's_spa',
		array(
			'labels' => array(
				'name' => 'Sundance Spas',
				'singular_name' => 'Spa',
				'add_new' => 'Add New Spa',
				'add_new_item' => 'Add New Spa',
				'edit_item' => 'Edit Spa',
				'new_item' => 'New Spa',
				'view_item' => 'View Spa',
				'search_items' => 'Search Spas',
				'not_found' =>  'No spas found',
				'not_found_in_trash' => 'No spas found in Trash', 
				'parent_item_colon' => ''
			),
			'public' => true,
			//'exclude_from_search' => true,
			'show_in_menu' => true,
			//'menu_position' => 7,
			'menu_icon' => get_bloginfo('template_url') .'/images/icons/admin_tub.png',
			'supports' => array('title','editor','thumbnail','page-attributes','revisions', 'custom-fields'),
			'register_meta_box_cb' => 'sundance_tub_metaboxes',
			'rewrite' => array(
				'slug' => '%series%',
				'with_front' => false
			),
			//'has_archive'=>true
		)
	);
	
	register_post_type( 's_cat',
		array(
			'labels' => array(
				'name' => 'Spa Series',
				'singular_name' => 'Series',
				'add_new_item' => 'Add New Series',
				'edit_item' => 'Edit Series',
				'new_item' => 'New Series',
				'view_item' => 'View Series',
				'search_items' => 'Search Spa Series',
				'not_found' =>  'No Series Found',
				'not_found_in_trash' => 'No Series found in Trash', 
				'parent_item_colon' => '',
				'menu_name' => 'Spa Series'
			),
			'public' => true,
			//'exclude_from_search' => true,
			'show_in_menu' => 'edit.php?post_type=s_spa',
			'hierarchical' => true,
			'supports' => array('title','editor','thumbnail','excerpt','revisions','page-attributes', 'custom-fields'),
			//'register_meta_box_cb' => 'sundance_cat_metaboxes',
			'rewrite' => array(
				'slug' => '%s_none%',
				'with_front' => false,
			),
			//'has_archive'=>true
		)
	);
	
	register_post_type( 's_color',
		array(
			'labels' => array(
				'name' => 'Acrylic colors and textures',
				'singular_name' => 'Color',
				'add_new_item' => 'Add New Color',
				'edit_item' => 'Edit Color',
				'new_item' => 'New Color',
				'search_items' => 'Search Colors',
				'not_found' =>  'No Colors Found',
				'not_found_in_trash' => 'No Colors found in Trash', 
				'parent_item_colon' => '',
				'menu_name' => 'Shell Colors'
			),
			'public' => true,
			'exclude_from_search' => true,
			'show_in_menu' => 'edit.php?post_type=s_spa',
			'supports' => array('title','thumbnail','page-attributes'),
			'rewrite' => array(
				'slug' => 'color',
				'with_front' => false,
			),
			//'has_archive'=>true
		)
	);
	
	register_post_type( 's_cab',
		array(
			'labels' => array(
				'name' => 'Cabinet Colors',
				'singular_name' => 'Cabinet Color',
				'add_new_item' => 'Add New Cabinet Color',
				'edit_item' => 'Edit Cabinet Color',
				'new_item' => 'New Cabinet Color',
				'search_items' => 'Search Cabinet Colors',
				'not_found' =>  'No Cabinet Colors Found',
				'not_found_in_trash' => 'No Cabinet Colors found in Trash', 
				'parent_item_colon' => ''
			),
			'public' => true,
			'exclude_from_search' => true,
			'show_in_menu' => 'edit.php?post_type=s_spa',
			'supports' => array('title','thumbnail','page-attributes'),
			'rewrite' => array(
				'slug' => 'cabinetry',
				'with_front' => false,
			),
			//'has_archive'=>true
		)
	);
	
	register_post_type( 's_high',
		array(
			'labels' => array(
				'name' => 'Highlights',
				'singular_name' => 'Highlight',
				'add_new_item' => 'Add New Highlight',
				'edit_item' => 'Edit Highlight',
				'new_item' => 'New Highlight',
				'search_items' => 'Search Highlights',
				'not_found' =>  'No Highlight found',
				'not_found_in_trash' => 'No Highlight found in Trash', 
				'parent_item_colon' => ''
			),
			'public' => true,
			'exclude_from_search' => true,
			'show_in_menu' => 'edit.php?post_type=s_spa',
			'supports' => array('title','editor','thumbnail','page-attributes'),
			'rewrite' => array(
				'slug' => 'highlights',
				'with_front' => false,
			),
			//'has_archive'=>true
		)
	);
	
	register_post_type( 's_opt',
		array(
			'labels' => array(
				'name' => 'Options',
				'singular_name' => 'Option',
				'add_new_item' => 'Add New Option',
				'edit_item' => 'Edit Option',
				'new_item' => 'New Option',
				'search_items' => 'Search Options',
				'not_found' =>  'No Options found',
				'not_found_in_trash' => 'No Options found in Trash', 
				'parent_item_colon' => ''
			),
			'public' => true,
			'exclude_from_search' => true,
			'show_in_menu' => 'edit.php?post_type=s_spa',
			'supports' => array('title','editor','thumbnail','page-attributes'),
			'rewrite' => array(
				'slug' => 'features-options',
				'with_front' => false,
			),
			//'has_archive'=>true
		)
	);
	
	register_post_type( 's_feat',
		array(
			'labels' => array(
				'name' => 'Features',
				'singular_name' => 'Feature',
				'add_new_item' => 'Add New Feature',
				'edit_item' => 'Edit Feature',
				'new_item' => 'New Feature',
				'search_items' => 'Search Features',
				'not_found' =>  'No Features found',
				'not_found_in_trash' => 'No Features found in Trash', 
				'parent_item_colon' => ''
			),
			'public' => true,
			'exclude_from_search' => true,
			'show_in_menu' => 'edit.php?post_type=s_spa',
			'supports' => array('title','editor','thumbnail','page-attributes'),
			'register_meta_box_cb' => 'sundance_feat_metaboxes',
			'rewrite' => array(
				'slug' => 'feats',
				'with_front' => false,
			),
			//'has_archive'=>true
		)
	);
	
	register_post_type( 's_jet',
		array(
			'labels' => array(
				'name' => 'Jets',
				'singular_name' => 'Jet',
				'add_new_item' => 'Add New Jet',
				'edit_item' => 'Edit Jet',
				'new_item' => 'New Jet',
				'search_items' => 'Search Jets',
				'not_found' =>  'No Jets Found',
				'not_found_in_trash' => 'No Jets found in Trash', 
				'parent_item_colon' => '',
				'menu_name' => 'Jets'
			),
			'public' => true,
			'exclude_from_search' => true,
			'show_in_menu' => true,
			'menu_icon' => get_bloginfo('template_url') .'/images/icons/admin_jets.png',
			'taxonomies' => array( 's_jet_ser' ),
			'supports' => array('title','editor','thumbnail','page-attributes'),
			'rewrite' => array(
				'slug' => 'jets',
				'with_front' => false,
			),
			//'has_archive'=>true
		)
	);
	// more FEATURED IMAGES functionality
	if (class_exists('MultiPostThumbnails')) {
		// s_spa
		$thumb = new MultiPostThumbnails(array(
			'label' => 'Large Overhead (307x305)',
			'id' => 'overhead-large',
			'post_type' => 's_spa',
			)
		);
		$thumb = new MultiPostThumbnails(array(
			'label' => 'Small Overhead (101x103)',
			'id' => 'overhead-small',
			'post_type' => 's_spa',
			)
		);
	}
	
	register_post_type( 's_vid',
		array(
			'labels' => array(
				'name' => 'Videos',
				'singular_name' => 'Video',
				'add_new' => 'Add New Video',
				'add_new_item' => 'Add New Video',
				'edit_item' => 'Edit Video',
				'new_item' => 'New Video',
				'view_item' => 'View Video',
				'search_items' => 'Search Videos',
				'not_found' =>  'No Videos found',
				'not_found_in_trash' => 'No Videos found in Trash', 
				'parent_item_colon' => '',
				'menu_name' => 'Videos'
			),
			'public' => true,
			//'exclude_from_search' => true,
			'show_in_menu' => true,
			//'menu_position' => 7,
			'menu_icon' => get_bloginfo('template_url') .'/images/icons/admin_vid.png',
			'supports' => array('title','page-attributes','revisions'),
			'register_meta_box_cb' => 'sundance_vid_metaboxes',
			'rewrite' => array(
				'slug' => 'video-gallery',
				'with_front' => false
			),
			//'has_archive'=>true
		)
	);
	
	register_post_type( 's_acc',
		array(
			'labels' => array(
				'name' => 'Accessories',
				'singular_name' => 'Accessory',
				'add_new' => 'Add New Accessory',
				'add_new_item' => 'Add New Accessory',
				'edit_item' => 'Edit Accessory',
				'new_item' => 'New Accessory',
				'view_item' => 'View Accessory',
				'search_items' => 'Search Accessories',
				'not_found' =>  'No Accessories found',
				'not_found_in_trash' => 'No Accessories found in Trash', 
				'parent_item_colon' => '',
				'menu_name' => 'Accessories'
			),
			'public' => true,
			//'exclude_from_search' => true,
			'show_in_menu' => true,
			//'menu_position' => 7,
			'menu_icon' => get_bloginfo('template_url') .'/images/icons/admin_accessory.png',
			'supports' => array('title','editor','thumbnail','page-attributes','revisions'),
			//'register_meta_box_cb' => 'sundance_vid_metaboxes',
			'taxonomies' => array( 's_acc_cat' ),
			'rewrite' => array(
				'slug' => 'accessories',
				'with_front' => false,
			),
			//'has_archive'=>true
		)
	);
	$labels = array(
		'name' => _x( 'Accessory Categories', 'taxonomy general name' ),
		'singular_name' => _x( 'Accessory Category', 'taxonomy singular name' ),
		'search_items' =>  __( 'Search Accessory Categories' ),
		'all_items' => __( 'All Accessory Categories' ),
		'parent_item' => __( 'Parent Category' ),
		'parent_item_colon' => __( 'Parent Category:' ),
		'edit_item' => __( 'Edit Accessory Category' ), 
		'update_item' => __( 'Update Category' ),
		'add_new_item' => __( 'Add New Accessory Category' ),
		'new_item_name' => __( 'New Accessory Category Name' ),
		'menu_name' => __( 'Categories' ),
	);
	
	register_taxonomy('s_acc_cat',array('s_acc'), array(
		'hierarchical' => true,
		'labels' => $labels,
		'show_ui' => true,
		'query_var' => true,
		'rewrite' => array(
			'slug' => 'accessories',
			'with_front' => false,
		),
	));
	
	$labels = array(
		'name' => _x( 'Jet Series', 'taxonomy general name' ),
		'singular_name' => _x( 'Jet Series', 'taxonomy singular name' ),
		'search_items' =>  __( 'Search Jet Series' ),
		'all_items' => __( 'All Jet Series' ),
		'parent_item' => __( 'Parent Series' ),
		'parent_item_colon' => __( 'Parent Series:' ),
		'edit_item' => __( 'Edit Jet Series' ), 
		'update_item' => __( 'Update Jet Series' ),
		'add_new_item' => __( 'Add New Jet Series' ),
		'new_item_name' => __( 'New Jet Series Name' ),
		'menu_name' => __( 'Jet Series' ),
	);
	
	register_taxonomy('s_jet_ser',array('s_jet'), array(
		'hierarchical' => true,
		'labels' => $labels,
		'show_ui' => true,
		'query_var' => false,
		'rewrite' => array(
			'slug' => 'jetseries',
			'with_front' => false,
		),
	));
}

function sundance_tub_metaboxes() {
	add_meta_box("sundance_features_metabox", "Features &amp; Options", "sundance_features_metabox", "s_spa", "normal", "high");
	add_meta_box("sundance_cats_metabox", "Series", "sundance_cats_metabox", "s_spa", "side", "core");
	add_meta_box("sundance_specs_metabox", "Specifications", "sundance_specs_metabox", "s_spa", "normal", "high");
	add_meta_box("sundance_jets_metabox", "Jets", "sundance_jets_metabox", "s_spa", "normal", "high");
}

function sundance_feat_metaboxes() {
	add_meta_box("sundance_feat_metabox", "More Info", "sundance_feat_metabox", "s_feat", "normal", "low");
}

function sundance_vid_metaboxes() {
	add_meta_box("sundance_vid_metabox", "Video Info", "sundance_vid_metabox", "s_vid", "normal", "high");
}


add_action( 'add_meta_boxes', 'sundance_sidebar_add_metabox' );
function sundance_sidebar_add_metabox() {
	if ( isset( $_GET['post'] ) )
		$post_id = $_GET['post'];
	elseif ( isset( $_POST['post_ID'] ) )
		$post_id = $_POST['post_ID'];
	else
		$post_id = get_the_ID();

	if ( ! $post_id )
		return;

	if ( 'page' != get_post_type( $post_id ) )
		return;

	if ( 'page-landing-emailnurture.php' == get_post_meta( $post_id, '_wp_page_template', true ) ) {
		add_meta_box(
			'sundance_sidebar_metabox_area',
			'Sidebar Content',
			'sundance_sidebar_metaboxes_callback',
			'page',
			'normal',
			'high'
			);
	}
};
function sundance_sidebar_metaboxes_callback( $post ) {

	// Add an nonce field so we can check for it later.
	wp_nonce_field( 'sundance_sidebar_metabox', 'sundance_sidebar_metabox_nonce' );

	/*
	 * Use get_post_meta() to retrieve an existing value
	 * from the database and use the value for the form.
	 */
	$value = htmlspecialchars_decode( apply_filters('the_content', get_post_meta( $post->ID, '_sundance_sidebar_metabox_value_key', true ) ) );

	wp_editor( $value, 'sundance_sidebar_metabox' );
}
function sundance_sidebar_save_meta_box_data( $post_id ) {

	/*
	 * We need to verify this came from our screen and with proper authorization,
	 * because the save_post action can be triggered at other times.
	 */

	// Check if our nonce is set.
	if ( ! isset( $_POST['sundance_sidebar_metabox_nonce'] ) ) {
		return;
	}

	// Verify that the nonce is valid.
	if ( ! wp_verify_nonce( $_POST['sundance_sidebar_metabox_nonce'], 'sundance_sidebar_metabox' ) ) {
		return;
	}

	// If this is an autosave, our form has not been submitted, so we don't want to do anything.
	if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
		return;
	}

	// Check the user's permissions.
	if ( isset( $_POST['post_type'] ) && 'page' == $_POST['post_type'] ) {

		if ( ! current_user_can( 'edit_page', $post_id ) ) {
			return;
		}

	} else {

		if ( ! current_user_can( 'edit_post', $post_id ) ) {
			return;
		}
	}

	/* OK, it's safe for us to save the data now. */
	
	// Make sure that it is set.
	if ( ! isset( $_POST['sundance_sidebar_metabox'] ) ) {
		return;
	}

	// Sanitize user input.
	$my_data = $_POST['sundance_sidebar_metabox'];

	// Update the meta field in the database.
	update_post_meta( $post_id, '_sundance_sidebar_metabox_value_key', $my_data );
}
add_action( 'save_post', 'sundance_sidebar_save_meta_box_data' );



function sundance_checks( $tub_id, $opt_name, $ptype, $cols=1 ) {
	$cols = absint($cols);
	$custom = get_post_meta($tub_id, $opt_name);
	$tub_cats = $custom[0];
	if($tub_cats=='') $tub_cats = array();
	
	$postargs = array(
		'numberposts' => -1,
		'orderby' => 'menu_order',
		'order' => 'ASC',
		'post_type' => $ptype,
	);
	
	if ( $opt_name == 's_cats' ) {
		$postargs['exclude'] = 8;
	}
	
	
	$allcats = get_posts( $postargs );
	$splitat = 0;
	
	if ( $cols > 1 ) {
		$allcount = count($allcats);
		$splitat = ceil($allcount / $cols);
		$i = 0;
		$colwidth = ceil(100/$cols) .'%';
		echo '<table width="100%" cellpadding="0" cellspacing="0"><tr valign="top"><td width="'. $colwidth .'%">';
	}
	
	$hierarchical_check = is_post_type_hierarchical($ptype);
	
	echo '<ul>';
	foreach ( $allcats as $c ) {
		if ( $c->post_parent == '' ) {
			echo '<li><label><input type="checkbox" name="'. esc_attr($opt_name) .'[]" value="'. $c->ID .'"'. (in_array($c->ID,$tub_cats) ? ' checked="checked"' : '') .' /> '. esc_attr($c->post_title) .'</label>';
			if ( $hierarchical_check ) {
				$subcats = get_posts( array(
					'numberposts' => -1,
					'orderby' => 'menu_order',
					'order' => 'ASC',
					'post_type' => $ptype,
					'post_parent' => $c->ID,
				));
				if ( count($subcats) > 0 ) {
					echo '<ul style="padding-left:2em">';
				foreach ( $subcats as $s ) {
					echo '<li><label><input type="checkbox" name="'. esc_attr($opt_name) .'[]" value="'. $s->ID .'"'. (in_array($s->ID,$tub_cats) ? ' checked="checked"' : '') .' /> '. esc_attr($s->post_title) .'</label></li>';
				}
					echo '</ul>';
				}
			}
			echo '</li>';
		}
		if ( $splitat > 0 ) {
			$i++;
			if ( $i >= $splitat ) {
				$i = 0;
				echo '</td><td width="'. $colwidth .'%"><ul>';
			}
		}
	}
    echo '</ul>';
	if ( $splitat > 0 ) {
		echo '</td></tr></table>';
	}
}

function sundance_jets( $tub_id ) {
	$custom = get_post_meta($tub_id, 's_jets');
	$tub_jets = $custom[0];
	if($tub_jets=='') $tub_jets = array();
	
	$jets = get_posts( array(
		'numberposts' => -1,
		'orderby' => 'menu_order',
		'order' => 'ASC',
		'post_type' => 's_jet',
	));
	
	echo '<table>';
	foreach ( $jets as $j ) {
		echo '<tr><td><label for="s_jets['. $j->ID .']">'. str_replace("PowerPro ","", esc_attr($j->post_title)) .'</label></td><td><input type="text" name="s_jets['. $j->ID .']" value="'. (isset($tub_jets[$j->ID]) ? absint($tub_jets[$j->ID]) : '0') .'" class="jetcount" /></td></tr>';
	}
    echo '</table>';
	?>
    <script type="text/javascript">
	jQuery(function($) {
		$('#sundance_jets_metabox .jetcount').blur(function() {
			var jc = 0;
			$('#sundance_jets_metabox .jetcount').each(function() {
				jc += parseInt($(this).val(),10);
			});
			$('#sundance_jets_metabox h3.hndle span').html('Jets ('+jc+')');
		}).eq(0).trigger('blur');
	});
	</script>
    <?php
}

function sundance_cats_metabox() {
	global $post;
	sundance_checks($post->ID, 's_cats', 's_cat');
}

function sundance_jets_metabox() {
	global $post;
	sundance_jets( $post->ID );
}

function sundance_features_metabox() {
	global $post;
	$custom = get_post_meta($post->ID,'s_info');
	$info = $custom[0];
	if($info=='') $info = array(
		'topheadline' => '',
	);
	
	$custom = get_post_meta($post->ID,'s_size');
	$size = $custom[0];
	?>
    <p><label for="s_info[topheadline]"><strong>Top Sub Headline</strong></label><br />
    <input type="text" name="s_info[topheadline]" size="160" value="<?php echo esc_attr($info['topheadline']); ?>" /></p>
    <p><label for="s_size"><strong>Size (grouping)</strong></label> <select name="s_size"><?php
	$sizes = array('Large', 'Medium', 'Small', 'In-Ground');
	foreach ( $sizes as $s ) {
		echo '<option'. ($size == $s ? ' selected="selected"' : '') .'>'. esc_attr($s) .'</option>';
	}
	?></select></p>
    <table width="100%">
    <tr valign="top"><td width="25%">
    <p><strong>Acrylic Shell Colors</strong></p>
    <?php sundance_checks($post->ID, 's_colors', 's_color'); ?>
    </td><td width="25%">
    <p><strong>Cabinetry</strong></p>
    <?php sundance_checks($post->ID, 's_cabs', 's_cab'); ?>
    </td><td width="25%">
    <p><strong>Feature Pts</strong></p>
    <?php sundance_checks($post->ID, 's_feats', 's_feat'); ?>
    </td><td>
    <p><strong>Options</strong></p>
    <?php
	sundance_checks($post->ID, 's_opt', 's_opt'); ?>
    </td></tr></table>
    <p><strong>Highlights</strong></p>
    <?php sundance_checks($post->ID, 's_high', 's_high', 2);
}


function sundance_specs_metabox() {
	global $post;
	$custom = get_post_meta($post->ID,'s_specs');
	$info = $custom[0];
	if($info=='') $info = array(
		'product_id' => '',
		'video_id' => '',
		'msrp' => '',
		'seats' => '',
		'dim_us' => '',
		'dim_int' => '',
		'vol_us' => '',
		'vol_int' => '',
		'weight' => '',
		'emoc' => '',
		'pumps' => '',
		'armoa' => '',
		'control' => '',
		'filter' => '',
		'wt' => '',
		'wps' => '',
		'elec' => '',
		'lighting' => '',
		'trim' => '',
		'headrests' => '',
		'heater' => '',
		'waterfall' => '',
		'stereo' => '',
		'cpanel' => '',
	);
	?><table width="100%">
	<tr valign="top">
    <td width="187"><label for="s_specs[product_id]">Product ID</label></td><td><input type="text" name="s_specs[product_id]" value="<?php esc_attr_e($info['product_id']); ?>" size="20" /></td>
    </tr>
	<tr valign="top">
    <td width="187"><label for="s_specs[video_id]">YouTube Video ID</label></td><td><input type="text" name="s_specs[video_id]" value="<?php esc_attr_e($info['video_id']); ?>" size="20" /></td>
    </tr>
	<tr valign="top">
	<td width="187"><label for="s_specs[msrp]">MSRP</label></td><td><input type="text" name="s_specs[msrp]" value="<?php esc_attr_e($info['msrp']); ?>" size="20" /></td>
	</tr>
    <tr valign="top">
    <td width="187"><label for="s_specs[seats]">Seats</label></td><td><input type="text" name="s_specs[seats]" value="<?php esc_attr_e($info['seats']); ?>" size="10" /></td>
    </tr>
    <tr valign="top">
    <td><label for="s_specs[dim_us]">Dimensions</label></td><td><table><tr>
    <td><input type="text" name="s_specs[dim_us]" value="<?php esc_attr_e($info['dim_us']); ?>" /></td><td><label for="s_specs[dim_us]">(US)</label></td><td>&nbsp;&nbsp;/&nbsp;&nbsp;</td><td><input type="text" name="s_specs[dim_int]" value="<?php esc_attr_e($info['dim_int']); ?>" size="30" /></td><td><label for="s_specs[dim_int]">(INT)</label></td>
    </tr></table></td>
    </tr>
    <tr valign="top">
    <td><label for="s_specs[weight]">Dry Weight</label></td><td><input type="text" name="s_specs[weight]" value="<?php esc_attr_e($info['weight']); ?>" size="120" /></td>
    </tr>
    <tr valign="top">
    <td><label for="s_specs[weight]">Filled Weight</label></td><td><input type="text" name="s_specs[fweight]" value="<?php esc_attr_e($info['fweight']); ?>" size="120" /></td>
    </tr>
    <tr valign="top">
    <td><label for="s_specs[vol_us]">Spa Volume</label></td><td><table><tr>
    <td><input type="text" name="s_specs[vol_us]" value="<?php esc_attr_e($info['vol_us']); ?>" /></td><td><label for="s_specs[vol_us]">(US)</label></td><td>&nbsp;&nbsp;/&nbsp;&nbsp;</td><td><input type="text" name="s_specs[vol_int]" value="<?php esc_attr_e($info['vol_int']); ?>" /></td><td><label for="s_specs[vol_int]">(INT)</label></td>
    </tr></table></td>
    </tr>
    <tr valign="top">
    <td><label for="s_specs[emoc]">Operating Cost at<br />101&deg;F water temperature </label></td><td><input type="text" name="s_specs[emoc]" value="<?php esc_attr_e($info['emoc']); ?>" /></td>
    </tr>
    <tr valign="top">
    <td><label for="s_specs[pumps]">Jet Pumps</label></td><td><textarea name="s_specs[pumps]" cols="120"><?php esc_attr_e($info['pumps']); ?></textarea></td>
    </tr>
    <tr valign="top">
    <td><label for="s_specs[aroma]">Aromatherapy Delivery/Air Blower</label></td><td><input type="text" name="s_specs[aroma]" value="<?php esc_attr_e($info['aroma']); ?>" size="120" /></td>
    </tr>
    <tr valign="top">
    <td><label for="s_specs[control]">Air Controls/Massage Selectors</label></td><td><input type="text" name="s_specs[control]" value="<?php esc_attr_e($info['control']); ?>" size="120" /></td>
    </tr>
    <tr valign="top">
    <td><label for="s_specs[wps]">Water Purification System</label></td><td><input type="text" name="s_specs[wps]" value="<?php esc_attr_e($info['wps']); ?>" size="120" /></td>
    </tr>
    <tr valign="top">
    <td><label for="s_specs[filter]">Filter</label></td><td><input type="text" name="s_specs[filter]" value="<?php esc_attr_e($info['filter']); ?>" size="120" /></td>
    </tr>
    <tr valign="top">
    <td><label for="s_specs[wt]">Circulation Pump</label></td><td><input type="text" name="s_specs[wt]" value="<?php esc_attr_e($info['wt']); ?>" size="120" /></td>
    </tr>
    <tr valign="top">
    <td><label for="s_specs[elec]">Electrical Requirements</label></td><td><textarea name="s_specs[elec]" cols="120"><?php esc_attr_e($info['elec']); ?></textarea></td>
    </tr>
    <tr><td colspan="2"><p><strong>Features</strong></p></td></tr>
    <tr valign="top">
    <td><label for="s_specs[lighting]">Lighting</label></td><td><input type="text" name="s_specs[lighting]" value="<?php esc_attr_e($info['lighting']); ?>" size="120" /></td>
    </tr>
    <tr valign="top">
    <td><label for="s_specs[trim]">Stainless Steel Jet Trim</label></td><td><input type="text" name="s_specs[trim]" value="<?php esc_attr_e($info['trim']); ?>" size="120" /></td>
    </tr>
    <tr valign="top">
    <td><label for="s_specs[headrests]">Headrests</label></td><td><input type="text" name="s_specs[headrests]" value="<?php esc_attr_e($info['headrests']); ?>" /></td>
    </tr>
    <tr valign="top">
    <td><label for="s_specs[heater]">Heater</label></td><td><input type="text" name="s_specs[heater]" value="<?php esc_attr_e($info['heater']); ?>" size="120" /></td>
    </tr>
    <tr valign="top">
    <td><label for="s_specs[waterfall]">Water Feature</label></td><td><input type="text" name="s_specs[waterfall]" value="<?php esc_attr_e($info['waterfall']); ?>" size="120" /></td>
    </tr>
    <tr valign="top">
    <td><label for="s_specs[stereo]">Stereo</label></td><td><input type="text" name="s_specs[stereo]" value="<?php esc_attr_e($info['stereo']); ?>" size="120" /></td>
    </tr>
    <tr valign="top">
    <td><label for="s_specs[cpanel]">AquaTerrace Control Panel</label></td><td><input type="text" name="s_specs[cpanel]" value="<?php esc_attr_e($info['cpanel']); ?>" size="120" /></td>
    </tr>
    </table>
    <?php
}

function sundance_feat_metabox() {
	global $post;
	$custom = get_post_meta($post->ID,'s_info');
	$info = $custom[0];
	if($info=='') $info = array(
		'lnk' => '',
		'url' => ''
	);
	?>
    <p><em>** NOTE : in the TITLE and DESCRIPTION fields above, and the Link Title below, the string %spa% will be replaced by the name of the Spa being viewed.</em></p> 
    <p><label for="s_info[lnk]"><strong>"More" Link: Title</strong></label><br />
    <input type="text" name="s_info[lnk]" size="160" value="<?php echo esc_attr($info['lnk']); ?>" /></p>
    <p><label for="s_info[url]"><strong>"More" Link: URL</strong></label><br />
    <input type="text" name="s_info[url]" size="160" value="<?php echo esc_attr($info['url']); ?>" /></p>
    <?php
}

function sundance_vid_metabox() {
	global $post;
	$custom = get_post_meta($post->ID,'s_info');
	$info = $custom[0];
	if($info=='') $info = array(
		'url' => '',
		'dur' => ''
	);
	?>
    <p><label for="s_info[url]"><strong>YouTube URL</strong></label><br />
    <input type="text" name="s_info[url]" size="160" value="<?php echo esc_attr($info['url']); ?>" /></p>
    <p><label for="s_info[dur]"><strong>Duration</strong></label><br />
    <input type="text" name="s_info[dur]" size="10" value="<?php echo esc_attr($info['dur']); ?>" /></p>
    <?php
}
// helper sorting function
function sundance_tub_sort($a,$b) {
	return $a['menu_order'] > $b['menu_order'];
}

// Fix serialized data
function fix_serialized_data( $data ) {
	$fixed_serialized_data = preg_replace_callback( '!s:(\d+):"(.*?)";!',
		/*function($match) {
		return ($match[1] == strlen($match[2])) ? $match[0] : 's:' . strlen($match[2]) . ':"' . $match[2] . '";';
		},*/
		'fix_serialized_data_function',
		$data );
	return $fixed_serialized_data;
}
function fix_serialized_data_function($match) {
	return ($match[1] == strlen($match[2])) ? $match[0] : 's:' . strlen($match[2]) . ':"' . $match[2] . '";';
}

function sundance_meta_save($post_id){
	// verify if this is an auto save routine. If it is our form has not been submitted, so we dont want
	// to do anything
	if ( defined('DOING_AUTOSAVE') && DOING_AUTOSAVE ) 
	return $post_id;
	
	// Check permissions
	if ( isset($_POST['post_type']) && in_array($_POST['post_type'],array('s_spa', 'page', 's_vid', 's_feat')) ) {
		if ( !current_user_can( 'edit_page', $post_id ) ) return $post_id;
	} else {
	//if ( !current_user_can( 'edit_post', $post_id ) )
	  return $post_id;
	}
	
	if( isset($_POST['post_type']) && $_POST['post_type'] == 'page') {
		$info = $_POST['s_pageopts'];
		update_post_meta($post_id, 's_pageopts', $info);
		return $info;
	}
	
	if( isset($_POST['post_type']) && in_array( $_POST['post_type'] , array( 's_feat', 's_vid' ) ) ) {
		$info = $_POST['s_info'];
		update_post_meta($post_id, 's_info', $info);
		return $info;
	}
	
	//if($_POST['post_type'] == 's_spa') {
	$custom_tub_arrays = array('s_size', 's_cats', 's_colors', 's_cabs', 's_feats', 's_high', 's_opt', 's_info', 's_specs', 's_jets');
	foreach ( $custom_tub_arrays as $c ) {
		$info = $_POST[$c];
		update_post_meta($post_id, $c, $info);
		
		if ( $c == 's_cats' ) {
			$post = get_post($post_id);
			if ( $post->post_type == 's_spa' ) {
				// when we SAVE a TUB DETAIL
				// we also want to go through and update each Category's array of Tubs
				// example : $s_cats = array(20,39,44,48)
				$allcats = get_posts(array('numberposts'=>-1,'post_type'=>'s_cat','orderby'=>'menu_order','order'=>'ASC','exclude'=>1894)); // skip HOT TUBS cat
				if ( count($info) > 0 ) {
					$specs = $_POST['s_specs'];
					$seats = $specs['seats'];
					$vol = $specs['vol_us'];
					
					$size = $_POST['s_size'];
					
					$jets = $_POST['s_jets'];
					$jetcount = 0;
					foreach ( $jets as $j ) $jetcount += $j;
					/*
					$tag = $post->post_content;
					$tag = wp_kses(substr($tag, 0, strpos($tag, '</h1>')), array());
					*/
					$post_url = get_permalink($post->ID);
					// rather than looping through only the cats that this tub is IN
					// also want to make sure it is NOT in any cats it shouldnt be in, so
					foreach($allcats as $c) {
						$cat_id = $c->ID;
						$custom = get_post_meta($cat_id);
						$cat_tubs = unserialize(fix_serialized_data( $custom['s_cat_tubs'][0] ));
						if($cat_tubs=='') $cat_tubs = array();
						
						$tname = 's_'. $cat_id .'_tubtable';
						delete_transient( $tname );
						
						if ( ( in_array($cat_id, $info) == false ) || ( $_POST['post_status'] != 'publish' ) ) {
							unset($cat_tubs[$post_id]);
						} else {						
							$cat_tubs[$post_id] = array(
								'name' => $post->post_title,
								'slug' => $post->post_name,
								'menu_order' => $post->menu_order,
								'url' => $post_url,
								'size' => $size,
								'seats' => $seats,
								'jets' => $jetcount,
								'vol' => str_replace('US gal', 'gal', $vol),
								'id' => $post->ID,
							);
						}
						//uasort($cat_tubs, 's_spa_sort');
						update_post_meta($cat_id, 's_cat_tubs', $cat_tubs);
					}
				} else {
					foreach($allcats as $c) {
						$cat_id = $c->ID;
						$custom = get_post_meta($cat_id);
						$cat_tubs = unserialize(fix_serialized_data( $custom['s_cat_tubs'][0] ));
						if($cat_tubs=='') $cat_tubs = array();
						
						$tname = 's_'. $cat_id .'_tubtable';
						delete_transient( $tname );
						
						if ( isset($cat_tubs[$post_id]) ) {
							unset($cat_tubs[$post_id]);
							update_post_meta($cat_id, 's_cat_tubs', $cat_tubs);
						}
					}
				}
				/*$moretransients = array( 's_tubcats', 's_allcats', 's_alltubs' );
				foreach ( $moretransients as $t ) {
					delete_transient( $t );
				}*/
				}
			}
		}
	return $info;
}

function sundance_admin_menu_cleanup() {
	global $menu;
	$restricted = array('Links','Comments');
	end ($menu);
	while (prev($menu)){
		$value = explode(' ',$menu[key($menu)][0]);
		if(in_array($value[0] != NULL?$value[0]:"" , $restricted)){
			unset($menu[key($menu)]);
		}
	}
	
}

function jht_all_tubs_images($tubs) {
	$o = array();
	return $o;	
}

// sets array of categories/collections, with sub arrays of associated TUBS
function sundance_series_setup() {
	// Get SERIES, exclude the ALL series
	$series = get_posts(array('numberposts'=>-1,'post_type'=>'s_cat','orderby'=>'menu_order','order'=>'ASC','exclude'=>1894));

	$alltubs = get_posts( array( 'numberposts' => -1, 'post_type' => 's_spa', 'orderby' => 'menu_order', 'order' => 'ASC' ) );

	$cats = array();

	foreach ( $series as $c ) {
		if ( ! isset( $cats[$c->ID] ) )
			$cats[$c->ID] = array();

		$cats[$c->ID]['name'] = $c->post_title;
		$cats[$c->ID]['url'] = get_bloginfo('url') .'/'. $c->post_name .'/'; //get_permalink($c->ID);
		$cats[$c->ID]['tubs'] = array();

		$custom = get_post_meta($c->ID);
		$cat_tubs = unserialize(fix_serialized_data( $custom['s_cat_tubs'][0] ));
		if($cat_tubs=='') {
			$cat_tubs = array();
		} else {
			usort($cat_tubs, 'sundance_tub_sort');
		}

		$cats[$c->ID]['tubs'] = $cat_tubs;
	}

	/*
	foreach ($alltubs as $tub) {
		$custom_c = get_post_meta($tub->ID, 's_cats');
		$cat_id = $custom_c[0][0];
		$custom_s = get_post_meta($tub->ID, 's_specs');
		$tub_specs = $custom_s[0];
		//var_dump($tub);
		$cats[$cat_id]['tubs'][$tub->ID] = array(
			'id' => $tub->ID,
			'url' => get_bloginfo('url') .'/'. $tub->post_name .'/',
			'name' => $tub->post_title,
			'size' => get_post_meta($tub->ID, 's_size', true),
			'jets' => 1,
			'vol' => ( sds_is_US() ? $tub_specs['vol_us'] : $tub_specs['vol_int'] ),
			);
		foreach ($tub_specs as $spec => $value) {
			$cats[$cat_id]['tubs'][ $tub->ID ][ $spec ] = $value;
		}
	}*/

	global $tubcats;
	$tubcats = $cats;
}

/* remove heartbeat when not needed */
add_action( 'init', 'my_deregister_heartbeat', 1 );
function my_deregister_heartbeat() {
	global $pagenow;

	if ( 'post.php' != $pagenow && 'post-new.php' != $pagenow )
		wp_deregister_script('heartbeat');
}



function sundance_add_scripts() {
	if ( ! is_admin() && !is_front_page()) {
		//wp_deregister_script('jquery');
		//wp_register_script( 'jquery', '//ajax.googleapis.com/ajax/libs/jquery/1.11.2/jquery.min.js', array(), '1.11.2', false);
		wp_enqueue_script( 'jquery-migrate','http://code.jquery.com/jquery-migrate-1.2.1.js',array('jquery'), '1.2.1', false);
		wp_enqueue_script( 'jquery-ui', '//ajax.googleapis.com/ajax/libs/jqueryui/1.11.2/jquery-ui.min.js', array('jquery'), '1.11.2', false );
		wp_enqueue_script( 'jquery-ui-tooltip', get_bloginfo('template_url') .'/js/jquery-ui-1.10.3.custom.min.js', array('jquery'), '1.10.4' );
		wp_enqueue_script( 'jquery.cookie', get_bloginfo('template_url') .'/js/jquery.cookie.js', array('jquery'), '1.0' );
		wp_enqueue_script( 'sundance-frontend', get_bloginfo('template_url') .'/js/frontend.js', array('jquery', 'jquery.cookie', 'thickbox'), '1.1' );
		wp_enqueue_script( 'tipr', get_bloginfo('template_url') .'/js/tipr/tipr.js', array('jquery'), '1' );
	}
}

function sundance_add_styles() {
	if ( ! is_admin() && !is_front_page()) {
			wp_enqueue_style('thickbox');
			
			$theme = wp_get_theme();
			wp_enqueue_style( 'sundance', get_bloginfo( 'stylesheet_url' ), array(), $theme->Version );
			wp_enqueue_style( 'jquery-ui', '//ajax.googleapis.com/ajax/libs/jqueryui/1.11.2/themes/smoothness/jquery-ui.css', array(), $theme->Version );
			wp_enqueue_style( 'jquery-ui-css', get_template_directory_uri() . '/css/ui-lightness/jquery-ui-1.10.3.custom.min.css', array(), $theme->Version );
			wp_enqueue_style( 'style-dealerpage',  get_template_directory_uri() . '/style-dealerpage.css', array(), $theme->Version );
			wp_enqueue_style( 'style-tipr',  get_template_directory_uri() . '/js/tipr/tipr.css', array(), $theme->Version );
	}
}

function sundance_custom_login_logo() { ?>
<style type="text/css">
.login h1 a { background: #797866 url(<?php bloginfo( 'template_url' ); ?>/images/sundance-spas.png) no-repeat 50% 50%; margin-bottom: 16px; padding-bottom: 0; width: 245px }
</style>
<?php
}

function sundance_custom_login_url() {
	return get_bloginfo( 'url' );
}

function sundance_shortdesc($content, $ellips = false, $onlyexcerpt = false) {
	$moreat = strpos($content, '<!--more-->');
	$oot = $content;
	if ( $moreat ) {
		$exc = substr($content, 0, $moreat);
		if ( $ellips == true ) {
			$exc .= '<span class="ellips">... </span>';
		}
		if ( $onlyexcerpt == true ) {
			$oot = force_balance_tags(apply_filters('the_content', $exc ));
		} else {
			$exc .= '<a href="#" class="readmore'. ($ellips ? ' ellip">Read' : '">Learn' ) .' More</a>';
			$oot = '<div class="exc">'. force_balance_tags(apply_filters('the_content', $exc )) .'</div>';
			$oot .= '<div style="display:none">'. apply_filters('the_content', $content).'</div>';
		}
	}
	return $oot;
}

function sundance_hr( $atts ){
	return '<div class="horzBorder"></div>';
}
add_shortcode( 'hr', 'sundance_hr' );

if ( ! function_exists( 'sundance_comments' ) ) :
/**
 * Template for comments and pingbacks.
 *
 * To override this walker in a child theme without modifying the comments template
 * simply create your own twentyten_comment(), and that function will be used instead.
 *
 * Used as a callback by wp_list_comments() for displaying the comments.
 *
 * @since Sundance 2.0
 */
function sundance_comments( $comment, $args, $depth ) {
	$GLOBALS['comment'] = $comment;
	switch ( $comment->comment_type ) :
		case '' :
	?>
	<li <?php comment_class(); ?> id="li-comment-<?php comment_ID(); ?>">
		<div id="comment-<?php comment_ID(); ?>">
		<?php if ( $comment->comment_approved == '0' ) : ?>
			<em class="comment-awaiting-moderation"><?php _e( 'Your comment is awaiting moderation.', 'twentyten' ); ?></em>
			<br />
		<?php endif; ?>

		<div class="comment-body"><?php comment_text(); ?></div>
		<div class="comment-meta">Posted by: <?php echo get_comment_author_link(); ?> &nbsp;|&nbsp; <?php
				/* translators: 1: date, 2: time */
				printf( __( '%1$s at %2$s', 'sundance' ), get_comment_date(),  get_comment_time() ); edit_comment_link( __( '(Edit)', 'twentyten' ), ' ' );
			?></div>
		<div class="reply">
			<?php comment_reply_link( array_merge( $args, array( 'depth' => $depth, 'max_depth' => $args['max_depth'] ) ) ); ?>
		</div><!-- .reply -->
	</div><!-- #comment-##  -->

	<?php
			break;
		case 'pingback'  :
		case 'trackback' :
	?>
	<li class="post pingback">
		<p><?php _e( 'Pingback:', 'twentyten' ); ?> <?php comment_author_link(); ?><?php edit_comment_link( __( '(Edit)', 'twentyten' ), ' ' ); ?></p>
	<?php
			break;
	endswitch;
}
endif;

function sundance_grouptubs($tubs) {
	$oot = array(
		'Large' => array(),
		'Medium' => array(),
		'Small' => array(),
		//'In-Ground' => array(),
	);
	foreach ( $tubs as $t ) {
		$oot[$t['size']][$t['id']] = $t;
	}
	foreach ( $oot as $s => $r ) {
		$c = count($r);
		if ( $c == 0 ) {
			unset($oot[$s]);
		} else {
			// sort?
			// pad to divisible by 4
			$rem = $c % 4;
			if ( $rem != 0 ) {
				for ( $i=$rem; $i<4; $i++ ) {
					$oot[$s][] = '';
				}
			}
		}
	}
	return $oot;
}

function sundance_series_tubs( $cat_tubs, $series_id ) {
	// transient for s_tubcats
	//$tname = 's_'. $series_id .'_tubtable';
	$bazaarvoices = array();
	//if ( false === ( $special_query_results = get_transient( $tname ) ) ) {
		$o = '<table class="tubGrid">';
		if ( count( $cat_tubs ) == 0 ) {
			$o .= '<tr><td><div><h3>None found</h3></div></td><td colspan="3">&nbsp;</td></tr>';
		} else {
			//echo '<pre style="display:none">'. print_r($cat_tubs,true) .'</pre>';
			$cat_tubs = sundance_grouptubs($cat_tubs);
			foreach ( $cat_tubs as $s => $r ) {
				if ( strtolower( esc_attr($s) ) !== 'in-ground' ) {
					$o .= '<tr>';
					$o .= '<td colspan="4"><h4>'. esc_attr($s) .' Spas</h4></td>';
					$o .= '</tr>';
				
					$c = 0;
					foreach ( $r as $i => $t ) {
						if ( $c == 0 ) {
							$o .= '<tr>';
						}
						$o .= '<td width="168">';
						if ( $t == '' ) {
							$o .= '&nbsp;';	
						} else {
							$spa_id = $t['id'];
							$custom = get_post_meta($spa_id, 's_specs', false);
							$specs = $custom[0];
							$t['dim_us'] = $specs['dim_us'];
							$t['dim_int'] = $specs['dim_int'];
							$bazaarvoices[] = $bazaarvoiceID = $specs['product_id'];
							$o .= '<a href="'. esc_url($t['url']) .'">';
							$o .= '<div class="tubThumb ' . esc_attr( strtolower( preg_replace( '/[^A-Za-z0-9]/', '', str_replace('&trade;','',$t['name'] ) ) ) ) . '" ><div class="tubViewDetails"></div></div>';
							$o .= '<div id="BVRRInlineRating-' . $bazaarvoiceID . '"></div>';
							$o .= '<span class="h3">'. esc_attr($t['name']) .'</span>';
							//$o .= '<span class="p">Seats: '. esc_attr($t['seats']) .'</span>';
							//$o .= '<span class="p">Total Jets: '. absint($t['jets']) .'</span>';
							//$o .= '<span class="p">Capacity: '. esc_attr($t['vol']) .'</span>';
							$o .= '<span class="p">Seats: '. esc_attr($t['seats']) .'</span>';
							$o .= '<span class="p">Dimensions:<br/> '. esc_attr($t['dim_us']) .'<br/><small>('. esc_attr($t['dim_int']) .')</small></span>';
							$o .= '<span class="p">Series: '. esc_attr(sundance_series($spa_id)) .'</span>';
														
							//$o .= '<span class="p"><img src="'. get_bloginfo('template_url') .'/images/icons/view-details-arrow.png" border="0" class="det" /></span>';
							$o .= '<span class="p"><div class="view-details"></div></span>';
							//if (class_exists('MultiPostThumbnails')) {
							//	$o .= MultiPostThumbnails::get_the_post_thumbnail('s_spa', 'overhead-large', $i, 'overhead-mid', array('class'=>'ov'));
							//}
							$o .= '</a>';
						}
						$o .= '</td>';
						if ( $c == 3 ) {
							$o .= '</tr>';
						}
						$c++;
					}
					$bazaarvoicetext = implode("','",$bazaarvoices);
					$bazaarvoicetext = "'".$bazaarvoicetext."'";
				}
			}
		}
		$o .= '</table>';
		ob_start();
		?>
					
			<script type="text/javascript">
				$BV.ui( 'rr', 'inline_ratings', {
					productIds : [<?php echo $bazaarvoicetext; ?>],
					containerPrefix : 'BVRRInlineRating'
				});
			</script>
		
		<?php
		$o .= ob_get_clean();
		set_transient( $tname, $o, 60*60*12 );
	}
	// Use the data like you would have normally...
	//$o = get_transient( $tname );
	return $o;
}

function sundance_acc_cats() {
	// transient for s_acc_cats
	if ( false === ( $special_query_results = get_transient( 's_acc_cats' ) ) ) {
		$o = '';
		$cats = get_terms('s_acc_cat', array(
			'orderby' => 'id',
		));
		foreach( $cats as $c ) {
			$o .= '<li><a href="'. get_term_link($c, 's_acc_cat') .'">'. esc_attr($c->name) .'</a></li>';
		}
		set_transient( 's_acc_cats', $o, 60*60*12 );
	}
	// Use the data like you would have normally...
	$o = get_transient( 's_acc_cats' );
	return $o;
}

function sundance_wplistpages_cache( $parent_id, $transname ) {
	if ( false === ( $special_query_results = get_transient( $transname ) ) ) {
		$o = wp_list_pages('child_of='. absint($parent_id) .'&title_li=&echo=0');
		set_transient( $transname, $o, 60*60*12 );
	}
	return get_transient( $transname );
}

/* lets try some transient stuff ... */
function sundance_pub_post_transient($post_id) {
	// sundance_latest5 : for RECENT POSTS in sidebar-blog.php
    delete_transient( 'sundance_latest5' );
	// sundance_latest10 : for blog slideshow
    delete_transient( 'sundance_latest10' );
}
add_action( 'publish_post', 'sundance_pub_post_transient' );

function sundance_pub_page_transients($p_id) {
	// wipe sitemap
	delete_transient( 's_sitemap' );
	
	// various transients on various pages / page templates
	$post = get_post($p_id);
	
	// s_feats_wimgs , on Features Landing page
	if ( is_page(2414) || ( $post->post_parent == 2414 ) ) {
    	delete_transient( 's_feats_wimgs' );
    	delete_transient( 's_feats_listpages' );
	}
	// s_backyard_listpages , Backyard Ideas subpages
	if ( is_page(2447) || ( $post->post_parent == 2447 ) ) {
    	delete_transient( 's_backyard_listpages' );
	}
	// s_healthben_listpages , Health Benefits subpages ( minus Hydrotherapy... )
	if ( is_page(2811) || ( $post->post_parent == 2811 ) ) {
    	delete_transient( 's_healthben_listpages' );
	}
	// s_difference_listpages , Sundance Difference subpages
	if ( is_page(2862) || ( $post->post_parent == 2862 ) ) {
    	delete_transient( 's_difference_listpages' );
    	delete_transient( 's_difference_subpages' );
	}
}
add_action( 'publish_page', 'sundance_pub_page_transients' );

// s_acc_cats : on sitemap.php & elsewhere?
function sundance_edit_acccat_transient($c_id) {
    delete_transient( 's_acc_cats' );
    delete_transient( 's_acc_cats_wimgs' );
}
add_action( 'edit_s_acc_cat', 'sundance_edit_acccat_transient' );

// s_allvids : on video page
function sundance_vid_transients($c_id) {
    delete_transient( 's_allvids' );
}
add_action( 'publish_s_vid', 'sundance_vid_transients' );

// s_allfeats : on spa details pages
function sundance_edit_allfeats_transient($c_id) {
    delete_transient( 's_allfeats' );
}
add_action( 'publish_s_feat', 'sundance_edit_allfeats_transient' );

// wp_list_categories : on front-page & sidebar-blog
function sundance_edit_cat_transients($c_id) {
    delete_transient( 'wp_list_categories' );
}
add_action( 'edit_category', 'sundance_edit_cat_transients' );

// [slug]-accs : on sitemap.php & elsewhere?
function sundance_publ_acc_transients($post_id) {
	$thispost = get_post($post_id);
	$thisterms = wp_get_object_terms($post_id, 's_acc_cat', array('fields' => 'slugs'));
	foreach ( $thisterms as $t ) {
		delete_transient( $t .'-accs' );
	}
}
add_action( 'publish_s_acc', 'sundance_publ_acc_transients' );

/*
function sundance_flush_tubcats_transients($post_id) {
	$thispost = get_post($post_id);
	// we know we want to flush jht_tubs
    delete_transient( 's_tubcats' );
    delete_transient( 's_tubcats_landing' );
    delete_transient( 's_htdrop' );
	
	switch ( $thispost->post_type ) {
		case 's_spa':
    		delete_transient( 's_alltubs' );
    		//delete_transient( 's_alltubimgs' );
			break;
		case 's_cat':
    		delete_transient( 's_allcats' );
			break;
		case 's_jet':
    		delete_transient( 's_alljets' );
    		delete_transient( 's_alljets_bycat' );
			break;
	}
}
add_action( 'publish_s_spa', 'sundance_flush_tubcats_transients' );
add_action( 'publish_s_cat', 'sundance_flush_tubcats_transients' );
add_action( 'publish_s_jet', 'sundance_flush_tubcats_transients' );
*/

function sundance_pagetitle() {
	global $post;
	$ptitle = get_post_meta($post->ID, 'pagetitle', true);
	if ( $ptitle != '' ) {
		esc_attr_e($ptitle);
	} else {
		the_title('');
	}
}


function sms_dealer_email() {
	if ( isset($_POST['emailTo']) ) {
		$mailTo = $_POST['emailTo'];
		$subject = $_POST['subject'];
		$message = $_POST['message'];
		$mailFrom = 'Sundance Spas <noreply@sundancespas.com>';
		wp_mail($mailTo, $subject, $message, "From: ".$mailFrom);
	}
}
add_action('wp_ajax_sms_dealer_email', 'sms_dealer_email');
add_action('wp_ajax_nopriv_sms_dealer_email', 'sms_dealer_email');


// cookie to collect browsing data

function search_engine_query_string($url = false) {
    if(!$url) {
        $url = isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : false;
    }
    if($url == false) {
        return '';
    }
    $parts = parse_url($url);
    parse_str($parts['query'], $query);
    $search_engines = array(
        'bing' => 'q',
        'google' => 'q',
        'yahoo' => 'p'
    );
    preg_match('/(' . implode('|', array_keys($search_engines)) . ')\./', $parts['host'], $matches);
    return isset($matches[1]) && isset($query[$search_engines[$matches[1]]]) ? $query[$search_engines[$matches[1]]] : '';
}

function search_engine_referrer($url = false) {
    if(!$url) {
        $url = isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : false;
    }
    if($url == false) {
        return '';
    }
    $parts = parse_url($url);
    return isset($parts['host']) ? $parts['host'] : '';
}

function track_visits() {
    $cName = $_SERVER['SERVER_NAME'] . "_visits";
    if ( !$_COOKIE["$cName"] ) {
        setcookie("$cName", '1', time() + (60 * 60 * 24 * 30), '/' );
    }
    else {
        $i = $_COOKIE["$cName"];
        $i++;
        setcookie("$cName", "$i", time() + (60 * 60 * 24 * 30), '/' );
    }
    return $_COOKIE["$cName"];
}

function track_pages_viewed() {
    $vName = $_SERVER['SERVER_NAME'] . "_pages";
    $page = $_SERVER['REQUEST_URI'];
    if ( !$_COOKIE["$vName"] ) {
        setcookie("$vName", "$page", 0, '/' );
    }
    else {
        $v = $_COOKIE["$vName"];
        $v .= "|" . $page;
        setcookie("$vName", "$v", 0, '/' );
    }
    return $_COOKIE["$vName"];
}

function track_page_count() {
    $cName = $_SERVER['SERVER_NAME'] . "_count";
    if ( !$_COOKIE["$cName"] ) {
        setcookie("$cName", '1', 0, '/' );
    }
    else {
        $c = $_COOKIE["$cName"];
        $c++;
        setcookie("$cName", "$c", 0, '/' );
    }
    return $_COOKIE["$cName"];
}

function parse_google_visits($i = 5) {
	$str = $_COOKIE['__utma'];
	$array = explode('.', $str);
	return $array[$i];
}

function parse_google_tos() {
	$str = $_COOKIE['__utmb'];
	$array = explode('.', $str);
	if ( $array[3] > 0 && $array[3] < time() ) {
		return $array[3];
	} else {
		return 0;
	}
}

function parse_google_page_views() {
	$str = $_COOKIE['__utmb'];
	$array = explode('.', $str);
	return $array[1];
}

function parse_google_referrers($var = null) {
	$str = $_COOKIE['__utmz'];
	$array = explode('.', $str);
	$subarray = explode('|', $array[4]);
	$results = array();
	foreach ($subarray as $key => $value) {
		$parts = explode('=', $value);
		$results[$parts[0]] = $parts[1];
	}
	return isset($results[$var]) ? $results[$var] : '';
}

function parse_history_tracker($var) {
	if($_COOKIE['History-Tracker']) {
		$str = $_COOKIE['History-Tracker'];
		$array = explode('::,::', $str);
		$results = array();
		$url_results = array();
		$i = count($array);
		foreach ($array as $key => $value) {
			$parts = explode('::::', $value);
			$results[$i] = $parts[0].' ('.$parts[1].')';
			$url_results[$parts[0]] = $parts[1];
			$i--;
		}
		ksort($results);
		$total = count($results);
		if ($var == 'count') {
			return $total;
		}
		if ($var == "array") {
			return $results;
		}
		if ($var == 'entry') {
			if ( $_COOKIE['History-Tracker-Tos'] ) {
				$str2 = $_COOKIE['History-Tracker-Tos'];
				$array2 = explode('=>', $str2);
				$results2 = array();
				$j = count($array2);
				foreach ($array2 as $key => $value) {
					$parts = explode('->', $value);
					$results2[$i] = $parts[0];
					$j--;
				}
				ksort($results2);
				return $results2[0];
			}
			return false;
		}
		if ($var = 'url') {
			$url_str = '<ul>';
			foreach ($url_results as $key => $value) {
				$addr = $value;
				$labl = $key;
				$url_str .= '<li><a href="'.$addr.'">'.$labl.'</a></li>';
			}
			$url_str .= "</ul>";
			return $url_str;
		}
		return implode('; ', $results);
	}
	if ($var = 'url') {
		$url_str = "<ul class='one'><li><a href='".get_permalink()."' target='_blank'>".get_the_title()."</a></li></ul>";
		return $url_str;
	}
	return false;
}

// # functions_avala.php is required for all lead forms...
include('functions_avala.php');

function sundance_404fix() {
	global $wp_query;
	if ( is_404() ) {
		/*
		 * make sure it really is...
		 * check for s_cat & s_spa
		 */
		$req = $_SERVER['REQUEST_URI'];
		if ( ( substr($req, -1) != '/' ) && ( strpos($req, '?s_cid=') === false ) ) {
			wp_redirect($req . '/');
			exit;
		}
		if ( strpos($req, 'accessories') > 0 ) {
			$unslashed = substr( $req, strrpos( $req, '/', -2)+1, -1 );
			$old_query = $wp_query;
			$args = array(
				'tax_query' => array(
					array(
						'taxonomy' => 's_acc_cat',
						'field' => 'slug',
						'terms' => $unslashed,
					)
				)
			);
			$wp_query = new WP_Query( $args );
			if ( $wp_query->post_count > 0 ) {
				$template = get_taxonomy_template();
				status_header(200);
				include($template);
				exit;
			} else {
				$wp_query = $old_query;
			}
		} else {
			$slashcount = substr_count($req, '/');
			if ( in_array($slashcount, array( 2, 3 ) ) ) {
				$old_query = $wp_query;
				$morevars = array( 'page' => 0 );
				if ( $slashcount == 2 ) {
					$ptype = 's_cat';
					//$unslashed = str_replace('/', '', $req);
				} else {
					$ptype = 's_spa';
					//$unslashed = substr( $req, strrpos( $req, '/', -2)+1, -1 );
				}
				$slash2 = strrpos($req, '/');
				$slash1 = strrpos(substr($req,0,$slash2-2), '/')+1;
				$unslashed = substr($req,$slash1, $slash2-$slash1);
				$wp_query = new WP_Query(array('post_type'=>$ptype, 'name'=> $unslashed));
				$morevars[$ptype] = $unslashed;
				
				if ( $wp_query->post_count > 0 ) {
					$wp_query->query_vars = array_merge($morevars, $wp_query->query_vars);
					$morevars['page'] = '';
					$wp_query->query = array_merge($morevars, $wp_query->query);
					
					global $post;
					$post = $wp_query->post;
					$template = get_single_template();
					status_header(200);
					include($template);
					exit;
				} else {
					$wp_query = $old_query;
				}
			}
		}
	}
}
//add_action( 'template_redirect', 'jht_404fix' );
function sundance_404fix2() {
	global $wp_query;
	global $post;
					//wp_die('<pre>'. print_r($wp_query,true) .'</pre>');
	
	// else, check for fixing permalinks of accessories & tubs & cats
	
	if ( ( $wp_query->query_vars['post_type'] == 's_acc' ) && ( $wp_query->post_count == 0 ) ) {
		
		/*
		 * make sure it really is...
		 * check for s_cat & s_spa
		 */
		//$req = $_SERVER['REQUEST_URI'];
		$req = strpos($_SERVER['REQUEST_URI'], "?") ? substr($_SERVER['REQUEST_URI'], 0, strpos($_SERVER['REQUEST_URI'], "?")) : $_SERVER['REQUEST_URI']; //debug??
	//		if ( ( substr($req, -1) != '/' ) && ( strpos($req, '?s_cid=') === false ) ) {
	//			wp_redirect($req . '/');
	//			exit;
	//		}
		if ( strpos($req, 'accessories') > 0 ) {
			$unslashed = substr( $req, strrpos( $req, '/', -2)+1, -1 );
			$old_query = $wp_query;
			$args = array(
				'tax_query' => array(
					array(
						'taxonomy' => 's_acc_cat',
						'field' => 'slug',
						'terms' => $unslashed,
					)
				)
			);
			$wp_query = new WP_Query( $args );
			if ( $wp_query->post_count > 0 ) {
	//				$template = get_taxonomy_template();
	//				status_header(200);
	//				include($template);
	//				exit;
			} else {
				$wp_query = $old_query;
			}
		}
	}
	if ( $wp_query->query_vars['error'] == '404' ) {
		
		/*
		 * make sure it really is...
		 * check for s_cat & s_spa
		 */
		//$req = $_SERVER['REQUEST_URI'];
		$req = strpos($_SERVER['REQUEST_URI'], "?") ? substr($_SERVER['REQUEST_URI'], 0, strpos($_SERVER['REQUEST_URI'], "?")) : $_SERVER['REQUEST_URI']; //debug??
	//		if ( ( substr($req, -1) != '/' ) && ( strpos($req, '?s_cid=') === false ) ) {
	//			wp_redirect($req . '/');
	//			exit;
	//		}
		if ( strpos($req, 'accessories') > 0 ) {
			$unslashed = substr( $req, strrpos( $req, '/', -2)+1, -1 );
			$old_query = $wp_query;
			$args = array(
				'tax_query' => array(
					array(
						'taxonomy' => 's_acc_cat',
						'field' => 'slug',
						'terms' => $unslashed,
					)
				)
			);
			$wp_query = new WP_Query( $args );
			if ( $wp_query->post_count > 0 ) {
	//				$template = get_taxonomy_template();
	//				status_header(200);
	//				include($template);
	//				exit;
			} else {
				$wp_query = $old_query;
			}
		} else {
			$slashcount = substr_count($req, '/');
			
			$catslash = 2;
			$tubslash = 3;
			/*
			if ( isset( $wp_query->query_vars['term'] ) ) {
				if ( ( $wp_query->query_vars['term'] != 'en' ) && ( !defined( 'JHTCA' ) ) ){
					$catslash = 3;
					$tubslash = 4;
				}
			}
			*/
			if ( in_array($slashcount, array( $catslash, $tubslash ) ) ) {
				$old_query = $wp_query;
				$morevars = array( 'page' => 0 );
				if ( $slashcount == $catslash ) {
					$ptype = 's_cat';
					//$unslashed = str_replace('/', '', $req);
				} else {
					$ptype = 's_spa';
					//$unslashed = substr( $req, strrpos( $req, '/', -2)+1, -1 );
				}
				$slash2 = strrpos($req, '/');
				$slash1 = strrpos(substr($req,0,$slash2-2), '/')+1;
				$unslashed = substr($req,$slash1, $slash2-$slash1);
				$wp_query = new WP_Query(array('post_type'=>$ptype, 'name'=> $unslashed));
				$morevars[$ptype] = $unslashed;
				
				if ( $wp_query->post_count > 0 ) {
					$wp_query->query_vars = array_merge($morevars, $wp_query->query_vars);
					$morevars['page'] = '';
					$wp_query->query = array_merge($morevars, $wp_query->query);
					
					$post = $wp_query->post;
	//					$template = get_single_template();
	//					status_header(200);
	//					include($template);
	//					wp_die('<pre>'. print_r($wp_query,true) .'</pre>');
	//					exit;
				} else {
					$wp_query = $old_query;
				}
			}
		}
	}
	$GLOBALS['wp_query'] = $wp_query;
	$GLOBALS['wp_the_query'] = $wp_query;
}
add_action( 'wp', 'sundance_404fix2', 98 );


function sds_getslug() {
	global $post;
	$slug = '';
	if ( is_object($post) ) {
		$slug = $post->post_name ;
	} else {
		$slug = get_post( $post )->post_name ;
	}
	return $slug;
}


function sds_copyright_meta() {
	if ( sds_getslug() !== 'spa-blog' || strpos($_SERVER['HTTP_HOST'], '/spa-blog/') === false ) { ?>
		<meta name="copyright" content="Sundance Spas." />
	<?php }
}
add_action('wp_head', 'sds_copyright_meta');



// session tracking
function tracker_session() {
	if( session_id() == '' ) {
		session_start();
	}
	$t = time();
	$title = wp_title('', false);
	$url = ( is_ssl() ? 'https://' : 'http://' ) . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
	$lru = strrev( $url );
	if ( strpos($url,'/wp-content/') !== false || $lru[0] !== '/' )
		return false;
	if ( empty( $_SESSION['page_history'] ) ) :
		unset($a);
		$a = array();
		$a[0] = array( '0' => $t, '1' => $t, 'title' => $title, 'url' => $url );
	else :
		$a = $_SESSION['page_history'];
		$i = count($a);
		$j = $i - 1 ;
		$a[$j][1] = $t;
		if ( $a[$j]['url'] != $url )
		{
			$a[$i] = array( '0' => $t, '1' => $t, 'title' => $title, 'url' => $url );
		}
	endif;
	$_SESSION['page_history'] = $a;
}
add_action('template_redirect','tracker_session'); // set the session data 

// return website tracking results from session data
function ws_track( $result_type = 'time_on_site' ) {
	if ( $_SESSION['page_history'] ) 
	{
		$a = $_SESSION['page_history'];
		$i = count($a);
		$j = $i - 1;
		if ($result_type == 'time_on_site') 
		{
			$time_on_site = time() - $a[0][0];
			return $time_on_site;
		}
		if ($result_type == 'page_count') { return $j; }
		if ($result_type == 'array') { return $a; }
		if ($result_type == 'enter_time') { return $a[0][0]; }
		if ($result_type == 'exit_time') { return $a[$j][1]; }
		if ($result_type == 'pages_viewed') {
			$output = '<ul>';
			$z = array_slice( $a, -6 );
			foreach ( $z as $b ) 
			{
				$output .= '<li><a href="' . $b['url'] . '" target="_blank">' . $b['title'] . '</a></li>';
			}
			$output .= '</ul>';
			return $output;
		}
	}
	return false;
}
// Time on site tracking
function time_on_site() {
	$t = 0;
	if ( !function_exists('ws_track') ) {
		return $t;
	}
	if ( ws_track('enter_time') > 0 && ws_track('enter_time') < time() ) {
		$total_time_in_seconds = ( time() - ws_track('enter_time') );
		$h = floor( $total_time_in_seconds / (60 * 60) );
		$m = floor( ( $total_time_in_seconds - ( $h * 60 * 60 ) ) / 60 );
		$s = floor( ( $total_time_in_seconds - ( ( $h * 60 * 60 ) + ( $m * 60 ) ) ) );
		$t = $h . ":" . str_pad($m, 2, "0", STR_PAD_LEFT) . ":" . str_pad($s, 2, "0", STR_PAD_LEFT);
	}
	return $t;
}
// get keywords from referrer
function get_search_keywords( $url = '' ) {
	// Get the referrer
	$referrer = (!empty($_SERVER['HTTP_REFERER'])) ? $_SERVER['HTTP_REFERER'] : '';
	$referrer = (!empty($url)) ? $url : $referrer;
	if (empty($referrer))
		return false;
	// Parse the referrer URL
	$parsed_url = parse_url($referrer);
	if (empty($parsed_url['host']))
		return false;
	$host = $parsed_url['host'];
	$query_str = (!empty($parsed_url['query'])) ? $parsed_url['query'] : '';
	$query_str = (empty($query_str) && !empty($parsed_url['fragment'])) ? $parsed_url['fragment'] : $query_str;
	if (empty($query_str))
		return false;
	// Parse the query string into a query array
	parse_str($query_str, $query);
	// Check some major search engines to get the correct query var
	$search_engines = array(
		'q' => 'alltheweb|aol|ask|ask|bing|google',
		'p' => 'yahoo',
		'wd' => 'baidu'
	);
	foreach ($search_engines as $query_var => $se)
	{
		$se = trim($se);
		preg_match('/(' . $se . ')\./', $host, $matches);
		if (!empty($matches[1]) && !empty($query[$query_var]))
			return $query[$query_var];
	}
	return false;
}

// returns formatted phone number
function format_phone_us( $phone = '', $format='standard', $convert = true, $trim = true ) {
	if ( empty( $phone ) ) {
		return false;
	}
	// Strip out non alphanumeric
	$phone = preg_replace( "/[^0-9A-Za-z]/", "", $phone );
	// Keep original phone in case of problems later on but without special characters
	$originalPhone = $phone;
	// If we have a number longer than 11 digits cut the string down to only 11
	// This is also only ran if we want to limit only to 11 characters
	if ( $trim == true && strlen( $phone ) > 11 ) {
		$phone = substr( $phone, 0, 11 );
	}
	// letters to their number equivalent
	if ( $convert == true && !is_numeric( $phone ) ) {
		$replace = array(
			'2'=>array('a','b','c'),
			'3'=>array('d','e','f'),
			'4'=>array('g','h','i'),
			'5'=>array('j','k','l'),
			'6'=>array('m','n','o'),
			'7'=>array('p','q','r','s'),
			'8'=>array('t','u','v'),
			'9'=>array('w','x','y','z'),
			);
		foreach ( $replace as $digit => $letters ) {
			$phone = str_ireplace( $letters, $digit, $phone );
		}
	}
	$a = $b = $c = $d = null;
	switch ( $format ) {
		case 'standard':
			$a = '(';
			$b = ') ';
			$c = '-';
			$d = '(';
			break;
		case 'decimal':
			$a = '';
			$b = '.';
			$c = '.';
			$d = '.';
			break;
		case 'period':
			$a = '';
			$b = '.';
			$c = '.';
			$d = '.';
			break;
		case 'hypen':
			$a = '';
			$b = '-';
			$c = '-';
			$d = '-';
			break;
		case 'dash':
			$a = '';
			$b = '-';
			$c = '-';
			$d = '-';
			break;
		case 'space':
			$a = '';
			$b = ' ';
			$c = ' ';
			$d = ' ';
			break;
		default:
			$a = '(';
			$b = ') ';
			$c = '-';
			$d = '(';
			break;
	}
	$length = strlen( $phone );
	// Perform phone number formatting here
	switch ( $length ) {
		case 7:
			// Format: xxx-xxxx / xxx.xxxx / xxx-xxxx / xxx xxxx
			return preg_replace( "/([0-9a-zA-Z]{3})([0-9a-zA-Z]{4})/", "$1$c$2", $phone );
		case 10:
			// Format: (xxx) xxx-xxxx / xxx.xxx.xxxx / xxx-xxx-xxxx / xxx xxx xxxx
			return preg_replace( "/([0-9a-zA-Z]{3})([0-9a-zA-Z]{3})([0-9a-zA-Z]{4})/", "$a$1$b$2$c$3", $phone );
		case 11:
			// Format: x(xxx) xxx-xxxx / x.xxx.xxx.xxxx / x-xxx-xxx-xxxx / x xxx xxx xxxx
			return preg_replace( "/([0-9a-zA-Z]{1})([0-9a-zA-Z]{3})([0-9a-zA-Z]{3})([0-9a-zA-Z]{4})/", "$1$d$2$b$3$c$4", $phone );
		default:
			// Return original phone if not 7, 10 or 11 digits long
			return $originalPhone;
	}
}

// GEO FUNCTIONS

require('functions_geo.php');

// geo redirect
if ( ! function_exists('geo_redirection') ) {
	function geo_redirection() {

		if ( isset($_COOKIE['georesult']) ) {
			$g = json_decode(stripcslashes($_COOKIE['georesult']), true);
		}
		else {
		$g = geo_data();
		}
		$c = $g['country'];

		switch ($c) {
			case 'CA':
				if ( is_front_page() && get_bloginfo('url') == 'http://www.sundancespas.com/' ) {
					wp_redirect('http://www.sundancespas.ca/');
					//exit();
				}
				break;
			default:
				break;
		}
	}
}
add_action( 'wp', 'geo_redirection' );


function sds_is_US() {
	return ( get_bloginfo('url') == 'http://www.sundancespas.com/' ? true : false );
}


// [get_blog_info dir="url"]
if ( ! function_exists('s_code_get_site_dir') ):
	function s_code_get_site_dir( $atts ) {

		extract( shortcode_atts( array(
			'dir' => 'url',
		), $atts ) );

		$url = get_bloginfo( $dir );

		return $url;

	}
	add_shortcode( 'get_blog_info', 's_code_get_site_dir' );
endif;



// Google Tag Manager Main
add_action('google_tag_manager', 'google_tag_manager_container', 10);
function google_tag_manager_container() {
	$str = "<!-- Google Tag Manager -->
	<noscript><iframe src=\"//www.googletagmanager.com/ns.html?id=GTM-NTFWKQ\"
	height=\"0\" width=\"0\" style=\"display:none;visibility:hidden\"></iframe></noscript>
	<script>(function(w,d,s,l,i){w[l]=w[l]||[];w[l].push({'gtm.start':
	new Date().getTime(),event:'gtm.js'});var f=d.getElementsByTagName(s)[0],
	j=d.createElement(s),dl=l!='dataLayer'?'&l='+l:'';j.async=true;j.src=
	'//www.googletagmanager.com/gtm.js?id='+i+dl;f.parentNode.insertBefore(j,f);
	})(window,document,'script','dataLayer','GTM-NTFWKQ');</script>
	<!-- End Google Tag Manager -->";
	echo $str;
}
function google_tag_manager() {
	do_action('google_tag_manager');
}

// Google Tag Manager Criteo
add_action('google_tag_manager_criteo', 'google_tag_manager_criteo_container', 10);
function google_tag_manager_criteo_container() {
	$str = "<!-- Google Tag Manager -->
	<noscript><iframe src=\"//www.googletagmanager.com/ns.html?id=GTM-PWJ2SH\"
	height=\"0\" width=\"0\" style=\"display:none;visibility:hidden\"></iframe></noscript>
	<script>(function(w,d,s,l,i){w[l]=w[l]||[];w[l].push({'gtm.start':
	new Date().getTime(),event:'gtm.js'});var f=d.getElementsByTagName(s)[0],
	j=d.createElement(s),dl=l!='dataLayer'?'&l='+l:'';j.async=true;j.src=
	'//www.googletagmanager.com/gtm.js?id='+i+dl;f.parentNode.insertBefore(j,f);
	})(window,document,'script','dataLayer','GTM-PWJ2SH');</script>
	<!-- End Google Tag Manager -->";
	echo $str;
}
function google_tag_manager_criteo() {
	do_action('google_tag_manager_criteo');
}


// Custom data layer
add_action('do_custom_data_layer', 'custom_data_layer_container');
function custom_data_layer_container() {
	global $post;
	
	$expire = time()+60*60*24*30;

	$custId = get_current_user_id() > 0 ? get_current_user_id() : ( isset($_COOKIE["sdscid"]) ? $_COOKIE["sdscid"] : rand( 1000000, 1000000000 ) );
	$prodId = isset($_COOKIE["sdsspa"]) ? $_COOKIE["sdsspa"] : '' ;
	//setcookie("sdscid", $custId, $expire, '/');
	

	$str = '<script>dataLayer = [{';
	$str .= '"get":' . json_encode($_SERVER['QUERY_STRING']) . ',';
	
	if ( isset($_COOKIE['georesult']) ) {
		$a = json_decode(stripcslashes($_COOKIE['georesult']), true);
		$str .= '"geo":' . json_encode( $a ) . ',';
	}
	
	$str .= '"customerId":"' . $custId . '",';
	
	if ( get_post_type($post->ID) == "s_spa" ) { // is single spa page
		$parts = explode( "&", get_the_title($post->ID) );
		$prodId = $parts[0];
		//setcookie("sdsspa", $prodId, $expire, '/');

		$str .= '"productId":"' . $prodId . '",';
	}
	if ( get_post_type($post->ID) == "s_cat" ) { // is category spa page
		//$custom = get_post_meta($post->ID, 's_cat_tubs');
		//$cat_tubs = $custom[0];
		$custom = get_post_meta($post->ID);
		$cat_tubs = unserialize(fix_serialized_data( $custom['s_cat_tubs'][0] ));
		$str_list = '';
		if ( count( $cat_tubs ) !== 0  && is_array($cat_tubs) ) {
			$i = 0;
			foreach( $cat_tubs as $k => $v ) {
				$i++;
				$parts = explode( "&", $v['name'] );
				$name = $parts[0];
				$str_list .= '"listingId' . $i . '":"' . $name . '",';
				if ( $i == 3 ) break;
			}
			$str .= $str_list;
		}
	}
	if ( is_page('request-literature') || is_page('sundance-brochure') ) { // brochure page - paid search
		$transId = time();
		if ( $prodId )
			$str .= '"productId":"' . $prodId . '",';
	}
	if ( is_page('thanks') ) { // brochure thanks page
		$transId = time();
		if ( $prodId )
			$str .= '"productId":"' . $prodId . '",';
		$str .= '"transactionId":"' . $transId . '",';
	}
	$str .= '}];</script>';
	
	echo $str;
}
function custom_data_layer() {
	do_action('do_custom_data_layer');
}
add_action('init', 'data_layer_cookie');
function data_layer_cookie() {
	if ( ! is_admin() ):
		$expire = time()+60*60*24*30;
		$custId = get_current_user_id() > 0 ? get_current_user_id() : ( isset($_COOKIE["sdscid"]) ? $_COOKIE["sdscid"] : rand( 1000000, 1000000000 ) );
		setcookie("sdscid", $custId, $expire, '/');
		if ( get_post_type() == "s_spa" ) { // is single spa page
			$parts = explode( "&", get_the_title($post->ID) );
			$prodId = $parts[0];
			setcookie("sdsspa", $prodId, $expire, '/');
		}
	endif;
}


// Tracking Codes
include('functions_trackingcodes.php');



/**
* Is SubPage?
*
* Checks if the current page is a sub-page and returns true or false.
*
* @param  $page mixed optional ( post_name or ID ) to check against.
* @return boolean
*/
function sds_is_subpage( $page = null )
{
    global $post;
    // is this even a page?
    if ( ! is_page() )
        return false;
    // does it have a parent?
    if ( ! isset( $post->post_parent ) OR $post->post_parent <= 0 )
        return false;
    // is there something to check against?
    if ( ! isset( $page ) ) {
        // yup this is a sub-page
        return true;
    } else {
        // if $page is an integer then its a simple check
        if ( is_int( $page ) ) {
            // check
            if ( $post->post_parent == $page )
                return true;
        } else if ( is_string( $page ) ) {
            // get ancestors
            $parent = get_ancestors( $post->ID, 'page' );
            // does it have ancestors?
            if ( empty( $parent ) )
                return false;
            // get the first ancestor
            $parent = get_post( $parent[0] );
            // compare the post_name
            if ( $parent->post_name == $page )
                return true;
        }
        return false;
    }
}



function sharpspring_become_a_dealer() {
	$str = '<script type="text/javascript">
		var _ss = _ss || [];
		_ss.push(\'_setDomain\', \'https://koi-7AFC9LNY.sharpspring.com/net\');
		_ss.push(\'_setAccount\', \'KOI-II72QWOE\');
		_ss.push(\'_trackPageView\');
		(function(){ 
			var ss = document.createElement(\'script\');
			ss.type = \'text/javascript\';
			ss.async = true;
			ss.src = (\'https:\' == document.location.protocol ? \'https://\' : \'http://\') + \'koi-7AFC9LNY.sharpspring.com/client/ss.js?ver=1.1.1\';
			var scr = document.getElementsByTagName(\'script\')[0];
			scr.parentNode.insertBefore(ss, scr); 
		}
		)();
		</script>';
	if ( sds_is_subpage('become-a-dealer') || is_page( 'become-a-dealer' ) )
		echo $str;
}

add_action( 'wp_head', 'sharpspring_become_a_dealer' );


/*	*	*	*	*	*	*	*	*
 *
 *	SDS Startup Guide Web app
 *
 *	*	*	*	*	*	*	*	*/

// StartUp Guide Ajaxify
function startupguide_ajaxify() {
	wp_enqueue_script( 'startupguide-ajax-request', get_template_directory_uri() . '/js/startupguide-ajax.js', array( 'jquery' ) );
	wp_localize_script( 'startupguide-ajax-request', 'StartupAjax', array( 'ajaxurl' => admin_url( 'admin-ajax.php' ) ) );
}
add_action('wp_enqueue_scripts', 'startupguide_ajaxify');

add_action( 'wp_ajax_load-content', 'startupguide_ajax_content'); // for users logged-in
add_action ( 'wp_ajax_nopriv_load-content', 'startupguide_ajax_content' ); // guests
function startupguide_ajax_content() {
	global $tubcats;
    $post_id = $_POST[ 'post_id' ];

    $post = get_post( $post_id, OBJECT);
    $response = apply_filters( 'the_content', $post->post_content );

	$custom = get_post_meta($post_id,'s_cats');
	$cats = $custom[0];
	$series_id = $cats[0];

	$spa_series = $tubcats[ $series_id ]['name'];
	$spa_name = str_replace("&trade;", "", get_the_title($post_id) );

	// integer starts at 0 before counting
	$air_count=0;
    $air_dir = new DirectoryIterator( dirname(__FILE__) . '/images/startup/' . strtolower($spa_name) . '/air/' );
	foreach ($air_dir as $airfileinfo) {
	    if ($airfileinfo->isFile()) {
	    	if ( preg_match( "/(r)([0-9]{1,2})(\.png)/" ,$airfileinfo->getFilename() ) ) {
	    		$air_count++;
	    	}
	    }
	}
	$massage_count=0;
    $massage_dir = new DirectoryIterator( dirname(__FILE__) . '/images/startup/' . strtolower($spa_name) . '/massage/' );
	foreach ($massage_dir as $massagefileinfo) {
	    if ($massagefileinfo->isFile()) {
	    	if ( preg_match( "/(r)([0-9]{1,2})(\.png)/" ,$massagefileinfo->getFilename() ) ) {
	    		$massage_count++;
	    	}
	    }
	}
    
    
    ?>
    <div id="sg-details-<?php echo strtolower($spa_name); ?>" class="spa-details">

    	<section id="sg-details-landing" class="sg-details active">
    		<span>
    			<h3><?php echo $spa_series; ?> SERIES</h3>
    			<h1><?php echo $spa_name; ?></h1>
    		</span>
    	</section>

    	<section id="sg-details-air" class="sg-details">

    		<div id="sg-air-landing-container" class="home active">

    			<h4 class="goback"><span><?php echo $spa_series . ' ' . $spa_name; ?> : </span>AIR CONTROL</h4>

    			<div id="sg-air-overhead">
					<img class="sg-air-overhead" src="<?php echo get_template_directory_uri() . '/images/startup/' . strtolower($spa_name) . '/air/overhead.png'; ?>" />
					<img class="sg-air-overhead-selectables" src="<?php echo get_template_directory_uri() . '/images/startup/' . strtolower($spa_name) . '/air/selectables.png'; ?>" />
					<?php for ( $i = 1; $i <= $air_count; $i++ ) { ?>
						<img id="sg-air-rollover-<?php echo $i; ?>" class="sg-air-layer" src="<?php echo get_template_directory_uri() . '/images/startup/' . strtolower($spa_name) . '/air/r' . $i . '.png'; ?>" />
						<a id="sg-air-anchor-<?php echo $i; ?>" class="sg-air-anchor" rel="sg-air-rollover-<?php echo $i; ?>"></a>
					<?php } ?>
				</div>

    		</div>

    	</section>

    	<section id="sg-details-massage" class="sg-details">
    		
    		<div id="sg-massage-landing-container" class="home active">

    			<h4 class="goback"><span><?php echo $spa_series . ' ' . $spa_name; ?> : </span>MASSAGE SELECTOR</h4>

    			<div id="sg-massage-overhead">

    				<div id="sg-massage-top-bar">
						<p>Diverter Control:</p>
						<div id="sg-massage-slider"></div>
					</div>
					<img class="sg-massage-overhead" src="<?php echo get_template_directory_uri() . '/images/startup/' . strtolower($spa_name) . '/massage/overhead.png'; ?>" />
					<img class="sg-massage-overhead-selectables" src="<?php echo get_template_directory_uri() . '/images/startup/' . strtolower($spa_name) . '/massage/selectables.png'; ?>" />
					<?php for ( $i = 1; $i <= $massage_count; $i++ ) { ?>
						<img id="sg-massage-rollover-<?php echo $i; ?>" class="sg-massage-layer" src="<?php echo get_template_directory_uri() . '/images/startup/' . strtolower($spa_name) . '/massage/r' . $i . '.png'; ?>" />
						<img id="sg-massage-rollover-<?php echo $i; ?>-a" class="sg-massage-layer" src="<?php echo get_template_directory_uri() . '/images/startup/' . strtolower($spa_name) . '/massage/r' . $i . '-a.png'; ?>" />
						<img id="sg-massage-rollover-<?php echo $i; ?>-b" class="sg-massage-layer" src="<?php echo get_template_directory_uri() . '/images/startup/' . strtolower($spa_name) . '/massage/r' . $i . '-b.png'; ?>" />
						<a id="sg-massage-anchor-<?php echo $i; ?>" class="sg-massage-anchor" rel="sg-massage-rollover-<?php echo $i; ?>"></a>
					<?php } ?>
				</div>

    		</div>

    	</section>

    	<section id="sg-details-panel" class="sg-details">

    		<div id="sg-panel-landing-container" class="home active">
				<img id="sg-panel-landing-bg" class="" src="<?php echo get_template_directory_uri() . '/images/startup/' . strtolower($spa_name) . '/jets/' . strtolower($spa_name) . '-control-panel.png'; ?>" />
    			<button id="sg-panel-explore-jets"></button>
    			<div id="sg-panel-landing-rightbar" class="toggle-me closed">
    				<div id="sg-panel-landing-rightbar-openclose" icon="sg-cp-rightbar"></div>
    				<div id="sg-panel-landing-rightbar-temp" icon="sg-cp-rightbar">
    					<h3>Temperature</h3>
    					<p>Warmer and Cooler Buttons: These buttons display and increase or decrease temperature setting and other programming features.</p>
    				</div>
    				<div id="sg-panel-landing-rightbar-lighting" icon="sg-cp-rightbar">
    					<h3>Lighting</h3>
    					<p>The 880<sup>&trade;</sup> Series features colorful LED lighting for evening spa sessions. The AquaTerrace<sup>&trade;</sup> water feature also features colored backlighting, adding a tropical touch to your spa.</p>
    				</div>
    				<div id="sg-panel-landing-rightbar-setting" icon="sg-cp-rightbar">
    					<h3>Settings</h3>
    					<p>The main control panel functions on your Sundance spa include filter programing, mode buttons to go from eco or standard mode, display time and much more.</p>
    				</div>
    			</div>
    		</div>

    		<div id="sg-panel-overhead-container">

    			<h4><span><?php echo $spa_series . ' ' . $spa_name; ?> : </span>CONTROL PANEL</h4>

				<div id="sg-panel-top-bar">
					<div class="sg-back-arrow"><span></span></div>
					<p>Jet Controls:</p>
					<button id="sg-btn-jets" layer="jets"></button>
					<button id="sg-btn-bubbles" layer="bubbles"></button>
					<button id="sg-btn-bubbles2" layer="bubbles2"></button>
				</div>

				<div id="sg-panel-overhead">
					<img class="sg-overhead" src="<?php echo get_template_directory_uri() . '/images/startup/' . strtolower($spa_name) . '/jets/' . strtolower($spa_name) . '-panel-overhead.png'; ?>" />
					<img id="sg-jets" class="sg-layer jets" src="<?php echo get_template_directory_uri() . '/images/startup/' . strtolower($spa_name) . '/jets/' . strtolower($spa_name) . '-panel-layer-jets.png'; ?>" />
					<img id="sg-bubbles" class="sg-layer bubbles" src="<?php echo get_template_directory_uri() . '/images/startup/' . strtolower($spa_name) . '/jets/' . strtolower($spa_name) . '-panel-layer-bubbles.png'; ?>" />
					<img id="sg-bubbles2" class="sg-layer bubbles2" src="<?php echo get_template_directory_uri() . '/images/startup/' . strtolower($spa_name) . '/jets/' . strtolower($spa_name) . '-panel-layer-bubbles2.png'; ?>" />
				</div>
			</div>

    	</section>

    </div>

    <?php
    //echo $response;
    die(1);
}

//wp_enqueue_script( 'startupguide-mobile-ajax-request', get_template_directory_uri() . '/js/startupguide-ajax.js', array( 'jquery' ) );
//wp_localize_script( 'startupguide-mobile-ajax-request', 'StartupMobileAjax', array( 'mobileajaxurl' => admin_url( 'admin-ajax.php' ) ) );

add_action( 'wp_ajax_load-mobile-content', 'startupguide_mobile_ajax_content'); // for users logged-in
add_action ( 'wp_ajax_nopriv_load-mobile-content', 'startupguide_mobile_ajax_content' ); // guests
function startupguide_mobile_ajax_content() {
	global $tubcats;
    $post_id = $_POST[ 'post_id' ];

    $post = get_post( $post_id, OBJECT);
    $response = apply_filters( 'the_content', $post->post_content );

	$custom = get_post_meta($post_id,'s_cats');
	$cats = $custom[0];
	$series_id = $cats[0];

	$spa_series = $tubcats[ $series_id ]['name'];
	$spa_name = str_replace("&trade;", "", get_the_title($post_id) );

	// integer starts at 0 before counting
	$air_count=0;
    $air_dir = new DirectoryIterator( dirname(__FILE__) . '/images/startup/' . strtolower($spa_name) . '/air/' );
	foreach ($air_dir as $airfileinfo) {
	    if ($airfileinfo->isFile()) {
	    	if ( preg_match( "/(r)([0-9]{1,2})(\.png)/" ,$airfileinfo->getFilename() ) ) {
	    		$air_count++;
	    	}
	    }
	}
	$massage_count=0;
    $massage_dir = new DirectoryIterator( dirname(__FILE__) . '/images/startup/' . strtolower($spa_name) . '/massage/' );
	foreach ($massage_dir as $massagefileinfo) {
	    if ($massagefileinfo->isFile()) {
	    	if ( preg_match( "/(r)([0-9]{1,2})(\.png)/" ,$massagefileinfo->getFilename() ) ) {
	    		$massage_count++;
	    	}
	    }
	}
    
    
    ?>
    <div id="sg-details-<?php echo strtolower($spa_name); ?>" class="spa-details">

    	<section id="sg-details-air" class="sg-details">

    		<div id="sg-air-landing-container" class="home active">

    			<h4 class="goback">

    				<div class="chevron chevron-left"></div>

    				AIR CONTROL

    			</h4>

    			<div id="sg-air-overhead">
					<img class="sg-air-overhead" src="<?php echo get_template_directory_uri() . '/images/startup/' . strtolower($spa_name) . '/air/overhead.png'; ?>" />
					<img class="sg-air-overhead-selectables" src="<?php echo get_template_directory_uri() . '/images/startup/' . strtolower($spa_name) . '/air/selectables.png'; ?>" />
					<?php for ( $i = 1; $i <= $air_count; $i++ ) { ?>
						<img id="sg-air-rollover-<?php echo $i; ?>" class="sg-air-layer" src="<?php echo get_template_directory_uri() . '/images/startup/' . strtolower($spa_name) . '/air/r' . $i . '.png'; ?>" />
						<a id="sg-air-anchor-<?php echo $i; ?>" class="sg-air-anchor" rel="sg-air-rollover-<?php echo $i; ?>"></a>
					<?php } ?>
				</div>

    		</div>

    	</section>

    	<section id="sg-details-massage" class="sg-details">
    		
    		<div id="sg-massage-landing-container" class="home active">

    			<h4 class="goback">

    				<div class="chevron chevron-left"></div>

    				MASSAGE<br />SELECTOR

    			</h4>

    			<div id="sg-massage-overhead">

					<img class="sg-massage-overhead" src="<?php echo get_template_directory_uri() . '/images/startup/' . strtolower($spa_name) . '/massage/overhead.png'; ?>" />
					<img class="sg-massage-overhead-selectables" src="<?php echo get_template_directory_uri() . '/images/startup/' . strtolower($spa_name) . '/massage/selectables.png'; ?>" />
					<?php for ( $i = 1; $i <= $massage_count; $i++ ) { ?>
						<img id="sg-massage-rollover-<?php echo $i; ?>" class="sg-massage-layer main" src="<?php echo get_template_directory_uri() . '/images/startup/' . strtolower($spa_name) . '/massage/r' . $i . '.png'; ?>" />
						<img id="sg-massage-rollover-<?php echo $i; ?>-a" class="sg-massage-layer" src="<?php echo get_template_directory_uri() . '/images/startup/' . strtolower($spa_name) . '/massage/r' . $i . '-a.png'; ?>" />
						<img id="sg-massage-rollover-<?php echo $i; ?>-b" class="sg-massage-layer" src="<?php echo get_template_directory_uri() . '/images/startup/' . strtolower($spa_name) . '/massage/r' . $i . '-b.png'; ?>" />
						<a id="sg-massage-anchor-<?php echo $i; ?>" class="sg-massage-anchor" rel="sg-massage-rollover-<?php echo $i; ?>"></a>
					<?php } ?>
				</div>

				<div id="sg-massage-top-bar">

					<input type="range" id="sg-massage-slider" value="50" min="0" max="100" />

				</div>

    		</div>

    	</section>

    	<section id="sg-details-panel" class="sg-details">

    		<div id="sg-panel-landing-container" class="home active">

    			<h4 class="goback">

    				<div class="chevron chevron-left"></div>

    				CONTROL<br />PANEL

    			</h4>

				<img id="sg-panel-landing-bg" class="" src="<?php echo get_template_directory_uri() . '/images/startup/' . strtolower($spa_name) . '/jets/' . strtolower($spa_name) . '-cp-mobile.png'; ?>" />

				<ul class="nav-control-panel">

					<li id="show-cp-jets" class="details">

						<div class="chevron chevron-right"></div>

						<div icon="cp-jets" class="wrapr">

							<span>Jets</span>

						</div>

					</li>

					<li id="show-cp-temp" class="details">

						<div class="chevron chevron-down"></div>

						<div icon="cp-temp" class="wrapr">

							<span>Temperature</span>

						</div>

						<div>

							<p>Warmer and Cooler Buttons: These buttons display and increase or decrease temperature setting and other programming features.</p>

						</div>

					</li>

					<li id="show-cp-light" class="details">

						<div class="chevron chevron-down"></div>

						<div icon="cp-light" class="wrapr">

							<span>Lighting</span>

						</div>

						<div>

							<p>The 880<sup>&trade;</sup> Series features colorful LED lighting for evening spa sessions. The AquaTerrace<sup>&trade;</sup> water feature also features colored backlighting, adding a tropical touch to your spa.</p>

						</div>

					</li>

					<li id="show-cp-setting" class="details">

						<div class="chevron chevron-down"></div>

						<div icon="cp-setting" class="wrapr">

							<span>Settings</span>

						</div>

						<div>

							<p>The main control panel functions on your Sundance spa include filter programing, mode buttons to go from eco or standard mode, display time and much more.</p>

						</div>

					</li>

				</ul>

    		</div>

    		<div id="sg-panel-overhead-container">

    			<h4 class="goback fromjets">

    				<div class="chevron chevron-left"></div>

    				JETS

    			</h4>

				<div id="sg-panel-top-bar">
					<button id="sg-btn-jets" layer="jets"></button>
					<button id="sg-btn-bubbles" layer="bubbles"></button>
					<button id="sg-btn-bubbles2" layer="bubbles2"></button>
				</div>

				<div id="sg-panel-overhead">
					<img class="sg-overhead" src="<?php echo get_template_directory_uri() . '/images/startup/' . strtolower($spa_name) . '/jets/' . strtolower($spa_name) . '-panel-overhead.png'; ?>" />
					<img id="sg-jets" class="sg-layer jets" src="<?php echo get_template_directory_uri() . '/images/startup/' . strtolower($spa_name) . '/jets/' . strtolower($spa_name) . '-panel-layer-jets.png'; ?>" />
					<img id="sg-bubbles" class="sg-layer bubbles" src="<?php echo get_template_directory_uri() . '/images/startup/' . strtolower($spa_name) . '/jets/' . strtolower($spa_name) . '-panel-layer-bubbles.png'; ?>" />
					<img id="sg-bubbles2" class="sg-layer bubbles2" src="<?php echo get_template_directory_uri() . '/images/startup/' . strtolower($spa_name) . '/jets/' . strtolower($spa_name) . '-panel-layer-bubbles2.png'; ?>" />
				</div>
			</div>

    	</section>

    </div>

    <?php
    //echo $response;
    die(1);
}


function sds_my_server() {
	$url = get_bloginfo('url');
	switch ( $url ) {
		case 'http://www.sundancespas.com' :
		case 'http://www.sundancespas.com/' :
		case 'http://www.sundancespas.ca' :
		case 'http://www.sundancespas.ca/' :
			return 'live';
			break;
		case 'http://sds.nlkdev.net' :
		case 'http://sds.nlkdev.net/' :
			return 'dev';
			break;
		case 'http://localhost/sundancespas.com' :
		case 'http://localhost/sundancespas.com/' :
		case 'http://localhost.sundancespas.com' :
		case 'http://localhost.sundancespas.com/' :
			return 'local';
			break;
	}
	return 'live';
}
function sds_is_ca() {
	$url = get_bloginfo('url');
	switch ( $url ) {
		case 'http://www.sundancespas.ca' :
		case 'http://www.sundancespas.ca/' :
			return true;
			break;
		case 'http://www.sundancespas.com' :
		case 'http://www.sundancespas.com/' :
		default :
			return false;
			break;
	}
	return false;
}



/** BV : BazaarVoice Integrations **/

	// load SDK
	require('includes/bvseosdk.php');

	// Enqueue BV scripts
	if ( ! function_exists('bazaar_voice_scripts') ) {
		function bazaar_voice_scripts() {
			// load bvpai.js
			if ( is_page('reviews') ) {
				if( sds_my_server() != 'live' )
				{
					wp_enqueue_script( 'bvapi-js', '//display-stg.ugc.bazaarvoice.com/static/sundancespas/ReadOnly/en_US/bvapi.js', array(), '1.0', false); //staging
				}
				else
				{
					wp_enqueue_script( 'bvapi-js', '//display.ugc.bazaarvoice.com/static/sundancespas/ReadOnly/en_US/bvapi.js', array(), '1.0', false); //production
				}
			}
			else {
				if( sds_my_server() != 'live' )
				{
					wp_enqueue_script( 'bvapi-js', '//display-stg.ugc.bazaarvoice.com/static/sundancespas/en_US/bvapi.js', array(), '1.0', false); //staging
				}
				else
				{
					wp_enqueue_script( 'bvapi-js', '//display.ugc.bazaarvoice.com/static/sundancespas/en_US/bvapi.js', array(), '1.0', false); //production
				}
			}
		}
		add_action( 'wp_enqueue_scripts', 'bazaar_voice_scripts' );
	}

	// Remove Canonical Link Added By Yoast WordPress SEO Plugin if URL has query string (this is for BV SEO Pagination)
	function remove_yoast_canonical_link() {
		return false;
	}
	if ( $_GET )
		add_filter( 'wpseo_canonical', 'remove_yoast_canonical_link' );

/** END BazaarVoice **/




add_action('wp_head', 'jht_do_hreflang');
function jht_do_hreflang() {
	$url = 'http' . (isset($_SERVER['HTTPS']) ? 's' : '') . '://' . "{$_SERVER['HTTP_HOST']}{$_SERVER['REQUEST_URI']}";
	$p = parse_url($url);
	$a = array(
        '/hot-tub-dealer-locator/quebec-qc/hot-tubs-valdor/' => '<link rel="alternate" href="http://www.sundancespas.com/hot-tub-dealer-locator/quebec-qc/hot-tubs-valdor/" hreflang="en-us" /><link rel="alternate" href="http://www.sundancespas.ca/hot-tub-dealer-locator/quebec-qc/hot-tubs-valdor/" hreflang="en-ca" />',
        '/hot-tub-dealer-locator/quebec-qc/hot-tubs-saint-jean-sur-richelieu/' => '<link rel="alternate" href="http://www.sundancespas.com/hot-tub-dealer-locator/quebec-qc/hot-tubs-saint-jean-sur-richelieu/" hreflang="en-us" /><link rel="alternate" href="http://www.sundancespas.ca/hot-tub-dealer-locator/quebec-qc/hot-tubs-saint-jean-sur-richelieu/" hreflang="en-ca" />',
        '/hot-tub-dealer-locator/british-columbia-bc/hot-tubs-fernie/' => '<link rel="alternate" href="http://www.sundancespas.com/hot-tub-dealer-locator/british-columbia-bc/hot-tubs-fernie/" hreflang="en-us" /><link rel="alternate" href="http://www.sundancespas.ca/hot-tub-dealer-locator/british-columbia-bc/hot-tubs-fernie/" hreflang="en-ca" />',
        '/hot-tub-dealer-locator/manitoba-mb/hot-tubs-winnipeg/' => '<link rel="alternate" href="http://www.sundancespas.com/hot-tub-dealer-locator/manitoba-mb/hot-tubs-winnipeg/" hreflang="en-us" /><link rel="alternate" href="http://www.sundancespas.ca/hot-tub-dealer-locator/manitoba-mb/hot-tubs-winnipeg/" hreflang="en-ca" />',
        '/hot-tub-dealer-locator/quebec-qc/hot-tubs-brossard/' => '<link rel="alternate" href="http://www.sundancespas.com/hot-tub-dealer-locator/quebec-qc/hot-tubs-brossard/" hreflang="en-us" /><link rel="alternate" href="http://www.sundancespas.ca/hot-tub-dealer-locator/quebec-qc/hot-tubs-brossard/" hreflang="en-ca" />',
        '/hot-tub-dealer-locator/quebec-qc/hot-tubs-bois-des-filion/' => '<link rel="alternate" href="http://www.sundancespas.com/hot-tub-dealer-locator/quebec-qc/hot-tubs-bois-des-filion/" hreflang="en-us" /><link rel="alternate" href="http://www.sundancespas.ca/hot-tub-dealer-locator/quebec-qc/hot-tubs-bois-des-filion/" hreflang="en-ca" />',
        '/hot-tub-dealer-locator/quebec-qc/hot-tubs-ville-vanier/' => '<link rel="alternate" href="http://www.sundancespas.com/hot-tub-dealer-locator/quebec-qc/hot-tubs-ville-vanier/" hreflang="en-us" /><link rel="alternate" href="http://www.sundancespas.ca/hot-tub-dealer-locator/quebec-qc/hot-tubs-ville-vanier/" hreflang="en-ca" />',
        '/hot-tub-dealer-locator/quebec-qc/hot-tubs-levis/' => '<link rel="alternate" href="http://www.sundancespas.com/hot-tub-dealer-locator/quebec-qc/hot-tubs-levis/" hreflang="en-us" /><link rel="alternate" href="http://www.sundancespas.ca/hot-tub-dealer-locator/quebec-qc/hot-tubs-levis/" hreflang="en-ca" />',
        '/hot-tub-dealer-locator/quebec-qc/hot-tubs-granby/' => '<link rel="alternate" href="http://www.sundancespas.com/hot-tub-dealer-locator/quebec-qc/hot-tubs-granby/" hreflang="en-us" /><link rel="alternate" href="http://www.sundancespas.ca/hot-tub-dealer-locator/quebec-qc/hot-tubs-granby/" hreflang="en-ca" />',
        '/hot-tub-dealer-locator/quebec-qc/hot-tubs-vaudreuil-dorion/' => '<link rel="alternate" href="http://www.sundancespas.com/hot-tub-dealer-locator/quebec-qc/hot-tubs-vaudreuil-dorion/" hreflang="en-us" /><link rel="alternate" href="http://www.sundancespas.ca/hot-tub-dealer-locator/quebec-qc/hot-tubs-vaudreuil-dorion/" hreflang="en-ca" />',
        '/hot-tub-dealer-locator/quebec-qc/hot-tubs-victoriaville/' => '<link rel="alternate" href="http://www.sundancespas.com/hot-tub-dealer-locator/quebec-qc/hot-tubs-victoriaville/" hreflang="en-us" /><link rel="alternate" href="http://www.sundancespas.ca/hot-tub-dealer-locator/quebec-qc/hot-tubs-victoriaville/" hreflang="en-ca" />',
        '/hot-tub-dealer-locator/quebec-qc/hot-tubs-shawinigan-sud/' => '<link rel="alternate" href="http://www.sundancespas.com/hot-tub-dealer-locator/quebec-qc/hot-tubs-shawinigan-sud/" hreflang="en-us" /><link rel="alternate" href="http://www.sundancespas.ca/hot-tub-dealer-locator/quebec-qc/hot-tubs-shawinigan-sud/" hreflang="en-ca" />',
        '/hot-tub-dealer-locator/quebec-qc/hot-tubs-drummondville/' => '<link rel="alternate" href="http://www.sundancespas.com/hot-tub-dealer-locator/quebec-qc/hot-tubs-drummondville/" hreflang="en-us" /><link rel="alternate" href="http://www.sundancespas.ca/hot-tub-dealer-locator/quebec-qc/hot-tubs-drummondville/" hreflang="en-ca" />',
        '/hot-tub-dealer-locator/quebec-qc/hot-tubs-gatineau/' => '<link rel="alternate" href="http://www.sundancespas.com/hot-tub-dealer-locator/quebec-qc/hot-tubs-gatineau/" hreflang="en-us" /><link rel="alternate" href="http://www.sundancespas.ca/hot-tub-dealer-locator/quebec-qc/hot-tubs-gatineau/" hreflang="en-ca" />',
        '/hot-tub-dealer-locator/british-columbia-bc/hot-tubs-vancouver/' => '<link rel="alternate" href="http://www.sundancespas.com/hot-tub-dealer-locator/british-columbia-bc/hot-tubs-vancouver/" hreflang="en-us" /><link rel="alternate" href="http://www.sundancespas.ca/hot-tub-dealer-locator/british-columbia-bc/hot-tubs-vancouver/" hreflang="en-ca" />',
        '/hot-tub-dealer-locator/ontario-on/hot-tubs-newmarket/' => '<link rel="alternate" href="http://www.sundancespas.com/hot-tub-dealer-locator/ontario-on/hot-tubs-newmarket/" hreflang="en-us" /><link rel="alternate" href="http://www.sundancespas.ca/hot-tub-dealer-locator/ontario-on/hot-tubs-newmarket/" hreflang="en-ca" />',
        '/hot-tub-dealer-locator/ontario-on/hot-tubs-whitby/' => '<link rel="alternate" href="http://www.sundancespas.com/hot-tub-dealer-locator/ontario-on/hot-tubs-whitby/" hreflang="en-us" /><link rel="alternate" href="http://www.sundancespas.ca/hot-tub-dealer-locator/ontario-on/hot-tubs-whitby/" hreflang="en-ca" />',
        '/hot-tub-dealer-locator/ontario-on/hot-tubs-thunder-bay/' => '<link rel="alternate" href="http://www.sundancespas.com/hot-tub-dealer-locator/ontario-on/hot-tubs-thunder-bay/" hreflang="en-us" /><link rel="alternate" href="http://www.sundancespas.ca/hot-tub-dealer-locator/ontario-on/hot-tubs-thunder-bay/" hreflang="en-ca" />',
        '/hot-tub-dealer-locator/quebec-qc/hot-tubs-laval/' => '<link rel="alternate" href="http://www.sundancespas.com/hot-tub-dealer-locator/quebec-qc/hot-tubs-laval/" hreflang="en-us" /><link rel="alternate" href="http://www.sundancespas.ca/hot-tub-dealer-locator/quebec-qc/hot-tubs-laval/" hreflang="en-ca" />',
        '/hot-tub-dealer-locator/quebec-qc/hot-tubs-auteuil-laval/' => '<link rel="alternate" href="http://www.sundancespas.com/hot-tub-dealer-locator/quebec-qc/hot-tubs-auteuil-laval/" hreflang="en-us" /><link rel="alternate" href="http://www.sundancespas.ca/hot-tub-dealer-locator/quebec-qc/hot-tubs-auteuil-laval/" hreflang="en-ca" />',
        '/hot-tub-dealer-locator/quebec-qc/hot-tubs-blainville/' => '<link rel="alternate" href="http://www.sundancespas.com/hot-tub-dealer-locator/quebec-qc/hot-tubs-blainville/" hreflang="en-us" /><link rel="alternate" href="http://www.sundancespas.ca/hot-tub-dealer-locator/quebec-qc/hot-tubs-blainville/" hreflang="en-ca" />',
        '/hot-tub-dealer-locator/quebec-qc/hot-tubs-st-eustache/' => '<link rel="alternate" href="http://www.sundancespas.com/hot-tub-dealer-locator/quebec-qc/hot-tubs-st-eustache/" hreflang="en-us" /><link rel="alternate" href="http://www.sundancespas.ca/hot-tub-dealer-locator/quebec-qc/hot-tubs-st-eustache/" hreflang="en-ca" />',
        '/hot-tub-dealer-locator/quebec-qc/hot-tubs-longueuil/' => '<link rel="alternate" href="http://www.sundancespas.com/hot-tub-dealer-locator/quebec-qc/hot-tubs-longueuil/" hreflang="en-us" /><link rel="alternate" href="http://www.sundancespas.ca/hot-tub-dealer-locator/quebec-qc/hot-tubs-longueuil/" hreflang="en-ca" />',
        '/hot-tub-dealer-locator/quebec-qc/hot-tubs-rimouski/' => '<link rel="alternate" href="http://www.sundancespas.com/hot-tub-dealer-locator/quebec-qc/hot-tubs-rimouski/" hreflang="en-us" /><link rel="alternate" href="http://www.sundancespas.ca/hot-tub-dealer-locator/quebec-qc/hot-tubs-rimouski/" hreflang="en-ca" />',
        '/hot-tub-dealer-locator/alberta-ab/hot-tubs-calgary/' => '<link rel="alternate" href="http://www.sundancespas.com/hot-tub-dealer-locator/alberta-ab/hot-tubs-calgary/" hreflang="en-us" /><link rel="alternate" href="http://www.sundancespas.ca/hot-tub-dealer-locator/alberta-ab/hot-tubs-calgary/" hreflang="en-ca" />',
        '/hot-tub-dealer-locator/quebec-qc/hot-tubs-repontigny/' => '<link rel="alternate" href="http://www.sundancespas.com/hot-tub-dealer-locator/quebec-qc/hot-tubs-repontigny/" hreflang="en-us" /><link rel="alternate" href="http://www.sundancespas.ca/hot-tub-dealer-locator/quebec-qc/hot-tubs-repontigny/" hreflang="en-ca" />',
        '/hot-tub-dealer-locator/ontario-on/hot-tubs-southampton/' => '<link rel="alternate" href="http://www.sundancespas.com/hot-tub-dealer-locator/ontario-on/hot-tubs-southampton/" hreflang="en-us" /><link rel="alternate" href="http://www.sundancespas.ca/hot-tub-dealer-locator/ontario-on/hot-tubs-southampton/" hreflang="en-ca" />',
        '/hot-tub-dealer-locator/quebec-qc/hot-tubs-st.-hubert/' => '<link rel="alternate" href="http://www.sundancespas.com/hot-tub-dealer-locator/quebec-qc/hot-tubs-st.-hubert/" hreflang="en-us" /><link rel="alternate" href="http://www.sundancespas.ca/hot-tub-dealer-locator/quebec-qc/hot-tubs-st.-hubert/" hreflang="en-ca" />',
        '/hot-tub-dealer-locator/quebec-qc/hot-tubs-sorel-tracy/' => '<link rel="alternate" href="http://www.sundancespas.com/hot-tub-dealer-locator/quebec-qc/hot-tubs-sorel-tracy/" hreflang="en-us" /><link rel="alternate" href="http://www.sundancespas.ca/hot-tub-dealer-locator/quebec-qc/hot-tubs-sorel-tracy/" hreflang="en-ca" />',
        '/hot-tub-dealer-locator/quebec-qc/hot-tubs-thetford-mines/' => '<link rel="alternate" href="http://www.sundancespas.com/hot-tub-dealer-locator/quebec-qc/hot-tubs-thetford-mines/" hreflang="en-us" /><link rel="alternate" href="http://www.sundancespas.ca/hot-tub-dealer-locator/quebec-qc/hot-tubs-thetford-mines/" hreflang="en-ca" />',
        '/hot-tub-dealer-locator/quebec-qc/hot-tubs-chicoutimi/' => '<link rel="alternate" href="http://www.sundancespas.com/hot-tub-dealer-locator/quebec-qc/hot-tubs-chicoutimi/" hreflang="en-us" /><link rel="alternate" href="http://www.sundancespas.ca/hot-tub-dealer-locator/quebec-qc/hot-tubs-chicoutimi/" hreflang="en-ca" />',
        '/hot-tub-dealer-locator/quebec-qc/hot-tubs-terrebonne/' => '<link rel="alternate" href="http://www.sundancespas.com/hot-tub-dealer-locator/quebec-qc/hot-tubs-terrebonne/" hreflang="en-us" /><link rel="alternate" href="http://www.sundancespas.ca/hot-tub-dealer-locator/quebec-qc/hot-tubs-terrebonne/" hreflang="en-ca" />',
        '/hot-tub-dealer-locator/alberta-ab/hot-tubs-edmonton/' => '<link rel="alternate" href="http://www.sundancespas.com/hot-tub-dealer-locator/alberta-ab/hot-tubs-edmonton/" hreflang="en-us" /><link rel="alternate" href="http://www.sundancespas.ca/hot-tub-dealer-locator/alberta-ab/hot-tubs-edmonton/" hreflang="en-ca" />',
        '/hot-tub-dealer-locator/quebec-qc/hot-tubs-sherbrooke/' => '<link rel="alternate" href="http://www.sundancespas.com/hot-tub-dealer-locator/quebec-qc/hot-tubs-sherbrooke/" hreflang="en-us" /><link rel="alternate" href="http://www.sundancespas.ca/hot-tub-dealer-locator/quebec-qc/hot-tubs-sherbrooke/" hreflang="en-ca" />',
        '/hot-tub-dealer-locator/alberta-ab/hot-tubs-red-deer/' => '<link rel="alternate" href="http://www.sundancespas.com/hot-tub-dealer-locator/alberta-ab/hot-tubs-red-deer/" hreflang="en-us" /><link rel="alternate" href="http://www.sundancespas.ca/hot-tub-dealer-locator/alberta-ab/hot-tubs-red-deer/" hreflang="en-ca" />',
        '/hot-tub-dealer-locator/quebec-qc/hot-tubs-st-jerome/' => '<link rel="alternate" href="http://www.sundancespas.com/hot-tub-dealer-locator/quebec-qc/hot-tubs-st-jerome/" hreflang="en-us" /><link rel="alternate" href="http://www.sundancespas.ca/hot-tub-dealer-locator/quebec-qc/hot-tubs-st-jerome/" hreflang="en-ca" />',
        '/hot-tub-dealer-locator/ontario-on/hot-tubs-kingston/' => '<link rel="alternate" href="http://www.sundancespas.com/hot-tub-dealer-locator/ontario-on/hot-tubs-kingston/" hreflang="en-us" /><link rel="alternate" href="http://www.sundancespas.ca/hot-tub-dealer-locator/ontario-on/hot-tubs-kingston/" hreflang="en-ca" />',
        '/hot-tub-dealer-locator/ontario-on/hot-tubs-brockville/' => '<link rel="alternate" href="http://www.sundancespas.com/hot-tub-dealer-locator/ontario-on/hot-tubs-brockville/" hreflang="en-us" /><link rel="alternate" href="http://www.sundancespas.ca/hot-tub-dealer-locator/ontario-on/hot-tubs-brockville/" hreflang="en-ca" />',
        '/hot-tub-dealer-locator/ontario-on/hot-tubs-belleville/' => '<link rel="alternate" href="http://www.sundancespas.com/hot-tub-dealer-locator/ontario-on/hot-tubs-belleville/" hreflang="en-us" /><link rel="alternate" href="http://www.sundancespas.ca/hot-tub-dealer-locator/ontario-on/hot-tubs-belleville/" hreflang="en-ca" />',
        '/hot-tub-dealer-locator/ontario-on/hot-tubs-hamilton/' => '<link rel="alternate" href="http://www.sundancespas.com/hot-tub-dealer-locator/ontario-on/hot-tubs-hamilton/" hreflang="en-us" /><link rel="alternate" href="http://www.sundancespas.ca/hot-tub-dealer-locator/ontario-on/hot-tubs-hamilton/" hreflang="en-ca" />',
        '/hot-tub-dealer-locator/ontario-on/hot-tubs-burlington/' => '<link rel="alternate" href="http://www.sundancespas.com/hot-tub-dealer-locator/ontario-on/hot-tubs-burlington/" hreflang="en-us" /><link rel="alternate" href="http://www.sundancespas.ca/hot-tub-dealer-locator/ontario-on/hot-tubs-burlington/" hreflang="en-ca" />',
        '/hot-tub-dealer-locator/ontario-on/hot-tubs-oakville/' => '<link rel="alternate" href="http://www.sundancespas.com/hot-tub-dealer-locator/ontario-on/hot-tubs-oakville/" hreflang="en-us" /><link rel="alternate" href="http://www.sundancespas.ca/hot-tub-dealer-locator/ontario-on/hot-tubs-oakville/" hreflang="en-ca" />',
        '/hot-tub-dealer-locator/british-columbia-bc/hot-tubs-victoria/' => '<link rel="alternate" href="http://www.sundancespas.com/hot-tub-dealer-locator/british-columbia-bc/hot-tubs-victoria/" hreflang="en-us" /><link rel="alternate" href="http://www.sundancespas.ca/hot-tub-dealer-locator/british-columbia-bc/hot-tubs-victoria/" hreflang="en-ca" />',
        '/hot-tub-dealer-locator/quebec-qc/hot-tubs-pierrefonds/' => '<link rel="alternate" href="http://www.sundancespas.com/hot-tub-dealer-locator/quebec-qc/hot-tubs-pierrefonds/" hreflang="en-us" /><link rel="alternate" href="http://www.sundancespas.ca/hot-tub-dealer-locator/quebec-qc/hot-tubs-pierrefonds/" hreflang="en-ca" />',
        '/hot-tub-dealer-locator/quebec-qc/hot-tubs-st.-constant/' => '<link rel="alternate" href="http://www.sundancespas.com/hot-tub-dealer-locator/quebec-qc/hot-tubs-st.-constant/" hreflang="en-us" /><link rel="alternate" href="http://www.sundancespas.ca/hot-tub-dealer-locator/quebec-qc/hot-tubs-st.-constant/" hreflang="en-ca" />',
        '/hot-tub-dealer-locator/ontario-on/hot-tubs-orleans/' => '<link rel="alternate" href="http://www.sundancespas.com/hot-tub-dealer-locator/ontario-on/hot-tubs-orleans/" hreflang="en-us" /><link rel="alternate" href="http://www.sundancespas.ca/hot-tub-dealer-locator/ontario-on/hot-tubs-orleans/" hreflang="en-ca" />',
        '/hot-tub-dealer-locator/ontario-on/hot-tubs-nepean/' => '<link rel="alternate" href="http://www.sundancespas.com/hot-tub-dealer-locator/ontario-on/hot-tubs-nepean/" hreflang="en-us" /><link rel="alternate" href="http://www.sundancespas.ca/hot-tub-dealer-locator/ontario-on/hot-tubs-nepean/" hreflang="en-ca" />',
        '/hot-tub-dealer-locator/quebec-qc/hot-tubs-ste-agathe/' => '<link rel="alternate" href="http://www.sundancespas.com/hot-tub-dealer-locator/quebec-qc/hot-tubs-ste-agathe/" hreflang="en-us" /><link rel="alternate" href="http://www.sundancespas.ca/hot-tub-dealer-locator/quebec-qc/hot-tubs-ste-agathe/" hreflang="en-ca" />',
        '/hot-tub-dealer-locator/ontario-on/hot-tubs-harrow/' => '<link rel="alternate" href="http://www.sundancespas.com/hot-tub-dealer-locator/ontario-on/hot-tubs-harrow/" hreflang="en-us" /><link rel="alternate" href="http://www.sundancespas.ca/hot-tub-dealer-locator/ontario-on/hot-tubs-harrow/" hreflang="en-ca" />',
        '/hot-tub-dealer-locator/ontario-on/hot-tubs-london/' => '<link rel="alternate" href="http://www.sundancespas.com/hot-tub-dealer-locator/ontario-on/hot-tubs-london/" hreflang="en-us" /><link rel="alternate" href="http://www.sundancespas.ca/hot-tub-dealer-locator/ontario-on/hot-tubs-london/" hreflang="en-ca" />',
        '/hot-tub-dealer-locator/quebec-qc/hot-tubs-montreal/' => '<link rel="alternate" href="http://www.sundancespas.com/hot-tub-dealer-locator/quebec-qc/hot-tubs-montreal/" hreflang="en-us" /><link rel="alternate" href="http://www.sundancespas.ca/hot-tub-dealer-locator/quebec-qc/hot-tubs-montreal/" hreflang="en-ca" />',
        '/hot-tub-dealer-locator/quebec-qc/hot-tubs-st-georges/' => '<link rel="alternate" href="http://www.sundancespas.com/hot-tub-dealer-locator/quebec-qc/hot-tubs-st-georges/" hreflang="en-us" /><link rel="alternate" href="http://www.sundancespas.ca/hot-tub-dealer-locator/quebec-qc/hot-tubs-st-georges/" hreflang="en-ca" />',
        '/hot-tub-dealer-locator/quebec-qc/hot-tubs-notre-dame-des-prairies/' => '<link rel="alternate" href="http://www.sundancespas.com/hot-tub-dealer-locator/quebec-qc/hot-tubs-notre-dame-des-prairies/" hreflang="en-us" /><link rel="alternate" href="http://www.sundancespas.ca/hot-tub-dealer-locator/quebec-qc/hot-tubs-notre-dame-des-prairies/" hreflang="en-ca" />',
        '/hot-tub-dealer-locator/quebec-qc/hot-tubs-cowansville/' => '<link rel="alternate" href="http://www.sundancespas.com/hot-tub-dealer-locator/quebec-qc/hot-tubs-cowansville/" hreflang="en-us" /><link rel="alternate" href="http://www.sundancespas.ca/hot-tub-dealer-locator/quebec-qc/hot-tubs-cowansville/" hreflang="en-ca" />',
        '/hot-tub-dealer-locator/quebec-qc/hot-tubs-valleyfield/' => '<link rel="alternate" href="http://www.sundancespas.com/hot-tub-dealer-locator/quebec-qc/hot-tubs-valleyfield/" hreflang="en-us" /><link rel="alternate" href="http://www.sundancespas.ca/hot-tub-dealer-locator/quebec-qc/hot-tubs-valleyfield/" hreflang="en-ca" />',
        '/hot-tub-dealer-locator/ontario-on/hot-tubs-mississauga/' => '<link rel="alternate" href="http://www.sundancespas.com/hot-tub-dealer-locator/ontario-on/hot-tubs-mississauga/" hreflang="en-us" /><link rel="alternate" href="http://www.sundancespas.ca/hot-tub-dealer-locator/ontario-on/hot-tubs-mississauga/" hreflang="en-ca" />',
        '/hot-tub-dealer-locator/quebec-qc/hot-tubs-beloeil/' => '<link rel="alternate" href="http://www.sundancespas.com/hot-tub-dealer-locator/quebec-qc/hot-tubs-beloeil/" hreflang="en-us" /><link rel="alternate" href="http://www.sundancespas.ca/hot-tub-dealer-locator/quebec-qc/hot-tubs-beloeil/" hreflang="en-ca" />',
        '/hot-tub-dealer-locator/ontario-on/hot-tubs-collingwood/' => '<link rel="alternate" href="http://www.sundancespas.com/hot-tub-dealer-locator/ontario-on/hot-tubs-collingwood/" hreflang="en-us" /><link rel="alternate" href="http://www.sundancespas.ca/hot-tub-dealer-locator/ontario-on/hot-tubs-collingwood/" hreflang="en-ca" />',
        '/hot-tub-dealer-locator/quebec-qc/hot-tubs-st-hyacinthe/' => '<link rel="alternate" href="http://www.sundancespas.com/hot-tub-dealer-locator/quebec-qc/hot-tubs-st-hyacinthe/" hreflang="en-us" /><link rel="alternate" href="http://www.sundancespas.ca/hot-tub-dealer-locator/quebec-qc/hot-tubs-st-hyacinthe/" hreflang="en-ca" />',
        '/hot-tub-dealer-locator/quebec-qc/hot-tubs-st--catharines/' => '<link rel="alternate" href="http://www.sundancespas.com/hot-tub-dealer-locator/quebec-qc/hot-tubs-st--catharines/" hreflang="en-us" /><link rel="alternate" href="http://www.sundancespas.ca/hot-tub-dealer-locator/quebec-qc/hot-tubs-st--catharines/" hreflang="en-ca" />',
        '/hot-tub-dealer-locator/connecticut-ct/hot-tubs-avon/' => '<link rel="alternate" href="http://www.sundancespas.com/hot-tub-dealer-locator/connecticut-ct/hot-tubs-avon/" hreflang="en-us" /><link rel="alternate" href="http://www.sundancespas.ca/hot-tub-dealer-locator/connecticut-ct/hot-tubs-avon/" hreflang="en-ca" />',
        '/hot-tub-dealer-locator/connecticut-ct/hot-tubs-milldale-southington/' => '<link rel="alternate" href="http://www.sundancespas.com/hot-tub-dealer-locator/connecticut-ct/hot-tubs-milldale-southington/" hreflang="en-us" /><link rel="alternate" href="http://www.sundancespas.ca/hot-tub-dealer-locator/connecticut-ct/hot-tubs-milldale-southington/" hreflang="en-ca" />',
        '/hot-tub-dealer-locator/washington-wa/hot-tubs-spokane/' => '<link rel="alternate" href="http://www.sundancespas.com/hot-tub-dealer-locator/washington-wa/hot-tubs-spokane/" hreflang="en-us" /><link rel="alternate" href="http://www.sundancespas.ca/hot-tub-dealer-locator/washington-wa/hot-tubs-spokane/" hreflang="en-ca" />',
        '/hot-tub-dealer-locator/florida-fl/hot-tubs-estero/' => '<link rel="alternate" href="http://www.sundancespas.com/hot-tub-dealer-locator/florida-fl/hot-tubs-estero/" hreflang="en-us" /><link rel="alternate" href="http://www.sundancespas.ca/hot-tub-dealer-locator/florida-fl/hot-tubs-estero/" hreflang="en-ca" />',
        '/hot-tub-dealer-locator/iowa-ia/hot-tubs-cherokee/' => '<link rel="alternate" href="http://www.sundancespas.com/hot-tub-dealer-locator/iowa-ia/hot-tubs-cherokee/" hreflang="en-us" /><link rel="alternate" href="http://www.sundancespas.ca/hot-tub-dealer-locator/iowa-ia/hot-tubs-cherokee/" hreflang="en-ca" />',
        '/hot-tub-dealer-locator/new-york-ny/hot-tubs-patchogue/' => '<link rel="alternate" href="http://www.sundancespas.com/hot-tub-dealer-locator/new-york-ny/hot-tubs-patchogue/" hreflang="en-us" /><link rel="alternate" href="http://www.sundancespas.ca/hot-tub-dealer-locator/new-york-ny/hot-tubs-patchogue/" hreflang="en-ca" />',
        '/hot-tub-dealer-locator/alaska-ak/hot-tubs-anchorage/' => '<link rel="alternate" href="http://www.sundancespas.com/hot-tub-dealer-locator/alaska-ak/hot-tubs-anchorage/" hreflang="en-us" /><link rel="alternate" href="http://www.sundancespas.ca/hot-tub-dealer-locator/alaska-ak/hot-tubs-anchorage/" hreflang="en-ca" />',
        '/hot-tub-dealer-locator/texas-tx/hot-tubs-lubbock/' => '<link rel="alternate" href="http://www.sundancespas.com/hot-tub-dealer-locator/texas-tx/hot-tubs-lubbock/" hreflang="en-us" /><link rel="alternate" href="http://www.sundancespas.ca/hot-tub-dealer-locator/texas-tx/hot-tubs-lubbock/" hreflang="en-ca" />',
        '/hot-tub-dealer-locator/florida-fl/hot-tubs-miami/' => '<link rel="alternate" href="http://www.sundancespas.com/hot-tub-dealer-locator/florida-fl/hot-tubs-miami/" hreflang="en-us" /><link rel="alternate" href="http://www.sundancespas.ca/hot-tub-dealer-locator/florida-fl/hot-tubs-miami/" hreflang="en-ca" />',
        '/hot-tub-dealer-locator/kentucky-ky/hot-tubs-paducah/' => '<link rel="alternate" href="http://www.sundancespas.com/hot-tub-dealer-locator/kentucky-ky/hot-tubs-paducah/" hreflang="en-us" /><link rel="alternate" href="http://www.sundancespas.ca/hot-tub-dealer-locator/kentucky-ky/hot-tubs-paducah/" hreflang="en-ca" />',
        '/hot-tub-dealer-locator/ohio-oh/hot-tubs-hilliard/' => '<link rel="alternate" href="http://www.sundancespas.com/hot-tub-dealer-locator/ohio-oh/hot-tubs-hilliard/" hreflang="en-us" /><link rel="alternate" href="http://www.sundancespas.ca/hot-tub-dealer-locator/ohio-oh/hot-tubs-hilliard/" hreflang="en-ca" />',
        '/hot-tub-dealer-locator/south-carolina-sc/hot-tubs-taylors/' => '<link rel="alternate" href="http://www.sundancespas.com/hot-tub-dealer-locator/south-carolina-sc/hot-tubs-taylors/" hreflang="en-us" /><link rel="alternate" href="http://www.sundancespas.ca/hot-tub-dealer-locator/south-carolina-sc/hot-tubs-taylors/" hreflang="en-ca" />',
        '/hot-tub-dealer-locator/louisiana-la/hot-tubs-west-monroe/' => '<link rel="alternate" href="http://www.sundancespas.com/hot-tub-dealer-locator/louisiana-la/hot-tubs-west-monroe/" hreflang="en-us" /><link rel="alternate" href="http://www.sundancespas.ca/hot-tub-dealer-locator/louisiana-la/hot-tubs-west-monroe/" hreflang="en-ca" />',
        '/hot-tub-dealer-locator/california-ca/hot-tubs-san-luis-obispo/' => '<link rel="alternate" href="http://www.sundancespas.com/hot-tub-dealer-locator/california-ca/hot-tubs-san-luis-obispo/" hreflang="en-us" /><link rel="alternate" href="http://www.sundancespas.ca/hot-tub-dealer-locator/california-ca/hot-tubs-san-luis-obispo/" hreflang="en-ca" />',
        '/hot-tub-dealer-locator/new-york-ny/hot-tubs-chestnut-ridge/' => '<link rel="alternate" href="http://www.sundancespas.com/hot-tub-dealer-locator/new-york-ny/hot-tubs-chestnut-ridge/" hreflang="en-us" /><link rel="alternate" href="http://www.sundancespas.ca/hot-tub-dealer-locator/new-york-ny/hot-tubs-chestnut-ridge/" hreflang="en-ca" />',
        '/hot-tub-dealer-locator/california-ca/hot-tubs-fairfield/' => '<link rel="alternate" href="http://www.sundancespas.com/hot-tub-dealer-locator/california-ca/hot-tubs-fairfield/" hreflang="en-us" /><link rel="alternate" href="http://www.sundancespas.ca/hot-tub-dealer-locator/california-ca/hot-tubs-fairfield/" hreflang="en-ca" />',
        '/hot-tub-dealer-locator/utah-ut/hot-tubs-sandy/' => '<link rel="alternate" href="http://www.sundancespas.com/hot-tub-dealer-locator/utah-ut/hot-tubs-sandy/" hreflang="en-us" /><link rel="alternate" href="http://www.sundancespas.ca/hot-tub-dealer-locator/utah-ut/hot-tubs-sandy/" hreflang="en-ca" />',
        '/hot-tub-dealer-locator/michigan-mi/hot-tubs-saginaw/' => '<link rel="alternate" href="http://www.sundancespas.com/hot-tub-dealer-locator/michigan-mi/hot-tubs-saginaw/" hreflang="en-us" /><link rel="alternate" href="http://www.sundancespas.ca/hot-tub-dealer-locator/michigan-mi/hot-tubs-saginaw/" hreflang="en-ca" />',
        '/hot-tub-dealer-locator/michigan-mi/hot-tubs-saginaw/' => '<link rel="alternate" href="http://www.sundancespas.com/hot-tub-dealer-locator/michigan-mi/hot-tubs-saginaw/" hreflang="en-us" /><link rel="alternate" href="http://www.sundancespas.ca/hot-tub-dealer-locator/michigan-mi/hot-tubs-saginaw/" hreflang="en-ca" />',
        '/hot-tub-dealer-locator/arizona-az/hot-tubs-sedona/' => '<link rel="alternate" href="http://www.sundancespas.com/hot-tub-dealer-locator/arizona-az/hot-tubs-sedona/" hreflang="en-us" /><link rel="alternate" href="http://www.sundancespas.ca/hot-tub-dealer-locator/arizona-az/hot-tubs-sedona/" hreflang="en-ca" />',
        '/hot-tub-dealer-locator/arizona-az/hot-tubs-prescott/' => '<link rel="alternate" href="http://www.sundancespas.com/hot-tub-dealer-locator/arizona-az/hot-tubs-prescott/" hreflang="en-us" /><link rel="alternate" href="http://www.sundancespas.ca/hot-tub-dealer-locator/arizona-az/hot-tubs-prescott/" hreflang="en-ca" />',
        '/hot-tub-dealer-locator/montana-mt/hot-tubs-kalispell/' => '<link rel="alternate" href="http://www.sundancespas.com/hot-tub-dealer-locator/montana-mt/hot-tubs-kalispell/" hreflang="en-us" /><link rel="alternate" href="http://www.sundancespas.ca/hot-tub-dealer-locator/montana-mt/hot-tubs-kalispell/" hreflang="en-ca" />',
        '/hot-tub-dealer-locator/kansas-ks/hot-tubs-garden-city/' => '<link rel="alternate" href="http://www.sundancespas.com/hot-tub-dealer-locator/kansas-ks/hot-tubs-garden-city/" hreflang="en-us" /><link rel="alternate" href="http://www.sundancespas.ca/hot-tub-dealer-locator/kansas-ks/hot-tubs-garden-city/" hreflang="en-ca" />',
        '/hot-tub-dealer-locator/wyoming-wy/hot-tubs-sheridan/' => '<link rel="alternate" href="http://www.sundancespas.com/hot-tub-dealer-locator/wyoming-wy/hot-tubs-sheridan/" hreflang="en-us" /><link rel="alternate" href="http://www.sundancespas.ca/hot-tub-dealer-locator/wyoming-wy/hot-tubs-sheridan/" hreflang="en-ca" />',
        '/hot-tub-dealer-locator/montana-mt/hot-tubs-lewistown/' => '<link rel="alternate" href="http://www.sundancespas.com/hot-tub-dealer-locator/montana-mt/hot-tubs-lewistown/" hreflang="en-us" /><link rel="alternate" href="http://www.sundancespas.ca/hot-tub-dealer-locator/montana-mt/hot-tubs-lewistown/" hreflang="en-ca" />',
        '/hot-tub-dealer-locator/california-ca/hot-tubs-long-beach/' => '<link rel="alternate" href="http://www.sundancespas.com/hot-tub-dealer-locator/california-ca/hot-tubs-long-beach/" hreflang="en-us" /><link rel="alternate" href="http://www.sundancespas.ca/hot-tub-dealer-locator/california-ca/hot-tubs-long-beach/" hreflang="en-ca" />',
        '/hot-tub-dealer-locator/florida-fl/hot-tubs-jacksonville/' => '<link rel="alternate" href="http://www.sundancespas.com/hot-tub-dealer-locator/florida-fl/hot-tubs-jacksonville/" hreflang="en-us" /><link rel="alternate" href="http://www.sundancespas.ca/hot-tub-dealer-locator/florida-fl/hot-tubs-jacksonville/" hreflang="en-ca" />',
        '/hot-tub-dealer-locator/montana-mt/hot-tubs-bozeman/' => '<link rel="alternate" href="http://www.sundancespas.com/hot-tub-dealer-locator/montana-mt/hot-tubs-bozeman/" hreflang="en-us" /><link rel="alternate" href="http://www.sundancespas.ca/hot-tub-dealer-locator/montana-mt/hot-tubs-bozeman/" hreflang="en-ca" />',
        '/hot-tub-dealer-locator/indiana-in/hot-tubs-indianapolis/' => '<link rel="alternate" href="http://www.sundancespas.com/hot-tub-dealer-locator/indiana-in/hot-tubs-indianapolis/" hreflang="en-us" /><link rel="alternate" href="http://www.sundancespas.ca/hot-tub-dealer-locator/indiana-in/hot-tubs-indianapolis/" hreflang="en-ca" />',
        '/hot-tub-dealer-locator/iowa-ia/hot-tubs-des-moines/' => '<link rel="alternate" href="http://www.sundancespas.com/hot-tub-dealer-locator/iowa-ia/hot-tubs-des-moines/" hreflang="en-us" /><link rel="alternate" href="http://www.sundancespas.ca/hot-tub-dealer-locator/iowa-ia/hot-tubs-des-moines/" hreflang="en-ca" />',
        '/hot-tub-dealer-locator/texas-tx/hot-tubs-corpus-christi/' => '<link rel="alternate" href="http://www.sundancespas.com/hot-tub-dealer-locator/texas-tx/hot-tubs-corpus-christi/" hreflang="en-us" /><link rel="alternate" href="http://www.sundancespas.ca/hot-tub-dealer-locator/texas-tx/hot-tubs-corpus-christi/" hreflang="en-ca" />',
        '/hot-tub-dealer-locator/california-ca/hot-tubs-san-diego/' => '<link rel="alternate" href="http://www.sundancespas.com/hot-tub-dealer-locator/california-ca/hot-tubs-san-diego/" hreflang="en-us" /><link rel="alternate" href="http://www.sundancespas.ca/hot-tub-dealer-locator/california-ca/hot-tubs-san-diego/" hreflang="en-ca" />',
        '/hot-tub-dealer-locator/california-ca/hot-tubs-encinitas/' => '<link rel="alternate" href="http://www.sundancespas.com/hot-tub-dealer-locator/california-ca/hot-tubs-encinitas/" hreflang="en-us" /><link rel="alternate" href="http://www.sundancespas.ca/hot-tub-dealer-locator/california-ca/hot-tubs-encinitas/" hreflang="en-ca" />',
        '/hot-tub-dealer-locator/california-ca/hot-tubs-mission-viejo/' => '<link rel="alternate" href="http://www.sundancespas.com/hot-tub-dealer-locator/california-ca/hot-tubs-mission-viejo/" hreflang="en-us" /><link rel="alternate" href="http://www.sundancespas.ca/hot-tub-dealer-locator/california-ca/hot-tubs-mission-viejo/" hreflang="en-ca" />',
        '/hot-tub-dealer-locator/hawaii-hi/hot-tubs-honolulu/' => '<link rel="alternate" href="http://www.sundancespas.com/hot-tub-dealer-locator/hawaii-hi/hot-tubs-honolulu/" hreflang="en-us" /><link rel="alternate" href="http://www.sundancespas.ca/hot-tub-dealer-locator/hawaii-hi/hot-tubs-honolulu/" hreflang="en-ca" />',
        '/hot-tub-dealer-locator/louisiana-la/hot-tubs-houma/' => '<link rel="alternate" href="http://www.sundancespas.com/hot-tub-dealer-locator/louisiana-la/hot-tubs-houma/" hreflang="en-us" /><link rel="alternate" href="http://www.sundancespas.ca/hot-tub-dealer-locator/louisiana-la/hot-tubs-houma/" hreflang="en-ca" />',
        '/hot-tub-dealer-locator/new-york-ny/hot-tubs-oswego/' => '<link rel="alternate" href="http://www.sundancespas.com/hot-tub-dealer-locator/new-york-ny/hot-tubs-oswego/" hreflang="en-us" /><link rel="alternate" href="http://www.sundancespas.ca/hot-tub-dealer-locator/new-york-ny/hot-tubs-oswego/" hreflang="en-ca" />',
        '/hot-tub-dealer-locator/new-york-ny/hot-tubs-pulaski/' => '<link rel="alternate" href="http://www.sundancespas.com/hot-tub-dealer-locator/new-york-ny/hot-tubs-pulaski/" hreflang="en-us" /><link rel="alternate" href="http://www.sundancespas.ca/hot-tub-dealer-locator/new-york-ny/hot-tubs-pulaski/" hreflang="en-ca" />',
        '/hot-tub-dealer-locator/california-ca/hot-tubs-merced/' => '<link rel="alternate" href="http://www.sundancespas.com/hot-tub-dealer-locator/california-ca/hot-tubs-merced/" hreflang="en-us" /><link rel="alternate" href="http://www.sundancespas.ca/hot-tub-dealer-locator/california-ca/hot-tubs-merced/" hreflang="en-ca" />',
        '/hot-tub-dealer-locator/california-ca/hot-tubs-loma-linda/' => '<link rel="alternate" href="http://www.sundancespas.com/hot-tub-dealer-locator/california-ca/hot-tubs-loma-linda/" hreflang="en-us" /><link rel="alternate" href="http://www.sundancespas.ca/hot-tub-dealer-locator/california-ca/hot-tubs-loma-linda/" hreflang="en-ca" />',
        '/hot-tub-dealer-locator/nebraska-ne/hot-tubs-burwell/' => '<link rel="alternate" href="http://www.sundancespas.com/hot-tub-dealer-locator/nebraska-ne/hot-tubs-burwell/" hreflang="en-us" /><link rel="alternate" href="http://www.sundancespas.ca/hot-tub-dealer-locator/nebraska-ne/hot-tubs-burwell/" hreflang="en-ca" />',
        '/hot-tub-dealer-locator/iowa-ia/hot-tubs-clear-lake/' => '<link rel="alternate" href="http://www.sundancespas.com/hot-tub-dealer-locator/iowa-ia/hot-tubs-clear-lake/" hreflang="en-us" /><link rel="alternate" href="http://www.sundancespas.ca/hot-tub-dealer-locator/iowa-ia/hot-tubs-clear-lake/" hreflang="en-ca" />',
        '/hot-tub-dealer-locator/north-dakota-nd/hot-tubs-dickinson/' => '<link rel="alternate" href="http://www.sundancespas.com/hot-tub-dealer-locator/north-dakota-nd/hot-tubs-dickinson/" hreflang="en-us" /><link rel="alternate" href="http://www.sundancespas.ca/hot-tub-dealer-locator/north-dakota-nd/hot-tubs-dickinson/" hreflang="en-ca" />',
        '/hot-tub-dealer-locator/ohio-oh/hot-tubs-northwood/' => '<link rel="alternate" href="http://www.sundancespas.com/hot-tub-dealer-locator/ohio-oh/hot-tubs-northwood/" hreflang="en-us" /><link rel="alternate" href="http://www.sundancespas.ca/hot-tub-dealer-locator/ohio-oh/hot-tubs-northwood/" hreflang="en-ca" />',
        '/hot-tub-dealer-locator/ohio-oh/hot-tubs-maumee/' => '<link rel="alternate" href="http://www.sundancespas.com/hot-tub-dealer-locator/ohio-oh/hot-tubs-maumee/" hreflang="en-us" /><link rel="alternate" href="http://www.sundancespas.ca/hot-tub-dealer-locator/ohio-oh/hot-tubs-maumee/" hreflang="en-ca" />',
        '/hot-tub-dealer-locator/ohio-oh/hot-tubs-toledo/' => '<link rel="alternate" href="http://www.sundancespas.com/hot-tub-dealer-locator/ohio-oh/hot-tubs-toledo/" hreflang="en-us" /><link rel="alternate" href="http://www.sundancespas.ca/hot-tub-dealer-locator/ohio-oh/hot-tubs-toledo/" hreflang="en-ca" />',
        '/hot-tub-dealer-locator/oregon-or/hot-tubs-eugene/' => '<link rel="alternate" href="http://www.sundancespas.com/hot-tub-dealer-locator/oregon-or/hot-tubs-eugene/" hreflang="en-us" /><link rel="alternate" href="http://www.sundancespas.ca/hot-tub-dealer-locator/oregon-or/hot-tubs-eugene/" hreflang="en-ca" />',
        '/hot-tub-dealer-locator/wisconsin-wi/hot-tubs-green-bay/' => '<link rel="alternate" href="http://www.sundancespas.com/hot-tub-dealer-locator/wisconsin-wi/hot-tubs-green-bay/" hreflang="en-us" /><link rel="alternate" href="http://www.sundancespas.ca/hot-tub-dealer-locator/wisconsin-wi/hot-tubs-green-bay/" hreflang="en-ca" />',
        '/hot-tub-dealer-locator/south-dakota-sd/hot-tubs-brookings/' => '<link rel="alternate" href="http://www.sundancespas.com/hot-tub-dealer-locator/south-dakota-sd/hot-tubs-brookings/" hreflang="en-us" /><link rel="alternate" href="http://www.sundancespas.ca/hot-tub-dealer-locator/south-dakota-sd/hot-tubs-brookings/" hreflang="en-ca" />',
        '/hot-tub-dealer-locator/california-ca/hot-tubs-auburn/' => '<link rel="alternate" href="http://www.sundancespas.com/hot-tub-dealer-locator/california-ca/hot-tubs-auburn/" hreflang="en-us" /><link rel="alternate" href="http://www.sundancespas.ca/hot-tub-dealer-locator/california-ca/hot-tubs-auburn/" hreflang="en-ca" />',
        '/hot-tub-dealer-locator/oklahoma-ok/hot-tubs-tulsa/' => '<link rel="alternate" href="http://www.sundancespas.com/hot-tub-dealer-locator/oklahoma-ok/hot-tubs-tulsa/" hreflang="en-us" /><link rel="alternate" href="http://www.sundancespas.ca/hot-tub-dealer-locator/oklahoma-ok/hot-tubs-tulsa/" hreflang="en-ca" />',
        '/hot-tub-dealer-locator/pennsylvania-pa/hot-tubs-camp-hill/' => '<link rel="alternate" href="http://www.sundancespas.com/hot-tub-dealer-locator/pennsylvania-pa/hot-tubs-camp-hill/" hreflang="en-us" /><link rel="alternate" href="http://www.sundancespas.ca/hot-tub-dealer-locator/pennsylvania-pa/hot-tubs-camp-hill/" hreflang="en-ca" />',
        '/hot-tub-dealer-locator/pennsylvania-pa/hot-tubs-harrisburg/' => '<link rel="alternate" href="http://www.sundancespas.com/hot-tub-dealer-locator/pennsylvania-pa/hot-tubs-harrisburg/" hreflang="en-us" /><link rel="alternate" href="http://www.sundancespas.ca/hot-tub-dealer-locator/pennsylvania-pa/hot-tubs-harrisburg/" hreflang="en-ca" />',
        '/hot-tub-dealer-locator/pennsylvania-pa/hot-tubs-lebanon/' => '<link rel="alternate" href="http://www.sundancespas.com/hot-tub-dealer-locator/pennsylvania-pa/hot-tubs-lebanon/" hreflang="en-us" /><link rel="alternate" href="http://www.sundancespas.ca/hot-tub-dealer-locator/pennsylvania-pa/hot-tubs-lebanon/" hreflang="en-ca" />',
        '/hot-tub-dealer-locator/new-hampshire-nh/hot-tubs-portsmouth/' => '<link rel="alternate" href="http://www.sundancespas.com/hot-tub-dealer-locator/new-hampshire-nh/hot-tubs-portsmouth/" hreflang="en-us" /><link rel="alternate" href="http://www.sundancespas.ca/hot-tub-dealer-locator/new-hampshire-nh/hot-tubs-portsmouth/" hreflang="en-ca" />',
        '/hot-tub-dealer-locator/new-hampshire-nh/hot-tubs-amherst/' => '<link rel="alternate" href="http://www.sundancespas.com/hot-tub-dealer-locator/new-hampshire-nh/hot-tubs-amherst/" hreflang="en-us" /><link rel="alternate" href="http://www.sundancespas.ca/hot-tub-dealer-locator/new-hampshire-nh/hot-tubs-amherst/" hreflang="en-ca" />',
        '/hot-tub-dealer-locator/new-hampshire-nh/hot-tubs-meredith/' => '<link rel="alternate" href="http://www.sundancespas.com/hot-tub-dealer-locator/new-hampshire-nh/hot-tubs-meredith/" hreflang="en-us" /><link rel="alternate" href="http://www.sundancespas.ca/hot-tub-dealer-locator/new-hampshire-nh/hot-tubs-meredith/" hreflang="en-ca" />',
        '/hot-tub-dealer-locator/colorado-co/hot-tubs-monte-vista/' => '<link rel="alternate" href="http://www.sundancespas.com/hot-tub-dealer-locator/colorado-co/hot-tubs-monte-vista/" hreflang="en-us" /><link rel="alternate" href="http://www.sundancespas.ca/hot-tub-dealer-locator/colorado-co/hot-tubs-monte-vista/" hreflang="en-ca" />',
        '/hot-tub-dealer-locator/california-ca/hot-tubs-sonora/' => '<link rel="alternate" href="http://www.sundancespas.com/hot-tub-dealer-locator/california-ca/hot-tubs-sonora/" hreflang="en-us" /><link rel="alternate" href="http://www.sundancespas.ca/hot-tub-dealer-locator/california-ca/hot-tubs-sonora/" hreflang="en-ca" />',
        '/hot-tub-dealer-locator/alabama-al/hot-tubs-birmingham/' => '<link rel="alternate" href="http://www.sundancespas.com/hot-tub-dealer-locator/alabama-al/hot-tubs-birmingham/" hreflang="en-us" /><link rel="alternate" href="http://www.sundancespas.ca/hot-tub-dealer-locator/alabama-al/hot-tubs-birmingham/" hreflang="en-ca" />',
        '/hot-tub-dealer-locator/new-york-ny/hot-tubs-herkimer/' => '<link rel="alternate" href="http://www.sundancespas.com/hot-tub-dealer-locator/new-york-ny/hot-tubs-herkimer/" hreflang="en-us" /><link rel="alternate" href="http://www.sundancespas.ca/hot-tub-dealer-locator/new-york-ny/hot-tubs-herkimer/" hreflang="en-ca" />',
        '/hot-tub-dealer-locator/california-ca/hot-tubs-canoga-park/' => '<link rel="alternate" href="http://www.sundancespas.com/hot-tub-dealer-locator/california-ca/hot-tubs-canoga-park/" hreflang="en-us" /><link rel="alternate" href="http://www.sundancespas.ca/hot-tub-dealer-locator/california-ca/hot-tubs-canoga-park/" hreflang="en-ca" />',
        '/hot-tub-dealer-locator/california-ca/hot-tubs-santa-clarita/' => '<link rel="alternate" href="http://www.sundancespas.com/hot-tub-dealer-locator/california-ca/hot-tubs-santa-clarita/" hreflang="en-us" /><link rel="alternate" href="http://www.sundancespas.ca/hot-tub-dealer-locator/california-ca/hot-tubs-santa-clarita/" hreflang="en-ca" />',
        '/hot-tub-dealer-locator/california-ca/hot-tubs-arnold/' => '<link rel="alternate" href="http://www.sundancespas.com/hot-tub-dealer-locator/california-ca/hot-tubs-arnold/" hreflang="en-us" /><link rel="alternate" href="http://www.sundancespas.ca/hot-tub-dealer-locator/california-ca/hot-tubs-arnold/" hreflang="en-ca" />',
        '/hot-tub-dealer-locator/washington-wa/hot-tubs-bellingham/' => '<link rel="alternate" href="http://www.sundancespas.com/hot-tub-dealer-locator/washington-wa/hot-tubs-bellingham/" hreflang="en-us" /><link rel="alternate" href="http://www.sundancespas.ca/hot-tub-dealer-locator/washington-wa/hot-tubs-bellingham/" hreflang="en-ca" />',
        '/hot-tub-dealer-locator/texas-tx/hot-tubs-amarillo/' => '<link rel="alternate" href="http://www.sundancespas.com/hot-tub-dealer-locator/texas-tx/hot-tubs-amarillo/" hreflang="en-us" /><link rel="alternate" href="http://www.sundancespas.ca/hot-tub-dealer-locator/texas-tx/hot-tubs-amarillo/" hreflang="en-ca" />',
        '/hot-tub-dealer-locator/new-jersey-nj/hot-tubs-rio-grande/' => '<link rel="alternate" href="http://www.sundancespas.com/hot-tub-dealer-locator/new-jersey-nj/hot-tubs-rio-grande/" hreflang="en-us" /><link rel="alternate" href="http://www.sundancespas.ca/hot-tub-dealer-locator/new-jersey-nj/hot-tubs-rio-grande/" hreflang="en-ca" />',
        '/hot-tub-dealer-locator/idaho-id/hot-tubs-ketchum/' => '<link rel="alternate" href="http://www.sundancespas.com/hot-tub-dealer-locator/idaho-id/hot-tubs-ketchum/" hreflang="en-us" /><link rel="alternate" href="http://www.sundancespas.ca/hot-tub-dealer-locator/idaho-id/hot-tubs-ketchum/" hreflang="en-ca" />',
        '/hot-tub-dealer-locator/california-ca/hot-tubs-eureka/' => '<link rel="alternate" href="http://www.sundancespas.com/hot-tub-dealer-locator/california-ca/hot-tubs-eureka/" hreflang="en-us" /><link rel="alternate" href="http://www.sundancespas.ca/hot-tub-dealer-locator/california-ca/hot-tubs-eureka/" hreflang="en-ca" />',
        '/hot-tub-dealer-locator/alabama-al/hot-tubs-huntsville/' => '<link rel="alternate" href="http://www.sundancespas.com/hot-tub-dealer-locator/alabama-al/hot-tubs-huntsville/" hreflang="en-us" /><link rel="alternate" href="http://www.sundancespas.ca/hot-tub-dealer-locator/alabama-al/hot-tubs-huntsville/" hreflang="en-ca" />',
        '/hot-tub-dealer-locator/kansas-ks/hot-tubs-lawrence/' => '<link rel="alternate" href="http://www.sundancespas.com/hot-tub-dealer-locator/kansas-ks/hot-tubs-lawrence/" hreflang="en-us" /><link rel="alternate" href="http://www.sundancespas.ca/hot-tub-dealer-locator/kansas-ks/hot-tubs-lawrence/" hreflang="en-ca" />',
        '/hot-tub-dealer-locator/maryland-md/hot-tubs-baltimore/' => '<link rel="alternate" href="http://www.sundancespas.com/hot-tub-dealer-locator/maryland-md/hot-tubs-baltimore/" hreflang="en-us" /><link rel="alternate" href="http://www.sundancespas.ca/hot-tub-dealer-locator/maryland-md/hot-tubs-baltimore/" hreflang="en-ca" />',
        '/hot-tub-dealer-locator/missouri-mo/hot-tubs-springfield/' => '<link rel="alternate" href="http://www.sundancespas.com/hot-tub-dealer-locator/missouri-mo/hot-tubs-springfield/" hreflang="en-us" /><link rel="alternate" href="http://www.sundancespas.ca/hot-tub-dealer-locator/missouri-mo/hot-tubs-springfield/" hreflang="en-ca" />',
        '/hot-tub-dealer-locator/washington-wa/hot-tubs-kennewick/' => '<link rel="alternate" href="http://www.sundancespas.com/hot-tub-dealer-locator/washington-wa/hot-tubs-kennewick/" hreflang="en-us" /><link rel="alternate" href="http://www.sundancespas.ca/hot-tub-dealer-locator/washington-wa/hot-tubs-kennewick/" hreflang="en-ca" />',
        '/hot-tub-dealer-locator/oklahoma-ok/hot-tubs-oklahoma-city/' => '<link rel="alternate" href="http://www.sundancespas.com/hot-tub-dealer-locator/oklahoma-ok/hot-tubs-oklahoma-city/" hreflang="en-us" /><link rel="alternate" href="http://www.sundancespas.ca/hot-tub-dealer-locator/oklahoma-ok/hot-tubs-oklahoma-city/" hreflang="en-ca" />',
        '/hot-tub-dealer-locator/oklahoma-ok/hot-tubs-oklahoma-city/' => '<link rel="alternate" href="http://www.sundancespas.com/hot-tub-dealer-locator/oklahoma-ok/hot-tubs-oklahoma-city/" hreflang="en-us" /><link rel="alternate" href="http://www.sundancespas.ca/hot-tub-dealer-locator/oklahoma-ok/hot-tubs-oklahoma-city/" hreflang="en-ca" />',
        '/hot-tub-dealer-locator/illinois-il/hot-tubs-effingham/' => '<link rel="alternate" href="http://www.sundancespas.com/hot-tub-dealer-locator/illinois-il/hot-tubs-effingham/" hreflang="en-us" /><link rel="alternate" href="http://www.sundancespas.ca/hot-tub-dealer-locator/illinois-il/hot-tubs-effingham/" hreflang="en-ca" />',
        '/hot-tub-dealer-locator/virginia-va/hot-tubs-christiansburg/' => '<link rel="alternate" href="http://www.sundancespas.com/hot-tub-dealer-locator/virginia-va/hot-tubs-christiansburg/" hreflang="en-us" /><link rel="alternate" href="http://www.sundancespas.ca/hot-tub-dealer-locator/virginia-va/hot-tubs-christiansburg/" hreflang="en-ca" />',
        '/hot-tub-dealer-locator/maine-me/hot-tubs-benton/' => '<link rel="alternate" href="http://www.sundancespas.com/hot-tub-dealer-locator/maine-me/hot-tubs-benton/" hreflang="en-us" /><link rel="alternate" href="http://www.sundancespas.ca/hot-tub-dealer-locator/maine-me/hot-tubs-benton/" hreflang="en-ca" />',
        '/hot-tub-dealer-locator/utah-ut/hot-tubs-st-george/' => '<link rel="alternate" href="http://www.sundancespas.com/hot-tub-dealer-locator/utah-ut/hot-tubs-st-george/" hreflang="en-us" /><link rel="alternate" href="http://www.sundancespas.ca/hot-tub-dealer-locator/utah-ut/hot-tubs-st-george/" hreflang="en-ca" />',
        '/hot-tub-dealer-locator/wisconsin-wi/hot-tubs-bryant/' => '<link rel="alternate" href="http://www.sundancespas.com/hot-tub-dealer-locator/wisconsin-wi/hot-tubs-bryant/" hreflang="en-us" /><link rel="alternate" href="http://www.sundancespas.ca/hot-tub-dealer-locator/wisconsin-wi/hot-tubs-bryant/" hreflang="en-ca" />',
        '/hot-tub-dealer-locator/new-york-ny/hot-tubs-hudson/' => '<link rel="alternate" href="http://www.sundancespas.com/hot-tub-dealer-locator/new-york-ny/hot-tubs-hudson/" hreflang="en-us" /><link rel="alternate" href="http://www.sundancespas.ca/hot-tub-dealer-locator/new-york-ny/hot-tubs-hudson/" hreflang="en-ca" />',
        '/hot-tub-dealer-locator/missouri-mo/hot-tubs-st-louis/' => '<link rel="alternate" href="http://www.sundancespas.com/hot-tub-dealer-locator/missouri-mo/hot-tubs-st-louis/" hreflang="en-us" /><link rel="alternate" href="http://www.sundancespas.ca/hot-tub-dealer-locator/missouri-mo/hot-tubs-st-louis/" hreflang="en-ca" />',
        '/hot-tub-dealer-locator/new-mexico-nm/hot-tubs-santa-fe/' => '<link rel="alternate" href="http://www.sundancespas.com/hot-tub-dealer-locator/new-mexico-nm/hot-tubs-santa-fe/" hreflang="en-us" /><link rel="alternate" href="http://www.sundancespas.ca/hot-tub-dealer-locator/new-mexico-nm/hot-tubs-santa-fe/" hreflang="en-ca" />',
        '/hot-tub-dealer-locator/california-ca/hot-tubs-oakhurst/' => '<link rel="alternate" href="http://www.sundancespas.com/hot-tub-dealer-locator/california-ca/hot-tubs-oakhurst/" hreflang="en-us" /><link rel="alternate" href="http://www.sundancespas.ca/hot-tub-dealer-locator/california-ca/hot-tubs-oakhurst/" hreflang="en-ca" />',
        '/hot-tub-dealer-locator/california-ca/hot-tubs-napa/' => '<link rel="alternate" href="http://www.sundancespas.com/hot-tub-dealer-locator/california-ca/hot-tubs-napa/" hreflang="en-us" /><link rel="alternate" href="http://www.sundancespas.ca/hot-tub-dealer-locator/california-ca/hot-tubs-napa/" hreflang="en-ca" />',
        '/hot-tub-dealer-locator/new-jersey-nj/hot-tubs-hillsborough/' => '<link rel="alternate" href="http://www.sundancespas.com/hot-tub-dealer-locator/new-jersey-nj/hot-tubs-hillsborough/" hreflang="en-us" /><link rel="alternate" href="http://www.sundancespas.ca/hot-tub-dealer-locator/new-jersey-nj/hot-tubs-hillsborough/" hreflang="en-ca" />',
        '/hot-tub-dealer-locator/new-jersey-nj/hot-tubs-robbinsville/' => '<link rel="alternate" href="http://www.sundancespas.com/hot-tub-dealer-locator/new-jersey-nj/hot-tubs-robbinsville/" hreflang="en-us" /><link rel="alternate" href="http://www.sundancespas.ca/hot-tub-dealer-locator/new-jersey-nj/hot-tubs-robbinsville/" hreflang="en-ca" />',
        '/hot-tub-dealer-locator/new-jersey-nj/hot-tubs-morris-plains/' => '<link rel="alternate" href="http://www.sundancespas.com/hot-tub-dealer-locator/new-jersey-nj/hot-tubs-morris-plains/" hreflang="en-us" /><link rel="alternate" href="http://www.sundancespas.ca/hot-tub-dealer-locator/new-jersey-nj/hot-tubs-morris-plains/" hreflang="en-ca" />',
        '/hot-tub-dealer-locator/colorado-co/hot-tubs-durango/' => '<link rel="alternate" href="http://www.sundancespas.com/hot-tub-dealer-locator/colorado-co/hot-tubs-durango/" hreflang="en-us" /><link rel="alternate" href="http://www.sundancespas.ca/hot-tub-dealer-locator/colorado-co/hot-tubs-durango/" hreflang="en-ca" />',
        '/hot-tub-dealer-locator/massachusetts-ma/hot-tubs-natick/' => '<link rel="alternate" href="http://www.sundancespas.com/hot-tub-dealer-locator/massachusetts-ma/hot-tubs-natick/" hreflang="en-us" /><link rel="alternate" href="http://www.sundancespas.ca/hot-tub-dealer-locator/massachusetts-ma/hot-tubs-natick/" hreflang="en-ca" />',
        '/hot-tub-dealer-locator/massachusetts-ma/hot-tubs-norwell/' => '<link rel="alternate" href="http://www.sundancespas.com/hot-tub-dealer-locator/massachusetts-ma/hot-tubs-norwell/" hreflang="en-us" /><link rel="alternate" href="http://www.sundancespas.ca/hot-tub-dealer-locator/massachusetts-ma/hot-tubs-norwell/" hreflang="en-ca" />',
        '/hot-tub-dealer-locator/massachusetts-ma/hot-tubs-auburn/' => '<link rel="alternate" href="http://www.sundancespas.com/hot-tub-dealer-locator/massachusetts-ma/hot-tubs-auburn/" hreflang="en-us" /><link rel="alternate" href="http://www.sundancespas.ca/hot-tub-dealer-locator/massachusetts-ma/hot-tubs-auburn/" hreflang="en-ca" />',
        '/hot-tub-dealer-locator/connecticut-ct/hot-tubs-danbury/' => '<link rel="alternate" href="http://www.sundancespas.com/hot-tub-dealer-locator/connecticut-ct/hot-tubs-danbury/" hreflang="en-us" /><link rel="alternate" href="http://www.sundancespas.ca/hot-tub-dealer-locator/connecticut-ct/hot-tubs-danbury/" hreflang="en-ca" />',
        '/hot-tub-dealer-locator/south-dakota-sd/hot-tubs-aberdeen/' => '<link rel="alternate" href="http://www.sundancespas.com/hot-tub-dealer-locator/south-dakota-sd/hot-tubs-aberdeen/" hreflang="en-us" /><link rel="alternate" href="http://www.sundancespas.ca/hot-tub-dealer-locator/south-dakota-sd/hot-tubs-aberdeen/" hreflang="en-ca" />',
        '/hot-tub-dealer-locator/south-carolina-sc/hot-tubs-myrtle-beach/' => '<link rel="alternate" href="http://www.sundancespas.com/hot-tub-dealer-locator/south-carolina-sc/hot-tubs-myrtle-beach/" hreflang="en-us" /><link rel="alternate" href="http://www.sundancespas.ca/hot-tub-dealer-locator/south-carolina-sc/hot-tubs-myrtle-beach/" hreflang="en-ca" />',
        '/hot-tub-dealer-locator/maryland-md/hot-tubs-salisbury/' => '<link rel="alternate" href="http://www.sundancespas.com/hot-tub-dealer-locator/maryland-md/hot-tubs-salisbury/" hreflang="en-us" /><link rel="alternate" href="http://www.sundancespas.ca/hot-tub-dealer-locator/maryland-md/hot-tubs-salisbury/" hreflang="en-ca" />',
        '/hot-tub-dealer-locator/oklahoma-ok/hot-tubs-woodward/' => '<link rel="alternate" href="http://www.sundancespas.com/hot-tub-dealer-locator/oklahoma-ok/hot-tubs-woodward/" hreflang="en-us" /><link rel="alternate" href="http://www.sundancespas.ca/hot-tub-dealer-locator/oklahoma-ok/hot-tubs-woodward/" hreflang="en-ca" />',
        '/hot-tub-dealer-locator/idaho-id/hot-tubs-sandpoint/' => '<link rel="alternate" href="http://www.sundancespas.com/hot-tub-dealer-locator/idaho-id/hot-tubs-sandpoint/" hreflang="en-us" /><link rel="alternate" href="http://www.sundancespas.ca/hot-tub-dealer-locator/idaho-id/hot-tubs-sandpoint/" hreflang="en-ca" />',
        '/hot-tub-dealer-locator/missouri-mo/hot-tubs-maryville/' => '<link rel="alternate" href="http://www.sundancespas.com/hot-tub-dealer-locator/missouri-mo/hot-tubs-maryville/" hreflang="en-us" /><link rel="alternate" href="http://www.sundancespas.ca/hot-tub-dealer-locator/missouri-mo/hot-tubs-maryville/" hreflang="en-ca" />',
        '/hot-tub-dealer-locator/virginia-va/hot-tubs-chester/' => '<link rel="alternate" href="http://www.sundancespas.com/hot-tub-dealer-locator/virginia-va/hot-tubs-chester/" hreflang="en-us" /><link rel="alternate" href="http://www.sundancespas.ca/hot-tub-dealer-locator/virginia-va/hot-tubs-chester/" hreflang="en-ca" />',
        '/hot-tub-dealer-locator/texas-tx/hot-tubs-sugar-land/' => '<link rel="alternate" href="http://www.sundancespas.com/hot-tub-dealer-locator/texas-tx/hot-tubs-sugar-land/" hreflang="en-us" /><link rel="alternate" href="http://www.sundancespas.ca/hot-tub-dealer-locator/texas-tx/hot-tubs-sugar-land/" hreflang="en-ca" />',
        '/hot-tub-dealer-locator/texas-tx/hot-tubs-willis/' => '<link rel="alternate" href="http://www.sundancespas.com/hot-tub-dealer-locator/texas-tx/hot-tubs-willis/" hreflang="en-us" /><link rel="alternate" href="http://www.sundancespas.ca/hot-tub-dealer-locator/texas-tx/hot-tubs-willis/" hreflang="en-ca" />',
        '/hot-tub-dealer-locator/texas-tx/hot-tubs-wichita-falls/' => '<link rel="alternate" href="http://www.sundancespas.com/hot-tub-dealer-locator/texas-tx/hot-tubs-wichita-falls/" hreflang="en-us" /><link rel="alternate" href="http://www.sundancespas.ca/hot-tub-dealer-locator/texas-tx/hot-tubs-wichita-falls/" hreflang="en-ca" />',
        '/hot-tub-dealer-locator/minnesota-mn/hot-tubs-alexandria/' => '<link rel="alternate" href="http://www.sundancespas.com/hot-tub-dealer-locator/minnesota-mn/hot-tubs-alexandria/" hreflang="en-us" /><link rel="alternate" href="http://www.sundancespas.ca/hot-tub-dealer-locator/minnesota-mn/hot-tubs-alexandria/" hreflang="en-ca" />',
        '/hot-tub-dealer-locator/texas-tx/hot-tubs-league-city/' => '<link rel="alternate" href="http://www.sundancespas.com/hot-tub-dealer-locator/texas-tx/hot-tubs-league-city/" hreflang="en-us" /><link rel="alternate" href="http://www.sundancespas.ca/hot-tub-dealer-locator/texas-tx/hot-tubs-league-city/" hreflang="en-ca" />',
        '/hot-tub-dealer-locator/michigan-mi/hot-tubs-muskegon/' => '<link rel="alternate" href="http://www.sundancespas.com/hot-tub-dealer-locator/michigan-mi/hot-tubs-muskegon/" hreflang="en-us" /><link rel="alternate" href="http://www.sundancespas.ca/hot-tub-dealer-locator/michigan-mi/hot-tubs-muskegon/" hreflang="en-ca" />',
        '/hot-tub-dealer-locator/iowa-ia/hot-tubs-dubuque/' => '<link rel="alternate" href="http://www.sundancespas.com/hot-tub-dealer-locator/iowa-ia/hot-tubs-dubuque/" hreflang="en-us" /><link rel="alternate" href="http://www.sundancespas.ca/hot-tub-dealer-locator/iowa-ia/hot-tubs-dubuque/" hreflang="en-ca" />',
        '/hot-tub-dealer-locator/wisconsin-wi/hot-tubs-onalaska/' => '<link rel="alternate" href="http://www.sundancespas.com/hot-tub-dealer-locator/wisconsin-wi/hot-tubs-onalaska/" hreflang="en-us" /><link rel="alternate" href="http://www.sundancespas.ca/hot-tub-dealer-locator/wisconsin-wi/hot-tubs-onalaska/" hreflang="en-ca" />',
        '/hot-tub-dealer-locator/california-ca/hot-tubs-san-mateo/' => '<link rel="alternate" href="http://www.sundancespas.com/hot-tub-dealer-locator/california-ca/hot-tubs-san-mateo/" hreflang="en-us" /><link rel="alternate" href="http://www.sundancespas.ca/hot-tub-dealer-locator/california-ca/hot-tubs-san-mateo/" hreflang="en-ca" />',
        '/hot-tub-dealer-locator/california-ca/hot-tubs-san-rafael/' => '<link rel="alternate" href="http://www.sundancespas.com/hot-tub-dealer-locator/california-ca/hot-tubs-san-rafael/" hreflang="en-us" /><link rel="alternate" href="http://www.sundancespas.ca/hot-tub-dealer-locator/california-ca/hot-tubs-san-rafael/" hreflang="en-ca" />',
        '/hot-tub-dealer-locator/california-ca/hot-tubs-san-jose/' => '<link rel="alternate" href="http://www.sundancespas.com/hot-tub-dealer-locator/california-ca/hot-tubs-san-jose/" hreflang="en-us" /><link rel="alternate" href="http://www.sundancespas.ca/hot-tub-dealer-locator/california-ca/hot-tubs-san-jose/" hreflang="en-ca" />',
        '/hot-tub-dealer-locator/california-ca/hot-tubs-concord/' => '<link rel="alternate" href="http://www.sundancespas.com/hot-tub-dealer-locator/california-ca/hot-tubs-concord/" hreflang="en-us" /><link rel="alternate" href="http://www.sundancespas.ca/hot-tub-dealer-locator/california-ca/hot-tubs-concord/" hreflang="en-ca" />',
        '/hot-tub-dealer-locator/michigan-mi/hot-tubs-brooklyn/' => '<link rel="alternate" href="http://www.sundancespas.com/hot-tub-dealer-locator/michigan-mi/hot-tubs-brooklyn/" hreflang="en-us" /><link rel="alternate" href="http://www.sundancespas.ca/hot-tub-dealer-locator/michigan-mi/hot-tubs-brooklyn/" hreflang="en-ca" />',
        '/hot-tub-dealer-locator/georgia-ga/hot-tubs-evans/' => '<link rel="alternate" href="http://www.sundancespas.com/hot-tub-dealer-locator/georgia-ga/hot-tubs-evans/" hreflang="en-us" /><link rel="alternate" href="http://www.sundancespas.ca/hot-tub-dealer-locator/georgia-ga/hot-tubs-evans/" hreflang="en-ca" />',
        '/hot-tub-dealer-locator/iowa-ia/hot-tubs-sioux-city/' => '<link rel="alternate" href="http://www.sundancespas.com/hot-tub-dealer-locator/iowa-ia/hot-tubs-sioux-city/" hreflang="en-us" /><link rel="alternate" href="http://www.sundancespas.ca/hot-tub-dealer-locator/iowa-ia/hot-tubs-sioux-city/" hreflang="en-ca" />',
        '/hot-tub-dealer-locator/california-ca/hot-tubs-shingle-springs/' => '<link rel="alternate" href="http://www.sundancespas.com/hot-tub-dealer-locator/california-ca/hot-tubs-shingle-springs/" hreflang="en-us" /><link rel="alternate" href="http://www.sundancespas.ca/hot-tub-dealer-locator/california-ca/hot-tubs-shingle-springs/" hreflang="en-ca" />',
        '/hot-tub-dealer-locator/new-mexico-nm/hot-tubs-albuquerque/' => '<link rel="alternate" href="http://www.sundancespas.com/hot-tub-dealer-locator/new-mexico-nm/hot-tubs-albuquerque/" hreflang="en-us" /><link rel="alternate" href="http://www.sundancespas.ca/hot-tub-dealer-locator/new-mexico-nm/hot-tubs-albuquerque/" hreflang="en-ca" />',
        '/hot-tub-dealer-locator/texas-tx/hot-tubs-graham/' => '<link rel="alternate" href="http://www.sundancespas.com/hot-tub-dealer-locator/texas-tx/hot-tubs-graham/" hreflang="en-us" /><link rel="alternate" href="http://www.sundancespas.ca/hot-tub-dealer-locator/texas-tx/hot-tubs-graham/" hreflang="en-ca" />',
        '/hot-tub-dealer-locator/california-ca/hot-tubs-visalia/' => '<link rel="alternate" href="http://www.sundancespas.com/hot-tub-dealer-locator/california-ca/hot-tubs-visalia/" hreflang="en-us" /><link rel="alternate" href="http://www.sundancespas.ca/hot-tub-dealer-locator/california-ca/hot-tubs-visalia/" hreflang="en-ca" />',
        '/hot-tub-dealer-locator/texas-tx/hot-tubs-paris/' => '<link rel="alternate" href="http://www.sundancespas.com/hot-tub-dealer-locator/texas-tx/hot-tubs-paris/" hreflang="en-us" /><link rel="alternate" href="http://www.sundancespas.ca/hot-tub-dealer-locator/texas-tx/hot-tubs-paris/" hreflang="en-ca" />',
        '/hot-tub-dealer-locator/new-york-ny/hot-tubs-amherst/' => '<link rel="alternate" href="http://www.sundancespas.com/hot-tub-dealer-locator/new-york-ny/hot-tubs-amherst/" hreflang="en-us" /><link rel="alternate" href="http://www.sundancespas.ca/hot-tub-dealer-locator/new-york-ny/hot-tubs-amherst/" hreflang="en-ca" />',
        '/hot-tub-dealer-locator/pennsylvania-pa/hot-tubs-west-mifflin/' => '<link rel="alternate" href="http://www.sundancespas.com/hot-tub-dealer-locator/pennsylvania-pa/hot-tubs-west-mifflin/" hreflang="en-us" /><link rel="alternate" href="http://www.sundancespas.ca/hot-tub-dealer-locator/pennsylvania-pa/hot-tubs-west-mifflin/" hreflang="en-ca" />',
        '/hot-tub-dealer-locator/pennsylvania-pa/hot-tubs-washington/' => '<link rel="alternate" href="http://www.sundancespas.com/hot-tub-dealer-locator/pennsylvania-pa/hot-tubs-washington/" hreflang="en-us" /><link rel="alternate" href="http://www.sundancespas.ca/hot-tub-dealer-locator/pennsylvania-pa/hot-tubs-washington/" hreflang="en-ca" />',
        '/hot-tub-dealer-locator/pennsylvania-pa/hot-tubs-monroeville/' => '<link rel="alternate" href="http://www.sundancespas.com/hot-tub-dealer-locator/pennsylvania-pa/hot-tubs-monroeville/" hreflang="en-us" /><link rel="alternate" href="http://www.sundancespas.ca/hot-tub-dealer-locator/pennsylvania-pa/hot-tubs-monroeville/" hreflang="en-ca" />',
        '/hot-tub-dealer-locator/pennsylvania-pa/hot-tubs-pittsburgh/' => '<link rel="alternate" href="http://www.sundancespas.com/hot-tub-dealer-locator/pennsylvania-pa/hot-tubs-pittsburgh/" hreflang="en-us" /><link rel="alternate" href="http://www.sundancespas.ca/hot-tub-dealer-locator/pennsylvania-pa/hot-tubs-pittsburgh/" hreflang="en-ca" />',
        '/hot-tub-dealer-locator/pennsylvania-pa/hot-tubs-pittsburgh/' => '<link rel="alternate" href="http://www.sundancespas.com/hot-tub-dealer-locator/pennsylvania-pa/hot-tubs-pittsburgh/" hreflang="en-us" /><link rel="alternate" href="http://www.sundancespas.ca/hot-tub-dealer-locator/pennsylvania-pa/hot-tubs-pittsburgh/" hreflang="en-ca" />',
        '/hot-tub-dealer-locator/pennsylvania-pa/hot-tubs-greensburg/' => '<link rel="alternate" href="http://www.sundancespas.com/hot-tub-dealer-locator/pennsylvania-pa/hot-tubs-greensburg/" hreflang="en-us" /><link rel="alternate" href="http://www.sundancespas.ca/hot-tub-dealer-locator/pennsylvania-pa/hot-tubs-greensburg/" hreflang="en-ca" />',
        '/hot-tub-dealer-locator/pennsylvania-pa/hot-tubs-cranberry-township/' => '<link rel="alternate" href="http://www.sundancespas.com/hot-tub-dealer-locator/pennsylvania-pa/hot-tubs-cranberry-township/" hreflang="en-us" /><link rel="alternate" href="http://www.sundancespas.ca/hot-tub-dealer-locator/pennsylvania-pa/hot-tubs-cranberry-township/" hreflang="en-ca" />',
        '/hot-tub-dealer-locator/oklahoma-ok/hot-tubs-ponca-city/' => '<link rel="alternate" href="http://www.sundancespas.com/hot-tub-dealer-locator/oklahoma-ok/hot-tubs-ponca-city/" hreflang="en-us" /><link rel="alternate" href="http://www.sundancespas.ca/hot-tub-dealer-locator/oklahoma-ok/hot-tubs-ponca-city/" hreflang="en-ca" />',
        '/hot-tub-dealer-locator/oklahoma-ok/hot-tubs-stillwater/' => '<link rel="alternate" href="http://www.sundancespas.com/hot-tub-dealer-locator/oklahoma-ok/hot-tubs-stillwater/" hreflang="en-us" /><link rel="alternate" href="http://www.sundancespas.ca/hot-tub-dealer-locator/oklahoma-ok/hot-tubs-stillwater/" hreflang="en-ca" />',
        '/hot-tub-dealer-locator/rhode-island-ri/hot-tubs-cranston/' => '<link rel="alternate" href="http://www.sundancespas.com/hot-tub-dealer-locator/rhode-island-ri/hot-tubs-cranston/" hreflang="en-us" /><link rel="alternate" href="http://www.sundancespas.ca/hot-tub-dealer-locator/rhode-island-ri/hot-tubs-cranston/" hreflang="en-ca" />',
        '/hot-tub-dealer-locator/missouri-mo/hot-tubs-columbia/' => '<link rel="alternate" href="http://www.sundancespas.com/hot-tub-dealer-locator/missouri-mo/hot-tubs-columbia/" hreflang="en-us" /><link rel="alternate" href="http://www.sundancespas.ca/hot-tub-dealer-locator/missouri-mo/hot-tubs-columbia/" hreflang="en-ca" />',
        '/hot-tub-dealer-locator/wyoming-wy/hot-tubs-riverton/' => '<link rel="alternate" href="http://www.sundancespas.com/hot-tub-dealer-locator/wyoming-wy/hot-tubs-riverton/" hreflang="en-us" /><link rel="alternate" href="http://www.sundancespas.ca/hot-tub-dealer-locator/wyoming-wy/hot-tubs-riverton/" hreflang="en-ca" />',
        '/hot-tub-dealer-locator/wyoming-wy/hot-tubs-casper/' => '<link rel="alternate" href="http://www.sundancespas.com/hot-tub-dealer-locator/wyoming-wy/hot-tubs-casper/" hreflang="en-us" /><link rel="alternate" href="http://www.sundancespas.ca/hot-tub-dealer-locator/wyoming-wy/hot-tubs-casper/" hreflang="en-ca" />',
        '/hot-tub-dealer-locator/new-york-ny/hot-tubs-glens-falls/' => '<link rel="alternate" href="http://www.sundancespas.com/hot-tub-dealer-locator/new-york-ny/hot-tubs-glens-falls/" hreflang="en-us" /><link rel="alternate" href="http://www.sundancespas.ca/hot-tub-dealer-locator/new-york-ny/hot-tubs-glens-falls/" hreflang="en-ca" />',
        '/hot-tub-dealer-locator/oregon-or/hot-tubs-redmond/' => '<link rel="alternate" href="http://www.sundancespas.com/hot-tub-dealer-locator/oregon-or/hot-tubs-redmond/" hreflang="en-us" /><link rel="alternate" href="http://www.sundancespas.ca/hot-tub-dealer-locator/oregon-or/hot-tubs-redmond/" hreflang="en-ca" />',
        '/hot-tub-dealer-locator/maine-me/hot-tubs-presque-isle/' => '<link rel="alternate" href="http://www.sundancespas.com/hot-tub-dealer-locator/maine-me/hot-tubs-presque-isle/" hreflang="en-us" /><link rel="alternate" href="http://www.sundancespas.ca/hot-tub-dealer-locator/maine-me/hot-tubs-presque-isle/" hreflang="en-ca" />',
        '/hot-tub-dealer-locator/california-ca/hot-tubs-glendora/' => '<link rel="alternate" href="http://www.sundancespas.com/hot-tub-dealer-locator/california-ca/hot-tubs-glendora/" hreflang="en-us" /><link rel="alternate" href="http://www.sundancespas.ca/hot-tub-dealer-locator/california-ca/hot-tubs-glendora/" hreflang="en-ca" />',
        '/hot-tub-dealer-locator/new-york-ny/hot-tubs-new-hampton/' => '<link rel="alternate" href="http://www.sundancespas.com/hot-tub-dealer-locator/new-york-ny/hot-tubs-new-hampton/" hreflang="en-us" /><link rel="alternate" href="http://www.sundancespas.ca/hot-tub-dealer-locator/new-york-ny/hot-tubs-new-hampton/" hreflang="en-ca" />',
        '/hot-tub-dealer-locator/new-york-ny/hot-tubs-newburgh/' => '<link rel="alternate" href="http://www.sundancespas.com/hot-tub-dealer-locator/new-york-ny/hot-tubs-newburgh/" hreflang="en-us" /><link rel="alternate" href="http://www.sundancespas.ca/hot-tub-dealer-locator/new-york-ny/hot-tubs-newburgh/" hreflang="en-ca" />',
        '/hot-tub-dealer-locator/vermont-vt/hot-tubs-colchester/' => '<link rel="alternate" href="http://www.sundancespas.com/hot-tub-dealer-locator/vermont-vt/hot-tubs-colchester/" hreflang="en-us" /><link rel="alternate" href="http://www.sundancespas.ca/hot-tub-dealer-locator/vermont-vt/hot-tubs-colchester/" hreflang="en-ca" />',
        '/hot-tub-dealer-locator/texas-tx/hot-tubs-abilene/' => '<link rel="alternate" href="http://www.sundancespas.com/hot-tub-dealer-locator/texas-tx/hot-tubs-abilene/" hreflang="en-us" /><link rel="alternate" href="http://www.sundancespas.ca/hot-tub-dealer-locator/texas-tx/hot-tubs-abilene/" hreflang="en-ca" />',
        '/hot-tub-dealer-locator/california-ca/hot-tubs-cotati/' => '<link rel="alternate" href="http://www.sundancespas.com/hot-tub-dealer-locator/california-ca/hot-tubs-cotati/" hreflang="en-us" /><link rel="alternate" href="http://www.sundancespas.ca/hot-tub-dealer-locator/california-ca/hot-tubs-cotati/" hreflang="en-ca" />',
        '/hot-tub-dealer-locator/california-ca/hot-tubs-mammoth-lakes/' => '<link rel="alternate" href="http://www.sundancespas.com/hot-tub-dealer-locator/california-ca/hot-tubs-mammoth-lakes/" hreflang="en-us" /><link rel="alternate" href="http://www.sundancespas.ca/hot-tub-dealer-locator/california-ca/hot-tubs-mammoth-lakes/" hreflang="en-ca" />',
        '/hot-tub-dealer-locator/north-dakota-nd/hot-tubs-bismarck/' => '<link rel="alternate" href="http://www.sundancespas.com/hot-tub-dealer-locator/north-dakota-nd/hot-tubs-bismarck/" hreflang="en-us" /><link rel="alternate" href="http://www.sundancespas.ca/hot-tub-dealer-locator/north-dakota-nd/hot-tubs-bismarck/" hreflang="en-ca" />',
        '/hot-tub-dealer-locator/wyoming-wy/hot-tubs-rock-springs/' => '<link rel="alternate" href="http://www.sundancespas.com/hot-tub-dealer-locator/wyoming-wy/hot-tubs-rock-springs/" hreflang="en-us" /><link rel="alternate" href="http://www.sundancespas.ca/hot-tub-dealer-locator/wyoming-wy/hot-tubs-rock-springs/" hreflang="en-ca" />',
        '/hot-tub-dealer-locator/nebraska-ne/hot-tubs-lincoln/' => '<link rel="alternate" href="http://www.sundancespas.com/hot-tub-dealer-locator/nebraska-ne/hot-tubs-lincoln/" hreflang="en-us" /><link rel="alternate" href="http://www.sundancespas.ca/hot-tub-dealer-locator/nebraska-ne/hot-tubs-lincoln/" hreflang="en-ca" />',
        '/hot-tub-dealer-locator/oklahoma-ok/hot-tubs-lawton/' => '<link rel="alternate" href="http://www.sundancespas.com/hot-tub-dealer-locator/oklahoma-ok/hot-tubs-lawton/" hreflang="en-us" /><link rel="alternate" href="http://www.sundancespas.ca/hot-tub-dealer-locator/oklahoma-ok/hot-tubs-lawton/" hreflang="en-ca" />',
        '/hot-tub-dealer-locator/illinois-il/hot-tubs-decatur/' => '<link rel="alternate" href="http://www.sundancespas.com/hot-tub-dealer-locator/illinois-il/hot-tubs-decatur/" hreflang="en-us" /><link rel="alternate" href="http://www.sundancespas.ca/hot-tub-dealer-locator/illinois-il/hot-tubs-decatur/" hreflang="en-ca" />',
        '/hot-tub-dealer-locator/california-ca/hot-tubs-stockton/' => '<link rel="alternate" href="http://www.sundancespas.com/hot-tub-dealer-locator/california-ca/hot-tubs-stockton/" hreflang="en-us" /><link rel="alternate" href="http://www.sundancespas.ca/hot-tub-dealer-locator/california-ca/hot-tubs-stockton/" hreflang="en-ca" />',
        '/hot-tub-dealer-locator/arizona-az/hot-tubs-overgaard/' => '<link rel="alternate" href="http://www.sundancespas.com/hot-tub-dealer-locator/arizona-az/hot-tubs-overgaard/" hreflang="en-us" /><link rel="alternate" href="http://www.sundancespas.ca/hot-tub-dealer-locator/arizona-az/hot-tubs-overgaard/" hreflang="en-ca" />',
        '/hot-tub-dealer-locator/wisconsin-wi/hot-tubs-waupaca/' => '<link rel="alternate" href="http://www.sundancespas.com/hot-tub-dealer-locator/wisconsin-wi/hot-tubs-waupaca/" hreflang="en-us" /><link rel="alternate" href="http://www.sundancespas.ca/hot-tub-dealer-locator/wisconsin-wi/hot-tubs-waupaca/" hreflang="en-ca" />',
        '/hot-tub-dealer-locator/california-ca/hot-tubs-chico/' => '<link rel="alternate" href="http://www.sundancespas.com/hot-tub-dealer-locator/california-ca/hot-tubs-chico/" hreflang="en-us" /><link rel="alternate" href="http://www.sundancespas.ca/hot-tub-dealer-locator/california-ca/hot-tubs-chico/" hreflang="en-ca" />',
        '/hot-tub-dealer-locator/washington-wa/hot-tubs-lakewood/' => '<link rel="alternate" href="http://www.sundancespas.com/hot-tub-dealer-locator/washington-wa/hot-tubs-lakewood/" hreflang="en-us" /><link rel="alternate" href="http://www.sundancespas.ca/hot-tub-dealer-locator/washington-wa/hot-tubs-lakewood/" hreflang="en-ca" />',
        '/hot-tub-dealer-locator/washington-wa/hot-tubs-bellevue/' => '<link rel="alternate" href="http://www.sundancespas.com/hot-tub-dealer-locator/washington-wa/hot-tubs-bellevue/" hreflang="en-us" /><link rel="alternate" href="http://www.sundancespas.ca/hot-tub-dealer-locator/washington-wa/hot-tubs-bellevue/" hreflang="en-ca" />',
        '/hot-tub-dealer-locator/washington-wa/hot-tubs-seattle/' => '<link rel="alternate" href="http://www.sundancespas.com/hot-tub-dealer-locator/washington-wa/hot-tubs-seattle/" hreflang="en-us" /><link rel="alternate" href="http://www.sundancespas.ca/hot-tub-dealer-locator/washington-wa/hot-tubs-seattle/" hreflang="en-ca" />',
        '/hot-tub-dealer-locator/indiana-in/hot-tubs-newburgh/' => '<link rel="alternate" href="http://www.sundancespas.com/hot-tub-dealer-locator/indiana-in/hot-tubs-newburgh/" hreflang="en-us" /><link rel="alternate" href="http://www.sundancespas.ca/hot-tub-dealer-locator/indiana-in/hot-tubs-newburgh/" hreflang="en-ca" />',
        '/hot-tub-dealer-locator/indiana-in/hot-tubs-fort-wayne/' => '<link rel="alternate" href="http://www.sundancespas.com/hot-tub-dealer-locator/indiana-in/hot-tubs-fort-wayne/" hreflang="en-us" /><link rel="alternate" href="http://www.sundancespas.ca/hot-tub-dealer-locator/indiana-in/hot-tubs-fort-wayne/" hreflang="en-ca" />',
        '/hot-tub-dealer-locator/pennsylvania-pa/hot-tubs-state-college/' => '<link rel="alternate" href="http://www.sundancespas.com/hot-tub-dealer-locator/pennsylvania-pa/hot-tubs-state-college/" hreflang="en-us" /><link rel="alternate" href="http://www.sundancespas.ca/hot-tub-dealer-locator/pennsylvania-pa/hot-tubs-state-college/" hreflang="en-ca" />',
        '/hot-tub-dealer-locator/new-york-ny/hot-tubs-vestal/' => '<link rel="alternate" href="http://www.sundancespas.com/hot-tub-dealer-locator/new-york-ny/hot-tubs-vestal/" hreflang="en-us" /><link rel="alternate" href="http://www.sundancespas.ca/hot-tub-dealer-locator/new-york-ny/hot-tubs-vestal/" hreflang="en-ca" />',
        '/hot-tub-dealer-locator/new-york-ny/hot-tubs-oneonta/' => '<link rel="alternate" href="http://www.sundancespas.com/hot-tub-dealer-locator/new-york-ny/hot-tubs-oneonta/" hreflang="en-us" /><link rel="alternate" href="http://www.sundancespas.ca/hot-tub-dealer-locator/new-york-ny/hot-tubs-oneonta/" hreflang="en-ca" />',
        '/hot-tub-dealer-locator/indiana-in/hot-tubs-merrillville/' => '<link rel="alternate" href="http://www.sundancespas.com/hot-tub-dealer-locator/indiana-in/hot-tubs-merrillville/" hreflang="en-us" /><link rel="alternate" href="http://www.sundancespas.ca/hot-tub-dealer-locator/indiana-in/hot-tubs-merrillville/" hreflang="en-ca" />',
        '/hot-tub-dealer-locator/illinois-il/hot-tubs-loves-park/' => '<link rel="alternate" href="http://www.sundancespas.com/hot-tub-dealer-locator/illinois-il/hot-tubs-loves-park/" hreflang="en-us" /><link rel="alternate" href="http://www.sundancespas.ca/hot-tub-dealer-locator/illinois-il/hot-tubs-loves-park/" hreflang="en-ca" />',
        '/hot-tub-dealer-locator/illinois-il/hot-tubs-mundelein/' => '<link rel="alternate" href="http://www.sundancespas.com/hot-tub-dealer-locator/illinois-il/hot-tubs-mundelein/" hreflang="en-us" /><link rel="alternate" href="http://www.sundancespas.ca/hot-tub-dealer-locator/illinois-il/hot-tubs-mundelein/" hreflang="en-ca" />',
        '/hot-tub-dealer-locator/illinois-il/hot-tubs-gurnee/' => '<link rel="alternate" href="http://www.sundancespas.com/hot-tub-dealer-locator/illinois-il/hot-tubs-gurnee/" hreflang="en-us" /><link rel="alternate" href="http://www.sundancespas.ca/hot-tub-dealer-locator/illinois-il/hot-tubs-gurnee/" hreflang="en-ca" />',
        '/hot-tub-dealer-locator/illinois-il/hot-tubs-batavia/' => '<link rel="alternate" href="http://www.sundancespas.com/hot-tub-dealer-locator/illinois-il/hot-tubs-batavia/" hreflang="en-us" /><link rel="alternate" href="http://www.sundancespas.ca/hot-tub-dealer-locator/illinois-il/hot-tubs-batavia/" hreflang="en-ca" />',
        '/hot-tub-dealer-locator/illinois-il/hot-tubs-peoria/' => '<link rel="alternate" href="http://www.sundancespas.com/hot-tub-dealer-locator/illinois-il/hot-tubs-peoria/" hreflang="en-us" /><link rel="alternate" href="http://www.sundancespas.ca/hot-tub-dealer-locator/illinois-il/hot-tubs-peoria/" hreflang="en-ca" />',
        '/hot-tub-dealer-locator/illinois-il/hot-tubs-schaumburg/' => '<link rel="alternate" href="http://www.sundancespas.com/hot-tub-dealer-locator/illinois-il/hot-tubs-schaumburg/" hreflang="en-us" /><link rel="alternate" href="http://www.sundancespas.ca/hot-tub-dealer-locator/illinois-il/hot-tubs-schaumburg/" hreflang="en-ca" />',
        '/hot-tub-dealer-locator/illinois-il/hot-tubs-aurora/' => '<link rel="alternate" href="http://www.sundancespas.com/hot-tub-dealer-locator/illinois-il/hot-tubs-aurora/" hreflang="en-us" /><link rel="alternate" href="http://www.sundancespas.ca/hot-tub-dealer-locator/illinois-il/hot-tubs-aurora/" hreflang="en-ca" />',
        '/hot-tub-dealer-locator/illinois-il/hot-tubs-bridgeview/' => '<link rel="alternate" href="http://www.sundancespas.com/hot-tub-dealer-locator/illinois-il/hot-tubs-bridgeview/" hreflang="en-us" /><link rel="alternate" href="http://www.sundancespas.ca/hot-tub-dealer-locator/illinois-il/hot-tubs-bridgeview/" hreflang="en-ca" />',
        '/hot-tub-dealer-locator/indiana-in/hot-tubs-mishawaka/' => '<link rel="alternate" href="http://www.sundancespas.com/hot-tub-dealer-locator/indiana-in/hot-tubs-mishawaka/" hreflang="en-us" /><link rel="alternate" href="http://www.sundancespas.ca/hot-tub-dealer-locator/indiana-in/hot-tubs-mishawaka/" hreflang="en-ca" />',
        '/hot-tub-dealer-locator/illinois-il/hot-tubs-downers-grove/' => '<link rel="alternate" href="http://www.sundancespas.com/hot-tub-dealer-locator/illinois-il/hot-tubs-downers-grove/" hreflang="en-us" /><link rel="alternate" href="http://www.sundancespas.ca/hot-tub-dealer-locator/illinois-il/hot-tubs-downers-grove/" hreflang="en-ca" />',
        '/hot-tub-dealer-locator/virginia-va/hot-tubs-waynesboro/' => '<link rel="alternate" href="http://www.sundancespas.com/hot-tub-dealer-locator/virginia-va/hot-tubs-waynesboro/" hreflang="en-us" /><link rel="alternate" href="http://www.sundancespas.ca/hot-tub-dealer-locator/virginia-va/hot-tubs-waynesboro/" hreflang="en-ca" />',
        '/hot-tub-dealer-locator/virginia-va/hot-tubs-harrisonburg/' => '<link rel="alternate" href="http://www.sundancespas.com/hot-tub-dealer-locator/virginia-va/hot-tubs-harrisonburg/" hreflang="en-us" /><link rel="alternate" href="http://www.sundancespas.ca/hot-tub-dealer-locator/virginia-va/hot-tubs-harrisonburg/" hreflang="en-ca" />',
        '/hot-tub-dealer-locator/new-jersey-nj/hot-tubs-cherry-hill/' => '<link rel="alternate" href="http://www.sundancespas.com/hot-tub-dealer-locator/new-jersey-nj/hot-tubs-cherry-hill/" hreflang="en-us" /><link rel="alternate" href="http://www.sundancespas.ca/hot-tub-dealer-locator/new-jersey-nj/hot-tubs-cherry-hill/" hreflang="en-ca" />',
        '/hot-tub-dealer-locator/colorado-co/hot-tubs-grand-junction/' => '<link rel="alternate" href="http://www.sundancespas.com/hot-tub-dealer-locator/colorado-co/hot-tubs-grand-junction/" hreflang="en-us" /><link rel="alternate" href="http://www.sundancespas.ca/hot-tub-dealer-locator/colorado-co/hot-tubs-grand-junction/" hreflang="en-ca" />',
        '/hot-tub-dealer-locator/washington-wa/hot-tubs-union-gap/' => '<link rel="alternate" href="http://www.sundancespas.com/hot-tub-dealer-locator/washington-wa/hot-tubs-union-gap/" hreflang="en-us" /><link rel="alternate" href="http://www.sundancespas.ca/hot-tub-dealer-locator/washington-wa/hot-tubs-union-gap/" hreflang="en-ca" />',
        '/hot-tub-dealer-locator/north-dakota-nd/hot-tubs-fargo/' => '<link rel="alternate" href="http://www.sundancespas.com/hot-tub-dealer-locator/north-dakota-nd/hot-tubs-fargo/" hreflang="en-us" /><link rel="alternate" href="http://www.sundancespas.ca/hot-tub-dealer-locator/north-dakota-nd/hot-tubs-fargo/" hreflang="en-ca" />',
        '/hot-tub-dealer-locator/wisconsin-wi/hot-tubs-franklin/' => '<link rel="alternate" href="http://www.sundancespas.com/hot-tub-dealer-locator/wisconsin-wi/hot-tubs-franklin/" hreflang="en-us" /><link rel="alternate" href="http://www.sundancespas.ca/hot-tub-dealer-locator/wisconsin-wi/hot-tubs-franklin/" hreflang="en-ca" />',
        '/hot-tub-dealer-locator/wisconsin-wi/hot-tubs-oshkosh/' => '<link rel="alternate" href="http://www.sundancespas.com/hot-tub-dealer-locator/wisconsin-wi/hot-tubs-oshkosh/" hreflang="en-us" /><link rel="alternate" href="http://www.sundancespas.ca/hot-tub-dealer-locator/wisconsin-wi/hot-tubs-oshkosh/" hreflang="en-ca" />',
        '/hot-tub-dealer-locator/wisconsin-wi/hot-tubs-redgranite/' => '<link rel="alternate" href="http://www.sundancespas.com/hot-tub-dealer-locator/wisconsin-wi/hot-tubs-redgranite/" hreflang="en-us" /><link rel="alternate" href="http://www.sundancespas.ca/hot-tub-dealer-locator/wisconsin-wi/hot-tubs-redgranite/" hreflang="en-ca" />',
        '/hot-tub-dealer-locator/wisconsin-wi/hot-tubs-fond-du-lac/' => '<link rel="alternate" href="http://www.sundancespas.com/hot-tub-dealer-locator/wisconsin-wi/hot-tubs-fond-du-lac/" hreflang="en-us" /><link rel="alternate" href="http://www.sundancespas.ca/hot-tub-dealer-locator/wisconsin-wi/hot-tubs-fond-du-lac/" hreflang="en-ca" />',
        '/hot-tub-dealer-locator/california-ca/hot-tubs-paradise/' => '<link rel="alternate" href="http://www.sundancespas.com/hot-tub-dealer-locator/california-ca/hot-tubs-paradise/" hreflang="en-us" /><link rel="alternate" href="http://www.sundancespas.ca/hot-tub-dealer-locator/california-ca/hot-tubs-paradise/" hreflang="en-ca" />',
        '/hot-tub-dealer-locator/virginia-va/hot-tubs-danville/' => '<link rel="alternate" href="http://www.sundancespas.com/hot-tub-dealer-locator/virginia-va/hot-tubs-danville/" hreflang="en-us" /><link rel="alternate" href="http://www.sundancespas.ca/hot-tub-dealer-locator/virginia-va/hot-tubs-danville/" hreflang="en-ca" />',
        '/hot-tub-dealer-locator/california-ca/hot-tubs-fresno/' => '<link rel="alternate" href="http://www.sundancespas.com/hot-tub-dealer-locator/california-ca/hot-tubs-fresno/" hreflang="en-us" /><link rel="alternate" href="http://www.sundancespas.ca/hot-tub-dealer-locator/california-ca/hot-tubs-fresno/" hreflang="en-ca" />',
        '/hot-tub-dealer-locator/oregon-or/hot-tubs-roseburg/' => '<link rel="alternate" href="http://www.sundancespas.com/hot-tub-dealer-locator/oregon-or/hot-tubs-roseburg/" hreflang="en-us" /><link rel="alternate" href="http://www.sundancespas.ca/hot-tub-dealer-locator/oregon-or/hot-tubs-roseburg/" hreflang="en-ca" />',
        '/hot-tub-dealer-locator/virginia-va/hot-tubs-culpeper/' => '<link rel="alternate" href="http://www.sundancespas.com/hot-tub-dealer-locator/virginia-va/hot-tubs-culpeper/" hreflang="en-us" /><link rel="alternate" href="http://www.sundancespas.ca/hot-tub-dealer-locator/virginia-va/hot-tubs-culpeper/" hreflang="en-ca" />',
        '/hot-tub-dealer-locator/california-ca/hot-tubs-los-angeles/' => '<link rel="alternate" href="http://www.sundancespas.com/hot-tub-dealer-locator/california-ca/hot-tubs-los-angeles/" hreflang="en-us" /><link rel="alternate" href="http://www.sundancespas.ca/hot-tub-dealer-locator/california-ca/hot-tubs-los-angeles/" hreflang="en-ca" />',
        '/hot-tub-dealer-locator/virginia-va/hot-tubs-warrenton/' => '<link rel="alternate" href="http://www.sundancespas.com/hot-tub-dealer-locator/virginia-va/hot-tubs-warrenton/" hreflang="en-us" /><link rel="alternate" href="http://www.sundancespas.ca/hot-tub-dealer-locator/virginia-va/hot-tubs-warrenton/" hreflang="en-ca" />',
        '/hot-tub-dealer-locator/virginia-va/hot-tubs-orange/' => '<link rel="alternate" href="http://www.sundancespas.com/hot-tub-dealer-locator/virginia-va/hot-tubs-orange/" hreflang="en-us" /><link rel="alternate" href="http://www.sundancespas.ca/hot-tub-dealer-locator/virginia-va/hot-tubs-orange/" hreflang="en-ca" />',
        '/hot-tub-dealer-locator/pennsylvania-pa/hot-tubs-lancaster/' => '<link rel="alternate" href="http://www.sundancespas.com/hot-tub-dealer-locator/pennsylvania-pa/hot-tubs-lancaster/" hreflang="en-us" /><link rel="alternate" href="http://www.sundancespas.ca/hot-tub-dealer-locator/pennsylvania-pa/hot-tubs-lancaster/" hreflang="en-ca" />',
        '/hot-tub-dealer-locator/utah-ut/hot-tubs-springville/' => '<link rel="alternate" href="http://www.sundancespas.com/hot-tub-dealer-locator/utah-ut/hot-tubs-springville/" hreflang="en-us" /><link rel="alternate" href="http://www.sundancespas.ca/hot-tub-dealer-locator/utah-ut/hot-tubs-springville/" hreflang="en-ca" />',
        '/hot-tub-dealer-locator/pennsylvania-pa/hot-tubs-wilkes-barre/' => '<link rel="alternate" href="http://www.sundancespas.com/hot-tub-dealer-locator/pennsylvania-pa/hot-tubs-wilkes-barre/" hreflang="en-us" /><link rel="alternate" href="http://www.sundancespas.ca/hot-tub-dealer-locator/pennsylvania-pa/hot-tubs-wilkes-barre/" hreflang="en-ca" />',
        '/hot-tub-dealer-locator/pennsylvania-pa/hot-tubs-dickson-city/' => '<link rel="alternate" href="http://www.sundancespas.com/hot-tub-dealer-locator/pennsylvania-pa/hot-tubs-dickson-city/" hreflang="en-us" /><link rel="alternate" href="http://www.sundancespas.ca/hot-tub-dealer-locator/pennsylvania-pa/hot-tubs-dickson-city/" hreflang="en-ca" />',
        '/hot-tub-dealer-locator/illinois-il/hot-tubs-joliet/' => '<link rel="alternate" href="http://www.sundancespas.com/hot-tub-dealer-locator/illinois-il/hot-tubs-joliet/" hreflang="en-us" /><link rel="alternate" href="http://www.sundancespas.ca/hot-tub-dealer-locator/illinois-il/hot-tubs-joliet/" hreflang="en-ca" />',
        '/hot-tub-dealer-locator/new-jersey-nj/hot-tubs-howell/' => '<link rel="alternate" href="http://www.sundancespas.com/hot-tub-dealer-locator/new-jersey-nj/hot-tubs-howell/" hreflang="en-us" /><link rel="alternate" href="http://www.sundancespas.ca/hot-tub-dealer-locator/new-jersey-nj/hot-tubs-howell/" hreflang="en-ca" />',
        '/hot-tub-dealer-locator/utah-ut/hot-tubs-ogden/' => '<link rel="alternate" href="http://www.sundancespas.com/hot-tub-dealer-locator/utah-ut/hot-tubs-ogden/" hreflang="en-us" /><link rel="alternate" href="http://www.sundancespas.ca/hot-tub-dealer-locator/utah-ut/hot-tubs-ogden/" hreflang="en-ca" />',
        '/hot-tub-dealer-locator/south-carolina-sc/hot-tubs-sumter/' => '<link rel="alternate" href="http://www.sundancespas.com/hot-tub-dealer-locator/south-carolina-sc/hot-tubs-sumter/" hreflang="en-us" /><link rel="alternate" href="http://www.sundancespas.ca/hot-tub-dealer-locator/south-carolina-sc/hot-tubs-sumter/" hreflang="en-ca" />',
        '/hot-tub-dealer-locator/oklahoma-ok/hot-tubs-elk-city/' => '<link rel="alternate" href="http://www.sundancespas.com/hot-tub-dealer-locator/oklahoma-ok/hot-tubs-elk-city/" hreflang="en-us" /><link rel="alternate" href="http://www.sundancespas.ca/hot-tub-dealer-locator/oklahoma-ok/hot-tubs-elk-city/" hreflang="en-ca" />',
        '/hot-tub-dealer-locator/illinois-il/hot-tubs-terre-haute/' => '<link rel="alternate" href="http://www.sundancespas.com/hot-tub-dealer-locator/illinois-il/hot-tubs-terre-haute/" hreflang="en-us" /><link rel="alternate" href="http://www.sundancespas.ca/hot-tub-dealer-locator/illinois-il/hot-tubs-terre-haute/" hreflang="en-ca" />',
        '/hot-tub-dealer-locator/nebraska-ne/hot-tubs-omaha/' => '<link rel="alternate" href="http://www.sundancespas.com/hot-tub-dealer-locator/nebraska-ne/hot-tubs-omaha/" hreflang="en-us" /><link rel="alternate" href="http://www.sundancespas.ca/hot-tub-dealer-locator/nebraska-ne/hot-tubs-omaha/" hreflang="en-ca" />',
        '/hot-tub-dealer-locator/new-mexico-nm/hot-tubs-hobbs/' => '<link rel="alternate" href="http://www.sundancespas.com/hot-tub-dealer-locator/new-mexico-nm/hot-tubs-hobbs/" hreflang="en-us" /><link rel="alternate" href="http://www.sundancespas.ca/hot-tub-dealer-locator/new-mexico-nm/hot-tubs-hobbs/" hreflang="en-ca" />',
        '/hot-tub-dealer-locator/iowa-ia/hot-tubs-davenport/' => '<link rel="alternate" href="http://www.sundancespas.com/hot-tub-dealer-locator/iowa-ia/hot-tubs-davenport/" hreflang="en-us" /><link rel="alternate" href="http://www.sundancespas.ca/hot-tub-dealer-locator/iowa-ia/hot-tubs-davenport/" hreflang="en-ca" />',
        '/hot-tub-dealer-locator/illinois-il/hot-tubs-algonquin/' => '<link rel="alternate" href="http://www.sundancespas.com/hot-tub-dealer-locator/illinois-il/hot-tubs-algonquin/" hreflang="en-us" /><link rel="alternate" href="http://www.sundancespas.ca/hot-tub-dealer-locator/illinois-il/hot-tubs-algonquin/" hreflang="en-ca" />',
        '/hot-tub-dealer-locator/vermont-vt/hot-tubs-rutland/' => '<link rel="alternate" href="http://www.sundancespas.com/hot-tub-dealer-locator/vermont-vt/hot-tubs-rutland/" hreflang="en-us" /><link rel="alternate" href="http://www.sundancespas.ca/hot-tub-dealer-locator/vermont-vt/hot-tubs-rutland/" hreflang="en-ca" />',
        '/hot-tub-dealer-locator/indiana-in/hot-tubs-lafayette/' => '<link rel="alternate" href="http://www.sundancespas.com/hot-tub-dealer-locator/indiana-in/hot-tubs-lafayette/" hreflang="en-us" /><link rel="alternate" href="http://www.sundancespas.ca/hot-tub-dealer-locator/indiana-in/hot-tubs-lafayette/" hreflang="en-ca" />',
        '/hot-tub-dealer-locator/north-carolina-nc/hot-tubs-newport/' => '<link rel="alternate" href="http://www.sundancespas.com/hot-tub-dealer-locator/north-carolina-nc/hot-tubs-newport/" hreflang="en-us" /><link rel="alternate" href="http://www.sundancespas.ca/hot-tub-dealer-locator/north-carolina-nc/hot-tubs-newport/" hreflang="en-ca" />',
        '/hot-tub-dealer-locator/montana-mt/hot-tubs-billings/' => '<link rel="alternate" href="http://www.sundancespas.com/hot-tub-dealer-locator/montana-mt/hot-tubs-billings/" hreflang="en-us" /><link rel="alternate" href="http://www.sundancespas.ca/hot-tub-dealer-locator/montana-mt/hot-tubs-billings/" hreflang="en-ca" />',
        '/hot-tub-dealer-locator/michigan-mi/hot-tubs-livonia/' => '<link rel="alternate" href="http://www.sundancespas.com/hot-tub-dealer-locator/michigan-mi/hot-tubs-livonia/" hreflang="en-us" /><link rel="alternate" href="http://www.sundancespas.ca/hot-tub-dealer-locator/michigan-mi/hot-tubs-livonia/" hreflang="en-ca" />',
        '/hot-tub-dealer-locator/michigan-mi/hot-tubs-ann-arbor/' => '<link rel="alternate" href="http://www.sundancespas.com/hot-tub-dealer-locator/michigan-mi/hot-tubs-ann-arbor/" hreflang="en-us" /><link rel="alternate" href="http://www.sundancespas.ca/hot-tub-dealer-locator/michigan-mi/hot-tubs-ann-arbor/" hreflang="en-ca" />',
        '/hot-tub-dealer-locator/michigan-mi/hot-tubs-novi/' => '<link rel="alternate" href="http://www.sundancespas.com/hot-tub-dealer-locator/michigan-mi/hot-tubs-novi/" hreflang="en-us" /><link rel="alternate" href="http://www.sundancespas.ca/hot-tub-dealer-locator/michigan-mi/hot-tubs-novi/" hreflang="en-ca" />',
        '/hot-tub-dealer-locator/arizona-az/hot-tubs-yuma/' => '<link rel="alternate" href="http://www.sundancespas.com/hot-tub-dealer-locator/arizona-az/hot-tubs-yuma/" hreflang="en-us" /><link rel="alternate" href="http://www.sundancespas.ca/hot-tub-dealer-locator/arizona-az/hot-tubs-yuma/" hreflang="en-ca" />',
        '/hot-tub-dealer-locator/illinois-il/hot-tubs-godfrey/' => '<link rel="alternate" href="http://www.sundancespas.com/hot-tub-dealer-locator/illinois-il/hot-tubs-godfrey/" hreflang="en-us" /><link rel="alternate" href="http://www.sundancespas.ca/hot-tub-dealer-locator/illinois-il/hot-tubs-godfrey/" hreflang="en-ca" />',
        '/hot-tub-dealer-locator/california-ca/hot-tubs-willits/' => '<link rel="alternate" href="http://www.sundancespas.com/hot-tub-dealer-locator/california-ca/hot-tubs-willits/" hreflang="en-us" /><link rel="alternate" href="http://www.sundancespas.ca/hot-tub-dealer-locator/california-ca/hot-tubs-willits/" hreflang="en-ca" />',
        '/hot-tub-dealer-locator/california-ca/hot-tubs-grass-valley/' => '<link rel="alternate" href="http://www.sundancespas.com/hot-tub-dealer-locator/california-ca/hot-tubs-grass-valley/" hreflang="en-us" /><link rel="alternate" href="http://www.sundancespas.ca/hot-tub-dealer-locator/california-ca/hot-tubs-grass-valley/" hreflang="en-ca" />',
        '/hot-tub-dealer-locator/florida-fl/hot-tubs-fort-walton-beach/' => '<link rel="alternate" href="http://www.sundancespas.com/hot-tub-dealer-locator/florida-fl/hot-tubs-fort-walton-beach/" hreflang="en-us" /><link rel="alternate" href="http://www.sundancespas.ca/hot-tub-dealer-locator/florida-fl/hot-tubs-fort-walton-beach/" hreflang="en-ca" />',
        '/hot-tub-dealer-locator/indiana-in/hot-tubs-floyds-knob/' => '<link rel="alternate" href="http://www.sundancespas.com/hot-tub-dealer-locator/indiana-in/hot-tubs-floyds-knob/" hreflang="en-us" /><link rel="alternate" href="http://www.sundancespas.ca/hot-tub-dealer-locator/indiana-in/hot-tubs-floyds-knob/" hreflang="en-ca" />',
        '/hot-tub-dealer-locator/iowa-ia/hot-tubs-milford/' => '<link rel="alternate" href="http://www.sundancespas.com/hot-tub-dealer-locator/iowa-ia/hot-tubs-milford/" hreflang="en-us" /><link rel="alternate" href="http://www.sundancespas.ca/hot-tub-dealer-locator/iowa-ia/hot-tubs-milford/" hreflang="en-ca" />',
        '/hot-tub-dealer-locator/texas-tx/hot-tubs-austin/' => '<link rel="alternate" href="http://www.sundancespas.com/hot-tub-dealer-locator/texas-tx/hot-tubs-austin/" hreflang="en-us" /><link rel="alternate" href="http://www.sundancespas.ca/hot-tub-dealer-locator/texas-tx/hot-tubs-austin/" hreflang="en-ca" />',
        '/hot-tub-dealer-locator/florida-fl/hot-tubs-inverness/' => '<link rel="alternate" href="http://www.sundancespas.com/hot-tub-dealer-locator/florida-fl/hot-tubs-inverness/" hreflang="en-us" /><link rel="alternate" href="http://www.sundancespas.ca/hot-tub-dealer-locator/florida-fl/hot-tubs-inverness/" hreflang="en-ca" />',
        '/hot-tub-dealer-locator/texas-tx/hot-tubs-keller/' => '<link rel="alternate" href="http://www.sundancespas.com/hot-tub-dealer-locator/texas-tx/hot-tubs-keller/" hreflang="en-us" /><link rel="alternate" href="http://www.sundancespas.ca/hot-tub-dealer-locator/texas-tx/hot-tubs-keller/" hreflang="en-ca" />',
        '/hot-tub-dealer-locator/virginia-va/hot-tubs-staunton/' => '<link rel="alternate" href="http://www.sundancespas.com/hot-tub-dealer-locator/virginia-va/hot-tubs-staunton/" hreflang="en-us" /><link rel="alternate" href="http://www.sundancespas.ca/hot-tub-dealer-locator/virginia-va/hot-tubs-staunton/" hreflang="en-ca" />',
        '/hot-tub-dealer-locator/illinois-il/hot-tubs-wilmette/' => '<link rel="alternate" href="http://www.sundancespas.com/hot-tub-dealer-locator/illinois-il/hot-tubs-wilmette/" hreflang="en-us" /><link rel="alternate" href="http://www.sundancespas.ca/hot-tub-dealer-locator/illinois-il/hot-tubs-wilmette/" hreflang="en-ca" />',
        '/hot-tub-dealer-locator/california-ca/hot-tubs-roseville/' => '<link rel="alternate" href="http://www.sundancespas.com/hot-tub-dealer-locator/california-ca/hot-tubs-roseville/" hreflang="en-us" /><link rel="alternate" href="http://www.sundancespas.ca/hot-tub-dealer-locator/california-ca/hot-tubs-roseville/" hreflang="en-ca" />',
        '/hot-tub-dealer-locator/california-ca/hot-tubs-indio/' => '<link rel="alternate" href="http://www.sundancespas.com/hot-tub-dealer-locator/california-ca/hot-tubs-indio/" hreflang="en-us" /><link rel="alternate" href="http://www.sundancespas.ca/hot-tub-dealer-locator/california-ca/hot-tubs-indio/" hreflang="en-ca" />',
        '/hot-tub-dealer-locator/north-carolina-nc/hot-tubs-pineville/' => '<link rel="alternate" href="http://www.sundancespas.com/hot-tub-dealer-locator/north-carolina-nc/hot-tubs-pineville/" hreflang="en-us" /><link rel="alternate" href="http://www.sundancespas.ca/hot-tub-dealer-locator/north-carolina-nc/hot-tubs-pineville/" hreflang="en-ca" />',
        '/hot-tub-dealer-locator/ohio-oh/hot-tubs-zenia/' => '<link rel="alternate" href="http://www.sundancespas.com/hot-tub-dealer-locator/ohio-oh/hot-tubs-zenia/" hreflang="en-us" /><link rel="alternate" href="http://www.sundancespas.ca/hot-tub-dealer-locator/ohio-oh/hot-tubs-zenia/" hreflang="en-ca" />',
        '/hot-tub-dealer-locator/ohio-oh/hot-tubs-kettering/' => '<link rel="alternate" href="http://www.sundancespas.com/hot-tub-dealer-locator/ohio-oh/hot-tubs-kettering/" hreflang="en-us" /><link rel="alternate" href="http://www.sundancespas.ca/hot-tub-dealer-locator/ohio-oh/hot-tubs-kettering/" hreflang="en-ca" />',
        '/hot-tub-dealer-locator/ohio-oh/hot-tubs-tipp-city/' => '<link rel="alternate" href="http://www.sundancespas.com/hot-tub-dealer-locator/ohio-oh/hot-tubs-tipp-city/" hreflang="en-us" /><link rel="alternate" href="http://www.sundancespas.ca/hot-tub-dealer-locator/ohio-oh/hot-tubs-tipp-city/" hreflang="en-ca" />',
        '/hot-tub-dealer-locator/ohio-oh/hot-tubs-huber-heights/' => '<link rel="alternate" href="http://www.sundancespas.com/hot-tub-dealer-locator/ohio-oh/hot-tubs-huber-heights/" hreflang="en-us" /><link rel="alternate" href="http://www.sundancespas.ca/hot-tub-dealer-locator/ohio-oh/hot-tubs-huber-heights/" hreflang="en-ca" />',
        '/hot-tub-dealer-locator/ohio-oh/hot-tubs-beavercreek/' => '<link rel="alternate" href="http://www.sundancespas.com/hot-tub-dealer-locator/ohio-oh/hot-tubs-beavercreek/" hreflang="en-us" /><link rel="alternate" href="http://www.sundancespas.ca/hot-tub-dealer-locator/ohio-oh/hot-tubs-beavercreek/" hreflang="en-ca" />',
        '/hot-tub-dealer-locator/massachusetts-ma/hot-tubs-westfield/' => '<link rel="alternate" href="http://www.sundancespas.com/hot-tub-dealer-locator/massachusetts-ma/hot-tubs-westfield/" hreflang="en-us" /><link rel="alternate" href="http://www.sundancespas.ca/hot-tub-dealer-locator/massachusetts-ma/hot-tubs-westfield/" hreflang="en-ca" />',
        '/hot-tub-dealer-locator/tennessee-tn/hot-tubs-bluff-city/' => '<link rel="alternate" href="http://www.sundancespas.com/hot-tub-dealer-locator/tennessee-tn/hot-tubs-bluff-city/" hreflang="en-us" /><link rel="alternate" href="http://www.sundancespas.ca/hot-tub-dealer-locator/tennessee-tn/hot-tubs-bluff-city/" hreflang="en-ca" />',
        '/hot-tub-dealer-locator/new-jersey-nj/hot-tubs-new-jersey/' => '<link rel="alternate" href="http://www.sundancespas.com/hot-tub-dealer-locator/new-jersey-nj/hot-tubs-new-jersey/" hreflang="en-us" /><link rel="alternate" href="http://www.sundancespas.ca/hot-tub-dealer-locator/new-jersey-nj/hot-tubs-new-jersey/" hreflang="en-ca" />',
        '/hot-tub-dealer-locator/new-jersey-nj/hot-tubs-williamstown/' => '<link rel="alternate" href="http://www.sundancespas.com/hot-tub-dealer-locator/new-jersey-nj/hot-tubs-williamstown/" hreflang="en-us" /><link rel="alternate" href="http://www.sundancespas.ca/hot-tub-dealer-locator/new-jersey-nj/hot-tubs-williamstown/" hreflang="en-ca" />',
        '/hot-tub-dealer-locator/missouri-mo/hot-tubs-osage-beach/' => '<link rel="alternate" href="http://www.sundancespas.com/hot-tub-dealer-locator/missouri-mo/hot-tubs-osage-beach/" hreflang="en-us" /><link rel="alternate" href="http://www.sundancespas.ca/hot-tub-dealer-locator/missouri-mo/hot-tubs-osage-beach/" hreflang="en-ca" />',
        '/hot-tub-dealer-locator/colorado-co/hot-tubs-glenwood-springs/' => '<link rel="alternate" href="http://www.sundancespas.com/hot-tub-dealer-locator/colorado-co/hot-tubs-glenwood-springs/" hreflang="en-us" /><link rel="alternate" href="http://www.sundancespas.ca/hot-tub-dealer-locator/colorado-co/hot-tubs-glenwood-springs/" hreflang="en-ca" />',
        '/hot-tub-dealer-locator/pennsylvania-pa/hot-tubs-quakerstown/' => '<link rel="alternate" href="http://www.sundancespas.com/hot-tub-dealer-locator/pennsylvania-pa/hot-tubs-quakerstown/" hreflang="en-us" /><link rel="alternate" href="http://www.sundancespas.ca/hot-tub-dealer-locator/pennsylvania-pa/hot-tubs-quakerstown/" hreflang="en-ca" />',
        '/hot-tub-dealer-locator/georgia-ga/hot-tubs-athens/' => '<link rel="alternate" href="http://www.sundancespas.com/hot-tub-dealer-locator/georgia-ga/hot-tubs-athens/" hreflang="en-us" /><link rel="alternate" href="http://www.sundancespas.ca/hot-tub-dealer-locator/georgia-ga/hot-tubs-athens/" hreflang="en-ca" />',
        '/hot-tub-dealer-locator/illinois-il/hot-tubs-bloomington/' => '<link rel="alternate" href="http://www.sundancespas.com/hot-tub-dealer-locator/illinois-il/hot-tubs-bloomington/" hreflang="en-us" /><link rel="alternate" href="http://www.sundancespas.ca/hot-tub-dealer-locator/illinois-il/hot-tubs-bloomington/" hreflang="en-ca" />',
        '/hot-tub-dealer-locator/ohio-oh/hot-tubs-boardman/' => '<link rel="alternate" href="http://www.sundancespas.com/hot-tub-dealer-locator/ohio-oh/hot-tubs-boardman/" hreflang="en-us" /><link rel="alternate" href="http://www.sundancespas.ca/hot-tub-dealer-locator/ohio-oh/hot-tubs-boardman/" hreflang="en-ca" />',
        '/hot-tub-dealer-locator/indiana-in/hot-tubs-fort-wayne/' => '<link rel="alternate" href="http://www.sundancespas.com/hot-tub-dealer-locator/indiana-in/hot-tubs-fort-wayne/" hreflang="en-us" /><link rel="alternate" href="http://www.sundancespas.ca/hot-tub-dealer-locator/indiana-in/hot-tubs-fort-wayne/" hreflang="en-ca" />',
        '/hot-tub-dealer-locator/kansas-ks/hot-tubs-hays/' => '<link rel="alternate" href="http://www.sundancespas.com/hot-tub-dealer-locator/kansas-ks/hot-tubs-hays/" hreflang="en-us" /><link rel="alternate" href="http://www.sundancespas.ca/hot-tub-dealer-locator/kansas-ks/hot-tubs-hays/" hreflang="en-ca" />',
        '/hot-tub-dealer-locator/virginia-va/hot-tubs-mclean/' => '<link rel="alternate" href="http://www.sundancespas.com/hot-tub-dealer-locator/virginia-va/hot-tubs-mclean/" hreflang="en-us" /><link rel="alternate" href="http://www.sundancespas.ca/hot-tub-dealer-locator/virginia-va/hot-tubs-mclean/" hreflang="en-ca" />',
        '/hot-tub-dealer-locator/illinois-il/hot-tubs-sterling/' => '<link rel="alternate" href="http://www.sundancespas.com/hot-tub-dealer-locator/illinois-il/hot-tubs-sterling/" hreflang="en-us" /><link rel="alternate" href="http://www.sundancespas.ca/hot-tub-dealer-locator/illinois-il/hot-tubs-sterling/" hreflang="en-ca" />',
        '/hot-tub-dealer-locator/california-ca/hot-tubs-salinas/' => '<link rel="alternate" href="http://www.sundancespas.com/hot-tub-dealer-locator/california-ca/hot-tubs-salinas/" hreflang="en-us" /><link rel="alternate" href="http://www.sundancespas.ca/hot-tub-dealer-locator/california-ca/hot-tubs-salinas/" hreflang="en-ca" />',
        '/hot-tub-dealer-locator/oklahoma-ok/hot-tubs-ardmore/' => '<link rel="alternate" href="http://www.sundancespas.com/hot-tub-dealer-locator/oklahoma-ok/hot-tubs-ardmore/" hreflang="en-us" /><link rel="alternate" href="http://www.sundancespas.ca/hot-tub-dealer-locator/oklahoma-ok/hot-tubs-ardmore/" hreflang="en-ca" />',
        '/hot-tub-dealer-locator/tennessee-tn/hot-tubs-murfreesboro/' => '<link rel="alternate" href="http://www.sundancespas.com/hot-tub-dealer-locator/tennessee-tn/hot-tubs-murfreesboro/" hreflang="en-us" /><link rel="alternate" href="http://www.sundancespas.ca/hot-tub-dealer-locator/tennessee-tn/hot-tubs-murfreesboro/" hreflang="en-ca" />',
        '/hot-tub-dealer-locator/washington-wa/hot-tubs-olympia/' => '<link rel="alternate" href="http://www.sundancespas.com/hot-tub-dealer-locator/washington-wa/hot-tubs-olympia/" hreflang="en-us" /><link rel="alternate" href="http://www.sundancespas.ca/hot-tub-dealer-locator/washington-wa/hot-tubs-olympia/" hreflang="en-ca" />',
        '/hot-tub-dealer-locator/washington-wa/hot-tubs-chehalis/' => '<link rel="alternate" href="http://www.sundancespas.com/hot-tub-dealer-locator/washington-wa/hot-tubs-chehalis/" hreflang="en-us" /><link rel="alternate" href="http://www.sundancespas.ca/hot-tub-dealer-locator/washington-wa/hot-tubs-chehalis/" hreflang="en-ca" />',
        '/hot-tub-dealer-locator/indiana-in/hot-tubs-sellersburg/' => '<link rel="alternate" href="http://www.sundancespas.com/hot-tub-dealer-locator/indiana-in/hot-tubs-sellersburg/" hreflang="en-us" /><link rel="alternate" href="http://www.sundancespas.ca/hot-tub-dealer-locator/indiana-in/hot-tubs-sellersburg/" hreflang="en-ca" />',
        '/hot-tub-dealer-locator/south-carolina-sc/hot-tubs-aiken/' => '<link rel="alternate" href="http://www.sundancespas.com/hot-tub-dealer-locator/south-carolina-sc/hot-tubs-aiken/" hreflang="en-us" /><link rel="alternate" href="http://www.sundancespas.ca/hot-tub-dealer-locator/south-carolina-sc/hot-tubs-aiken/" hreflang="en-ca" />',
        '/hot-tub-dealer-locator/north-carolina-nc/hot-tubs-arden/' => '<link rel="alternate" href="http://www.sundancespas.com/hot-tub-dealer-locator/north-carolina-nc/hot-tubs-arden/" hreflang="en-us" /><link rel="alternate" href="http://www.sundancespas.ca/hot-tub-dealer-locator/north-carolina-nc/hot-tubs-arden/" hreflang="en-ca" />',
        '/hot-tub-dealer-locator/maryland-md/hot-tubs-owings/' => '<link rel="alternate" href="http://www.sundancespas.com/hot-tub-dealer-locator/maryland-md/hot-tubs-owings/" hreflang="en-us" /><link rel="alternate" href="http://www.sundancespas.ca/hot-tub-dealer-locator/maryland-md/hot-tubs-owings/" hreflang="en-ca" />',
        '/hot-tub-dealer-locator/iowa-ia/hot-tubs-fort-dodge/' => '<link rel="alternate" href="http://www.sundancespas.com/hot-tub-dealer-locator/iowa-ia/hot-tubs-fort-dodge/" hreflang="en-us" /><link rel="alternate" href="http://www.sundancespas.ca/hot-tub-dealer-locator/iowa-ia/hot-tubs-fort-dodge/" hreflang="en-ca" />',
        '/hot-tub-dealer-locator/tennessee-tn/hot-tubs-sevierville/' => '<link rel="alternate" href="http://www.sundancespas.com/hot-tub-dealer-locator/tennessee-tn/hot-tubs-sevierville/" hreflang="en-us" /><link rel="alternate" href="http://www.sundancespas.ca/hot-tub-dealer-locator/tennessee-tn/hot-tubs-sevierville/" hreflang="en-ca" />',
        '/hot-tub-dealer-locator/tennessee-tn/hot-tubs-morristown/' => '<link rel="alternate" href="http://www.sundancespas.com/hot-tub-dealer-locator/tennessee-tn/hot-tubs-morristown/" hreflang="en-us" /><link rel="alternate" href="http://www.sundancespas.ca/hot-tub-dealer-locator/tennessee-tn/hot-tubs-morristown/" hreflang="en-ca" />',
        '/hot-tub-dealer-locator/tennessee-tn/hot-tubs-knoxville/' => '<link rel="alternate" href="http://www.sundancespas.com/hot-tub-dealer-locator/tennessee-tn/hot-tubs-knoxville/" hreflang="en-us" /><link rel="alternate" href="http://www.sundancespas.ca/hot-tub-dealer-locator/tennessee-tn/hot-tubs-knoxville/" hreflang="en-ca" />',
        '/hot-tub-dealer-locator/california-ca/hot-tubs-big-bear-lake/' => '<link rel="alternate" href="http://www.sundancespas.com/hot-tub-dealer-locator/california-ca/hot-tubs-big-bear-lake/" hreflang="en-us" /><link rel="alternate" href="http://www.sundancespas.ca/hot-tub-dealer-locator/california-ca/hot-tubs-big-bear-lake/" hreflang="en-ca" />',
        '/hot-tub-dealer-locator/new-jersey-nj/hot-tubs-surf-city/' => '<link rel="alternate" href="http://www.sundancespas.com/hot-tub-dealer-locator/new-jersey-nj/hot-tubs-surf-city/" hreflang="en-us" /><link rel="alternate" href="http://www.sundancespas.ca/hot-tub-dealer-locator/new-jersey-nj/hot-tubs-surf-city/" hreflang="en-ca" />',
        '/hot-tub-dealer-locator/wisconsin-wi/hot-tubs-sun-prairie/' => '<link rel="alternate" href="http://www.sundancespas.com/hot-tub-dealer-locator/wisconsin-wi/hot-tubs-sun-prairie/" hreflang="en-us" /><link rel="alternate" href="http://www.sundancespas.ca/hot-tub-dealer-locator/wisconsin-wi/hot-tubs-sun-prairie/" hreflang="en-ca" />',
        '/hot-tub-dealer-locator/wisconsin-wi/hot-tubs-madison/' => '<link rel="alternate" href="http://www.sundancespas.com/hot-tub-dealer-locator/wisconsin-wi/hot-tubs-madison/" hreflang="en-us" /><link rel="alternate" href="http://www.sundancespas.ca/hot-tub-dealer-locator/wisconsin-wi/hot-tubs-madison/" hreflang="en-ca" />',
        '/hot-tub-dealer-locator/montana-mt/hot-tubs-missoula/' => '<link rel="alternate" href="http://www.sundancespas.com/hot-tub-dealer-locator/montana-mt/hot-tubs-missoula/" hreflang="en-us" /><link rel="alternate" href="http://www.sundancespas.ca/hot-tub-dealer-locator/montana-mt/hot-tubs-missoula/" hreflang="en-ca" />',
        '/hot-tub-dealer-locator/oregon-or/hot-tubs-pendleton/' => '<link rel="alternate" href="http://www.sundancespas.com/hot-tub-dealer-locator/oregon-or/hot-tubs-pendleton/" hreflang="en-us" /><link rel="alternate" href="http://www.sundancespas.ca/hot-tub-dealer-locator/oregon-or/hot-tubs-pendleton/" hreflang="en-ca" />',
        '/hot-tub-dealer-locator/texas-tx/hot-tubs-mission/' => '<link rel="alternate" href="http://www.sundancespas.com/hot-tub-dealer-locator/texas-tx/hot-tubs-mission/" hreflang="en-us" /><link rel="alternate" href="http://www.sundancespas.ca/hot-tub-dealer-locator/texas-tx/hot-tubs-mission/" hreflang="en-ca" />',
        '/hot-tub-dealer-locator/wyoming-wy/hot-tubs-cody/' => '<link rel="alternate" href="http://www.sundancespas.com/hot-tub-dealer-locator/wyoming-wy/hot-tubs-cody/" hreflang="en-us" /><link rel="alternate" href="http://www.sundancespas.ca/hot-tub-dealer-locator/wyoming-wy/hot-tubs-cody/" hreflang="en-ca" />',
        '/hot-tub-dealer-locator/california-ca/hot-tubs-redding/' => '<link rel="alternate" href="http://www.sundancespas.com/hot-tub-dealer-locator/california-ca/hot-tubs-redding/" hreflang="en-us" /><link rel="alternate" href="http://www.sundancespas.ca/hot-tub-dealer-locator/california-ca/hot-tubs-redding/" hreflang="en-ca" />',
        '/hot-tub-dealer-locator/california-ca/hot-tubs-auburn/' => '<link rel="alternate" href="http://www.sundancespas.com/hot-tub-dealer-locator/california-ca/hot-tubs-auburn/" hreflang="en-us" /><link rel="alternate" href="http://www.sundancespas.ca/hot-tub-dealer-locator/california-ca/hot-tubs-auburn/" hreflang="en-ca" />',
        '/hot-tub-dealer-locator/alaska-ak/hot-tubs-wasilla/' => '<link rel="alternate" href="http://www.sundancespas.com/hot-tub-dealer-locator/alaska-ak/hot-tubs-wasilla/" hreflang="en-us" /><link rel="alternate" href="http://www.sundancespas.ca/hot-tub-dealer-locator/alaska-ak/hot-tubs-wasilla/" hreflang="en-ca" />',
        '/hot-tub-dealer-locator/alaska-ak/hot-tubs-fairbanks/' => '<link rel="alternate" href="http://www.sundancespas.com/hot-tub-dealer-locator/alaska-ak/hot-tubs-fairbanks/" hreflang="en-us" /><link rel="alternate" href="http://www.sundancespas.ca/hot-tub-dealer-locator/alaska-ak/hot-tubs-fairbanks/" hreflang="en-ca" />',
        '/hot-tub-dealer-locator/kentucky-ky/hot-tubs-louisville/' => '<link rel="alternate" href="http://www.sundancespas.com/hot-tub-dealer-locator/kentucky-ky/hot-tubs-louisville/" hreflang="en-us" /><link rel="alternate" href="http://www.sundancespas.ca/hot-tub-dealer-locator/kentucky-ky/hot-tubs-louisville/" hreflang="en-ca" />',
        '/hot-tub-dealer-locator/kentucky-ky/hot-tubs-elizabethtown/' => '<link rel="alternate" href="http://www.sundancespas.com/hot-tub-dealer-locator/kentucky-ky/hot-tubs-elizabethtown/" hreflang="en-us" /><link rel="alternate" href="http://www.sundancespas.ca/hot-tub-dealer-locator/kentucky-ky/hot-tubs-elizabethtown/" hreflang="en-ca" />',
        '/hot-tub-dealer-locator/idaho-id/hot-tubs-idaho-falls/' => '<link rel="alternate" href="http://www.sundancespas.com/hot-tub-dealer-locator/idaho-id/hot-tubs-idaho-falls/" hreflang="en-us" /><link rel="alternate" href="http://www.sundancespas.ca/hot-tub-dealer-locator/idaho-id/hot-tubs-idaho-falls/" hreflang="en-ca" />',
        '/hot-tub-dealer-locator/idaho-id/hot-tubs-twin-falls/' => '<link rel="alternate" href="http://www.sundancespas.com/hot-tub-dealer-locator/idaho-id/hot-tubs-twin-falls/" hreflang="en-us" /><link rel="alternate" href="http://www.sundancespas.ca/hot-tub-dealer-locator/idaho-id/hot-tubs-twin-falls/" hreflang="en-ca" />',
        '/hot-tub-dealer-locator/idaho-id/hot-tubs-pocatello/' => '<link rel="alternate" href="http://www.sundancespas.com/hot-tub-dealer-locator/idaho-id/hot-tubs-pocatello/" hreflang="en-us" /><link rel="alternate" href="http://www.sundancespas.ca/hot-tub-dealer-locator/idaho-id/hot-tubs-pocatello/" hreflang="en-ca" />',
        '/hot-tub-dealer-locator/idaho-id/hot-tubs-boise/' => '<link rel="alternate" href="http://www.sundancespas.com/hot-tub-dealer-locator/idaho-id/hot-tubs-boise/" hreflang="en-us" /><link rel="alternate" href="http://www.sundancespas.ca/hot-tub-dealer-locator/idaho-id/hot-tubs-boise/" hreflang="en-ca" />',
        '/hot-tub-dealer-locator/colorado-co/hot-tubs-colorado-springs/' => '<link rel="alternate" href="http://www.sundancespas.com/hot-tub-dealer-locator/colorado-co/hot-tubs-colorado-springs/" hreflang="en-us" /><link rel="alternate" href="http://www.sundancespas.ca/hot-tub-dealer-locator/colorado-co/hot-tubs-colorado-springs/" hreflang="en-ca" />',
        '/hot-tub-dealer-locator/colorado-co/hot-tubs-fort-collins/' => '<link rel="alternate" href="http://www.sundancespas.com/hot-tub-dealer-locator/colorado-co/hot-tubs-fort-collins/" hreflang="en-us" /><link rel="alternate" href="http://www.sundancespas.ca/hot-tub-dealer-locator/colorado-co/hot-tubs-fort-collins/" hreflang="en-ca" />',
        '/hot-tub-dealer-locator/colorado-co/hot-tubs-pueblo/' => '<link rel="alternate" href="http://www.sundancespas.com/hot-tub-dealer-locator/colorado-co/hot-tubs-pueblo/" hreflang="en-us" /><link rel="alternate" href="http://www.sundancespas.ca/hot-tub-dealer-locator/colorado-co/hot-tubs-pueblo/" hreflang="en-ca" />',
        '/hot-tub-dealer-locator/colorado-co/hot-tubs-westminster/' => '<link rel="alternate" href="http://www.sundancespas.com/hot-tub-dealer-locator/colorado-co/hot-tubs-westminster/" hreflang="en-us" /><link rel="alternate" href="http://www.sundancespas.ca/hot-tub-dealer-locator/colorado-co/hot-tubs-westminster/" hreflang="en-ca" />',
        '/hot-tub-dealer-locator/colorado-co/hot-tubs-highlands-ranch/' => '<link rel="alternate" href="http://www.sundancespas.com/hot-tub-dealer-locator/colorado-co/hot-tubs-highlands-ranch/" hreflang="en-us" /><link rel="alternate" href="http://www.sundancespas.ca/hot-tub-dealer-locator/colorado-co/hot-tubs-highlands-ranch/" hreflang="en-ca" />',
        '/hot-tub-dealer-locator/illinois-il/hot-tubs-orland-park/' => '<link rel="alternate" href="http://www.sundancespas.com/hot-tub-dealer-locator/illinois-il/hot-tubs-orland-park/" hreflang="en-us" /><link rel="alternate" href="http://www.sundancespas.ca/hot-tub-dealer-locator/illinois-il/hot-tubs-orland-park/" hreflang="en-ca" />',
        '/hot-tub-dealer-locator/north-carolina-nc/hot-tubs-marioin/' => '<link rel="alternate" href="http://www.sundancespas.com/hot-tub-dealer-locator/north-carolina-nc/hot-tubs-marioin/" hreflang="en-us" /><link rel="alternate" href="http://www.sundancespas.ca/hot-tub-dealer-locator/north-carolina-nc/hot-tubs-marioin/" hreflang="en-ca" />',
        '/hot-tub-dealer-locator/texas-tx/hot-tubs-dallas/' => '<link rel="alternate" href="http://www.sundancespas.com/hot-tub-dealer-locator/texas-tx/hot-tubs-dallas/" hreflang="en-us" /><link rel="alternate" href="http://www.sundancespas.ca/hot-tub-dealer-locator/texas-tx/hot-tubs-dallas/" hreflang="en-ca" />',
        '/hot-tub-dealer-locator/wisconsin-wi/hot-tubs-chippewa-falls/' => '<link rel="alternate" href="http://www.sundancespas.com/hot-tub-dealer-locator/wisconsin-wi/hot-tubs-chippewa-falls/" hreflang="en-us" /><link rel="alternate" href="http://www.sundancespas.ca/hot-tub-dealer-locator/wisconsin-wi/hot-tubs-chippewa-falls/" hreflang="en-ca" />',
        '/hot-tub-dealer-locator/connecticut-ct/hot-tubs-marlborough/' => '<link rel="alternate" href="http://www.sundancespas.com/hot-tub-dealer-locator/connecticut-ct/hot-tubs-marlborough/" hreflang="en-us" /><link rel="alternate" href="http://www.sundancespas.ca/hot-tub-dealer-locator/connecticut-ct/hot-tubs-marlborough/" hreflang="en-ca" />',
        '/hot-tub-dealer-locator/wisconsin-wi/hot-tubs-brookfield/' => '<link rel="alternate" href="http://www.sundancespas.com/hot-tub-dealer-locator/wisconsin-wi/hot-tubs-brookfield/" hreflang="en-us" /><link rel="alternate" href="http://www.sundancespas.ca/hot-tub-dealer-locator/wisconsin-wi/hot-tubs-brookfield/" hreflang="en-ca" />',
        '/hot-tub-dealer-locator/north-carolina-nc/hot-tubs-greensboro/' => '<link rel="alternate" href="http://www.sundancespas.com/hot-tub-dealer-locator/north-carolina-nc/hot-tubs-greensboro/" hreflang="en-us" /><link rel="alternate" href="http://www.sundancespas.ca/hot-tub-dealer-locator/north-carolina-nc/hot-tubs-greensboro/" hreflang="en-ca" />',
        '/hot-tub-dealer-locator/ohio-oh/hot-tubs-north-olmsted/' => '<link rel="alternate" href="http://www.sundancespas.com/hot-tub-dealer-locator/ohio-oh/hot-tubs-north-olmsted/" hreflang="en-us" /><link rel="alternate" href="http://www.sundancespas.ca/hot-tub-dealer-locator/ohio-oh/hot-tubs-north-olmsted/" hreflang="en-ca" />',
        '/hot-tub-dealer-locator/ohio-oh/hot-tubs-canton/' => '<link rel="alternate" href="http://www.sundancespas.com/hot-tub-dealer-locator/ohio-oh/hot-tubs-canton/" hreflang="en-us" /><link rel="alternate" href="http://www.sundancespas.ca/hot-tub-dealer-locator/ohio-oh/hot-tubs-canton/" hreflang="en-ca" />',
        '/hot-tub-dealer-locator/ohio-oh/hot-tubs-avon/' => '<link rel="alternate" href="http://www.sundancespas.com/hot-tub-dealer-locator/ohio-oh/hot-tubs-avon/" hreflang="en-us" /><link rel="alternate" href="http://www.sundancespas.ca/hot-tub-dealer-locator/ohio-oh/hot-tubs-avon/" hreflang="en-ca" />',
        '/hot-tub-dealer-locator/ohio-oh/hot-tubs-macedonia/' => '<link rel="alternate" href="http://www.sundancespas.com/hot-tub-dealer-locator/ohio-oh/hot-tubs-macedonia/" hreflang="en-us" /><link rel="alternate" href="http://www.sundancespas.ca/hot-tub-dealer-locator/ohio-oh/hot-tubs-macedonia/" hreflang="en-ca" />',
        '/hot-tub-dealer-locator/california-ca/hot-tubs-santa-barbara/' => '<link rel="alternate" href="http://www.sundancespas.com/hot-tub-dealer-locator/california-ca/hot-tubs-santa-barbara/" hreflang="en-us" /><link rel="alternate" href="http://www.sundancespas.ca/hot-tub-dealer-locator/california-ca/hot-tubs-santa-barbara/" hreflang="en-ca" />',
        '/hot-tub-dealer-locator/west-virginia-wv/hot-tubs-martinsburg/' => '<link rel="alternate" href="http://www.sundancespas.com/hot-tub-dealer-locator/west-virginia-wv/hot-tubs-martinsburg/" hreflang="en-us" /><link rel="alternate" href="http://www.sundancespas.ca/hot-tub-dealer-locator/west-virginia-wv/hot-tubs-martinsburg/" hreflang="en-ca" />',
        '/hot-tub-dealer-locator/texas-tx/hot-tubs-san-antonio/' => '<link rel="alternate" href="http://www.sundancespas.com/hot-tub-dealer-locator/texas-tx/hot-tubs-san-antonio/" hreflang="en-us" /><link rel="alternate" href="http://www.sundancespas.ca/hot-tub-dealer-locator/texas-tx/hot-tubs-san-antonio/" hreflang="en-ca" />',
        '/hot-tub-dealer-locator/california-ca/hot-tubs-granite-bay/' => '<link rel="alternate" href="http://www.sundancespas.com/hot-tub-dealer-locator/california-ca/hot-tubs-granite-bay/" hreflang="en-us" /><link rel="alternate" href="http://www.sundancespas.ca/hot-tub-dealer-locator/california-ca/hot-tubs-granite-bay/" hreflang="en-ca" />',
        '/hot-tub-dealer-locator/nevada-nv/hot-tubs-reno/' => '<link rel="alternate" href="http://www.sundancespas.com/hot-tub-dealer-locator/nevada-nv/hot-tubs-reno/" hreflang="en-us" /><link rel="alternate" href="http://www.sundancespas.ca/hot-tub-dealer-locator/nevada-nv/hot-tubs-reno/" hreflang="en-ca" />',
        '/hot-tub-dealer-locator/minnesota-mn/hot-tubs-plymouth/' => '<link rel="alternate" href="http://www.sundancespas.com/hot-tub-dealer-locator/minnesota-mn/hot-tubs-plymouth/" hreflang="en-us" /><link rel="alternate" href="http://www.sundancespas.ca/hot-tub-dealer-locator/minnesota-mn/hot-tubs-plymouth/" hreflang="en-ca" />',
        '/hot-tub-dealer-locator/minnesota-mn/hot-tubs-woodbury/' => '<link rel="alternate" href="http://www.sundancespas.com/hot-tub-dealer-locator/minnesota-mn/hot-tubs-woodbury/" hreflang="en-us" /><link rel="alternate" href="http://www.sundancespas.ca/hot-tub-dealer-locator/minnesota-mn/hot-tubs-woodbury/" hreflang="en-ca" />',
        '/hot-tub-dealer-locator/minnesota-mn/hot-tubs-burnsville/' => '<link rel="alternate" href="http://www.sundancespas.com/hot-tub-dealer-locator/minnesota-mn/hot-tubs-burnsville/" hreflang="en-us" /><link rel="alternate" href="http://www.sundancespas.ca/hot-tub-dealer-locator/minnesota-mn/hot-tubs-burnsville/" hreflang="en-ca" />',
        '/hot-tub-dealer-locator/north-carolina-nc/hot-tubs-raleigh/' => '<link rel="alternate" href="http://www.sundancespas.com/hot-tub-dealer-locator/north-carolina-nc/hot-tubs-raleigh/" hreflang="en-us" /><link rel="alternate" href="http://www.sundancespas.ca/hot-tub-dealer-locator/north-carolina-nc/hot-tubs-raleigh/" hreflang="en-ca" />',
        '/hot-tub-dealer-locator/virginia-va/hot-tubs-virginia-beach/' => '<link rel="alternate" href="http://www.sundancespas.com/hot-tub-dealer-locator/virginia-va/hot-tubs-virginia-beach/" hreflang="en-us" /><link rel="alternate" href="http://www.sundancespas.ca/hot-tub-dealer-locator/virginia-va/hot-tubs-virginia-beach/" hreflang="en-ca" />',
        '/hot-tub-dealer-locator/virginia-va/hot-tubs-yorktown/' => '<link rel="alternate" href="http://www.sundancespas.com/hot-tub-dealer-locator/virginia-va/hot-tubs-yorktown/" hreflang="en-us" /><link rel="alternate" href="http://www.sundancespas.ca/hot-tub-dealer-locator/virginia-va/hot-tubs-yorktown/" hreflang="en-ca" />',
        '/hot-tub-dealer-locator/north-carolina-nc/hot-tubs-kill-devil-hills/' => '<link rel="alternate" href="http://www.sundancespas.com/hot-tub-dealer-locator/north-carolina-nc/hot-tubs-kill-devil-hills/" hreflang="en-us" /><link rel="alternate" href="http://www.sundancespas.ca/hot-tub-dealer-locator/north-carolina-nc/hot-tubs-kill-devil-hills/" hreflang="en-ca" />',
        '/hot-tub-dealer-locator/north-carolina-nc/hot-tubs-garner/' => '<link rel="alternate" href="http://www.sundancespas.com/hot-tub-dealer-locator/north-carolina-nc/hot-tubs-garner/" hreflang="en-us" /><link rel="alternate" href="http://www.sundancespas.ca/hot-tub-dealer-locator/north-carolina-nc/hot-tubs-garner/" hreflang="en-ca" />',
        '/hot-tub-dealer-locator/north-carolina-nc/hot-tubs-north-raleigh/' => '<link rel="alternate" href="http://www.sundancespas.com/hot-tub-dealer-locator/north-carolina-nc/hot-tubs-north-raleigh/" hreflang="en-us" /><link rel="alternate" href="http://www.sundancespas.ca/hot-tub-dealer-locator/north-carolina-nc/hot-tubs-north-raleigh/" hreflang="en-ca" />',
        '/hot-tub-dealer-locator/arizona-az/hot-tubs-scottsdale/' => '<link rel="alternate" href="http://www.sundancespas.com/hot-tub-dealer-locator/arizona-az/hot-tubs-scottsdale/" hreflang="en-us" /><link rel="alternate" href="http://www.sundancespas.ca/hot-tub-dealer-locator/arizona-az/hot-tubs-scottsdale/" hreflang="en-ca" />',
        '/hot-tub-dealer-locator/minnesota-mn/hot-tubs-waite-park/' => '<link rel="alternate" href="http://www.sundancespas.com/hot-tub-dealer-locator/minnesota-mn/hot-tubs-waite-park/" hreflang="en-us" /><link rel="alternate" href="http://www.sundancespas.ca/hot-tub-dealer-locator/minnesota-mn/hot-tubs-waite-park/" hreflang="en-ca" />',
        '/hot-tub-dealer-locator/south-carolina-sc/hot-tubs-charleston/' => '<link rel="alternate" href="http://www.sundancespas.com/hot-tub-dealer-locator/south-carolina-sc/hot-tubs-charleston/" hreflang="en-us" /><link rel="alternate" href="http://www.sundancespas.ca/hot-tub-dealer-locator/south-carolina-sc/hot-tubs-charleston/" hreflang="en-ca" />',
        '/hot-tub-dealer-locator/pennsylvania-pa/hot-tubs-carlisle/' => '<link rel="alternate" href="http://www.sundancespas.com/hot-tub-dealer-locator/pennsylvania-pa/hot-tubs-carlisle/" hreflang="en-us" /><link rel="alternate" href="http://www.sundancespas.ca/hot-tub-dealer-locator/pennsylvania-pa/hot-tubs-carlisle/" hreflang="en-ca" />',
        '/hot-tub-dealer-locator/arkansas-ar/hot-tubs-jonesboro/' => '<link rel="alternate" href="http://www.sundancespas.com/hot-tub-dealer-locator/arkansas-ar/hot-tubs-jonesboro/" hreflang="en-us" /><link rel="alternate" href="http://www.sundancespas.ca/hot-tub-dealer-locator/arkansas-ar/hot-tubs-jonesboro/" hreflang="en-ca" />',
        '/hot-tub-dealer-locator/nevada-nv/hot-tubs-las-vegas/' => '<link rel="alternate" href="http://www.sundancespas.com/hot-tub-dealer-locator/nevada-nv/hot-tubs-las-vegas/" hreflang="en-us" /><link rel="alternate" href="http://www.sundancespas.ca/hot-tub-dealer-locator/nevada-nv/hot-tubs-las-vegas/" hreflang="en-ca" />',
        '/hot-tub-dealer-locator/illinois-il/hot-tubs-oak-brook/' => '<link rel="alternate" href="http://www.sundancespas.com/hot-tub-dealer-locator/illinois-il/hot-tubs-oak-brook/" hreflang="en-us" /><link rel="alternate" href="http://www.sundancespas.ca/hot-tub-dealer-locator/illinois-il/hot-tubs-oak-brook/" hreflang="en-ca" />',
        '/hot-tub-dealer-locator/oregon-or/hot-tubs-bend/' => '<link rel="alternate" href="http://www.sundancespas.com/hot-tub-dealer-locator/oregon-or/hot-tubs-bend/" hreflang="en-us" /><link rel="alternate" href="http://www.sundancespas.ca/hot-tub-dealer-locator/oregon-or/hot-tubs-bend/" hreflang="en-ca" />',
        '/hot-tub-dealer-locator/georgia-ga/hot-tubs-blue-ridge/' => '<link rel="alternate" href="http://www.sundancespas.com/hot-tub-dealer-locator/georgia-ga/hot-tubs-blue-ridge/" hreflang="en-us" /><link rel="alternate" href="http://www.sundancespas.ca/hot-tub-dealer-locator/georgia-ga/hot-tubs-blue-ridge/" hreflang="en-ca" />',
        '/hot-tub-dealer-locator/california-ca/hot-tubs-corona/' => '<link rel="alternate" href="http://www.sundancespas.com/hot-tub-dealer-locator/california-ca/hot-tubs-corona/" hreflang="en-us" /><link rel="alternate" href="http://www.sundancespas.ca/hot-tub-dealer-locator/california-ca/hot-tubs-corona/" hreflang="en-ca" />',
        '/hot-tub-dealer-locator/oregon-or/hot-tubs-beaverton/' => '<link rel="alternate" href="http://www.sundancespas.com/hot-tub-dealer-locator/oregon-or/hot-tubs-beaverton/" hreflang="en-us" /><link rel="alternate" href="http://www.sundancespas.ca/hot-tub-dealer-locator/oregon-or/hot-tubs-beaverton/" hreflang="en-ca" />',
        '/hot-tub-dealer-locator/washington-wa/hot-tubs-kent/' => '<link rel="alternate" href="http://www.sundancespas.com/hot-tub-dealer-locator/washington-wa/hot-tubs-kent/" hreflang="en-us" /><link rel="alternate" href="http://www.sundancespas.ca/hot-tub-dealer-locator/washington-wa/hot-tubs-kent/" hreflang="en-ca" />',
        '/hot-tub-dealer-locator/iowa-ia/hot-tubs-cedar-rapids/' => '<link rel="alternate" href="http://www.sundancespas.com/hot-tub-dealer-locator/iowa-ia/hot-tubs-cedar-rapids/" hreflang="en-us" /><link rel="alternate" href="http://www.sundancespas.ca/hot-tub-dealer-locator/iowa-ia/hot-tubs-cedar-rapids/" hreflang="en-ca" />',
        '/hot-tub-dealer-locator/colorado-co/hot-tubs-avon/' => '<link rel="alternate" href="http://www.sundancespas.com/hot-tub-dealer-locator/colorado-co/hot-tubs-avon/" hreflang="en-us" /><link rel="alternate" href="http://www.sundancespas.ca/hot-tub-dealer-locator/colorado-co/hot-tubs-avon/" hreflang="en-ca" />',
        '/hot-tub-dealer-locator/ohio-oh/hot-tubs-oh/' => '<link rel="alternate" href="http://www.sundancespas.com/hot-tub-dealer-locator/ohio-oh/hot-tubs-oh/" hreflang="en-us" /><link rel="alternate" href="http://www.sundancespas.ca/hot-tub-dealer-locator/ohio-oh/hot-tubs-oh/" hreflang="en-ca" />',
    );
	if ( array_key_exists($p['path'], $a) )
		print $a[ $p['path'] ];
}

require get_template_directory() . '/includes/wp_bootstrap_navwalker.php';

function sundance_series( $tub_id ) {
	$custom = get_post_meta($tub_id,'s_cats');
	$cats = $custom[0];
	$serID = $cats[0];
	
	if(isset($serID))
	{
		return get_the_title($serID);
	}
	return '';
}

function add_hreflang_tags() {
   echo '<link rel="alternate" href="'.get_bloginfo('url').parse_url($_SERVER['REQUEST_URI'],PHP_URL_PATH).'" hreflang="en-us" />';
   echo '<link rel="alternate" href="http://www.sundance-spas.co.uk/" hreflang="en-gb" />';
}


add_action( 'wp_head', 'add_hreflang_tags' , 2 );

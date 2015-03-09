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

if ( !session_id() )
	add_action( 'init', 'session_start' );

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

function sundance_meta_save($post_id){
	// verify if this is an auto save routine. If it is our form has not been submitted, so we dont want
	// to do anything
	if ( defined('DOING_AUTOSAVE') && DOING_AUTOSAVE ) 
	return $post_id;
	
	// Check permissions
	if (in_array($_POST['post_type'],array('s_spa', 'page', 's_vid', 's_feat')) ) {
		if ( !current_user_can( 'edit_page', $post_id ) ) return $post_id;
	} else {
	//if ( !current_user_can( 'edit_post', $post_id ) )
	  return $post_id;
	}
	
	if($_POST['post_type'] == 'page') {
		$info = $_POST['s_pageopts'];
		update_post_meta($post_id, 's_pageopts', $info);
		return $info;
	}
	
	if( in_array( $_POST['post_type'] , array( 's_feat', 's_vid' ) ) ) {
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
						$custom = get_post_meta($cat_id, 's_cat_tubs');
						$cat_tubs = $custom[0];
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
						$custom = get_post_meta($cat_id, 's_cat_tubs');
						$cat_tubs = $custom[0];
						if($cat_tubs=='') $cat_tubs = array();
						
						$tname = 's_'. $cat_id .'_tubtable';
						delete_transient( $tname );
						
						if ( isset($cat_tubs[$post_id]) ) {
							unset($cat_tubs[$post_id]);
							update_post_meta($cat_id, 's_cat_tubs', $cat_tubs);
						}
					}
				}
				$moretransients = array( 's_tubcats', 's_allcats', 's_alltubs' );
				foreach ( $moretransients as $t ) {
					delete_transient( $t );
				}
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
	// transient for s_tubcats
	//if ( false === ( $special_query_results = get_transient( 's_tubcats' ) ) ) {
	// It wasn't there, so regenerate the data and save the transient

	// main HOT TUBS category is id #8, so we can skip that...

	// transient for s_allcats
	// if ( false === ( $special_query_results = get_transient( 's_allcats' ) ) ) {
	// It wasn't there, so regenerate the data and save the transient
	$series = get_posts(array('numberposts'=>-1,'post_type'=>'s_cat','orderby'=>'menu_order','order'=>'ASC','exclude'=>1894));
	//set_transient( 's_allcats', $special_query_results, 60*60*12 );
	//}
	// Use the data like you would have normally...
	//$series = get_transient( 's_allcats' );

	// transient for s_alltubs
	//if ( false === ( $special_query_results = get_transient( 's_alltubs' ) ) ) {
	// It wasn't there, so regenerate the data and save the transient
	$alltubs = get_posts( array( 'numberposts' => -1, 'post_type' => 's_spa', 'orderby' => 'menu_order', 'order' => 'ASC' ) );
	//set_transient( 's_alltubs', $special_query_results, 60*60*12 );
	//}
	// Use the data like you would have normally...
	//$alltubs = get_transient( 's_alltubs' );

	$cats = array();
	foreach ( $series as $c ) {
		if ( !isset($cats[$c->ID]) ) $cats[$c->ID] = array();

		$cats[$c->ID]['name'] = $c->post_title;
		$cats[$c->ID]['url'] = get_bloginfo('url') .'/'. $c->post_name .'/'; //get_permalink($c->ID);
		$cats[$c->ID]['tubs'] = array();
		/*
		if (class_exists('MultiPostThumbnails')) {
		$img_id = MultiPostThumbnails::get_post_thumbnail_id('jht_cat', 'nav-rollover', $c->ID);
		$cat_img = get_post($img_id);
		$cat_img = $cat_img->guid;
		} else {
		$cat_img = '';
		}

		$cats[$c->ID]['img'] = $cat_img;
		*/
		$custom = get_post_meta($c->ID, 's_cat_tubs');
		$cat_tubs = $custom[0];
		if($cat_tubs=='') {
			$cat_tubs = array();
		} else {
			/*
			foreach ( $cat_tubs as $k => $t ) {
			$cat_tubs[$k]['imgs'] = $tub_imgs[$k];
			}
			*/
			usort($cat_tubs, 'sundance_tub_sort');
		}

		$cats[$c->ID]['tubs'] = $cat_tubs;
	}

	set_transient( 's_tubcats', $cats, 60*60*12 );
	//}
	// Use the data like you would have normally...
	//$cats = get_transient( 's_tubcats' );
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
	if ( ! is_admin() ) {
		wp_deregister_script('jquery');
		wp_register_script( 'jquery', 'http://ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js', array(), '1.10.2', false);
		wp_enqueue_script( 'jquery-migrate','http://code.jquery.com/jquery-migrate-1.2.1.js',array('jquery'), '1.2.1', false);
		wp_enqueue_script( 'jquery-ui', '//ajax.googleapis.com/ajax/libs/jqueryui/1.10.4/jquery-ui.min.js', array('jquery'), '1.10.3', false );
		wp_enqueue_script( 'jquery-ui-tooltip', get_bloginfo('template_url') .'/js/jquery-ui-1.10.3.custom.min.js', array('jquery'), '1.10.4' );
		wp_enqueue_script( 'jquery.cookie', get_bloginfo('template_url') .'/js/jquery.cookie.js', array('jquery'), '1.0' );
		wp_enqueue_script( 'sundance-frontend', get_bloginfo('template_url') .'/js/frontend.js', array('jquery', 'jquery.cookie', 'thickbox'), '1.1' );
		wp_enqueue_script( 'tipr', get_bloginfo('template_url') .'/js/tipr/tipr.js', array('jquery'), '1' );

		// load bvpai.js
		//wp_enqueue_script( 'bvapi-js', '//display-stg.ugc.bazaarvoice.com/static/sundancespas/en_US/bvapi.js', array(), '1.0', false); //staging
		wp_enqueue_script( 'bvapi-js', '//display.ugc.bazaarvoice.com/static/sundancespas/en_US/bvapi.js', array(), '1.0', false); //production
	}
}

function sundance_add_styles() {
	if ( ! is_admin() ) {
		wp_enqueue_style('thickbox');
		
		$theme = wp_get_theme();
		wp_enqueue_style( 'sundance', get_bloginfo( 'stylesheet_url' ), array(), $theme->Version );
		wp_enqueue_style( 'jquery-ui', '//ajax.googleapis.com/ajax/libs/jqueryui/1.10.4/themes/smoothness/jquery-ui.css', array(), $theme->Version );
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
	$tname = 's_'. $series_id .'_tubtable';
	
	if ( false === ( $special_query_results = get_transient( $tname ) ) ) {
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
							$o .= '<a href="'. esc_url($t['url']) .'">';
							$o .= '<div class="tubThumb ' . esc_attr( strtolower( preg_replace( '/[^A-Za-z0-9]/', '', str_replace('&trade;','',$t['name'] ) ) ) ) . '" ><div class="tubViewDetails"></div></div>';
							$o .= '<span class="h3">'. esc_attr($t['name']) .'</span>';
							$o .= '<span class="p">Seats: '. esc_attr($t['seats']) .'</span>';
							$o .= '<span class="p">Total Jets: '. absint($t['jets']) .'</span>';
							$o .= '<span class="p">Capacity: '. esc_attr($t['vol']) .'</span>';
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
				}
			}
		}
		$o .= '</table>';
		set_transient( $tname, $o, 60*60*12 );
	}
	// Use the data like you would have normally...
	$o = get_transient( $tname );
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
	$thispost = wp_get_single_post($post_id);
	$thisterms = wp_get_object_terms($post_id, 's_acc_cat', array('fields' => 'slugs'));
	foreach ( $thisterms as $t ) {
		delete_transient( $t .'-accs' );
	}
}
add_action( 'publish_s_acc', 'sundance_publ_acc_transients' );

function sundance_flush_tubcats_transients($post_id) {
	$thispost = wp_get_single_post($post_id);
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

function sundance_pagetitle() {
	global $post;
	$ptitle = get_post_meta($post->ID, 'pagetitle', true);
	if ( $ptitle != '' ) {
		esc_attr_e($ptitle);
	} else {
		the_title('');
	}
}


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
		$req = $_SERVER['REQUEST_URI'];
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
		$req = $_SERVER['REQUEST_URI'];
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
function geo_data( $debug = false, $return = null ) {
	// do nothing if viewing admin pages (geo not needed)
	if ( is_admin() )
		return false;
	if( session_id() == '' ) {
		session_start();
	}

	$ip = get_the_ip();

	if ( isset( $_POST['PostalCode'] ) ) :
		$a = geo_data_mysql_zip( $_POST['PostalCode'] );
		if ( $a ) {
			$_SESSION['geoDbLookupData'] = $a;
			return $a;
		}
	endif;

	if ( isset($_SESSION['geoDbLookupData']) && $_SESSION['geoDbLookupData']['ip'] === $ip ) :
		$a = $_SESSION['geoDbLookupData'];
	else :
		$a = geo_data_mysql_ip( $ip );
		if ( !$a ) {
			//$a = geo_data_curl( $ip );
		}
	endif;

	/* Possibly...

	// if session set, use that
	if ( isset($_SESSION['geoDbLookupData']) ) :
		$a = $_SESSION['geoDbLookupData'];
	// otherwise search by IP
	elseif ( $ip ) :
		$a = geo_data_mysql_ip( $ip );
	// if IP result not valid, search by post code
	elseif ( isset( $_POST['PostalCode'] ) ) :
		$a = geo_data_mysql_zip( $_POST['PostalCode'] );
	// if no post cde and no IP
	// return some sort of default
	endif;

	if ( $a ) {
		$_SESSION['geoDbLookupData'] = $a;
		return $a;
	}

	*/

	if ( !$a ) {
		return array(
			'locId'				=>	0,
			'country'			=>	'US',
			'region'			=>	'',
			'city'				=>	'',
			'postalCode'		=>	'00000',
			'latitude'			=>	'',
			'longitude'			=>	'',
			'metroCode'			=>	'',
			'areacode'			=>	'',
			'ip'				=>	'',
			);
	}

	$_SESSION['geoDbLookupData'] = $a;
	// And finally we return the resulting array to wherever it is needed...
	return $a;
}

function geo_data_curl( $ip ) {
	if ( !$ip ) {
		$ip = 'me';
	}
	$username = '66659';
	$password = 'FJv62Mz6ezIB';

	$ch = curl_init('https://geoip.maxmind.com/geoip/v2.0/city/' . $ip . '');
	curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 1);
	curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
	curl_setopt($ch, CURLOPT_USERPWD, "$username:$password");
	curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	$result = curl_exec($ch);
	$httpResult = curl_getinfo($ch, CURLINFO_HTTP_CODE);

	$a = json_decode($result, true);
	$b = array(
		'locId'				=>	0,
		'country'			=>	$a['country']['iso_code'],
		'region'			=>	$a['subdivisions'][0]['iso_code'],
		'city'				=>	$a['city']['names']['en'],
		'postalCode'		=>	$a['postal']['code'],
		'latitude'			=>	$a['location']['latitude'],
		'longitude'			=>	$a['location']['longitude'],
		'metroCode'			=>	$a['location']['metro_code'],
		'areacode'			=>	'',
		'ip'				=>	$ip,
		'queries_remaining'	=>	$a['maxmind']['queries_remaining'],
		);
	if ( $httpResult == 200 ) {
		return $b;
	}
	return false;
}

function geo_data_mysql_ip( $ip ) {
	$a = false;

	$livehost		= array( 'www.sundancespas.com', 'www.sundancespas.ca' );
	$betahost		= array( 'beta.sundancespas.com' );
	$devhost		= array( 'sundancespas.ninthlink.me' );
	$localhost		= array( 'local.sundance' );
	if ( in_array( $_SERVER['SERVER_NAME'], $livehost ) ) {
		$the_user = "sundance_geoip";
		$the_pass = "r4e3w2q1!";
		$the_name = "sundance_geoip";
	}
	if ( in_array( $_SERVER['SERVER_NAME'], $betahost ) ) {
		$the_user = "sundance_geoip";
		$the_pass = "r4e3w2q1!";
		$the_name = "sundance_geoip";
	}
	if ( in_array( $_SERVER['SERVER_NAME'], $devhost ) ) {
		$the_user = "admin_geoip";
		$the_pass = "r4e3w2q1";
		$the_name = "admin_geoip";
	}
	if ( in_array( $_SERVER['SERVER_NAME'], $localhost ) ) {
		$the_user = "root";
		$the_pass = "";
		$the_name = "nlk_geoip";
	}
	$mysqli = new mysqli(DB_HOST, $the_user, $the_pass, $the_name);

	// Unable to MySQL? Return false
	if ($mysqli->connect_errno) {
		$error = "Failed to connect to MySQL: (" . $mysqli->connect_errno . ") " . $mysqli->connect_error;
		return false;
	}
	$query = "SELECT gl.* FROM geoip_locations gl LEFT JOIN geoip_blocks gb ON gb.locId = gl.locId WHERE gb.startIpNum <= INET_ATON( ? ) AND gb.endIpNum >= INET_ATON( ? ) LIMIT 1";
	if ( $stmt = $mysqli->prepare( $query ) ) {
		$stmt->bind_param( "ss", $ip, $ip );
		$stmt->execute();
		$stmt->bind_result( $locId, $country, $region, $city, $postalCode, $latitude, $longitude, $metroCode, $areaCode );
		while ( $stmt->fetch() ) {
			$a = array(
				'locId'			=>	$locId,
				'country'		=>	$country,
				'region'		=>	$region,
				'city'			=>	$city,
				'postalCode'	=>	$postalCode,
				'latitude'		=>	$latitude,
				'longitude'		=>	$longitude,
				'metroCode'		=>	$metroCode,
				'areacode'		=>	$areaCode,
				'ip'			=>	$ip,
				);
		}
		$stmt->close();
	}
	$mysqli->close();
	return $a;
}

function geo_data_mysql_zip( $zip ) {
	$a = false;
	
	$livehost		= array( 'www.sundancespas.com', 'www.sundancespas.ca' );
	$betahost		= array( 'beta.sundancespas.com' );
	$devhost		= array( 'sundancespas.ninthlink.me' );
	$localhost		= array( 'local.sundance' );
	if ( in_array( $_SERVER['SERVER_NAME'], $livehost ) ) {
		$the_user = "sundance_geoip";
		$the_pass = "r4e3w2q1!";
		$the_name = "sundance_geoip";
	}
	if ( in_array( $_SERVER['SERVER_NAME'], $betahost ) ) {
		$the_user = "sundance_geoip";
		$the_pass = "r4e3w2q1!";
		$the_name = "sundance_geoip";
	}
	if ( in_array( $_SERVER['SERVER_NAME'], $devhost ) ) {
		$the_user = "admin_geoip";
		$the_pass = "r4e3w2q1";
		$the_name = "admin_geoip";
	}
	if ( in_array( $_SERVER['SERVER_NAME'], $localhost ) ) {
		$the_user = "root";
		$the_pass = "";
		$the_name = "nlk_geoip";
	}
	$mysqli = new mysqli(DB_HOST, $the_user, $the_pass, $the_name);

	// Unable to MySQL? Return false
	if ($mysqli->connect_errno) {
		$error = "Failed to connect to MySQL: (" . $mysqli->connect_errno . ") " . $mysqli->connect_error;
		return false;
	}
	$clean_zip = clean_zip( $zip );
	$query = "SELECT * FROM geoip_locations WHERE postalCode = ? LIMIT 1";
	if ( $stmt = $mysqli->prepare( $query ) ) {
		$stmt->bind_param( "s", $clean_zip );
		$stmt->execute();
		$stmt->bind_result( $locId, $country, $region, $city, $postalCode, $latitude, $longitude, $metroCode, $areaCode );
		while ( $stmt->fetch() ) {
			$a = array(
				'locId'			=>	$locId,
				'country'		=>	$country,
				'region'		=>	$region,
				'city'			=>	$city,
				'postalCode'	=>	$zip,
				'latitude'		=>	$latitude,
				'longitude'		=>	$longitude,
				'metroCode'		=>	$metroCode,
				'areacode'		=>	$areaCode,
				'ip'			=>	get_the_ip(),
				);
		}
		$stmt->close();
	}
	$mysqli->close();
	return $a;
}

function getRemoteIPAddress() {

    if (!empty($_SERVER['HTTP_CLIENT_IP'])) :
        return $_SERVER['HTTP_CLIENT_IP'];
	elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) :
        return $_SERVER['HTTP_X_FORWARDED_FOR'];
    endif;

    return $_SERVER['REMOTE_ADDR'];
}

function get_the_ip() {

	$s = $_SERVER[QUERY_STRING];
	parse_str($s, $o);

	if ( array_key_exists('ip', $o) ) :
		$ip = $o['ip'];
	elseif ( !empty($_SERVER['HTTP_CLIENT_IP']) ) :
        $ip = $_SERVER['HTTP_CLIENT_IP'];
	elseif ( !empty($_SERVER['HTTP_X_FORWARDED_FOR']) ) :
        $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
    else :
    	$ip = $_SERVER['REMOTE_ADDR'];
    endif;
	if ( !filter_var($ip, FILTER_VALIDATE_IP, FILTER_FLAG_NO_PRIV_RANGE) || !filter_var($ip, FILTER_VALIDATE_IP, FILTER_FLAG_NO_RES_RANGE) || $ip == '127.0.0.1' ) {
		return false;
	}
	return $ip;
}

function clean_zip( $zip ) {

	$zip = strtoupper( preg_replace( "/\s/", '', $zip ) );
	$valid_country = false;

	$reg	=	array(
		"US"	=>	"^\d{5}([\-]?\d{4})?$",
		"CA"	=>	"^([ABCEGHJKLMNPRSTVXY]\d[ABCEGHJKLMNPRSTVWXYZ])\ {0,1}(\d[ABCEGHJKLMNPRSTVWXYZ]\d)$",
		"UK"	=>	"^(GIR|[A-Z]\d[A-Z\d]??|[A-Z]{2}\d[A-Z\d]??)[ ]??(\d[A-Z]{2})$",
		"DE"	=>	"\b((?:0[1-46-9]\d{3})|(?:[1-357-9]\d{4})|(?:[4][0-24-9]\d{3})|(?:[6][013-9]\d{3}))\b",
		"FR"	=>	"^(F-)?((2[A|B])|[0-9]{2})[0-9]{3}$",
		"IT"	=>	"^(V-|I-)?[0-9]{5}$",
		"AU"	=>	"^(0[289][0-9]{2})|([1345689][0-9]{3})|(2[0-8][0-9]{2})|(290[0-9])|(291[0-4])|(7[0-4][0-9]{2})|(7[8-9][0-9]{2})$",
		"NL"	=>	"^[1-9][0-9]{3}\s?([a-zA-Z]{2})?$",
		"ES"	=>	"^([1-9]{2}|[0-9][1-9]|[1-9][0-9])[0-9]{3}$",
		"DK"	=>	"^([D-d][K-k])?( |-)?[1-9]{1}[0-9]{3}$",
		"SE"	=>	"^(s-|S-){0,1}[0-9]{3}\s?[0-9]{2}$",
		"BE"	=>	"^[1-9]{1}[0-9]{3}$"
	);

	// Check if we can validate the zip against one of the above countries
	foreach ( $reg as $k => $v ) {
		if ( preg_match( "/" . $v . "/i", $zip ) ) {
			$valid_country = $k;
			break;
		}
	}
	// For US or CA, clean the zip for geo search
	if ( $valid_country == 'US' ) :
		list($clean_zip) = explode('-', $zip);
	elseif ( $valid_country == 'CA' ) :
		$clean_zip = substr( $zip, 0, 3 );
	else :
		$clean_zip = $zip;
	endif;

	$clean_zip = strtolower( $clean_zip );

	return $clean_zip;
}
function zip_to_geo( $original_zip ) {
	$a = false;

	$zip = clean_zip( $original_zip );

	if ( $zip[1] !== false ) {

		$livehost		= array( 'www.sundancespas.com', 'www.sundancespas.ca', 'beta.sundancespas.com' );
		$betahost		= array( 'beta.sundancespas.com' );
		$devhost		= array( 'sundancespas.ninthlink.me' );
		$localhost		= array( 'local.sundance' );
		if ( in_array( $_SERVER['SERVER_NAME'], $livehost ) ) {
			$the_user = "sundance_geoip";
			$the_pass = "r4e3w2q1!";
			$the_name = "sundance_geoip";
		}
		if ( in_array( $_SERVER['SERVER_NAME'], $betahost ) ) {
			$the_user = "sundance_geoip";
			$the_pass = "r4e3w2q1!";
			$the_name = "sundance_geoip";
		}
		if ( in_array( $_SERVER['SERVER_NAME'], $devhost ) ) {
			$the_user = "admin_geoip";
			$the_pass = "r4e3w2q1";
			$the_name = "admin_geoip";
		}
		if ( in_array( $_SERVER['SERVER_NAME'], $localhost ) ) {
			$the_user = "root";
			$the_pass = "";
			$the_name = "nlk_geoip";
		}
		$mysqli = new mysqli(DB_HOST, $the_user, $the_pass, $the_name);
	
		if ($mysqli->connect_errno) {

			$error = "Failed to connect to MySQL: (" . $mysqli->connect_errno . ") " . $mysqli->connect_error;

			return false;
		}

		$query = "SELECT * FROM geoip_locations WHERE postalCode = ? LIMIT 1";

		if ( $stmt = $mysqli->prepare( $query ) ) {

			$stmt->bind_param( "s", $zip );

			$stmt->execute();

			$stmt->bind_result( $locId, $country, $region, $city, $postalCode, $latitude, $longitude, $metroCode, $areaCode );

			while ( $stmt->fetch() ) {

				$a = array(

					'locId'			=>	$locId,
					'country'		=>	$country,
					'region'		=>	$region,
					'city'			=>	$city,
					'postalCode'	=>	$original_zip,
					'latitude'		=>	$latitude,
					'longitude'		=>	$longitude,
					'metroCode'		=>	$metroCode,
					'areacode'		=>	$areaCode,
					'ip'			=>	get_the_ip(),

					);

			}

			$stmt->close();

		}

		$mysqli->close();

	}

	return $a;
}


// geo redirect
if ( ! function_exists(geo_redirection) ) {
	function geo_redirection() {

		$g = geo_data();
		$c = $g['country'];

		switch ($c) {
			case 'CA':
				if ( is_front_page() ) {
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




// [get_blog_info dir="url"]
if ( ! function_exists(s_code_get_site_dir) ):
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
add_action('do_google_tag_manager', 'google_tag_manager_container');
function google_tag_manager_container() {
	$str = <<<GTM
	<!-- Google Tag Manager -->
	<noscript><iframe src="//www.googletagmanager.com/ns.html?id=GTM-NTFWKQ"
	height="0" width="0" style="display:none;visibility:hidden"></iframe></noscript>
	<script>(function(w,d,s,l,i){w[l]=w[l]||[];w[l].push({'gtm.start':
	new Date().getTime(),event:'gtm.js'});var f=d.getElementsByTagName(s)[0],
	j=d.createElement(s),dl=l!='dataLayer'?'&l='+l:'';j.async=true;j.src=
	'//www.googletagmanager.com/gtm.js?id='+i+dl;f.parentNode.insertBefore(j,f);
	})(window,document,'script','dataLayer','GTM-NTFWKQ');</script>
	<!-- End Google Tag Manager -->
GTM;
	echo $str;
}
function google_tag_manager() {
	do_action('do_google_tag_manager');
}

// Google Tag Manager Criteo
add_action('do_google_tag_manager_criteo', 'google_tag_manager_criteo_container');
function google_tag_manager_criteo_container() {
	$str = <<<GTM
	<!-- Google Tag Manager -->
	<noscript><iframe src="//www.googletagmanager.com/ns.html?id=GTM-PWJ2SH"
	height="0" width="0" style="display:none;visibility:hidden"></iframe></noscript>
	<script>(function(w,d,s,l,i){w[l]=w[l]||[];w[l].push({'gtm.start':
	new Date().getTime(),event:'gtm.js'});var f=d.getElementsByTagName(s)[0],
	j=d.createElement(s),dl=l!='dataLayer'?'&l='+l:'';j.async=true;j.src=
	'//www.googletagmanager.com/gtm.js?id='+i+dl;f.parentNode.insertBefore(j,f);
	})(window,document,'script','dataLayer','GTM-PWJ2SH');</script>
	<!-- End Google Tag Manager -->
GTM;
	echo $str;
}
function google_tag_manager_criteo() {
	do_action('do_google_tag_manager_criteo');
}


// Custom data layer
add_action('do_custom_data_layer', 'custom_data_layer_container');
function custom_data_layer_container() {
	global $post;
	
	$expire = time()+60*60*24*30;

	$custId = get_current_user_id() > 0 ? get_current_user_id() : $_COOKIE["sdscid"] ? $_COOKIE["sdscid"] : rand( 1000000, 1000000000 ) ;
	$prodId = $_COOKIE["sdsspa"] ? $_COOKIE["sdsspa"] : '' ;
	setcookie("sdscid", $custId, $expire, '/');
	

	$str = '<script>dataLayer = [{';
	$str .= '"get":' . json_encode($_SERVER[QUERY_STRING]) . ',';
	$str .= '"geo":' . json_encode(geo_data()) . ',';
	$str .= '"customerId":"' . $custId . '",';
	
	if ( get_post_type($post->ID) == "s_spa" ) { // is single spa page
		$parts = explode( "&", get_the_title($post->ID) );
		$prodId = $parts[0];
		setcookie("sdsspa", $prodId, $expire, '/');

		$str .= '"productId":"' . $prodId . '",';
	}
	
	if ( get_post_type($post->ID) == "s_cat" ) { // is category spa page
		global $post;
		$custom = get_post_meta($post->ID, 's_cat_tubs');
		$cat_tubs = $custom[0];
		if ( count( $cat_tubs ) !== 0 ) {
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
	if ( is_page(5111) || is_page(2982) ) { // brochure page - paid search
		$transId = time();
		if ( $prodId )
			$str .= '"productId":"' . $prodId . '",';
	}
	if ( is_page(2984) ) { // brochure thanks page
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


// Tracking Codes
include('functions_trackingcodes.php');

/*	*	*	*	*	*	*	*	*
 *
 *	SDS Startup Guide Web app
 *
 *	*	*	*	*	*	*	*	*/

// StartUp Guide Ajaxify
wp_enqueue_script( 'startupguide-ajax-request', get_template_directory_uri() . '/js/startupguide-ajax.js', array( 'jquery' ) );
wp_localize_script( 'startupguide-ajax-request', 'StartupAjax', array( 'ajaxurl' => admin_url( 'admin-ajax.php' ) ) );

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

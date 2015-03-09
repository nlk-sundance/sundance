<?php
/**
 * @package Sundance
 * @subpackage Sundance
 * @since Sundance 2.0
 */
?>

<div class="side col w230 last">

<?php /* Start by adding the top menu options, video link etc. */ ?>
<?php if ( is_front_page() == false ) { ?>
	<div class="rightCol">
	<?php get_sidebar('rt4'); ?>
	</div>
	<?php } ?>
	<?php /* <!-- Blog categories, recent posts, FB widget--> */ ?>
	<div class="inner">
		<p>&nbsp;<br />&nbsp;</p>
	    <div class="categories widget">
	        <h2>Blog Categories</h2>
	        <ul>
	            <?php
				if ( false === ( $special_query_results = get_transient( 'wp_list_categories' ) ) ) {
					// It wasn't there, so regenerate the data and save the transient
					$special_query_results = wp_list_categories('title_li=&echo=0');
					set_transient( 'wp_list_categories', $special_query_results, 60*60*12 );
				}
				// Use the data like you would have normally...
				$wp_list_categories = get_transient( 'wp_list_categories' );
				echo $wp_list_categories;
				?>
	        </ul>
	    </div>
	    <div class="recent widget">
	        <h2>Recent Posts</h2>
	        <ul>
	            <?php
				if ( false === ( $special_query_results = get_transient( 'sundance_latest5' ) ) ) {
					// It wasn't there, so regenerate the data and save the transient
					$latest5 = get_posts(array('numberposts'=>5));
					$o = '';
					foreach ( $latest5 as $p ) {
						$o .= '<li><a href="'. get_permalink($p->ID) .'">'. esc_attr($p->post_title) .'</a></li>';
					}
					set_transient( 'sundance_latest5', $o, 60*60*12 );
				}
				// Use the data like you would have normally...
				$sundance_latest5 = get_transient( 'sundance_latest5' );
				echo $sundance_latest5;
				?>
	        </ul>
	    </div>
	    <div class="facebook widget">
	        <iframe src="//www.facebook.com/plugins/likebox.php?href=https%3A%2F%2Fwww.facebook.com%2FSundanceSpas&amp;width=203&amp;height=258&amp;colorscheme=light&amp;show_faces=true&amp;border_color=%23fff&amp;stream=false&amp;header=false&amp;appId=235635479789715" scrolling="no" frameborder="0" style="border:none; overflow:hidden; width:203px; height:258px;" allowTransparency="true"></iframe>
	    </div>
	    <?php /* Mock up has "facebook social plugin" text with horiz separator, would go here */
	    //adding line spacing
	    print('<p>&nbsp;</p>'); ?>


		<?php /* <!-- Drop in the added social stuff at the bottom --> */ ?>
		<ul class="list avenir65-12">
			<li><a href="#signup" class="esignup arrowRight">Email Sign Up</a></li>
			<li><a href="<?php echo get_permalink(4939); ?>" class="arrowRight">Trade-In Value</a></li>
			<li><a href="<?php echo get_permalink(2974); ?>" class="arrowRight">Finance Your Hot tub</a></li>
			<li><a href="<?php echo get_permalink(2978); ?>" class="arrowRight">View Special Offers</a></li>
			<li><a href="#search" class="arrowRight">Search</a>
			<?php get_search_form(); ?>
			</li>
			<?php get_sidebar('socialicons'); ?>
		</ul>
		<?php /*
		<div class="thirtyYears"></div>
		<div class="sizzle">
			<a href="/specials/">Learn More</a>
		</div>
		*/ ?>
	</div>
</div>
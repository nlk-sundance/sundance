<?php
/**
 * The main template file, which is used on the blog /spa-blog/ main (landing) page
 *
 * This is the most generic template file in a WordPress theme
 * and one of the two required files for a theme (the other being style.css).
 * It is used to display a page when nothing more specific matches a query.
 * E.g., it puts together the home page when no home.php file exists.
 * Learn more: http://codex.wordpress.org/Template_Hierarchy
 *
 * @package Sundance
 * @subpackage Sundance
 * @since Sundance 2.0
 */

get_header(); ?>
<div class="cols">
    <div class="main col w730">
        <div class="main-title">
			<h1><?php // sundance_pagetitle();
					echo 'Sundance Spas Blog';
				?></h1>
		</div>
        <?php if ( is_category() ) { ?>
        <div class="return">
            <p><a href="<?php echo get_permalink(1892); ?>">&lt; Back to the Sundance Spas Blog</a></p>
        </div>
        <?php } ?>
        <div class="post-intros">
    <?php if ( have_posts() ) {
		while ( have_posts() ) : the_post(); ?>
			<p>&nbsp;</p>
			<h1 class="title clickable" onClick='window.location.href="<?php echo get_permalink(''); ?>"'><?php the_title(); ?></h1>
			<div class="post">
	            <div class="entry-meta"><?php sundance_posted_on(); ?><!--Oct 5, 2011 &nbsp;|&nbsp; K. Lee--></div>
	            <div class="entry-content limited">
	            			<?php
	            			$content = get_the_content('READ MORE &gt;');
	            			//get the first occurence of an img
                            if ( function_exists('DOMDocument') ) {
                                $dom = new DOMDocument();
                                $dom->loadHTML($content);
                                $img = $dom->getElementsByTagName('img')->item(0);
                                if ( $img != null ) {
                                    echo '<div class="blog-preview-img-container">
                                            <img src="'.$img->attributes->getNamedItem("src")->value.'" class="blog-preview-img"/>
                                          </div>';
                                }
                                $content = preg_replace("/<img[^>]+\>/i", "", $content);
                            }
							//if ( strlen( $content ) > 400 ) {
							//	$content = substr($content, 0, 400);
							//	$content .= '<br /><p><a href="'.get_permalink('').'">READ MORE &gt;</a></p>';
							//}
							echo $content;
							?>
	            </div>
	            <div class="share"><?php if(function_exists('sharethis_button')) sharethis_button(); ?></div>
	        </div>
	        <div class="clearfix"></div>
	        <?php comments_template( '', true ); ?>

		<?php endwhile; ?>
	<?php }
	// insert some else code for "no posts found" here
	?>
<?php /* Display navigation to next/previous pages when applicable */ ?>
<?php if (  $wp_query->max_num_pages > 1 ) : ?>
        		<div class="return bottom">
					<p><?php next_posts_link( __( '<span class="meta-nav">&lt;</span> Older posts', 'sundance' ) ); ?></p>
					<p class="n"><?php previous_posts_link( __( 'Newer posts <span class="meta-nav">&gt;</span>', 'sundance' ) ); ?></p>
				</div><!-- #nav-below -->
<?php endif; ?>
        </div>
    </div>
    <?php get_sidebar('blog'); ?>
</div>
<br class="clear" />
<?php get_footer(); ?>

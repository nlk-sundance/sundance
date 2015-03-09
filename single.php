<?php
/**
 * Single Post Template
 *
 * @package Sundance
 * @subpackage Sundance
 * @since Sundance 2.0
 */

get_header(); ?>
<div class="cols">
    <div class="main col w730">
        <?php /* add top page title */ ?>
        <div class="main-title">
            <h1><?php
                print 'Sundance Spas Blog';
                //$this_title=the_title( '', '', false );
                //print substr($this_title, 0, 20).'...'; // <-- tweak this to be more dynamic
            ?></h1>
        </div>
        <?php /*
        Remove top slideshow - TS 3/15/2013
        get_sidebar('blogslideshow'); */ ?>
        <div class="return">
            <p><a href="<?php echo get_permalink(1892); ?>">&lt; Back to the Sundance Blog</a></p>
        </div>

        <?php /* Adding some whitespace between horiz line and h1*/ print('<p>&nbsp;</p>'); ?>

    <?php while ( have_posts() ) : the_post(); ?>
        
        <h1 class="title"><?php the_title(); ?></h1>
        <div class="post">
            <div class="entry-meta"><?php sundance_posted_on(); ?><!--Oct 5, 2011 &nbsp;|&nbsp; K. Lee--></div>
            <div class="entry-content">
                <?php the_content(); ?>
            </div>
            <div class="share"><?php if(function_exists('sharethis_button')) sharethis_button(); ?></div>
        </div>
        <?php //comments_template( '', true ); ?>
    <?php endwhile; ?>

    </div>
    <?php get_sidebar('blog'); ?>
</div>
<br class="clear" />
<?php get_footer(); ?>

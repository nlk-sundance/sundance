<?php
/**
 * Template Name: Backyard Life
 *
 * @package Sundance
 * @subpackage Sundance
 * @since Sundance 2.0
 */

get_header(); ?>
<div class="cols">
<?php while ( have_posts() ) : the_post(); ?>
<div class="main col w730">
<div class="main-title">
<?php the_post_thumbnail(); ?>
<h1><?php the_title(''); ?></h1>
</div>
<div class="tiles">
    <ul class="grid">
        <li><div>
            <h3><a href="<?php echo get_permalink(2790); ?>"><span class="icon dot dotBlue"></span>Planning</a></h3>
            <p><a href="<?php echo get_permalink(2790); ?>"><img src="<?php bloginfo('template_url'); ?>/images/backyard-life/thm-planning.jpg" border="0" alt="" width="195" height="109" /></a></p>
            <h2>Planning for your new hot tub</h2>
            <p>5 key tips for choosing a model and getting ready for delivery.<br /><br /></p>
        </div></li>
        <li><div>
            <h3><a href="<?php echo get_permalink(2796); ?>"><span class="icon dot dotTeal"></span>Installations</a></h3>
            <p><a href="<?php echo get_permalink(2796); ?>"><img src="<?php bloginfo('template_url'); ?>/images/backyard-life/thm-installations.jpg" border="0" alt="Installations" width="195" height="109" /></a></p>
            <h2>Installation Gallery<br /><br /></h2>
            <p>Get ideas for your own backyard from these award-winning installations.</p>
        </div></li>
        <li><div>
            <h3><a href="<?php echo get_permalink(2801); ?>"><span class="icon dot dotOrange"></span>How-To</a></h3>
            <p><a href="<?php echo get_permalink(2801); ?>"><img src="<?php bloginfo('template_url'); ?>/images/backyard-life/thm-backyard-idea.jpg" border="0" alt="How-To" width="195" height="109" /></a></p>
            <h2>Backyard Hot Tub Idea<br /><br /></h2>
            <p>How to take it easy, backyard hot tub style.<br /><br /></p>
        </div></li>
        <li><div>
            <h3><a href="<?php echo get_permalink(2806); ?>"><span class="icon dot dotGreen"></span>FAQs</a></h3>
            <p><a href="<?php echo get_permalink(2806); ?>"><img src="<?php bloginfo('template_url'); ?>/images/backyard-life/thm-faqs.jpg" border="0" alt="FAQs" width="195" height="109" /></a></p>
            <h2>Frequently Asked Q's</h2>
            <p>What informed buyers need to know about hot tubs.<br /><br /></p>
        </div></li>
        <li><div>
            <h3><a href="<?php echo get_permalink(3453); ?>"><span class="icon dot dotOrange"></span>How-To</a></h3>
            <p><a href="<?php echo get_permalink(3453); ?>"><img src="<?php bloginfo('template_url'); ?>/images/backyard-life/thm-outdoor-rooms.jpg" border="0" alt="How-To" width="195" height="109" /></a></p>
            <h2>Outdoor Rooms</h2>
            <p>Improve your property. Include an "outdoor room" addition in your hot tub plan.</p>
        </div></li>
        <li><div>
            <h3><a href="<?php echo get_permalink(3463); ?>"><span class="icon dot dotBlue"></span>Planning</a></h3>
            <p><a href="<?php echo get_permalink(3463); ?>"><img src="<?php bloginfo('template_url'); ?>/images/backyard-life/thm-predel.jpg" border="0" alt="Planning" width="195" height="109" /></a></p>
            <h2>Pre-Delivery Guide</h2>
            <p>Takes the mystery out of hot tub delivery. Download the PDF today.</p>
        </div></li>
    </ul>
</div>
<div class="page">
<div class="entry-content">
<div class="horzBorder"></div>
<?php the_content(); ?>
</div>
</div>
</div>
<?php
endwhile;
get_sidebar('generic');
?>
</div>
<br class="clear" />
<?php get_footer(); ?>

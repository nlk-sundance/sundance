<?php
/**
 * Template Name: Backyard Planning
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
<div class="col w240 leftSide">
<div class="inner">
<?php the_content(); ?>
</div>
        </div>
        <div class="col w480 last">
            <div class="inner">
                <h2>5 key points in planning for your hot tub installation.</h2>
                <p><span class="number">1</span><strong>Consider your family's needs and desires.</strong> Do you entertain? Love family barbeques? Enjoy reading or quiet meditation? The answers to these questions will help you determine the type of setting you want for your hot tub, and which Sundance model is best for you.</p>
                <p><span class="number">2</span><strong>Is your spa easy to get to from the house?</strong> The closer it is, the more you'll enjoy it. Consider a patio or deck just outside the home for spa installation.</p>
                <p><img src="<?php bloginfo('template_url'); ?>/images/backyard-life/privacy.jpg" class="alignright" /><span class="number">3</span><strong>Think about privacy</strong> when deciding where to place your Sundance hot tub. Check the views into your yard. Tall shrubs or plants can form an enclosure for your hot tub installation. Sundance Spas' SunStrong panels can add privacy and enhance the cozy atmosphere inside your spa.</p>
                <p><span class="number">4</span><strong>Identify sun and shade spots as well as wind directions.</strong> Place your Sundance where you can use it comfortably throughout the year.</p>
                <p><span class="number">5</span><strong>How's the view from your Sundance?</strong> Is there attractive landscaping or a sunset view? Need to keep an eye on the children as you soak? Consider the line of sight from your spa.</p>

            </div>
            <div class="horzBorder"></div>
            <div class="col w320">
                <div class="inner"
>                        	<h2>Drainage</h2>
                    <p>When it rains, which way does the water flow? Water should flow away from your house and other structures; it should not stand or puddle near your spa.</p>
                    <p>Will you need extra grading or drains to ensure water doesn't settle where your spa is going to be installed? If you have an existing drainage system, make sure your spa's drainage is connected to your existing system.</p>
                    <h2>Hot tub electrical installation</h2>
                    <p>The electrical source should be nearby to provide power for the spa's pump, motor, and filter system.</p>
                    <p>Electrical power from the main panel of your house may also provide for your irrigation controller and other electrical needs, such as lighting. An electrician can install an extra outlet or hard-wire additional controllers. Be sure your electrician consults the Sundance Spas Owner's Manual for your particular model before doing electrical work.</p>
                    <h2>External equipment</h2>
                    <p>If you are installing a spa "shell" with separate external equipment, instead of a self-contained spa with built-in equipment, the equipment (pump, motor, filter, and heater) should be out of direct view, with adequate room for maintenance.</p>
                </div>
            </div>
            <div class="col w160 last">
                <div class="delivery">
                    <h3>Delivery day is exciting</h3>
                    <p>To plan for your hot tub installation, talk to your Sundance Spas dealer. Our dealers do everything they can to make your hot tub spa installation fast, easy and trouble-free.</p>
                    <p><img src="<?php bloginfo('template_url'); ?>/images/backyard-life/delivery.jpg" /></p>
                    <p><a href="../pre-delivery-guide/" class="button">Pre-delivery Guide</a></p>
                    <br class="clear" />
                </div>
                <div class="faq">
                    <h2>FAQs</h2>
                    <p>Our "Informed Buyer" FAQs anticipate many of your questions about spas. The answers will help you evaluate spa features and determine which ones provide the best value for you.</p>
                    <p><a href="../faqs/" class="button">FAQs</a></p>
                </div>
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

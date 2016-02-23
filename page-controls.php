<?php
/**
 * Template Name: Features > Controls
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
<div class="col w240 leftMenu">
<div class="inner">
<div class="description">
<?php the_content(''); ?>
</div>
</div>
</div>
<div class="col w480 last smallTabs jetsSelect">
<ul class="tabs" id="spatabs"><li class="current"><a href="#controls-880">880 Controls</a></li><li><a href="#controls-780">780 Controls</a></li><li><a href="#controls-680">680 Controls</a></li><li></li></ul>
<div class="tab current" id="controls-880">
    <ul class="grid col2">
            <li><div>
           <img src="<?php bloginfo('template_url'); ?>/images/features/control_new.jpg" border="0" />
            <h3>i-Touch Controls</h3>
            <ul>
            
			<li>Fully programmable i-Touch control panel</li>
			<li>Digital touchpad control of all functions</li>
			<li>Backlit LCD display </li>
			<li>Advanced microprocessors check all functions 60 times per second</li>
			<li>Display inverts to read from inside or outside the spa</li>
            <li>Available on Majesta, Altamar, Marin, and Capri models.</li>
			
            </ul>
        </div></li>

        <li><div>
            <img src="<?php bloginfo('template_url'); ?>/images/features/waterfall_new.jpg" border="0" />
            <h3>AquaTerrace Waterfall Dial</h3>
            <ul>
           	
			<li>Vary stream from full waterfall to gentle cascade with control dial </li>
			<li>Works independently of jets</li> 
			<li>Features adjustable colored backlighting</li>
			
            </ul>
        </div></li>
    </ul>
    <br class="clear" />
</div>
<div class="tab" id="controls-780" style="display:none">
	<ul class="grid col2">
        <li class="doubwide"><div>
            <img src="<?php bloginfo('template_url'); ?>/images/features/Controls780-109.jpg" border="0" />
            <h3>i-Touch Controls</h3>
            <ul>
            
            <li>Easy-to-read LED display </li>
            <li>Digital touchpad control of all functions </li>
            <li>Choose from preprogrammed temperature and filtration cycles</li>
            
            </ul>
        </div></li>
    </ul>
    <br class="clear" />
</div>
<div class="tab" id="controls-680" style="display:none">
	<ul class="grid col2">
        <li class="doubwide"><div>
            <img src="<?php bloginfo('template_url'); ?>/images/features/Controls680-09.jpg" border="0" />
            <h3>Programmable Controls</h3>
            <ul>
                
            <li>Digital LED touchpad panel </li>
            <li>Choose from preprogrammed temperature and filtration cycles</li>
            <li>Operate multicolor in-spa LED light option  </li>
            <li>One-pump or two-pump control panel, depending on spa model  </li>
		
            </ul>
        </div></li>
    </ul>
    <br class="clear" />
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

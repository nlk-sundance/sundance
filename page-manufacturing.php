<?php
/**
 * Template Name: Difference > Manufacturing
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
<?php the_content(); ?>
</div>
</div>
</div>
<div class="col w480 last">
<div class="acc img"><img src="<?php bloginfo('template_url'); ?>/images/difference/manufacturing-1.jpg" alt="" class="alignleft" />
  <h3>Streamlined production process</h3>
  <ul>
    <li>Dedicated and experienced design and manufacturing staff</li>
    <li>State-of-the-art facility</li>
    <li>Two, quarter-mile long assembly lines with computer-controlled equipment</li>
    <li> Over 100 quality-assurance checkpoints</li>
    <li>Every spa is filled with water twice to &nbsp;test<br /> 
  systems</li>
</ul>
</div>
<div class="acc img"><img src="<?php bloginfo('template_url'); ?>/images/difference/manufacturing-3.jpg" alt="" class="alignleft" />
  <h3>Sundance Spas Complies with Virginia Graeme Baker Pool and Spa Safety Act (VGB)</h3>
<ul>
<li>The Virginia Graeme Baker Pool and Spa Safety Act (VGB) promotes the safe use of pools, spas and <a href="http://www.sundancespas.com/" title="Hot Tubs">hot tubs</a> in the USA through enhanced safety standards. We are pleased to let you know that Sundance spas manufactured for the U.S. market after December 19, 2008 are in compliance with the Act, the first day that the Act was in effect.</li>
<li>Safety is a top priority for Sundance Spas, and our products have an outstanding safety record.</li>
<li>Additional information about the Act can be found on the <a href="http://www.apsp.org" target="_blank">Association of Pool &amp; Spa Professionals (APSP) website</a> and on the <a href="http://www.cpsc.gov/" target="_blank">CPSC website</a>.</li>
</ul>
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

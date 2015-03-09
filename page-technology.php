<?php
/**
 * Template Name: Difference > Technology
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
<div class="acc img"><img src="<?php bloginfo('template_url'); ?>/images/difference/tech-1_09.jpg" alt="" class="alignleft" />
  <h3>Easy Access Control Panels </h3>
  <ul>
    <li>Digital read-out of actual water temperature.</li>
    <li>Push button operation.</li>
    <li>Flip the digital display to read from inside or outside the spa (880, 850 and 850E Series).   </li>
  </ul>
  </div>
<div class="acc img"><img src="<?php bloginfo('template_url'); ?>/images/difference/tech-2.jpg" alt="" class="alignleft" />
  <h3>Pure Aromatherapy</h3>
  <ul>
    <li>Sundance Spas' SunScents&trade; infuses the water with air-driven fragrance. </li>
  </ul>
  </div>
<div class="acc img"><img src="<?php bloginfo('template_url'); ?>/images/difference/tech-3.jpg" alt="" class="alignleft" />
  <h3>Innovative Therapy Seat / Versatile Jets </h3>
  <ul>
    <li><strong>Innovative Therapy Seat </strong><br />
      Jets target between 10 to 14 acupressure points.</li>
    <li><strong>Versatile Jets</strong><br />
      Combine jets for specific massage types, from bold to gentle.     </li>
  </ul>
  </div>
<div class="acc img"><img src="<?php bloginfo('template_url'); ?>/images/difference/tech-4.jpg" alt="" class="alignleft" />
  <h3>Secure Harness Plumbing </h3>
  <ul>
    <li>Allow precise clustering of jets, equalizes water pressure to each jet, enhances pumping efficiently and enables more secure fittings.  </li>
  </ul>
  </div>
<div class="acc img"><img src="<?php bloginfo('template_url'); ?>/images/difference/tech-5.jpg" alt="" class="alignleft" />
  <h3>Full-Foam Insulation </h3>
  <ul>
    <li>Overall foam conserves heat and supports spa plumbing. High-density, heat-resistant foam surrounds the equipment bay; lighter foam insulates elsewhere. </li>
  </ul>
  </div>
<div class="acc img"><img src="<?php bloginfo('template_url'); ?>/images/difference/tech-6.jpg" alt="" class="alignleft" />
  <h3>High-Quality Cabinetry </h3>
  <ul>
    <li>SunStrong&trade; UV-resistant synthetic cabinetry is durable and maintenance-free.</li>
    <li>Comes in four colors: Coastal, Mahogany, Autumn Walnut, and TerraStone (availability varies by model). </li>
  </ul>
  </div>
<div class="acc img"><img src="<?php bloginfo('template_url'); ?>/images/difference/tech-7.jpg" alt="" class="alignleft" />
  <h3>MicroClean System  </h3>
  <ul>
    <li>(880, 850 and 850E Series spas) <br />
      Filters from two directions; spa's main pump pulls water through pleated filter and 24-hour circulation pump pulls water through microfiber element.</li>
    <li>Produces an unprecedented level of water purity.</li>
    <li>FDA-compliant and approved by the National Sanitization Foundation.</li>
  </ul>
  </div>
<div class="acc img"><img src="<?php bloginfo('template_url'); ?>/images/difference/tech-8.jpg" alt="" class="alignleft" />
  <h3>Powerful Pumps  </h3>
  <ul>
    <li>Single, dual or triple pumps provide a generous amount of massage power.</li>
    <li>Filtration mode quietly and efficiently offers continuous filtration and ozone production.</li>
  </ul>
  </div>
  <div class="acc img"><img src="<?php bloginfo('template_url'); ?>/images/difference/tech-9.jpg" alt="" class="alignleft" />
  <h3>Efficient Sentry Smart&trade; Heater</h3>
  <ul>
    <li>(on select models)<br /> 
      Titanium coil directly heats water, increasing thermal efficiency and reducing costs.</li>
    <li>Titanium prohibits heater corrosion and failure.</li>
    <li>Five-year unconditional warranty. </li>
  </ul>
  </div>
  <div class="acc img"><img src="<?php bloginfo('template_url'); ?>/images/difference/tech-10.jpg" alt="" class="alignleft" />
  <h3>Reliable System Controls</h3>
  <ul>
    <li>A Sundance exclusive.</li>
    <li>Customize water/air pressure, select jets/direction.  Program filtration and temperature.</li>
	<li>Microprocessors check functions and regulate temperature for energy efficiency.</li>
  </ul>
  </div>
  <div class="acc">
  <h3>Protective ABS Barrier</h3>
  <ul>
    <li>Provides a moisture barrier. </li>
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

<?php
/**
 * Template Name : Difference > Water Quality
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
<div class="col w240 leftMenu normul">
<div class="inner">
<div class="description">
<?php the_content(); ?>
</div>
</div>
</div>
<div class="col w480 last">
<div class="acc img"><img src="<?php bloginfo('template_url'); ?>/images/difference/water-quality-1.jpg" alt="" class="alignleft" />
  <h3>Triple-Layer Filter Material (MicroClean Plus)</h3>
  <ul>
    <li>Multi-fiber outer layer is coated with acrylic resin for strength; retains large particles</li>
    <li>Polypropylene middle layer provides microscopic filtration</li>
    <li>Polyester inner layer provides additional filtering<br />
    </li>
  </ul>
  </div>
  <div class="acc img"><img src="<?php bloginfo('template_url'); ?>/images/difference/microclean_ultra2.jpg" alt="" class="alignleft" />
  <h3>MicroClean Ultra Filtration (880 Series)</h3>
  <ul>
    <li>The 2 piece interlocking system has an advanced dual stage filtration that is the ultimate in <i class="normalize">hot tub water treatment</i>.</li>
    <li>The outer filter is washable and acts as the first stage of filtration that traps larger particles</li>
    <li>Made from 100% meltblown polypropylene; these variable density filters are compatible with a wide range of chemicals</li>
    <li>Variable density construction uses the entire depth of the filter to trap sediment and debris and results in higher dirt holding capacity</li>
  </ul>
  </div>
<div class="acc img"><img src="<?php bloginfo('template_url'); ?>/images/difference/water-quality-2.jpg" alt="" class="alignleft" />
  <h3>MicroClean Plus Filtration (880 Series)</h3>
  <ul>
    <li>Most robust and efficient water filtration available</li>
    <li>Advanced, triple-layer pleated material filters water more thoroughly in less time </li>
    <li>Traps 99% of all particles, large and small</li>
    <li>Filters thoroughly from both ends</li>
    <li>Removes oils and lotions from the water</li>
  <li>Lasts longer; lower replacement costs</li>
  <li>No leaching of chemicals from pure fiber filter</li>
  </ul>
  </div>
<div class="acc">
  <h3>Dynamic Flow Circulation Pump (880 Series)</h3>
  <ul>
    <li>12-hour filtration effectively moves up to 50,000 gallons of water per day, at roughly same cost as other systems</li>
    <li>Pump works with the MicroClean and CLEAR<strong>RAY</strong>&trade; Water Purification System to optimize filtration</li>
    <li>Water is drawn over the Slipstream&trade; skimmer to remove impurities and debris</li>
    </ul>
</div>
<div class="acc img"><img src="<?php bloginfo('template_url'); ?>/images/difference/filter2.jpg" alt="" class="alignleft" />
  <h3>MicroClean Filtration (780 Series)</h3>
  <ul>
    <li>Filters thoroughly from two directions through pleated and microfiber materials</li>
    <li>Microfiber cartridge for ultra-fine filtration</li>
    <li>Pleated cartridge captures larger particles</li>
    </ul>
  </div>
<div class="acc">
  <p></p>
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

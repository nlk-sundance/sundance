<?php
/**
 * Template Name: Features > Purifiers
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
<div class="col w480 last">
<table summary="Spa Purifier">
    <tbody><tr>
      <td width="90" style="vertical-align:top"><img src="<?php bloginfo('template_url'); ?>/images/features/SunZone1.jpg"><br>
          <br>
        <br>
          <img src="<?php bloginfo('template_url'); ?>/images/features/SunZone2.jpg"></td>
      <td width="350" style="vertical-align:top"><p><strong>SunZone&trade; CD System </strong></p>
          <p>The SunZone CD options on system-ready models utilize ozone technology to effectively remove water impurities and micro-organisms. Reduced chemical usage and less maintenance are the two most significant benefits you get from using the SunZone CD system, found only on Sundance spas.</p>
        <p><br>
          The units use a highly efficient corona discharge (CD) element to maximize ozone production and provide clear water, surpassing the capabilities of UV-ozone options. Ozone maintains the water balance longer; so the spa will not need to be drained, refilled, and reheated as often. SunZone CD is standard on export models. </p>
        </td>
    </tr>
	<tr>
      <td style="vertical-align:top"><img alt="" src="<?php bloginfo('template_url'); ?>/images/features/SunPurity2.jpg"> </td>
      <td style="vertical-align:top"><p><strong>SunPurity&trade; Mineral Spa Purifier </strong></p>
          <p>A natural, effective alternative to chlorine and bromine, using organic minerals. Drop into the filter area to enhance water filtration and sanitizing in every cycle. The SunPurity mineral spa purifier is not available in all countries, <a title="Hot Tub Dealers" href="/hot-tub-dealer-locator/">see your dealer</a> for details. </p></td>
    </tr>
     
  </tbody></table>
</div>
</div>
<?php
endwhile;
get_sidebar('generic');
?>
</div>
<br class="clear" />
<?php get_footer(); ?>

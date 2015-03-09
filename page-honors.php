<?php
/**
 * Template Name : Difference > Honors
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
<div class="acc img"><img src="<?php bloginfo('template_url'); ?>/images/difference/BestBuy2007.jpg" alt="" class="alignleft" />
  <h3>2011 Consumers Digest &quot;Best Buy&quot; Rating </h3>
  <p>Once again the <a href="<?php echo get_permalink(2134); ?>">Sundance Cameo</a> spa was named a &ldquo;Best Buy&rdquo; by Consumers  Digest magazine in the Premium spa class. This marks the third time that the  model was recognized for its high quality and outstanding value. In  2003, the Sundance Solo was named a &ldquo;Best Buy&rdquo; in the Economy category  and in 1998 the Sundance Optima was honored as a &ldquo;Best Buy&rdquo; in the  Premium Category.</p>
</div>
<div class="acc img"><img src="<?php bloginfo('template_url'); ?>/images/difference/Apple.gif" alt="" class="alignleft" />
  <h3>Apple Performance Standards Certified </h3>
  <p>Sundance Spas products are designed specifically to work with iPod and  are certified by the developer to meet Apple performance standards.</p>
</div>

<div class="acc img"><img src="<?php bloginfo('template_url'); ?>/images/difference/Spasearch.gif" alt="" class="alignleft" />
  <h3>2007 Certified Spasearch.org </h3>
  <p>Sundance Spas was recently recognized as a Spasearch.org &ldquo;Certified&rdquo;  Company, based on independently audited consumer satisfaction surveys  in the U.S. and U.K.</p>
</div>
<div class="acc img"><img src="<?php bloginfo('template_url'); ?>/images/difference/NSF.gif" alt="" class="alignleft" />
  <h3>FDA-Compliant and NSF  Approved Filter</h3>
  <p>The MicroClean filter cartridge is the first FDA-compliant and NSF-certified filter within the hot tub industry.  Exclusively manufactured by Filter Specialists Inc. for Sundance Spas.</p>
</div>
<div class="acc img"><img src="<?php bloginfo('template_url'); ?>/images/difference/APSP.gif" alt="" class="alignleft" />
  <h3>Technical Excellence</h3>
  <p>The Association of Pool &amp; Spa Professionals has honored Sundance  Spas with its prestigious John Holcomb Silver Award for technical  contributions through innovation in the advancement of the hot water  industry.</p>
</div>
<div class="acc img"><img src="<?php bloginfo('template_url'); ?>/images/difference/BestOfClass2007.gif" alt="" class="alignleft" />
  <h3>Sundance Spas wins 2007 Best of Class Award</h3>
  <p>Sundance Spas was recently awarded a 2007 Best of Class award from  poolandspa.com. This award was presented to our company in recognition  of our exceptional commitment to product quality and our overall  customer satisfaction rating in the pool and spa industry.</p>
</div>
<div class="acc">
  <h3>Visionary Achievement Award</h3>
  <p>In 2001, the Mazzei&reg; Injector Corporation (MIC) presented Sundance Spas  with the Visionary Achievement Award for the advancement of ozone  technology for use in portable spas.</p>
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

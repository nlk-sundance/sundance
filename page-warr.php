<?php
/**
 * Template Name: Difference > Warranty
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
<h2>Warranty Documents</h2>
<ul>
<li><a href="/Communications/SD_13_Select_Domestic_Warranty_rev.pdf">Select Series Warranty</a></li>
<li><a href="/Communications/SD_13_Select_Export_Warranty_rev3.pdf">Select Series International Warranty</a></li>
<li><a href="/Communications/SD_13_880_Domestic_Warranty_rev.pdf">880 Series Warranty</a></li>
<li><a href="/Communications/SD_13_780_880_Export_Warranty_rev2.pdf">880 Series International Warranty</a></li>
<li><a href="/Communications/SD_13_780_Domestic_Warranty_rev.pdf">780 Series Warranty</a></li>
<li><a href="/Communications/SD_13_780_880_Export_Warranty_rev2.pdf">780 Series International Warranty</a></li>
<li><a href="/Communications/SD_13_680_Domestic_Warranty.pdf">680 Series Warranty</a></li>
<li><a href="/Communications/SD_13_680_Export_Warranty_rev.pdf">680 Series International Warranty</a></li>
</ul>
<p>You need Acrobat Reader to view PDF files. <a href="http://www.adobe.com/products/acrobat/readstep2.html" target="_blank">Download Acrobat Reader from adobe.com</a><br />
<br /><a href="http://www.adobe.com/products/acrobat/readstep2.html" target="_blank"><img src="<?php bloginfo('template_url'); ?>/images/difference/adobe.gif" alt="PDF" /></a></p>
</div>
</div>
</div>
<div class="col w480 last"><br />
<?php the_content(); ?>
</div>
</div>
<?php
endwhile;
get_sidebar('generic');
?>
</div>
<br class="clear" />
<?php get_footer(); ?>

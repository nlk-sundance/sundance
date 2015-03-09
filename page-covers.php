<?php
/**
 * Template Name: Features > Covers
 *
 * @package Sundance
 * @subpackage Sundance
 * @since Sundance 2.0
 */
$s_colors = get_posts(array(
	'numberposts' => -1,
	'post_type' => 's_color',
	'orderby' => 'menu_order',
	'order' => 'ASC',
));
$s_cabs = get_posts(array(
	'numberposts' => -1,
	'post_type' => 's_cab',
	'orderby' => 'menu_order',
	'order' => 'ASC',
));
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
<div class="col w480 last smallTabs"><div class="tab">
<h2>Vinyl and Sunbrella Covers</h2>
<ul class="grid col2">
<li><h3><img src="<?php bloginfo('template_url'); ?>/images/covers/Coastal.jpg" alt="" border="0" /><br />Coastal Spa Cover</h3></li>
<li><h3><img src="<?php bloginfo('template_url'); ?>/images/covers/Duetto.jpg" alt="" border="0" /><br />Duetto Spa Cover with Mahogany</h3></li>
<li><h3><img src="<?php bloginfo('template_url'); ?>/images/covers/Terrastone.jpg" alt="" border="0" /><br />Coastal Spa Cover with TerraStone</h3></li>
<li><h3><img src="<?php bloginfo('template_url'); ?>/images/covers/sunstrong.jpg" alt="" border="0" /><br />SunStrong&trade; Spa Cover with Autumn Walnut </h3></li>
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

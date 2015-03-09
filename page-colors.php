<?php
/**
 * Template Name: Features > Colors
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
<div class="col w480 last"><br />
<h2>Shell Colors</h2>
<ul class="grid accs">
<?php
foreach ( $s_colors as $i => $c ) {
	$c3 = ( $i%3 == 2 ) ? ' class="third"' : '';
	echo '<li'. $c3 .'><h3><a>'. get_the_post_thumbnail($c->ID, 'swatch-large') .'<br />'. esc_attr($c->post_title) .'</a></h3></li>';
}
?>
</ul>
<br />
<h2>Cabinet Colors</h2>
<ul class="grid accs">
<?php
foreach ( $s_cabs as $i => $c ) {
	$c3 = ( $i%3 == 2 ) ? ' class="third"' : '';
	echo '<li'. $c3 .'><h3><a>'. get_the_post_thumbnail($c->ID, 'swatch-large') .'<br />'. esc_attr($c->post_title) .'</a></h3></li>';
}
?>
</ul>
</div>
</div>
<?php
endwhile;
get_sidebar('generic');
?>
</div>
<br class="clear" />
<?php get_footer(); ?>

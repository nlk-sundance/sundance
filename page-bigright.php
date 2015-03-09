<?php
/**
 * Template Name: BigRight
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
<?php
$c = apply_filters('the_content', $post->post_content);
$moreat = strpos($c, '<!--more-->');
$firsthalf = substr($c, 0, $moreat);
$lasthalf = substr($c, $moreat);
echo '<div class="col w240 leftSide"><div class="inner">';
echo $firsthalf;
echo '</div></div>';
echo '<div class="col w480 leftSide last"><div class="inner">';
echo $lasthalf;
echo '</div></div>';
?>
</div>
<?php
endwhile;
get_sidebar('generic');
?>
</div>
<br class="clear" />
<?php get_footer(); ?>

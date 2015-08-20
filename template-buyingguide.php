<?php
/**
 * Template Name: Buyer's Guide - Hot Tub Buying Guide 
 *
 * @package Sundance
 * @subpackage Sundance
 * @since Sundance 2.0
 */

get_header(); ?>
<style>
	.bd
	{
		margin-top: 96px;
	}
</style>
<div class="cols">
	<div class="main col w960">
		<div class="main-title fullwidth-title">
			<?php the_post_thumbnail('banner-full'); ?>
			<h1>SPAS BUYER'S GUIDE</h1>
		</div>
	</div>
</div>
<div class="cols">
	<?php while ( have_posts() ) : the_post(); ?>
	<div class="main buyersguidemain col w730">
		<div class="hero-content">
			<h2><?php sundance_pagetitle(); ?></h2>
			<?php the_field('hero_content'); ?>
		</div>
		<div class="page">
			<div class="entry-content">
				<?php the_content(); ?>
			</div>
		</div>
		<div class="bottomimageholder">
			<?php the_field('below_page_content'); ?>
		</div>
		<div class="bottomnavigation">
			<?php the_field('bottom_navigation'); ?>
			<div class="clear"></div>
		</div>
	</div>
	<?php
	endwhile;
	
	get_sidebar('buyerguide');
	?>
	
</div>

<br class="clear" />
<?php get_footer(); ?>

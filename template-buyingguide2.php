<?php
/**
 * Template Name: Buyer's Guide - Dealer Checklist
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
	
	@media print {
	  body * {
	    visibility: hidden;
	  }
	  #section-to-print, #section-to-print * {
	    visibility: visible;
	  }
	  #section-to-print {
	    position:absolute;
	    left: 0;
	    top: 0;
	  }
	  
	  
	  ul li
	  {
	  	list-style: inside !important;
	  	padding-left: 0px;
	  }
	}
	

</style>
<div class="cols">
	<div class="main col w960">
		<div class="main-title fullwidth-title">
			<?php the_post_thumbnail('banner-full'); ?>
			<h1>SPA BUYER'S GUIDE</h1>
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
		<div class="bottomnavigation" style="margin-bottom: 0px;">
			<?php the_field('bottom_navigation'); ?>
			<div class="clear"></div>
		</div>
		<div class="page">
			<div class="entry-content" id="section-to-print">
				<?php the_content(); ?>
			</div>
		</div>
	</div>
	<?php
	endwhile;
	
	get_sidebar('buyerguide');
	?>
	
</div>

<br class="clear" />
<?php get_footer(); ?>

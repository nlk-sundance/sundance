<?php
/**
 * Template Name: Desktop Color Selector 
 *
 * @package Sundance
 * @subpackage Sundance
 * @since Sundance 2.0
 */

get_header(); ?>

<div class="cols">
	<?php while ( have_posts() ) : the_post(); ?>
	<div class="main col w760">
		<div class="main-title">
			<?php the_post_thumbnail( array(729, 320) ); ?>
			<h1><?php sundance_pagetitle(); ?></h1>
		</div>
	</div>
	<?php
	endwhile;
	?>
	<?php get_sidebar('colorselector'); ?>
	<br class="clear" />
	<div class="main col w960 color-container-new">
		<div class="page">
			<div class="entry-content">
				<?php get_template_part( 'block', 'color_selector' );  ?>
			</div>
		</div>
	</div>
</div>

<br class="clear" />
<?php get_footer(); ?>

<?php
/**
 * Template Name: Startup Guide
 *
 * @package Sundance
 * @subpackage Sundance
 * @since Sundance 2.0
 */

get_header(); ?>

<?php while ( have_posts() ) : the_post(); ?>

<div class="hero full-width">
	<?php the_post_thumbnail( 'original' ); ?>
</div>
<div class="cols sunsmart">
	
	<div class="main col w960">

				<?php get_template_part('startupguide','landing'); ?>
	</div>

</div>

<?php endwhile; ?>

<br class="clear" />
<?php get_footer(); ?>

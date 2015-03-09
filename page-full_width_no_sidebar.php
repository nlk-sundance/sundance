<?php
/**
 * Template Name: Full Width - No sidebar
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

				<?php the_content(); ?>
	
	</div>

</div>

<?php endwhile; ?>

<br class="clear" />
<?php get_footer(); ?>

<?php
/**
 * Page template file.
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
			<?php the_post_thumbnail( array(729, 320) ); ?>
			<h1><?php sundance_pagetitle(); ?></h1>
		</div>
		<div class="page">
			<div class="entry-content">
				<?php the_content(); ?>
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

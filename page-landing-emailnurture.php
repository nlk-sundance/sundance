<?php
/**
 * Template Name: Landing Page - Email Nurture
 *
 * @package Sundance
 * @subpackage Sundance
 * @since Sundance 2.0
 */

$meta = get_post_meta( get_the_ID() );

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
				<div class="custom-sidebar">
						<?php echo apply_filters('the_content', $meta['_sundance_sidebar_metabox_value_key'][0]); ?>
				</div>
			</div>
		</div>

		<div class="col w480 last">
			<div class="page page-narrow">
				<div class="entry-content">
					<?php the_content(); ?>
				</div>
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

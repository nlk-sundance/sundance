<?php
/**
 * Template Name: SunSmart - Full Width Hero
 *
 * @package Sundance
 * @subpackage Sundance
 * @since Sundance 2.0
 */

get_header(); ?>

<?php while ( have_posts() ) : the_post(); ?>

<div class="hero full-width">
	<?php the_post_thumbnail( 'original' ); ?>
	<?php echo do_shortcode('[video_lightbox_youtube video_id="T3wwfmc9qQM" width="640" height="360" anchor="'.get_template_directory_uri().'/images/icons/ss-video-works.png" class="sunsmart-video-top-right" title="sunsmart-works"]'); ?>
	<?php echo do_shortcode('[video_lightbox_youtube video_id="UwglBK3FcfU" width="640" height="360" anchor="'.get_template_directory_uri().'/images/icons/ss-video-network.png" class="sunsmart-video-top-left" title="sunsmart-network"]'); ?>
</div>
<div class="cols sunsmart">
	
	<div class="main col w730">
		<div class="page">
			<div class="entry-content">
				<?php the_content(); ?>
			</div>
		</div>
	</div>

	<?php get_sidebar('sunsmart'); ?>
	
</div>

<?php endwhile; ?>

<br class="clear" />
<?php get_footer(); ?>

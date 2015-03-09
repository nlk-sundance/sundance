<?php
/**
 * Template Name: Accessories
 *
 * @package Sundance
 * @subpackage Sundance
 * @since Sundance 2.0
 */

get_header(); ?>

<div class="cols topmen">
	<?php while ( have_posts() ) : the_post(); ?>
	<div class="main col w730">
		<div class="main-title">
			<?php the_post_thumbnail(); ?>
			<h1><?php the_title(''); ?></h1>
			<div class="fancy-button" goto="vidmodal" rel="//www.youtube-nocookie.com/embed/w7_w8jR0L1k?rel=0">VIDEO: CLEARRAY® Water Purification</div>
		</div>
		<div class="col w240 leftMenu">
			<div class="inner">
				<ul>
					<?php
						echo sundance_acc_cats();
					?>
				</ul>
			<div class="share"><?php if(function_exists('sharethis_button')) sharethis_button(); ?></div>
				<div class="description">
					<?php the_content(''); ?>
				</div>
			</div>
		</div>
		<div class="col w480 last">
			<ul class="grid accs">
				<?php
					if ( false === ( $special_query_results = get_transient( 's_acc_cats_wimgs' ) ) ) {
						$acccats = apply_filters('taxonomy-images-get-terms', '', array('taxonomy'=>'s_acc_cat'));
						$r = array();
						foreach ( $acccats as $a ) {
							$img = wp_get_attachment_image( $a->image_id, 'accthm' );
							$r[$a->term_id] = array(
								'name' => str_replace('Spa Cleaners &amp; Water Purification Systems', 'Cleaners &amp; Ozone Systems', esc_attr($a->name)),
								'url' => get_term_link($a->slug, 's_acc_cat'),
								'img' => $img,
							);
						}
						ksort($r);
						$o = '';
						$i = 0;
						foreach ( $r as $a ) {
							$c3 = ( $i%3 == 2 ) ? ' class="third"' : '';
							$o .= '<li'. $c3 .'><h3><a href="'. esc_url($a['url']) .'">'. $a['img'] .'<br />'. esc_attr($a['name']) .'</a></h3></li>';
							$i++;
						}
						set_transient( 's_acc_cats_wimgs', $o, 60*60*12 );
					}
					$o = get_transient( 's_acc_cats_wimgs' );
					echo $o;
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

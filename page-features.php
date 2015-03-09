<?php
/**
 * Template Name: Features Landing
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
			<div class="fancy-button" goto="vidmodal" rel="//www.youtube-nocookie.com/embed/W5yyveeNWM0?rel=0">VIDEO: Sundance Spas - The Collection</div>
		</div>
		<div class="col w240 leftMenu">
			<div class="inner">
				<div class="description">
					<?php the_content(''); ?>
				</div>
			</div>
		</div>
		<div class="col w480 last">
			<ul class="grid accs">
				<?php
					//if ( false === ( $special_query_results = get_transient( 's_feats_wimgs' ) ) ) {
						$q = get_posts(array(
							'numberposts' => -1,
							'orderby' => 'menu_order',
							'order' => 'ASC',
							'post_type' => 'page',
							'post_parent' => 2414,
						));
						$o = '';
						foreach ( $q as $i => $p ) {
							$img = get_the_post_thumbnail( $p->ID, 'accthm' );
							$r[$a->term_id] = array(
								'name' => str_replace('Spa Cleaners &amp; Water Purification Systems', 'Cleaners &amp; Ozone Systems', esc_attr($a->name)),
								'url' => get_term_link($a->slug, 's_acc_cat'),
								'img' => $img,
							);
							$c3 = ( $i%3 == 2 ) ? ' class="third"' : '';
							$o .= '<li'. $c3 .'><h3><a href="'. get_permalink($p->ID) .'">'. $img .'<br />'. esc_attr($p->post_name) .'</a></h3></li>';
						}

						//set_transient( 's_feats_wimgs', $o, 60*60*12 );
					//}
					//$o = get_transient( 's_feats_wimgs' );
					echo $o;
				?>
				<li class="third">
					<h3>
						<a href="<?php echo get_bloginfo('url'); ?>/customer-care/sunsmart/">
							<img width="140" height="125" src="<?php echo get_template_directory_uri(); ?>/images/icons/SunSmart-140x125.png" class="attachment-accthm wp-post-image" alt="SunSmart App">
							<br>SUNSMART&trade;
						</a>
					</h3>
				</li>
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

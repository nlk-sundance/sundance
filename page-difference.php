<?php
/**
 * Template Name: Difference
 *
 * @package Sundance
 * @subpackage Sundance
 * @since Sundance 2.0
 */

get_header(); ?>
<div class="cols">
<?php while ( have_posts() ) : the_post();
global $post;
?>
<div class="main col w730">
<div class="main-title">
<?php the_post_thumbnail(); ?>
<h1><?php the_title(''); ?></h1>
</div>
<div class="col w240 leftMenu">
        <div class="inner">
            <div class="description">
				<?php the_content(); ?>
            </div>
        </div>
    </div>
    <div class="col w480 last">
        <ul class="grid">
        	<?php
			$transname = 's_difference_subpages';
			if ( false === ( $special_query_results = get_transient( $transname ) ) ) {
				$o = get_posts( array(
					'numberposts' => -1,
					'orderby' => 'menu_order',
					'order' => 'ASC',
					'post_type' => 'page',
					'post_parent' => $post->ID,
				));
				$htm = '';
				foreach ( $o as $i => $p ) {
					$c3 = ( $i%3 == 2 ) ? ' class="third"' : '';
					$htm .= '<li'. $c3 .'><h3><a href="'. get_permalink($p->ID) .'">'. get_the_post_thumbnail($p->ID, 'accthm') .'<br />'. esc_attr($p->post_title) .'</a></h3></li>';
				}
				set_transient( $transname, $htm, 60*60*12 );
			}
			$subpages = get_transient( $transname );
			echo $subpages;
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

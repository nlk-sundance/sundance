<?php
/**
 * Template Name: Features > Jets
 *
 * @package Sundance
 * @subpackage Sundance
 * @since Sundance 2.0
 */

// transient for s_alljets_bycat
delete_transient( 's_alljets_bycat' );

if ( false === ( $special_query_results = get_transient( 's_alljets_bycat' ) ) ) {
	$jetcats = get_terms('s_jet_ser', array('orderby'=>'term_order', 'order' => 'ASC'));
	foreach ( $jetcats as $i => $c ) {
		$jet_ids = get_objects_in_term($c->term_id, 's_jet_ser');
		$jets = array();
		foreach ( $jet_ids as $j ) {
			$jets[] = get_post($j);
		}
		$jetcats[$i]->jets = $jets;
	}
	set_transient( 's_alljets_bycat', $jetcats, 60*60*12 );
}
// Use the data like you would have normally...
$jetcats = get_transient( 's_alljets_bycat' );

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
<div class="description">
<?php the_content(''); ?>
</div>
</div>
</div>
<div class="col w480 last smallTabs jetsSelect">
<ul class="tabs" id="spatabs">
<?php foreach ( $jetcats as $i => $c ) echo '<li'. ($i==0 ? ' class="current"' : '') .'><a href="#'. esc_attr($c->slug) .'">'. esc_attr($c->name) .'</a></li>'; ?>
</ul>
<?php foreach ( $jetcats as $i => $c ) { ?>
<div class="tab<?php echo ( $i > 0 ? '" style="display:none' : ' current' ); ?>" id="<?php esc_attr_e($c->slug); ?>">
<?php foreach ( $c->jets as $k => $j ) {
	if ( $k%3 == 0 ) {
		if ( $k > 0 ) echo '</ul>';
		echo '<ul class="grid">';
	}
	?>
<li class="jet"><div>
<?php echo get_the_post_thumbnail($j->ID, 'swatch-large'); ?>
<h3><?php esc_attr_e($j->post_title); ?></h3>
<?php echo apply_filters('the_content', $j->post_content); ?>
</div></li>
<?php } ?>
</ul>
<br class="clear" />
</div>
<?php } ?>

</div>
</div>
<?php
endwhile;
get_sidebar('generic');
?>
</div>
<br class="clear" />
<?php get_footer(); ?>

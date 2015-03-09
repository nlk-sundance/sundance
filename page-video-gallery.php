<?php
/**
 * Template Name: Video Gallery
 *
 * @package Sundance
 * @subpackage Sundance
 * @since Sundance 2.0
 */
get_header();

if ( false === ( $special_query_results = get_transient( 's_allvids' ) ) ) {
	$vids = get_posts(array(
		'numberposts' => -1,
		'post_type' => 's_vid',
		'orderby' => 'menu_order',
		'order' => 'ASC',
	));
	$o = array(
		'f' => '',
		'v' => array(),
	);
	foreach ( $vids as $v ) {
		$custom = get_post_meta($v->ID,'s_info');
		$info = $custom[0];
		$url = esc_url($info['url']);
		$yt = substr( $url, strpos( $url, '?v=') + 3, 11);
		$dur = $info['dur'];
		$o['v'][] = array(
			'n' => $v->post_title,
			'url' => $url,
			'yt' => $yt,
			'dur' => $dur,
		);
		
		if ( $o['f'] == '' ) $o['f'] = $yt; 
	}
	set_transient( 's_allvids', $o, 60*60*12 );
}
// Use the data like you would have normally...
$vids = get_transient( 's_allvids' );
echo '<pre style="display:none">'. print_r($vids,true) .'</pre>';
?>
<div class="cols">
<div class="main col w730">
<div class="mainVideo"><iframe width="728" height="408" src="http://www.youtube.com/embed/<?php esc_attr_e($vids['f']) ?>?showinfo=0&rel=0&autohide=1&wmode=opaque" name="mainplayer" id="mainplayer" frameborder="0" allowfullscreen></iframe></div>
<?php while ( have_posts() ) : the_post(); ?>
<h1 class="title"><?php the_title(''); ?></h1>
<div class="post">
<div class="entry-content">
<?php the_content(); ?>
</div>
            <div class="share"><?php if(function_exists('sharethis_button')) sharethis_button(); ?></div>
        </div>
        <?php endwhile; ?>
    </div>
    <div class="side col w230 last">
        <div class="videoList">
            <ul class="thumbs">
            <?php foreach ($vids['v'] as $i => $v ) { ?>
                <li<?php if ($i==0) echo ' class="onn"'; ?>>
                    <div>
                        <p class="thumb"><a href="<?php echo esc_url($v['url']) ?>"><img src="http://i4.ytimg.com/vi/<?php esc_attr_e($v['yt']) ?>/default.jpg" width="57" height="37" alt="<?php esc_attr_e($v['n']) ?>" border="0" /></a></p>
                        <p class="title"><a href="<?php echo esc_url($v['url']) ?>"><strong><?php esc_attr_e($v['n']) ?></strong><br />Time : <?php esc_attr_e($v['dur']) ?></a></p>
                        <span class="e">http://www.youtube.com/embed/<?php esc_attr_e($v['yt']) ?></span>
                    </div>
                </li>
                <?php } ?>
            </ul>
        </div>
        <div class="rightCol">
            <?php get_sidebar('rt4'); ?>
        </div>
    </div>
</div>
<br class="clear" />
<?php get_footer(); ?>

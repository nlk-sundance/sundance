<?php
/**
 * @package Sundance
 * @subpackage Sundance
 * @since Sundance 2.0
 */
?>
<div class="slideshow">
<?php
if ( false === ( $special_query_results = get_transient( 'sundance_latest10' ) ) ) {
	$latest10 = get_posts(array('numberposts'=>10));
	$mainslide = '<div class="mainImage" id="mainslide">';
	$slides = array();
	foreach ( $latest10 as $i => $p ) {
		$args = array(
			'post_type' => 'attachment',
			'numberposts' => null,
			'post_status' => null,
			'post_parent' => $p->ID,
			'orderby' => 'menu_order',
			'order' => 'ASC',
		);
		$attachments = get_posts($args);
		$img_id = 0;
		if ($attachments) {
			$img_id = $attachments[0]->ID;
		}
		$mimg = '';
		$timg = '';
		if ( $img_id > 0 ) {
			$imgsrc = wp_get_attachment_image_src( $img_id, 'slide-large' );
			$imgsrc = $imgsrc[0];
			$mimg = '<img src="'. esc_url($imgsrc) .'" />';
			$imgsrc = wp_get_attachment_image_src( $img_id, 'blog-thm' );
			$imgsrc = $imgsrc[0];
			$timg = '<img src="'. esc_url($imgsrc) .'" />';
		}
		$nam = wp_kses($p->post_title, array());
		$tl = strlen($nam);
		if ( $tl >= 190 ) {
			$nam = substr($nam, 0, 190);
			$pc = '';
		} else {
			$left = 190 - $tl;
			$pc = wp_kses($p->post_content, array());
			$pc = str_replace('<!--more-->', '', $pc);
			if ( strlen($pc) > $left ) {
				$pc = substr($pc, 0, $left) .'...';
			}
		}
		
		$slides[] = array(
			'id' => $p->ID,
			'url' => get_permalink($p->ID),
			'name' => $nam,
			'content' => $pc,
			'mimg' => $mimg,
			'timg' => $timg,
		);
	}
	set_transient( 'sundance_latest10', $slides, 60*60*12 );
}
// Use the data like you would have normally...
$slides = get_transient( 'sundance_latest10' );
	?>
    <div class="mainImage" id="mainslide">
    <?php foreach ( $slides as $i => $s ) {
        echo '<div class="bslide'. ($i==0 ? ' on' : '') .'" id="s'. $s['id'] .'"><a href="'. esc_url($s['url']) .'">';
		echo $s['mimg'];
        echo '<span class="caption"><strong>'. esc_attr($s['name']) .'</strong> '. esc_attr($s['content']) .'</span>';
		echo '</a></div>';
    } ?>
    </div>
    <?php if ( is_single() ) { ?>
    <div class="controls">
        <ul id="bslidebtns">
        	<?php
			foreach ( $slides as $i => $s ) {
				echo '<li'. ($i==0 ? ' class="current"' : '') .'>';
				echo '<a href="#s'. esc_attr($s['id']) .'" class="icon dot dot'. ($i==0 ? 'Drk' : 'Lt') .'Gray"></a>';
				echo '</li>';
			}
			?>
        </ul>
    </div>
    <?php } else { ?>
    <div class="slider">
        <a href="#p" class="previous"></a>
        <a href="#n" class="next"></a>
        <div class="thumbWrap">
            <ul class="thumbs" id="bslidebtns">
        	<?php
			foreach ( $slides as $i => $s ) {
				echo '<li'. ($i==0 ? ' class="current"' : '') .'>';
				echo '<a href="#s'. esc_attr($s['id']) .'" class="dot'. ($i==0 ? 'Drk' : 'Lt') .'Gray">'. $s['timg'] .'</a>';
				echo '</li>';
			}
			?>
            </ul>
        </div>
    </div>
    <?php } ?>
</div>
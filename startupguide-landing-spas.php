<?php




global $tubcats;

$o = '';

?>

<div id="sg-series-container">

<?php

foreach ( $tubcats as $s ) {

    $o .= '<div class="sgseries series-'. strtolower(esc_attr($s['name'])) .'">';

        foreach ( $s['tubs'] as $t ) {

        	if ( !in_array( $t['id'], array(2151,2157,2159) ) ) {

                $o .= '<div id="'.$t['id'].'" class="sgspa spa-'.strtolower(esc_attr($t['name'])).'">';
                
                if (class_exists('MultiPostThumbnails')) {
                    $img = MultiPostThumbnails::get_the_post_thumbnail( 's_spa', 'overhead-large', $t['id'], 'overhead-large' );
                    $o .= $img;
                } else {
                    $o .= '<img src="'. get_bloginfo('template_url') .'/images/tubs/tub-thumb-100.jpg" />';
                }

                $o .= '<h6>' . esc_attr($t['name']) . '</h6>';

                $o .= '<a id="spa-'.$t['id'].'" class="get-spa" spaname="'.strtolower(esc_attr($t['name'])).'">View Spa</a>';

                $o .= '</div>';

            }

        }

        $o .= '<div class="clearfix"></div>';
		
		$o .= '</div>';
}

print $o;

?>

</div>
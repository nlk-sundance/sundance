<?php
/**
 * Footer Block - Bottom Menu (2nd Row)
 * This is part of the footer
 *
 * @package Sundance
 * @subpackage Sundance
 * @since Sundance 2.0
 */

wp_nav_menu( 
    array(
        'menu'       => 'footer2',
        'depth'      => 2,
        'container'  => false,
        'menu_class' => 'footer-links ft2',
        //'fallback_cb' => 'wp_page_menu',
        //Process nav menu using our custom nav walker
        //'walker' => new wp_bootstrap_navwalker()
    )
);

?>
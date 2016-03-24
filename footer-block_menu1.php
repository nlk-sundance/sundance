<?php
/**
 * Footer Block - Top Menu
 * This is part of the footer
 *
 * @package Sundance
 * @subpackage Sundance
 * @since Sundance 2.0
 */

wp_nav_menu( 
    array(
            'menu'       => 'footer1',
            'depth'      => 2,
            'container'  => false,
            'menu_class' => 'spa ft1',
            //'fallback_cb' => 'wp_page_menu',
            //Process nav menu using our custom nav walker
            //'walker' => new wp_bootstrap_navwalker()
)); 

?>
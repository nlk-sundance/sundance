<?php
/**
 * The Main menu <ul>
 *
 * @package Sundance
 * @subpackage Sundance
 * @since Sundance 2.0
 */
?>
<ul class="mainMenu">
    <li class="hover"><?php
        global $tubcats;
        if ( true ) { // false === ( $special_query_results = get_transient( 's_htdrop' ) ) ) {
            $o = '<a href="'. get_permalink(1894) .'">Hot Tubs</a><ul>';
            foreach ( $tubcats as $s ) {
                $o .= '<li><a href="'. esc_url($s['url']) .'">'. esc_attr($s['name']) .' Series&trade;</a>';
                $o .= '<ul>';
                foreach ( $s['tubs'] as $t ) {
                    $o .= '<li><div><a href="'. esc_url($t['url']) .'" class="navtub">';
                    if (class_exists('MultiPostThumbnails')) {
                        $img = MultiPostThumbnails::get_the_post_thumbnail('s_spa', 'overhead-small', $t['id'], 'overhead-small' );
                        $o .= $img;
                    } else {
                        $o .= '<img src="'. get_bloginfo('template_url') .'/images/tubs/tub-thumb-100.jpg" />';
                    }
                    $o .= '</a>';
                    $o .= '<h2><a href="'. esc_url($t['url']) .'">'. esc_attr($t['name']).'</a></h2>';
                    $o .= '<p><a href="'. esc_url($t['url']) .'">Seats '. esc_attr($t['seats']) .'</a></p>';
                    $o .= '</div></li>';
                }
                $o .= '</ul></li>';
            }
            $o .= '</ul>';
            $drop = $o; //get_transient( 's_htdrop' );
        }
        echo $drop; ?>
    </li>
    <li class="hover<?php
        if ( is_page() ) {
            global $post;
            if ( is_page(2414) || ( $post->post_parent == 2414 ) ) echo ' active';
        }
        ?>">
        <a href="<?php echo get_permalink(2414) ;?>">Features</a>
        <ul>
            <?php echo sundance_wplistpages_cache(2414, 's_feats_listpages'); ?>
        </ul>
    </li>
    <li class="hover<?php if ( is_page(2408) || ( get_post_type() == 's_acc' ) || is_tax('s_acc_cat') ) echo ' active'; ?>">
        <a href="<?php echo get_permalink(2408) ; ?>">Accessories</a>
        <ul>
            <?php echo sundance_acc_cats(); ?>
        </ul>
    </li>
    <li class="hover<?php
        if ( is_page() ) {
            global $post;
            if ( is_page(2447) || ( $post->post_parent == 2447 ) ) echo ' active';
        } ?>"><a href="<?php echo get_permalink(2447); ?>">Backyard Life</a>
        <ul class="backyard-ideas">
            <?php echo sundance_wplistpages_cache(2447, 's_backyard_listpages'); ?>
        </ul>
    </li>
    <li class="sitemap<?php if ( is_page(3137) ) echo ' active'; ?>"><a href="<?php echo get_permalink(3137); ?>">Site Map</a>
        <ul>
            <li>
                <div class="sitemapFlop">
                    <?php
                    if ( true ) { //false === ( $special_query_results = get_transient( 's_sitemap' ) ) ) {
                        $mm = '<div class="cols">';
                        $mm .= '<div class="col w140">';
                        $mm .= '<h2><a href="'. get_permalink(1894) .'">Hot Tubs</a></h2>';
                        $mm .= '<ul>';
                        $mm .= '<li><a href="'. get_permalink(1894) .'">All Hot Tubs</a></li>';
                        foreach ( $tubcats as $s ) {
                            $mm .= '<li><a href="'. esc_url($s['url']) .'">'. esc_attr($s['name']) .' Series&trade;</a>';
                        }
                        //$mm .= '<li><a href="#">Compare Hot Tubs</a></li>';
                        $mm .= '</ul>';
                        $mm .= '<h2><a href="'. get_permalink(2414) .'">Features</a></h2>';
                        $mm .= '<ul>';
                        $mm .= sundance_wplistpages_cache(2414, 's_feats_listpages');
                        $mm .= '</ul>';
                        $mm .= '</div><div class="col w140">';
                        $mm .= '<h2><a href="'. get_permalink(2408) .'">Accessories</a></h2>';
                        $mm .= '<ul>';
                        $mm .= sundance_acc_cats();
                        $mm .= '</ul>';
                        $mm .= '<h2><a href="'. get_permalink(2447) .'">Backyard Life</a></h2>';
                        $mm .= '<ul>';
                        $mm .= sundance_wplistpages_cache(2447, 's_backyard_listpages');
                        $mm .= '</ul>';
                        $mm .= '</div>';
                        $mm .= '<div class="col w140">';
                        $mm .= '<h2><a href="'. get_permalink(2811) .'">Health Benefits</a></h2>';
                        $mm .= '<ul>';
                        $mm .= '<li><a href="'. get_permalink(2823) .'">Hydrotherapy</a></li>';
                        $mm .= sundance_wplistpages_cache(2811, 's_healthben_listpages');
                        $mm .= '</ul>';
                        $mm .= '<h2><a href="'. get_permalink(2862) .'">Sundance Difference</a></h2>';
                        $mm .= '<ul>';
                        $mm .= sundance_wplistpages_cache(2862, 's_difference_listpages');
                        $mm .= '</ul>';
                        $mm .= '</div>';
                        $mm .= '<div class="col w140">';
                        $mm .= '<h2><a href="'. get_permalink(2406) .'">Resources</a></h2>';
                        $mm .= '<ul>';
                        $mm .= '<li><a href="'. get_permalink(2406) .'">About Us</a></li>';
                        $mm .= '<li><a href="'. get_permalink(2410) .'">History</a></li>';
                        $mm .= '<li><a href="'. get_permalink(2897) .'">Press Room</a></li>';
                        $mm .= '<li><a href="'. get_permalink(2959) .'">Become a Dealer</a></li>';
                        $mm .= '<li><a href="'. get_permalink(2965) .'">Contact</a></li>';
                        $mm .= '<li><a href="http://www.jacuzzi.com/careers/">Employment Opportunities</a></li>';
                        $mm .= '<li><a href="'. get_permalink(2972) .'">Owner Resources</a></li>';
                        $mm .= '<li><a href="'. get_permalink(1892) .'">Sundance Blog</a></li>';
                        $mm .= '<li><a href="'. get_permalink(2412) .'">Sundance Video</a></li>';
                        $mm .= '</ul>';
                        $mm .= '<ul class="list">';
                        $mm .= '<li><a href="/hot-tub-dealer-locator/">Locate a Dealer</a></li>';
                        $mm .= '<li><a href="'. get_permalink(2974) .'">Finance Your Hot Tub</a></li>';
                        $mm .= '<li><a href="'. get_permalink(2978) .'">Special Offers</a></li>';
                        $mm .= '<li><a href="'. get_permalink(2982) .'">Download a Brochure</a></li>';
                        $mm .= '<li><a href="'. get_permalink(2989) .'">Get a Quote</a></li>';
                        $mm .= '<li><a href="'. get_permalink(4939) .'">Trade-In Value</a></li>';
                        $mm .= '<li><a href="#signup" class="esignup">Email Sign Up</a></li>';
                        $mm .= '</ul>';
                        $mm .= '</div><br class="clear" />';
                        $mm .= '</div>';
                        set_transient( 's_sitemap', $mm, 60*60*12 );
                    }
                    // Use the data like you would have normally...
                    $sitemap = $mm; //get_transient( 's_sitemap' );
                    echo $sitemap; ?>
                </div>
            </li>
        </ul>
    </li>
    <li class="find last"><?php get_sidebar('dlform'); ?></li>
</ul>

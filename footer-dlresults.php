<?php
/**
 * The template for displaying the footer.
 *
 * @package Sundance
 * @subpackage Sundance
 * @since Sundance 2.0
 */
?>
	</div>
</div>
<!-- Template for Dealer Pages results footer -->

<!-- Header / Top Menu stuff -->
<div class="hd">
    <div class="wrap">
        <div class="logo">
            <h1><a href="<?php bloginfo('url'); ?>">Sundance Spas&reg;</a></h1>
        </div>
        <?php
        wp_nav_menu( array( 'container' => 'false', 'menu_class' => 'topMenu', 'theme_location' => 'topres' ) );
        ?>
        <ul class="mainMenu">
            <li class="hover"><?php
                global $tubcats;
                // transient for sundance_htdrop
                if ( true ) { // false === ( $special_query_results = get_transient( 's_htdrop' ) ) ) {
                    // It wasn't there, so regenerate the data and save the transient
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
                    set_transient( 's_htdrop', $o, 60*60*12 );
                }
                // Use the data like you would have normally...
                $drop = get_transient( 's_htdrop' );
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
                <ul>
                    <?php echo sundance_wplistpages_cache(2447, 's_backyard_listpages'); ?>
                </ul>
            </li>
            <li class="sitemap<?php if ( is_page(3137) ) echo ' active'; ?>"><a href="<?php echo get_permalink(3137); ?>">Site Map</a>
                <ul>
                    <li>
                        <div class="sitemapFlop">
                            <?php
                            // transient for s_sitemap
                            if ( false === ( $special_query_results = get_transient( 's_sitemap' ) ) ) {
                                $mm .= '<div class="cols">';
                                $mm .= '<div class="col w140">';
                                $mm .= '<h2><a href="'. get_permalink(1894) .'">Hot Tubs</a></h2>';
                                $mm .= '<ul>';
                                $mm .= '<li><a href="'. get_permalink(1894) .'">All Hot Tubs</a></li>';
                                foreach ( $tubcats as $s ) $mm .= '<li><a href="'. esc_url($s['url']) .'">'. esc_attr($s['name']) .' Series&trade;</a>';
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
                                $mm .= '<li><a href="'. get_permalink(2970) .'">Employment Opportunities</a></li>';
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
                            $sitemap = get_transient( 's_sitemap' );
                            echo $sitemap; ?>
                        </div>
                    </li>
                </ul>
            </li>
            <li class="find last"><?php get_sidebar('dlform'); ?></li>
        </ul>
    </div>
</div>

<script type="text/javascript">
    jQuery(document).ready(function($){
        $('<option>')
            .attr('value','3')
            .html('Canada')
            .prependTo('#country');
        $('<option>')
            .attr('value','1')
            .html('United States')
            .prependTo('#country');
    });
</script>

<!-- Footer -->
<div class="ft">
    <div class="wrap">
        <div class="inner">
            <div class="logo">
                <p><br /><?php echo date('Y'); ?> all rights reserved</p>
            </div>
            <div class="menuWrap">
            <?php
            wp_nav_menu( array( 'container' => 'false', 'menu_class' => 'topMenu', 'theme_location' => 'ft1' ) );
            wp_nav_menu( array( 'container' => 'false', 'menu_class' => 'bottomMenu', 'theme_location' => 'ft2' ) );
            wp_nav_menu( array( 'container' => 'false', 'menu_class' => 'bottomMenu fresources', 'theme_location' => 'ftres' ) );
            ?>
            </div>
            <br class="clear" />
        </div>
    </div>
</div>

<!-- Newsletter Sign-up Overlay -->
<div class="overlay" id="emailover">
	<div class="dialog">
    	<a id="x" href="#"></a>
    	<div class="inner">
        	<h2>Sign Up for our Newsletter</h2>
            <?php //echo do_shortcode('[gravityform id="4" name="Sign Up for our Newsletter" title="false" description="false" ajax="true"]'); ?>
            <script type="text/javascript" src="http://login.sendmetric.com/phase2/bhecho_files/smartlists/check_entry.js"></script>
            <script type="text/javascript">
               <!--
                              function check_cdfs(form) {
                                             return true;
                              }
               -->
            </script>
            <script type="text/javascript">
            <!--
                function doSubmit() {
                    if (check_cdfs(document.survey)) {
                                                         window.open('','signup','resizable=1,scrollbars=0,width=300,height=150');
                        return true;
                    }
                    else { return false; }
                }
            -->
            </script>

            <form action="http://login.sendmetric.com/phase2/bullseye/contactupdate1.php3" method="post" name="bullseye" id="bullseye" onsubmit="return doSubmit();" target="signup">
                <table> 
                    <tr>
                        <td><label for="firstname">Name</label></td>
                        <td><input type="text" class="text" id="firstname" name="firstname" /></td>
                    </tr>
                    <tr>
                        <td><input type="hidden" name="cid" value="9bffaece0a39911668c51d8eb7af85c7" /><label for="email">Email</label></td>
                        <td><input type="text" class="text" id="email" name="email" /></td>
                    </tr>
                    <tr>
                        <td><label for="postal_code">Zip</label></td>
                        <td><input type="text" class="text zip" id="postal_code" name="postal_code" /></td>
                    </tr>
                    <tr>
                        <td><input type="hidden" name="message" value="Thank you. Your information has been submitted. To ensure delivery of your newsletter(s), please add donotreply@sundancespas.com to your address book, spam filter whitelist, or tell your company's IT group to allow this address to pass through any filtering software they may have set up." />&nbsp;</td>
                        <td><br /><input type="image" src="<?php bloginfo('template_url'); ?>/images/icons/dialogSubmit.png" value="submit" name="SubmitBullsEye" /><input type="hidden" name="grp[]" value="575636" /></td>
                    </tr>
                </table>
            </form>
        </div>
    </div>
</div>
<!-- end newsletter -->


<?php
	/* Always have wp_footer() just before the closing </body>
	 * tag of your theme, or you will break many plugins, which
	 * generally use this hook to reference JavaScript files.
	 */

	wp_footer();
?>


<!-- Google Code for Locate a Dealer Conversion Page -->
<script type="text/javascript">
/* <![CDATA[ */
var google_conversion_id = 971855216;
var google_conversion_language = "en";
var google_conversion_format = "3";
var google_conversion_color = "ffffff";
var google_conversion_label = "EgaYCMjtqwQQ8Kq1zwM";
var google_conversion_value = 0;
/* ]]> */
</script>
<script type="text/javascript" src="http://www.googleadservices.com/pagead/conversion.js">
</script>
<noscript>
<div style="display:inline;">
<img height="1" width="1" style="border-style:none;" alt="" src="http://www.googleadservices.com/pagead/conversion/971855216/?value=0&amp;label=EgaYCMjtqwQQ8Kq1zwM&amp;guid=ON&amp;script=0"/>
</div>
</noscript>


<!-- Start Quantcast Tag -->
<script type="text/javascript"> 
var _qevents = _qevents || [];
(function() {
var elem = document.createElement('script');
elem.src = (document.location.protocol == "https:" ? "https://secure" : "http://edge") + ".quantserve.com/quant.js";
elem.async = true;
elem.type = "text/javascript";
var scpt = document.getElementsByTagName('script')[0];
scpt.parentNode.insertBefore(elem, scpt);
})();
_qevents.push(
{qacct:"p-TAV6snse1j2Rf",labels:"_fp.event.Sundance Spas Locate a Dealer"}
);
</script>
<noscript>
<img src="//pixel.quantserve.com/pixel/p-TAV6snse1j2Rf.gif?labels=_fp.event.Sundance+Spas+Locate+a+Dealer" style="display: none;" border="0" height="1" width="1" alt="Quantcast"/>
</noscript>
<!-- End Quantcast tag -->

</body>
</html>

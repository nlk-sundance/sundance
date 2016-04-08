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
        <?php /*wp_nav_menu( array( 'container' => 'false', 'menu_class' => 'topMenu', 'theme_location' => 'topres' ) ); ?>
        <?php get_template_part( 'block', 'mainmenu' );*/ ?>
        <ul class="toplist">
            <li class="listborder"><a href="<?php bloginfo('url'); ?>/request-literature/">Free Brochure</a></li>
            <li><a href="<?php bloginfo('url'); ?>/hot-tub-dealer-locator/">Nearest Dealer</a></li>
            <li>
                <form id="dealer-finder" method="post" action="<?php bloginfo('url'); ?>/hot-tub-dealer-locator/cities/">
                    <input type="text" class="zip btn-start" name="zip" id="zip" placeholder="zip/postal code" />
                    <input name="zipcodeSearch" value="1" type="hidden">
                </form>
            </li>
        </ul>
        <?php 
            wp_nav_menu( array(
                'menu'       => 'mainmenu',
                'depth'      => 2,
                'container'  => false,
                'menu_class' => 'mainMenu',
                'fallback_cb' => 'wp_page_menu',
                //Process nav menu using our custom nav walker
                'walker' => new wp_bootstrap_navwalker())
            ); 
        ?>
        <form role="search" method="get" class="search-form" action="<?php bloginfo('url'); ?>">
				<label>
					<span class="screen-reader-text">Search for:</span>
					<input class="search-field" placeholder="Search …" value="" name="s" title="Search for:" type="search">
				</label>
				<input class="search-submit" value="Search" type="submit">
			</form>
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
            <div class="col-8">
                <h1 class="smalllogo"><a href="<?php bloginfo('url'); ?>">logo</a></h1>
            </div>
            <div class="col-4">
                <?php get_footer('block_social'); ?>
            </div>
            <div class="clear"></div>
            <div class="col-12">
                <?php get_footer('block_menu1'); ?>
            </div>
            <div class="clear"></div>
            <div class="col-12">
                <?php get_footer('block_menu2'); ?>
            </div>
            <div class="clear"></div>   
            <div class="col-12">
                <p>&#169;<?php echo date('Y'); ?> Sundance Spas - all rights reserved</p>
            </div>
            <div class="clear"></div>
        </div>
    </div>
</div>

<?php get_footer('block_newsletter'); ?>

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

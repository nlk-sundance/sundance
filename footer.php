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
<div class="hd">
    <div class="wrap">
        <div class="logo">
            <h1><a href="<?php bloginfo('url'); ?>">Sundance Spas&reg;</a></h1>
        </div>
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
  		<div class="main-navigation">
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
			<?php echo get_search_form(); ?>
		</div>
    </div>
</div>
    
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
<script type="text/javascript">
	jQuery(document).ready(function(){
		var h1Height = jQuery('.bd .main .main-title h1').height();
		if(h1Height > 72)
			jQuery('.bd .main .main-title').css('min-height', h1Height);
	});
</script>
<?php
	/* Always have wp_footer() just before the closing </body>
	 * tag of your theme, or you will break many plugins, which
	 * generally use this hook to reference JavaScript files.
	 */
	wp_footer();
?>
</body>
</html>
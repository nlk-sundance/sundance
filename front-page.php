<?php
/**
 * Home Page template file
 *
 * @package Sundance
 * @subpackage Sundance
 * @since Sundance 2.0
 */

get_header(); ?>
<div class="hero">
    <div class="slides">
    	<div class="slide">
            <!-- Holiday Promo (commented out - no promo) -->
            <?php /* <a href="<?php echo get_bloginfo('url'); ?>/specials/"><img src="<?php bloginfo('template_url'); ?>/images/hero/Homepage_03.jpg" width="960" height="320" alt="Hot Tubs" /></a> */ ?>

            <!-- Regular home hero -->
            <?php /* COMMENT OUT FOR PROMO */ ?>
            <a href="#"><img src="<?php bloginfo('template_url'); ?>/images/hero/home-slide2.jpg" width="960" height="320" alt="Hot Tubs" /></a>
            <h1>Life is better with a Sundance Spa</h1>
            <div class="fancy-button" goto="vidmodal" rel="//www.youtube-nocookie.com/embed/IB8YE8ka2s0?rel=0">Watch the Video</div>
        </div>
    </div>
    <div class="rightCol">
        <?php get_sidebar('rt4'); ?>
    </div>
</div>
<div class="cols">
    <div class="main col w730">
        <div class="inner">
            <div class="col w240">
                <div class="inner">
                    <a href="<?php echo bloginfo('url'); ?>/truckload/"><img src="<?php bloginfo('template_url'); ?>/images/icons/truckload-sale.jpg" alt="Truckload Super Sale" width="230" height="145" /></a>
                    <h2 class="avenir65-20"><a href="<?php echo bloginfo('url'); ?>/truckload/">Truckload Savings Event</a></h2>
                    <p class="avenir65-13">Request a sale in your town and <br />save up to 50%.</p>
                    <p><a href="<?php echo bloginfo('url'); ?>/truckload/" class="goArrow">Go</a></p>
                </div>
            </div>
            <div class="col w240">
                <div class="inner">
                    <a href="<?php echo get_permalink(2823); ?>"><img src="<?php bloginfo('template_url'); ?>/images/icons/hydrotherapy.jpg" border="0" /></a>
                    <h2 class="avenir65-20"><a href="<?php echo get_permalink(2823); ?>">Hydrotherapy Spa</a></h2>
                    <p class="avenir65-13">Positive results for our bodies and our minds.</p>
                    <p><a href="<?php echo get_permalink(2823); ?>" class="goArrow">Go</a></p>
                </div>
            </div>
            <div class="col w240 last">
                <div class="inner">
                    <a href="/hot-tub-dealer-locator/"><img src="<?php bloginfo('template_url'); ?>/images/icons/experience-sundance.jpg" border="0" /></a>
                    <h2 class="avenir65-20"><a href="/hot-tub-dealer-locator/">Experience a Sundance</a></h2>
                    <p class="avenir65-13">Visit your local dealer to see Sundance quality in person.</p>
                    <p><a href="/hot-tub-dealer-locator/" class="goArrow">Go</a></p>
                </div>
            </div>
            <br class="clear" />
            <div class="col w480">
                <div class="inner">
                    <?php while ( have_posts() ) : the_post();
					
					the_content();
					
					endwhile;
					?>
                </div>
            </div>
            <div class="col w240 last blog">
                <div class="inner">
                    <h2>Read Our Blog</h2>
                    <p>From news on the best ways to care for your spa to how to get a better night's sleep. Learn and stay informed with our Sundance Spas <a href="<?php echo get_bloginfo('url'); ?>/spa-blog/">Blog</a>.</p>
                    <h3>Categories</h3>
                    <ul>
                    <?php
					if ( false === ( $special_query_results = get_transient( 'wp_list_categories' ) ) ) {
						// It wasn't there, so regenerate the data and save the transient
						$special_query_results = wp_list_categories('title_li=&echo=0');
						set_transient( 'wp_list_categories', $special_query_results, 60*60*12 );
					}
					// Use the data like you would have normally...
					$wp_list_categories = get_transient( 'wp_list_categories' );
					echo $wp_list_categories;
					?>
                    </ul>
                </div>
            </div>
        </div>
    </div>
    <?php get_sidebar('generic'); ?>
    <br class="clear" />
</div>
<?php get_footer(); ?>

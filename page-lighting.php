<?php
/**
 * Template Name: Features > Lighting
 *
 * @package Sundance
 * @subpackage Sundance
 * @since Sundance 2.0
 */
 
get_header(); ?>
<div class="cols">
    <?php while ( have_posts() ) : the_post(); ?>
    <div class="main col w730">
        <div class="main-title">
            <?php the_post_thumbnail(); ?>
            <h1><?php the_title(''); ?></h1>
        </div>
        <div class="col w240 leftMenu">
            <div class="inner">
                <div class="description">
                    <?php the_content(''); ?>
                </div>
            </div>
        </div>
        <div class="col w480 last smallTabs jetsSelect">
            <ul class="tabs" id="spatabs"><li class="current"><a href="#lighting-880">880 Lighting</a></li><li><a href="#lighting-780">780 Lighting</a></li><li><a href="#lighting-680">680 Lighting</a></li><li></li></ul>
            <div class="tab current" id="lighting-880">
                <ul class="grid col2">
                    <li><div>
                        <img src="<?php bloginfo('template_url'); ?>/images/features/waterfall_v2.jpg" border="0" />
                        <h3>SunGlow&trade; Lighting System and AquaTerrace&trade; Water Feature</h3>
                        <ul>
                        <li>AquaTerrace&trade; water feature includes colored backlighting with a rotating spectrum or one-color settings.</li>
                        <li>iTouch AquaTerrace controls let you adjust lighting for waterfall and footwell.</li>
                        <li>Illuminated footwell jets reflect jet-driven water and add ambience.</li>
                        <li>Select from three levels of brightness. </li>
                        <li>LED (light-emitting diode) technology consumes less energy than incandescent lights.</li>
                        <li>A double AquaTerrace waterfall is standard in the Maxxus model.</li>
                        </ul>
                    </div></li>
                    <li><div>
                        <img src="<?php bloginfo('template_url'); ?>/images/features/bar.jpg" border="0" />
                        <h3>LED backlit Grab Bars</h3>
                        <ul>
                        <li>Enables easy and safe entry and exiting in the evening hours.</li>
                        <li>Lighting synchronized with the spa's interior lighting creating a stunning lighting effect. </li>
                        </ul>
                    </div></li>
                    <li><div>
                        <img src="<?php bloginfo('template_url'); ?>/images/features/880-indicator-light.jpg" border="0" />
                        <h3>Status Indicator Light</h3>
                        <ul>
                        <li>Standard on all 880 series, this logo light appears white when the hot tub has power and all systems are in normal operation, and red when the hot tub requires attention</li>           
                        </ul>
                    </div></li>
                </ul>
                <br class="clear" />
            </div>
            <div class="tab" id="lighting-780" style="display:none">
            	<ul class="grid col2">
                    <li><div>
                        <img src="<?php bloginfo('template_url'); ?>/images/features/780-lighting-1.jpg" border="0" />
                        <h3>SunRay&trade; Lighting System and AquaSheer Water Feature</h3>
                        <ul>
                        <li>AquaSheer water feature is designed with a curved edge for a sheer "curtain" of water.</li>
                        <li><span id="lighting">LED in-spa illumination includes light rings on air controls (on select models). </span></li>
                        <li><span id="lighting">Colors can be set to one shade or rotating.</span></li>
                        <li><span id="lighting">Improved footwell lighting enhances glow from within the spa's water.</span></li>
                        <li>iTouch control panel provides easy access for adjusting the lights in the waterfall and footwell.</li>
                        </ul>
                    </div></li>
                     </ul>
                <br class="clear" />
            </div>
            <div class="tab" id="lighting-680" style="display:none">
            	<ul class="grid col2">
                    <li><div>
                        <img src="<?php bloginfo('template_url'); ?>/images/features/680-pinpoint-lighting.jpg" border="0" />
                        <h3>LED PINPOINT LIGHTING</h3>
                        <ul>
                            <li>Illumination in a palette of colors you can set to rotating or stationary mode</li>
                            <li>Consumes less than 8% of the energy used by similar incandescent lighting systems</li>                
                        </ul>
                    </div></li>
                    <li><div>
                       <img src="<?php bloginfo('template_url'); ?>/images/features/680-lighting-2.jpg" border="0" />
                        <h3>Multicolor LED Light</h3>
                        <ul>
                        <li>Footwell light addition. </li>
                        <li>LED technology allows for changeable colors.</li>
                        <li>Set lighting to one color, or transition through a spectrum.
                        </ul>
                    </div></li>
                </ul>
                <br class="clear" />
            </div>
        </div>
    </div>
    <?php
    endwhile;
    get_sidebar('generic');
    ?>
</div>
<br class="clear" />
<?php get_footer(); ?>

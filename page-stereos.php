<?php
/**
 * Template Name: Features > Stereos
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
            <ul class="tabs" id="spatabs">
                <li class="current"><a href="#stereos-880">880 Stereos</a></li>
                <li><a href="#stereos-780">780 Stereos</a></li>
                <li><a href="#stereos-680">680 Stereos</a></li>
            </ul>
            <div class="tab current" id="stereos-880">
                <ul class="grid col2">
                    <li>
                        <div>
                            <img src="<?php bloginfo('template_url'); ?>/images/features/bluewave.jpg" border="0" />
                            <h3>Optional BLUEWAVE&trade; Spa Stereo System</h3>
                            <ul>
                                <li>BLUETOOTH&reg; allows you to stream audio from up to 30 ft.</li>
                    			<li>USB power gives you a direct connect and charge while listening</li>
                    			<li>AUX input for your alternative music sources</li>
                    			<li>FM Radio</li>
                			</ul>
                        </div>
                    </li>
                    <li>
                        <div>
                            <img src="<?php bloginfo('template_url'); ?>/images/features/SD_880_remote_2013.jpg" border="0" />
                            <h3>Wireless Remote</h3>
                           	<ul>
                		    	<li>Water resistant remote control allows you to operate the stereo without leaving your spa</li>
                		  	</ul>
                        </div>
                    </li>
                </ul>
                <br class="clear" />
            </div>
            <div class="tab" id="stereos-780" style="display:none">
    	       <ul class="grid col2">
                    <li>
                        <div>
                            <img src="<?php bloginfo('template_url'); ?>/images/features/bluewave.jpg" border="0" />
                            <h3>Optional BLUEWAVE&trade; Spa Stereo System</h3>
                            <ul>
                                <li>BLUETOOTH&reg; allows you to stream audio from up to 30 ft.</li>
                    			<li>USB power gives you a direct connect and charge while listening</li>
                    			<li>AUX input for your alternative music sources</li>
                    			<li>FM Radio</li>
                            </ul>
                        </div>
                    </li>
                    <li>
                        <div>
                            <img src="<?php bloginfo('template_url'); ?>/images/features/SD_780_spkr.jpg" border="0" />
                            <h3>Micro-Speakers</h3>
                            <ul>
                                <li>Four topside micro-speakers</li>
                                <li>Built into the spa near pillow headrests</li>
                                <li>Waterproof</li>
                            </ul>
                        </div>
                    </li>
           		</ul>
            </div>
            <div class="tab" id="stereos-680" style="display:none">
            	<ul class="grid col2">
                    <li>
                        <div>
                            <img src="<?php bloginfo('template_url'); ?>/images/features/bluewave.jpg" border="0" />
                                <h3>Optional BLUEWAVE&trade; Spa Stereo System</h3>
                                <ul>
                                <li>BLUETOOTH&reg; allows you to stream audio from up to 30 ft.</li>
                    			<li>USB power gives you a direct connect and charge while listening</li>
                    			<li>AUX input for your alternative music sources</li>
                    			<li>FM Radio</li>
                                <li>Excludes Tacoma and Denali</li>
                    			</ul>
                        </div>
                    </li>
                    <li>
                        <div>
                            <img src="<?php bloginfo('template_url'); ?>/images/features/SD_680_remote_2013.jpg" border="0" />
                            <h3>Wireless Remote Control</h3>
                            <ul>
                			<li>Water resistant remote control allows you to operate the stereo without leaving your spa </li>
                			</ul>
                        </div>
                    </li>
                    <li>
                        <div>
                            <h3>Speakers</h3>
                            <ul>
                			<li>Four Marine-grade speakers</li>
                			</ul>
                        </div>
                    </li>
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

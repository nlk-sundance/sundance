<?php
/**
 * Template Name: Brochure (1 part - 960px width)
 *
 * @package SDS
 * @subpackage SDS
 * @since SDS 1.0
 */

avala_form_submit();

wp_enqueue_style('Lato', 'http://fonts.googleapis.com/css?family=Lato:400,900');

get_header();

if ( have_posts() ) while ( have_posts() ) : the_post();

?>
<style>
.gform-body.row {
  width: 600px;
  padding-top: 13px;
}
select#input_11_4 {
  height: 24px;
  margin-top: 37px;
}
select#input_11_6 {
  height: 24px;
}
div#input_11_7_1 {
  padding-top: 4px;
}
label#label_11_7_1 {
  display: block;
}
input#gform_submit_button_11 {
  right: 12px;
}
</style>
<div id="main-wrapper">
	<div class="page-header one-part" id="page-header">
    	<div class="content center one-part"> <!-- bg image here -->
        	<div class="block span1of1">
            	
            </div>
            <div class="brown-bar">
                <div class="the-brochure-img"></div>
                <?php echo do_shortcode('[gravityform id=11 ajax=true title=false description=false]'); ?>
                <p class="privacy"><small>* indicates required fields.<br><a href="<?php echo get_bloginfo('url'); ?>/about-us/privacy-policy" target="_blank">Privacy policy.</a></small></p>
                <div class="more-fold"></div>
            </div>

        </div>
    </div>
    <section class="section">
    	<div class="content" role="main">
            <div class="content-list rt" style="height:390px;">
                <div id="img-model" class="listimg"></div>
                <h2>Compare Hot Tub Models at a Glance</h2>
                <ul>
                	<li>Find the perfect hot tub for you</li>
                	<li>Choose hot tub sizes, seating, and features</li>
                    <li>Use as an all-in-one shopping guide</li>
                </ul>
            
            </div>
        	<div class="content-list lt" style="height:345px;">
                <div id="img-benefits" class="listimg"></div>
                <h2>Explore the Benefits of Sundance Hydrotherapy</h2>
                <ul>
                	<li>Calms stress within minutes</li>
                    <li>Promotes relaxation and together-time</li>
                    <li>Diminishes muscle soreness and arthritis pain</li>
                </ul>
			</div>
            <div class="content-list rt" style="height:362px;">
                <div id="img-satisfied" class="listimg"></div>
                <h2>Learn About Why Satisfied Owners Recommend Sundance Spas</h2>
                <ul>
                	<li>Advanced, energy-saving technology</li>
                    <li>Crystal clear water without the high maintenance</li>
                    <li>Authorized dealer network since 1979</li>
                </ul>
            </div>
		</div>
    </section>
    <?php /* ?><div id="award-bar"></div><?php */ ?>
    <div id="video-bar">
        <div class="testimonial-block">
            <div class="l">
                <p class="quote"><i>"After we installed the spa we found we were using it a lot more than we ever expected. It's a great way to get away from work and stress and spend time together."</i></p>
                <p class="person"><i>William Wright, Sundance Spas hot tub owner</i></p>
            </div>
            <div class="r">
                <iframe width="466" height="262" src="//www.youtube-nocookie.com/embed/IB8YE8ka2s0?rel=0" frameborder="0" allowfullscreen></iframe>
            </div>
        </div>
    </div>
</div>
<div class="form-page-two-bg" style="display: none;"></div>
<?php endwhile; // end of the loop. ?>
<?php get_footer( 'ppclandingcta' ); ?>

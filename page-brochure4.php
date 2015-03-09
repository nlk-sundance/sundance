<?php
/**
 * Template Name: Brochure (1 part - 960px - Zip -> Postal)
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
<div id="main-wrapper">
	<div class="page-header one-part" id="page-header">
    	<div class="content center one-part"> <!-- bg image here -->
        	<div class="block span1of1">
            	
            </div>
            <div class="brown-bar">

                <form action="http://<?php echo $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI']; ?>" method="post" id="leadForm" class="two-part-form plus-phone">
                    <div class="the-brochure-img"></div>
                    <?php avala_hidden_fields( 13, 6, 12 ); ?>
                    <table>
                        <tr>
                            <td colspan="3">
                                <?php avala_field('first_name', 'text full', true, 'field', array('size'=>"14", 'placeholder'=>"First Name *", 'required'=>"required" )); ?>
                            </td>
                            <td colspan="3">
                                <?php avala_field('last_name', 'text full', true, 'field', array('size'=>"15", 'placeholder'=>"Last Name *", 'required'=>"required" )); ?>
                            </td>
                        </tr>

                        <tr>
                            <td colspan="2">
                                <?php avala_field('postal_code', 'text full', true, 'field', array('size'=>"7", 'placeholder'=>"Postal Code *", 'required'=>"required" )); ?>
                            </td>
                            <td colspan="2">
                                <?php avala_field('email', 'text full email', true, 'field', array('size'=>"20", 'placeholder'=>"Email *", 'required'=>"required" )); ?>
                            </td>
                            <td colspan="2">
                                <?php avala_field('phone', 'text full phone', false, 'field', array('size'=>"20", 'placeholder'=>"Phone" )); ?>
                            </td>
                        </tr>
                        <tr>
                            <td colspan="6">
                                <table>
                                    <tr>
                                        <td width="199"><?php avala_field('currently_own', '', false, 'all', '', 'select', 'Have you ever owned<br />a hot tub?'); ?></td>
                                        <td width="199"><?php avala_field('buy_time_frame', '', false, 'all', '', 'select', 'When do you plan to purchase<br />a hot tub?'); ?></td>
                                        <td width="195"><?php avala_field('product_use', '', false, 'all', '', 'select', 'What is the primary benefit you are looking for in a hot tub?'); ?></td>
                                    </tr>
                                    <tr>
                                        <td colspan="3">
                                            <?php avala_field('newsletter', '', false, 'field' ); ?>
                                        </td>
                                    </tr>
                                </table>
                            </td>
                        </tr>
                    </table>
                    <input type="submit" value="Get My Brochure" class="bigBlueBtn blueFade" onClick="_gaq.push(['_trackEvent', 'lead', 'brochure-full']);" />
                    <p><small>* indicates required fields.<br><a href="http://www.sundancespas.com/about-us/privacy-policy" target="_blank">Privacy policy.</a></small></p>
                </form>
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
    <div id="award-bar"></div>
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
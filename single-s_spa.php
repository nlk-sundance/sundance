<?php
/**
 * The main template file.
 *
 * This is the most generic template file in a WordPress theme
 * and one of the two required files for a theme (the other being style.css).
 * It is used to display a page when nothing more specific matches a query.
 * E.g., it puts together the home page when no home.php file exists.
 * Learn more: http://codex.wordpress.org/Template_Hierarchy
 *
 * @package Sundance
 * @subpackage Sundance
 * @since Sundance 2.0
 */
get_header();
while ( have_posts() ) : the_post();
global $post;

$custom = get_post_meta($post->ID,'s_info');
$s_info = $custom[0];

$custom = get_post_meta($post->ID,'s_colors');
$s_colors = $custom[0];
// array(66,68,70...) -> so process it
$o = array();
foreach ( $s_colors as $c ) {
	$co = get_post($c);
	$o[$c] = $co->post_title;
}
$s_colors = $o;

$custom = get_post_meta($post->ID,'s_cabs');
$s_cabs = $custom[0];
// array(66,68,70...) -> so process it
$o = array();
foreach ( $s_cabs as $c ) {
	$co = get_post($c);
	$o[$c] = $co->post_title;
}
$s_cabs = $o;

$custom = get_post_meta($post->ID,'s_specs');
$s_specs = $custom[0];
$custom = get_post_meta($post->ID,'s_feats');
$s_feats = $custom[0];
$custom = get_post_meta($post->ID,'s_high');
$s_high = $custom[0];
$custom = get_post_meta($post->ID,'s_opt');
$s_opts = $custom[0];
$custom = get_post_meta($post->ID,'s_jets');
$s_jets = $custom[0];
$jetcount = 0;
$jetvars = 0;
foreach ( $s_jets as $v ) {
	$jetcount += $v;
	if ( $v > 0 ) {
		$jetvars++;
	}
}


$prod = esc_attr($s_specs['product_id']);
/*$bv = new BV(
    array(
        'deployment_zone_id' => 'Main_Site-en_US',
        'product_id' => $prod, // must match ExternalID in the BV product feed
        'cloud_key' => 'sundancespas-486bb392da92b7b9f1e1628eec33b8ae',
        'staging' => FALSE
        )
    );
*/

// transient for s_alljets
if ( false === ( $special_query_results = get_transient( 's_alljets' ) ) ) {
	// It wasn't there, so regenerate the data and save the transient
	$special_query_results = get_posts(array('numberposts'=>-1,'post_type'=>'s_jet','orderby'=>'menu_order','order'=>'ASC'));
	set_transient( 's_alljets', $special_query_results, 60*60*12 );
}
// Use the data like you would have normally...
$alljets = get_transient( 's_alljets' );

// transient for s_allfeats
if ( false === ( $special_query_results = get_transient( 's_allfeats' ) ) ) {
	// It wasn't there, so regenerate the data and save the transient
	$feats = get_posts(array('numberposts'=>-1,'post_type'=>'s_feat','orderby'=>'menu_order','order'=>'ASC'));
	$o = array();
	foreach ( $feats as $f ) {
		$fimg = get_the_post_thumbnail($f->ID, 'original');
		$custom = get_post_meta($f->ID,'s_info');
		$finfo = $custom[0];
		
		$o[$f->ID] = array(
			'title' => esc_attr($f->post_title),
			'content' => wp_kses($f->post_content,array()),
			'img' => $fimg,
			'ltitle' => esc_attr($finfo['lnk']),
			'lurl' => esc_attr($finfo['url']),
		);
	}
	set_transient( 's_allfeats', $o, 60*60*12 );
}
// Use the data like you would have normally...
$allfeats = get_transient( 's_allfeats' );
//echo '<pre style="display:none">'. print_r($allfeats,true) .'</pre>';

$custom = get_post_meta($post->ID,'s_cats');
$cats = $custom[0];
$serID = $cats[0];
$ser = wp_get_single_post($serID);

if ( $ser->post_title == 'Select' ) { $serval = 'aDETNR5oAgg'; }
if ( $ser->post_title == 880 ) { $serval = 'zCdzYmarTWk'; }
if ( $ser->post_title == 780 ) { $serval = '5aWp_SGPXD8'; }
if ( $ser->post_title == 680 ) { $serval = '38_rQgt0IAc'; }

?>
<div class="cols istub <?php if ( $ser->post_title == 'Select' ) { echo 'select'; } ?>">
    <div class="main col w730">
        <?php the_post_thumbnail(); ?>
        <!--h1><?php the_title(); ?></h1-->
        <?php if ( $ser->post_title != 'Select' ) { ?>
        <div class="fancy-button" goto="vidmodal" rel="//www.youtube-nocookie.com/embed/<?php echo $serval; ?>?rel=0">VIDEO: Learn about the <?php esc_attr_e($ser->post_title); ?> Series</div>
        <?php } ?>
        <div class="spa-name"><?php echo get_the_title(); ?></div>
    </div>
    <div class="side col w230 last">
        <div class="rightCol">
            <?php get_sidebar('rt4'); ?>
        </div>
    </div>
    <br class="clear" />
    <div class="actions">
        <ul>
            <li><a href="<?php echo get_permalink(2982); ?>">Request a free brochure</a></li>
            <li><a href="/hot-tub-dealer-locator/">Locate a dealer</a></li>
            <li><a href="<?php echo get_permalink(2974); ?>">Get financed</a></li>
            <li><a href="#signup" class="esignup">Email sign up</a></li>
            <li><a href="<?php echo get_permalink(2989); ?>">Get a quote</a></li>
        </ul>
    </div>
    <div class="main col w730">
        <div class="inner">
            <h1><?php esc_attr_e($s_info['topheadline']); ?></h1>
            <div class="details">
                <div class="overhead"><?php
                if (class_exists('MultiPostThumbnails')) {
					MultiPostThumbnails::the_post_thumbnail('s_spa', 'overhead-large', $post->ID, 'overhead-large');
				}
				?></div>
                <div class="specs">
                    <div itemscope itemtype="http://schema.org/Product">
                        <div id="BVRRSummaryContainer"></div>
                            <div class="description"><?php echo sundance_shortdesc($post->post_content); ?></div>
                             <table width="100%">
                                <tr>
                                    <td class="label">Series</td>
                                    <td><?php
        							esc_attr_e($ser->post_title);
        							?></td>
                                </tr>
                                <tr>
                                    <td class="label">Seats</td>
                                    <td><?php esc_attr_e($s_specs['seats']);
                                    //echo '<pre>'. print_r($s_specs,true) .'</pre>';
        							?></td>
                                </tr>
                                <tr>
                                    <td class="label">Dimensions</td>
                                    <td><?php esc_attr_e($s_specs['dim_us']); ?> <small>(<?php esc_attr_e($s_specs['dim_int']); ?>)</small></td>
                                </tr>
                                <tr>
                                    <td class="label">Spa Volume</td>
                                    <td><?php esc_attr_e($s_specs['vol_us']); ?> <small>(<?php esc_attr_e($s_specs['vol_int']); ?>)</small></td>
                                </tr>
                                <tr>
                                    <td class="label">Total Jets</td>
                                    <td><?php echo ''. absint($jetcount) .' <small>('. absint($jetvars) .' varieties)</small>'; ?></td>
                                </tr>
                            </table>
                            <div class="share"><?php if(function_exists('sharethis_button')) sharethis_button(); ?></div>
                        </div>
                    </div>
                <div class="colors">
                    <h3>Shell Colors</h3>
                    <ul>
					<?php foreach ( $s_colors as $i => $n ) {
                        echo '<li class="has-img-tooltip"><a title="'. $n .'">'. get_the_post_thumbnail($i, 'swatch-small') .'</a>';
                        echo '<div class="tooltip-img" style="display:none;"><div>' . get_the_post_thumbnail($i) . '<p style="padding-top: 6px;">' . $n . '</p></div></div>';
                        echo '</li>';
                    } ?>
                    </ul>
                    <?php if ( count ($s_cabs) > 0 ) { ?>
                    <h3>Cabinet Colors</h3>
                    <ul>
					<?php foreach ( $s_cabs as $i => $n ) {
                        echo '<li class="has-img-tooltip"><a title="'. $n .'">'. get_the_post_thumbnail($i, 'swatch-small') .'</a>';
                        echo '<div class="tooltip-img" style="display:none;"><div>' . get_the_post_thumbnail($i) . '<p style="padding-top: 6px;">' . $n . '</p></div></div>';
                        echo '</li>';
                    } ?>
                    </ul>
                    <?php } ?>
                </div>
                <div class="energy">
                    <img src="<?php bloginfo('template_url'); ?>/images/icons/energy-efficient.png" border="0" />
                    <span class="cost"><?php echo str_replace('.', '<sup>', $s_specs['emoc']). '</sup>'; ?></span>
                    <h4>Energy Efficiency <a href="#" title="Monthly energy costs are estimates based on the results of the California Energy Commissions Portable Hot Tub Testing Protocol. Ambient temperature of 60° F / 15° C and national average of 10 cents per kWh. Actual monthly costs may vary depending on temperature, electricity costs, and usage.">(?)</a></h4>
                    <p>Monthly Cost at<br />101º water temperature</p>
                </div>
                <div class="clear"></div>
                <a class="get-colorselector" href="#" onClick="jQuery('.color-selector-modal-bg').show();">View the Color Selector</a>
            </div>
        </div>
    </div>
    <div class="side col features w230 last">
        <div class="inner">
            <h3>Features</h3>
            <ul>
            	<?php foreach ( $s_feats as $i ) { ?>
                <li><a href="#"><?php esc_attr_e(str_replace('%spa%', $post->post_title, $allfeats[$i]['title'])); ?></a><div class="feature">
                        <span class="arrow"></span>
                        <div class="inner">
                            <h2><?php esc_attr_e(str_replace('%spa%', $post->post_title, $allfeats[$i]['title'])); ?></h2>
                            <p><?php echo wp_kses(str_replace('%spa%', $post->post_title, $allfeats[$i]['content']), array()); ?></p>
                            <p><?php echo $allfeats[$i]['img']; ?></p>
                        </div>
                        <?php if ( $allfeats[$i]['ltitle'] != '' ) { ?>
                        <div class="more"><?php
						if ( $allfeats[$i]['lurl'] != '' ) echo '<a href="'. esc_attr($allfeats[$i]['lurl']) .'">';
						esc_attr_e(str_replace('%spa%', $post->post_title, $allfeats[$i]['ltitle']));
						if ( $allfeats[$i]['lurl'] != '' ) echo '</a>';
						?></div>
                        <?php } ?>
                    </div></li>
               <?php } ?>
            </ul>
        </div>

        <?php if ( msrp_display() ) : ?>

            <?php
            $msrp = esc_attr($s_specs['msrp']);
            $msrp = ( $msrp[0] == '$' ? $msrp : '$'.$msrp );
            ?>

            <a id="show-msrp" href="#msrp" class="bigBlueBtn getpricing">View MSRP</a>

            <div class="msrp" style="display: none;">
                <?php echo '<p><span class="msrp-price">' . $msrp . '</span> MSRP</p>'; ?>
                <p>Prices listed are Manufacturer's Suggested Retail Price (MSRP). Prices may not include additional fees, see authorized dealer for details.</p>
                <a id="msrp-pricing" href="/get-a-quote/" class="bigBlueBtn">Get Pricing</a>
                <a id="msrp-dealer" href="/hot-tub-dealer-locator/" class="bigBlueBtn gap15px" style="margin-top: 15px;">Find a Dealer</a>
            </div>

        <?php else : ?>

            <a href="/get-a-quote/" class="bigBlueBtn">Get Pricing</a>
            <a href="/hot-tub-dealer-locator/" class="bigBlueBtn gap15px" style="margin-top: 15px;">Find a Dealer</a>

        <?php endif; ?>

    </div>
    <br class="clear" />
    <div class="tabWrap">
        <div class="tabContainer col w730">
            <ul class="tabs" id="spatabs">
                <li class="current"><a href="#highlights">Highlights</a></li>
                <li><a href="#colors">Colors</a></li>
                <li><a href="#specs">Specifications</a></li>
                <li><a href="#jets">Jets</a></li>
                <li><a href="#options">Options</a></li>
                <li><a href="#reviews">Reviews</a></li>
            </ul>
            <div class="tab highlights current" id="highlights">
                <div class="inner">
                    <div class="cols">
                    <?php
					//$lasti = count($s_high) - 1;
					foreach ( $s_high as $i => $hid ) {
						$h = get_post($hid);
					?>
                        <div class="col w160<?php if ( $i == 3 ) echo ' last'; ?>">
                            <h3><?php esc_attr_e($h->post_title); ?></h3>
                            <?php
							echo get_the_post_thumbnail($hid);
							echo apply_filters('the_content', $h->post_content);
							?>
                        </div>
                        <?php } ?>
                    </div>
                </div>
            </div>
            
            <div class="tab colors" id="colors">
                <div class="inner">
                    <h3>Shell Colors</h3>
                    <ul>
					<?php foreach ( $s_colors as $i => $n ) echo '<li>'. get_the_post_thumbnail($i, 'swatch-large') .'<br />'. $n .'</li>'; ?>
                    </ul>
                    <?php if ( count($s_cabs) > 0 ) { ?>
                    <h3>Cabinet Colors</h3>
                    <ul>
					<?php foreach ( $s_cabs as $i => $n ) echo '<li>'. get_the_post_thumbnail($i, 'swatch-large') .'<br />'. $n .'</li>'; ?>
                    </ul>
                    <?php } ?>
                </div>
            </div>
            
            <div class="tab specs" id="specs">
                <div class="inner">
                    <table>
                    	<?php if ( $s_specs['seats'] != '' ) { ?>
                        <tr>
                            <td class="label">Seats:</td>
                            <td class="data"><?php esc_attr_e($s_specs['seats']); ?></td>
                        </tr>
                        <?php }
						if ( $s_specs['dim_us'] != '' ) { ?>
                        <tr>
                            <td class="label">Dimensions:*</td>
                            <td class="data"><?php echo esc_attr($s_specs['dim_us']) .' ('. esc_attr($s_specs['dim_int']) .')'; ?></td>
                        </tr>
                        <?php }
						if ( $s_specs['weight'] != '' ) { ?>
                        <tr>
                            <td class="label">Dry Weight:</td>
                            <td class="data"><?php esc_attr_e($s_specs['weight']); ?></td>
                        </tr>
                         <?php }
						if ( $s_specs['fweight'] != '' ) { ?>
                        <tr>
                            <td class="label">Total Filled Weight:</td>
                            <td class="data"><?php esc_attr_e($s_specs['fweight']); ?></td>
                        </tr>
                        <?php }
						if ( $s_specs['vol_us'] != '' ) { ?>
                        <tr>
                            <td class="label">Average Spa Volume:</td>
                            <td class="data"><?php esc_attr_e($s_specs['vol_us']); ?> (<?php esc_attr_e($s_specs['vol_int']); ?>)</td>
                        </tr>
                        <?php }
						if ( $s_specs['pumps'] != '' ) { ?>
                        <tr>
                            <td class="label">Jet Pumps:</td>
                            <td class="data"><?php echo nl2br(esc_attr($s_specs['pumps'])); ?></td>
                        </tr>
                        <?php } ?>
                        <tr>
                            <td class="label"><strong>Total Jets</strong></td>
                            <td class="data"><?php echo ''. absint($jetcount); ?></td>
                        </tr>
                        <?php
						// LIST JETS
						$i = 0;
						foreach ( $s_jets as $j => $c ) {
							$c = absint($c);
							if ( $c > 0 ) {
							?>
						<tr>
							<td class="label"><?php esc_attr_e($alljets[$i]->post_title); ?> Jets:</td>
							<td class="data"><?php echo $c; ?></td>
						</tr><?php
							}
							$i++;
						}
						if ( $s_specs['aroma'] != '' ) {
						?>
                        <tr>
                            <td class="label">Aromatherapy Delivery/Air Blower:</td>
                            <td class="data"><?php esc_attr_e($s_specs['aroma']); ?></td>
                        </tr>
                        <?php }
                        if ( $s_specs['control'] != '' ) { ?>
                        <tr>
                            <td class="label">Air Control/Massage Selectors:</td>
                            <td class="data"><?php esc_attr_e($s_specs['control']); ?></td>
                        </tr>
                        <?php }
                        if ( $s_specs['wps'] != '' ) { ?>
                        <tr>
                            <td class="label">Water Purification System:</td>
                            <td class="data"><?php esc_attr_e($s_specs['wps']); ?></td>
                        </tr>
                        <?php }
                        if ( $s_specs['filter'] != '' ) { ?>
                        <tr>
                            <td class="label">Filter:</td>
                            <td class="data"><?php esc_attr_e($s_specs['filter']); ?></td>
                        </tr>
                        <?php }
                        if ( $s_specs['wt'] != '' ) { ?>
                        <tr>
                            <td class="label">Circulation Pump:</td>
                            <td class="data"><?php esc_attr_e($s_specs['wt']); ?></td>
                        </tr>
                        <?php }
                        if ( $s_specs['elec'] != '' ) { ?>
                        <tr>
                            <td class="label">Electrical Requirements:</td>
                            <td class="data"><?php echo nl2br(esc_attr($s_specs['elec'])); ?></td>
                        </tr>
                        <?php }
                        if ( $s_specs['lighting'] != '' ) { ?>
                        <tr>
                            <td class="label">Lighting:</td>
                            <td class="data"><?php esc_attr_e($s_specs['lighting']); ?></td>
                        </tr>
                        <?php }
                        if ( $s_specs['trim'] != '' ) { ?>
                        <tr>
                            <td class="label">Stainless Steel Jet Trim:</td>
                            <td class="data"><?php esc_attr_e($s_specs['trim']); ?></td>
                        </tr>
                        <?php }
                        if ( $s_specs['headrests'] != '' ) { ?>
                        <tr>
                            <td class="label">Headrests:</td>
                            <td class="data"><?php esc_attr_e($s_specs['headrests']); ?></td>
                        </tr>
                        <?php }
                        if ( $s_specs['heater'] != '' ) { ?>
                        <tr>
                            <td class="label">Heater:</td>
                            <td class="data"><?php esc_attr_e($s_specs['heater']); ?></td>
                        </tr>
                        <?php }
                        if ( $s_specs['waterfall'] != '' ) { ?>
                        <tr>
                            <td class="label">Water Feature:</td>
                            <td class="data"><?php esc_attr_e($s_specs['waterfall']); ?></td>
                        </tr>
                        <?php }
                        if ( $s_specs['stereo'] != '' ) { ?>
                        <tr>
                            <td class="label">Stereo:</td>
                            <td class="data"><?php esc_attr_e($s_specs['stereo']); ?></td>
                        </tr>
                        <?php }
                        if ( $s_specs['cpanel'] != '' ) { ?>
                        <tr>
                            <td class="label">AquaTerrace Control Panel:</td>
                            <td class="data"><?php esc_attr_e($s_specs['cpanel']); ?></td>
                        </tr>
                        <?php } ?>
                        <tr>
                            <td class="label">Shell Colors:</td>
                            <td class="data"><?php echo implode(', ', $s_colors); ?></td>
                        </tr>
                    <?php if ( count ($s_cabs) > 0 ) { ?>
                        <tr>
                            <td class="label">Cabinetry:</td>
                            <td class="data"><?php echo implode(', ', $s_cabs); ?></td>
                        </tr>
                        <?php } ?>
                    </table>
                    <div style="float: left; width:690px; margin: 18px; font-size: 10px; line-height: 15px">
                    Sundance Spas may make product modifications and enhancements. Specifications may change without notice. International products may be configured differently to meet local electrical requirements. Spa volume is based on average fill. Manufactured under one or more United States patent numbers. Other patents may apply.<br /><br />
                    Estimated monthly cost is based on CEC test protocol for standby power consumption only. Test results measured in a controlled environment based on a kilowatt rate per hour of $0.10. Local and future energy rates, local conditions and individual use will alter these estimated monthly costs.For complete CEC test protocol and results visit <a href="http://www.energy.ca.gov">http://www.energy.ca.gov</a><br /><br />
                    * BHP (brake horsepower) is a maximum value measured by the motor manufacturer with no pump installed.<br />
                    ** Heater warranty varies outside North America<br /><br />
                    Dimensions, capacities and weights are approximate. Specifications are subject to change without notice.</div>
                </div>
            </div>
            
            <div class="tab jets" id="jets">
                <div class="inner">
                    <div class="description">
                        <h3><?php the_title(''); ?> Jets</h3>
                        <p>Experience a full range of massage types. The jets in our <?php esc_attr_e($ser->post_title); ?> Series Spas work effectively for a variety of therapeutic techniques.</p>
                        <p><a href="<?php echo get_permalink(2416); ?>">More about our jets</a></p>
                    </div>
                    <ul>
                    	<?php
						$i = 0;
						foreach ( $s_jets as $id => $v ) {
							if ( $v > 0 ) {
								echo '<li><a href="#">'. get_the_post_thumbnail($id, 'swatch-large') .'<br />'. esc_attr($alljets[$i]->post_title) .'</a></li>';
							}
							$i++;
						} ?>
                    </ul>
                </div>
            </div>
            
            <div class="tab options" id="options">
                <div class="inner">
                	<?php if ( count($s_opts) > 0 ) { ?>
                    <div class="cols">
                    <?php
					foreach ( $s_opts as $i => $hid ) {
						$h = get_post($hid);
					?>
                        <div class="col w160<?php if ( $i == 3 ) echo ' last'; ?>">
                            <h3><?php esc_attr_e($h->post_title); ?></h3>
                            <?php
							echo get_the_post_thumbnail($hid);
							echo apply_filters('the_content', $h->post_content);
							?>
                        </div>
                        <?php } ?>
                    </div>
                    <?php } else echo '<p>No Options</p>'; ?>
                </div>
            </div>
            <div class="tab reviews" id="reviews">
                <div class="inner">
                    <div itemscope itemtype="http://schema.org/Product">
                        <meta itemprop="name" content="<?php echo the_title(); ?>" />
                        <div id="BVRRContainer">
                            <?php echo $bv->reviews->getContent();?>
                        </div>
                        <script type="text/javascript">
//                        $BV.ui( 'rr', 'show_reviews', {
//                        doShowContent : function () {
//                        // If the container is hidden (such as behind a tab), put code here to make it visible (open the tab).
//                            $('ul#spatabs li.current').removeClass('current');
//                            $('div.tab.current').removeClass('current');
//                            $('li a[href="#reviews"]').parent().addClass('current');
//                            $('#reviews').addClass('current').css('display', 'block');
//                        }
//                        });
                        </script>
                    </div>
                </div>
            </div>

            
            
            <br class="clear" />
            
        </div>
        <div class="findDealer col w230 last">
            <div class="inner">
                <?php get_sidebar('spaactions'); ?>
            </div>
        </div>
    </div>
    <br class="clear" />
</div>
<?php endwhile; ?>

<div class="color-selector-modal-bg" style="display: none;">
    <div class="color-selector-modal">
    	<div class="color-selector-modal-title"><h2>Spa Color Selector</h2><span><a id="close-cs-modal">close</a></div>
    	<?php get_template_part('block', 'color_selector'); ?>
	</div>
</div>

<script type="text/javascript">jQuery(document).tooltip();</script>
<?php get_footer(); ?>

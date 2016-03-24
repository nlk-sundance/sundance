<?php
/**
 * @package Sundance
 * @subpackage Sundance
 * @since Sundance 2.0
 */
 
$mainSlugArray = array('features', 'accessories', 'backyard-ideas');
$onfront = is_front_page();
$ontop = in_array( sds_getslug(), $mainSlugArray );
$istubSeries = in_array( sds_getslug(), array('880series', '780series', '680series', 'selectseries') );

$url = $_SERVER["REQUEST_URI"];
$is880 = strpos($url, '880series');
$is780 = strpos($url, '780series');
$is680 = strpos($url, '680series');
$isSel = strpos($url, 'selectseries');
$istub = ( $is880 || $is780 || $is680 || $isSel ) ? true : false;



function show_rightside_link_brochure( $c, $t = NULL ) {
		?>
			<div class="block brochure bigBlueBtn <?php echo $c; ?>">
				<div class="arrow"></div>
			    <a href="<?php echo get_permalink(2982); ?>">
			    	<?php if ( $t ) { echo $t; } else { echo 'Free Brochure'; } ?>
			    </a>
			</div>
		<?php
}
function show_rightside_link_dealer( $c, $t = NULL ) {
		?>
			<div class="block locate bigBlueBtn <?php echo $c; ?>">
			    <a href="/hot-tub-dealer-locator/">
			    	<?php if ( $t ) { echo $t; } else { echo 'Locate a Dealer'; } ?>
			    </a>
			</div>
		<?php
}
function show_rightside_link_pricing( $c, $t = NULL ) {
		?>
			<div class="block quote bigBlueBtn <?php echo $c; ?>">
			    <a href="<?php echo get_permalink(2989); ?>">
			    	<?php if ( $t ) { echo $t; } else { echo 'Get Pricing'; } ?>
			    </a>
			</div>
		<?php
}
function show_rightside_img_brochure( $c ) {
		?>
			<div class="block brochure-thumb <?php echo $c ?>" onclick="window.location='/request-literature/';">
			    <p style="display:none;"><span>52 Pages</span><br />
			    	Packed with<br />
			    	Details and<br />
			    	Features</p>
			</div>
		<?php
}



if ( $onfront ) { 
		?>
			<div id="home-dealer-locate" class="block locate bigBlueBtn" onclick="window.location='/hot-tub-dealer-locator/';">
    			<a href="/hot-tub-dealer-locator/">Locate a Dealer</a>
			</div>
		<?php
}
else if ( $ontop || ( $istub && !$istubSeries ) ) {

	show_rightside_img_brochure( 'w231 top' );
	show_rightside_link_brochure( 'h56 w231 top' );
	show_rightside_link_dealer( 'h56 w231' );
	show_rightside_link_pricing( 'h56 w231' );

}
else { 

	show_rightside_link_dealer( 'h69 w231' );
	show_rightside_link_pricing( 'h69 w231' );
	show_rightside_link_brochure( 'h69 w231 no-border bottom' );
	show_rightside_img_brochure( 'bottom' );

}

?>

<?php
/*
 *		Sidebar - Tracking Codes
 *
 *		Can just add necessary tracking codes here to be injected on all pages with 
 *		@ get_sidebar('trackingcode')
 *
 */

function sds_is_form_page() {
	$page = array(
		'request-literature',
		'get-a-quote',
		'trade-in-value',
		'truckload',
		'sundance-brochure',
		);
	if ( in_array( sds_getslug(), $page ) )
		return true;
	return false;
}
function sds_is_thanks_page() {
	$page = array(
		'trade-in-thanks',
		'thank-you',
		'thanks',
		);
	if ( in_array( sds_getslug(), $page ) )
		return true;
	return false;
}



/******************** GOOGLE TRACKING CODES ********************/
	function google_tracking_codes_header() {
		?>
			<script type="text/javascript">
				var _gaq = _gaq || [];
				_gaq.push(['_setAccount', 'UA-4045619-1']);
				_gaq.push(['_trackPageview']);
				(function() {
					var ga = document.createElement('script');
					ga.type = 'text/javascript';
					ga.async = true;
					ga.src = ('https:' == document.location.protocol ? 'https://' : 'http://') + 'stats.g.doubleclick.net/dc.js';
					var s = document.getElementsByTagName('script')[0];
					s.parentNode.insertBefore(ga, s);
				})();
			</script>
		<?php
	}
	//add_action('wp_head', 'google_tracking_codes_header'); // this is now in GTM

	function google_tracking_codes_footer() {

		// Thanks Pages
		if ( sds_is_thanks_page() ) { ?>
				<script>
					var prodName ="";  				// add a name for Form being submitted
					var transId = (new Date).getTime();
					var thankUrl = '/jh'+window.location.pathname;
					var thankTitle = prodName;
					if (!prodName || prodName.length === 0){
						var thankTitle = document.title;	
					}
					_gaq.push(['_addTrans',
						transId,           // order ID - required
						thankUrl,  // affiliation or store name
						'50',          // total - required
						'',           // tax
						'',              // shipping
						'',       // city
						'',     // state or province
						''             // country
					]);
					// add item might be called for every item in the shopping cart
					// where your ecommerce engine loops through each item in the cart and
					// prints out _addItem for each
					_gaq.push(['_addItem',
						transId,           // order ID - required
						thankUrl,           // SKU/code - required
						thankTitle,        // product name
						'',   // category or variation
						'1',          // unit price - required
						'1'               // quantity - required
					]);
					_gaq.push(['_trackTrans']); //submits transaction to the Analytics servers
				</script>
		<?php }

	}
	add_action('wp_footer', 'google_tracking_codes_footer');
/********************* END GOOGLE ******************************/


/************* ALL OTHER TRACKERS *******************/
	function pixel_ms() {
		if ( get_the_ID() == 2984 ) { ?>
				<script type="text/javascript"> if (!window.mstag) mstag = {loadTag : function(){},time : (new Date()).getTime()};</script> <script id="mstag_tops" type="text/javascript" src="//flex.atdmt.com/mstag/site/0b4c715e-1b10-4543-b6f7-a48e79c178eb/mstag.js"></script> <script type="text/javascript"> mstag.loadTag("analytics", {dedup:"1",domainId:"1188126",type:"1",revenue:"250",actionid:"28711"})</script> <noscript> <iframe src="//flex.atdmt.com/mstag/tag/0b4c715e-1b10-4543-b6f7-a48e79c178eb/analytics.html?dedup=1&domainId=1188126&type=1&revenue=250&actionid=28711" frameborder="0" scrolling="no" width="1" height="1" style="visibility:hidden;display:none"> </iframe> </noscript>
		<?php } 
		if ( get_the_ID() == 2991 ) { ?>
				<script type="text/javascript"> if (!window.mstag) mstag = {loadTag : function(){},time : (new Date()).getTime()};</script> <script id="mstag_tops" type="text/javascript" src="//flex.atdmt.com/mstag/site/0b4c715e-1b10-4543-b6f7-a48e79c178eb/mstag.js"></script> <script type="text/javascript"> mstag.loadTag("analytics", {dedup:"1",domainId:"1188126",type:"1",revenue:"250",actionid:"28710"})</script> <noscript> <iframe src="//flex.atdmt.com/mstag/tag/0b4c715e-1b10-4543-b6f7-a48e79c178eb/analytics.html?dedup=1&domainId=1188126&type=1&revenue=250&actionid=28710" frameborder="0" scrolling="no" width="1" height="1" style="visibility:hidden;display:none"> </iframe> </noscript>
		<?php }
	}
	
	function pixel_turn() { ?>
				<!-- Do Not Remove - Turn Tracking Beacon Code - Do Not Remove -->
				<!-- Advertiser Name : Sundance Spas (Gold) -->
				<!-- Beacon Name : Sundance Spa Remarketing Pixel -->
				<!-- If Beacon is placed on a Transaction or Lead Generation based page, please populate the turn_client_track_id with your order/confirmation ID -->
				<script type="text/javascript">turn_client_track_id = "";</script>
				<script type="text/javascript" src="http://r.turn.com/server/beacon_call.js?b2=ig_outROcC9hZdoiZUTitPTAIm7cZMBYWwssug9Uz3y3uMS2pytS1vOwLYK93uS7Sp_jyZw7bxk-eF3bDn0uvg"></script>
				<noscript><img border="0" src="http://r.turn.com/r/beacon?b2=ig_outROcC9hZdoiZUTitPTAIm7cZMBYWwssug9Uz3y3uMS2pytS1vOwLYK93uS7Sp_jyZw7bxk-eF3bDn0uvg&amp;cid="></noscript>
				<!-- End Turn Tracking Beacon Code Do Not Remove -->
	<?php }

	function pixel_sitecatalyst() { ?>
				<!-- SiteCatalyst code version: H.15.1.
				Copyright 1997-2008 Omniture, Inc. More info available at
				http://www.omniture.com -->
				<script language="JavaScript" type="text/javascript" src="<?php echo get_bloginfo('url'); ?>/Omniture/Omniture_SundanceSpas_Code.js"></script>
				<script language="JavaScript" type="text/javascript"><!--
				/* You may give each page an identifying name, server, and channel on
				the next lines. */
				var s = s || [];
				s.pageName=""
				s.server=""
				s.channel=""
				s.pageType=""
				s.prop1=""
				s.prop2=""
				s.prop3=""
				s.prop4=""
				s.prop5=""
				/* Conversion Variables */
				s.campaign=""
				s.state=""
				s.zip=""
				s.events=""
				s.products=""
				s.purchaseID=""
				s.eVar1=""
				s.eVar2=""
				s.eVar3=""
				s.eVar4=""
				s.eVar5=""
				/************* DO NOT ALTER ANYTHING BELOW THIS LINE ! **************/
				var s_code=s.t();if(s_code)document.write(s_code)//--></script>
				<script language="JavaScript" type="text/javascript"><!--
				if(navigator.appVersion.indexOf('MSIE')>=0)document.write(unescape('%3C')+'\!-'+'-')
				//--></script><noscript><a href="http://www.omniture.com" title="Web Analytics"><img
				src="http://sundancespas.112.2O7.net/b/ss/sundancespas/1/H.15.1--NS/0"
				height="1" width="1" border="0" alt="" /></a></noscript><!--/DO NOT REMOVE/-->
				<!-- End SiteCatalyst code version: H.15.1. -->
	<?php }

	function quantcast_tag() { 
		if ( is_front_page() ) { ?>
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
			{qacct:"p-TAV6snse1j2Rf",labels:"_fp.event.Sundance Spas Home Page"}
			);
			</script>
			<noscript>
			<img src="//pixel.quantserve.com/pixel/p-TAV6snse1j2Rf.gif?labels=_fp.event.Sundance+Spas+Home+Page" style="display: none;" border="0" height="1" width="1" alt="Quantcast"/>
			</noscript>
			<!-- End Quantcast tag -->
		<?php }
	}

	function pixel_bazaarinvoice() {

		global $post;
		$custom = get_post_meta($post->ID,'s_specs');
		$s_specs = isset($custom[0]) ? $custom[0] : $custom;
		$prod = isset($s_specs['product_id']) ? esc_attr($s_specs['product_id']) : false;
		$val = get_post_meta( $post->ID, 'lead-type', true );

		if ( $prod ) { ?>
			<script type="text/javascript"> 
			$BV.configure("global", { productId : "<?php echo $prod; ?>" });
			</script>
		<?php }
		
		if ( $val ) { ?>
			<script>
			$BV.SI.trackConversion({
			"type" : "lead",
			"value" : "<?php echo $val; ?>"
			});
			</script>
		<?php }
	}

	if (sds_my_server() !== 'local') {
		add_action('wp_footer', 'pixel_ms');
		add_action('wp_footer', 'pixel_turn');
		add_action('wp_footer', 'pixel_sitecatalyst');
		add_action('wp_footer', 'quantcast_tag');
		add_action('wp_head', 'pixel_bazaarinvoice');
	}

/************* END OF THE OTHERS ********************/

?>

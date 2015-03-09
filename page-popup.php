<?php
/**
 * Template Name: Popup
 *
 * @package Sundance
 * @subpackage Sundance
 * @since Sundance 2.0
 */

//get_header(); ?>
<html>
<head>
<TITLE>Sundance Spas</TITLE>
<META NAME="MSSmartTagsPreventParsing" CONTENT="TRUE">
<style type="text/css">
body { text-align: center; font: 11px/14px Tahoma, Arial, Helvetica, sans-serif; color: #786c61; background-color: #fff; }
div { width: <?php echo is_page('color-picker') ? '714' : '400'; ?>px; margin: 0 auto; text-align: left; }
h2 { margin: 0; padding: 0; font: 15px/17px Verdana, Arial, Helvetica, sans-serif; font-weight: normal; color: #949c51; }
a { color: #236dc7; text-decoration: none; }
a:hover { text-decoration: underline; }
</style>
<!-- BEGIN GA CODE -->
<script type="text/javascript">

  var _gaq = _gaq || [];
  _gaq.push(['_setAccount', 'UA-4045619-1']);
  _gaq.push(['_trackPageview']);

  (function() {
    var ga = document.createElement('script'); ga.type = 'text/javascript'; ga.async = true;
    ga.src = ('https:' == document.location.protocol ? 'https://ssl' : 'http://www') + '.google-analytics.com/ga.js';
    var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(ga, s);
  })();

</script>
<!-- END GA CODE -->
<?php if ( is_page('color-picker') ) { ?><script type="text/javascript" src="<?php bloginfo('url'); ?>/wp-includes/js/swfobject.js"></script><?php } ?>
</head>
<body>
<div>
<?php while ( have_posts() ) : the_post();
the_content();
endwhile; ?>
<p align="right"><a href="javascript:window.close();">close</a></p>
</div>
<!-- Do Not Remove - Turn Tracking Beacon Code - Do Not Remove -->
<!-- Advertiser Name : Sundance Spas (Gold) -->
<!-- Beacon Name : Sundance Spa Remarketing Pixel -->
<!-- If Beacon is placed on a Transaction or Lead Generation based page, please populate the turn_client_track_id with your order/confirmation ID -->

<script type="text/javascript">
  turn_client_track_id = "";
</script>
<script type="text/javascript" src="http://r.turn.com/server/beacon_call.js?b2=ig_outROcC9hZdoiZUTitPTAIm7cZMBYWwssug9Uz3y3uMS2pytS1vOwLYK93uS7Sp_jyZw7bxk-eF3bDn0uvg">
</script>
<noscript>
  <img border="0" src="http://r.turn.com/r/beacon?b2=ig_outROcC9hZdoiZUTitPTAIm7cZMBYWwssug9Uz3y3uMS2pytS1vOwLYK93uS7Sp_jyZw7bxk-eF3bDn0uvg&cid=">
</noscript>
<!-- End Turn Tracking Beacon Code Do Not Remove -->
</body>
</html>

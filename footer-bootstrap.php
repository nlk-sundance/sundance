<?php
/**
 * The template for displaying the footer.
 *
 * @package Sundance
 * @subpackage Sundance
 * @since Sundance 2.0
 */
?>
<section class="wrapper footercontainer">
  		<div class="container smallcontainer">
  			<div class="row">
  				<div class="col-xs-12 col-sm-8 col-md-8">
  					<h1 class="smalllogo"><a href="<?php bloginfo('url'); ?>">logo</a></h1>
  				</div>
  				<div class="col-xs-12 col-sm-4 col-md-4">
  					<ul class="footericon">
  						<li class="facebook"><a href="http://www.facebook.com/SundanceSpas">facebook</a></li>
  						<li class="twitter"><a href="http://twitter.com/sundance_spas">twitter</a></li>
  						<li class="youtube"><a href="http://www.youtube.com/sundancespas">youtube</a></li>
  						<li class="google"><a href="https://plus.google.com/107104241400965217576">google</a></li>
  					</ul>
  				</div>
  				<div class="col-xs-12 col-sm-12 col-md-12">
  				<ul class="spa">
  					<li class="border" ><a href="<?php bloginfo('url'); ?>/hot-tubs-and-spas/">Tubs</a></li>
  					<li class="border"><a href="<?php bloginfo('url'); ?>/accessories/">Spa Accessories</a></li>
  					<li class="border"><a href="<?php bloginfo('url'); ?>/get-a-quote/">Get Pricing</a></li>
  					<li><a href="<?php bloginfo('url'); ?>/customer-care/">Owners</a></li>
  				</ul>
  				</div>
  				<div class="col-xs-12 col-sm-12 col-md-12">
  				<ul class="footer-links">
  					<li class="border"><a href="<?php bloginfo('url'); ?>">Home</a></li>
  					<li class="border"><a href="<?php bloginfo('url'); ?>/about-us/">About Us</a></li>
  					<li class="border"><a href="<?php bloginfo('url'); ?>/about-us/press-room/">Press room</a></li>
  					<li class="border"><a href="<?php bloginfo('url'); ?>/become-a-dealer/">Become a Dealer</a></li>
  					<li class="border"><a href="<?php bloginfo('url'); ?>/#resources">Resources</a></li>
  					<li class="border"><a href="<?php bloginfo('url'); ?>/contact/">Contact</a></li>
  					<li class="border"><a href="<?php bloginfo('url'); ?>/customer-care/">Warrenty/Registration</a></li>
  					<li class="border"><a href="<?php bloginfo('url'); ?>/about-us/privacy-policy/">Policies</a></li>
  					<li><a href="<?php bloginfo('url'); ?>/site-map/">Sitemap</a></li>
  				</ul>
  				</div>
  				<div class="col-xs-12 col-sm-12 col-md-12">
  				<p>&#169;<?php echo date('Y'); ?> Sundance Spas-all right reserved</p>
  				</div>
  			</div>
  		</div>
  	</section>
  	
<?php
	/* Always have wp_footer() just before the closing </body>
	 * tag of your theme, or you will break many plugins, which
	 * generally use this hook to reference JavaScript files.
	 */

	wp_footer();
?>

</body>
</html>

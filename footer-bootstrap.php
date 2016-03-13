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
  					<?php get_footer('block_social'); ?>
  				</div>
  				<div class="col-xs-12 col-sm-12 col-md-12">
  				  <?php get_footer('block_menu1'); ?>
  				</div>
  				<div class="col-xs-12 col-sm-12 col-md-12">
  				  <?php get_footer('block_menu2'); ?>
  				</div>
  				<div class="col-xs-12 col-sm-12 col-md-12">
  				<p>&#169;<?php echo date('Y'); ?> Sundance Spas - all rights reserved</p>
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

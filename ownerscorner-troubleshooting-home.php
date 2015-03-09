<?php
/**
 * Template Name: Owners Corner > Troubleshooting -> Home
 *
 * @package Sundance
 * @subpackage Sundance
 * @since Sundance 2.0
 */

get_header(); ?>

<div class="cols owners-corner">
	<?php while ( have_posts() ) : the_post(); ?>
	<div class="main col w730">
		<div class="main-title">
			<?php the_post_thumbnail( array(729, 320) ); ?>
			<h1><?php sundance_pagetitle(); ?></h1>
		</div>
		<div class="page">
			<div class="entry-content">
				<?php the_content(); ?>
				<br />
				<h2>880 Series Interactive Guides</h2>
				<hr />
				<br />
				<a id="troubleshooting-display-messages-btn" href="/customer-care/troubleshooting-display-messages/">Troubleshooting Display Messages</a><a id="troubleshooting-procedures-btn" href="/customer-care/troubleshooting-procedures/">Troubleshooting Procedures</a>
				<p>&nbsp;</p>
				<p>&nbsp;</p>
				<p>&nbsp;</p>
				<h2>Troubleshoot Guides in PDF format</h2>
				<hr />
				<div style="position: relative; float: right; top: -50px;"><a href="https://get.adobe.com/reader/"><img src="<?php echo get_template_directory_uri(); ?>/images/icons/adobe.png" height="24"/>&nbsp;Get Adobe Acrobat</a><span class="tool-tip-thing" data-tip="Lorem ipsum">?</span></div>
				<p>&nbsp;</p>
				<ul>
					<li>· <a href="<?php echo get_template_directory_uri(); ?>/pdfs/SunSmart_WiFi_Connection_Troubleshooting-2.pdf" target="_blank">SunSmart™ WI-FI Bridge Connection</a> (56k)</li>
				</ul>
				<p>&nbsp;</p>
				<hr />
				<p>More Guides Coming Soon...</p>
				<p>Contact Sundance® Spas Customer Service at (800) 497-7727 option 8 then extension 1920 for more information.</p>
			</div>
		</div>
	</div>
	<?php
	endwhile;
	
	get_sidebar('generic');
	?>
	
</div>

<script>
jQuery(function($){
	$('.tool-tip-thing').tipr();
});
</script>

<br class="clear" />
<?php get_footer(); ?>

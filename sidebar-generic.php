<?php
/**
 * @package Sundance
 * @subpackage Sundance
 * @since Sundance 2.0
 */
?>
<div class="side col w230 last">
	
	<?php if ( is_front_page() == false ) { ?>
	<div class="rightCol">
		<?php get_sidebar('rt4'); ?>
	</div>
	<?php } ?>
	
	<div class="inner">
		<ul class="list avenir65-12">
			<?php if ( is_front_page() ) { ?>
				<li><a href="<?php echo get_permalink(2982); ?>" class="arrowRight">Free Brochure</a></li>
				<li><a href="<?php echo get_permalink(2989); ?>" class="arrowRight">Get Pricing</a></li>
				<li><a href="#signup" class="esignup arrowRight">Email Sign Up</a></li>
				<li><a href="<?php echo get_permalink(2978); ?>" class="arrowRight">View Special Offers</a></li>
			<?php } else { ?>
				<li><a href="#signup" class="esignup arrowRight">Email Sign Up</a></li>
				<li><a href="<?php echo get_permalink(4939); ?>" class="arrowRight">Trade-In Value</a></li>
				<li><a href="<?php echo get_permalink(2974); ?>" class="arrowRight">Finance Your Hot tub</a></li>
				<li><a href="<?php echo get_permalink(2978); ?>" class="arrowRight">View Special Offers</a></li>
			<?php } ?>
			<?php /* <li><a href="#search" class="arrowRight">Search</a>
			<?php get_search_form(); ?>
			</li> */ ?>
			<?php get_sidebar('socialicons'); ?>
		</ul>
		<div class="thirtyYears"></div>
		<?php /*
		<div class="sizzle">
		<a href="/specials/">Learn More</a>
		</div>
		*/ ?>
	</div>
</div>

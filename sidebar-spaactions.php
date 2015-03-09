<?php
/**
 * @package Sundance
 * @subpackage Sundance
 * @since Sundance 2.0
 */
?>
<h2>Find Your Nearest Dealer</h2>
<form action="<?php bloginfo('url'); ?>/hot-tub-dealer-locator/cities/" method="post">
<input type="text" value="Your Zip Code" class="text" onfocus="if(jQuery(this).val()=='Your Zip Code') jQuery(this).addClass('noi').val(''); " onblur="if(jQuery(this).val()=='') jQuery(this).removeClass('noi').val('Your Zip Code');" name="zip" />
<input type="image" src="<?php bloginfo('template_url'); ?>/images/icons/search-arrow.jpg" value="submit" />
</form>
<ul class="actions">
    <li><a href="<?php echo get_permalink(2982); ?>"><span class="icon brochure-sm"></span> Request a free brochure</a></li>
    <li><a href="<?php echo get_permalink(2974); ?>"><span class="icon sundance"></span> Get financed</a></li>
    <li><a href="#signup" class="esignup"><span class="icon email"></span> Email sign up</a></li>
    <li><a href="http://www.facebook.com/SundanceSpas" target="_blank" title="join us on facebook"><span class="icon fb"></span></a><a href="http://twitter.com/sundance_spas" target="_blank" title="follow us on twitter"><span class="icon tw"></span></a><a href="http://www.youtube.com/sundancespas" target="_blank" title="watch us on youtube"><span class="icon yt"></span></a><a href="<?php echo get_permalink(1892); ?>" title="read our blog"><span class="icon rss"></span></a></li>
</ul>
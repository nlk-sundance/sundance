<?php
$casearch = false;
if ( defined("SDSCA") ) {
	if ( SDSCA ) {
		$casearch = true;
	}
}
?>
<form id="dealer-finder" method="post" action="<?php echo trailingslashit(get_bloginfo('url')); ?>hot-tub-dealer-locator/cities/">
	<a href="/hot-tub-dealer-locator/">Find a Dealer</a>
	<input type="text" class="text" name="<?php echo $casearch ? 'data[Dealer][zip]' : 'zip'; ?>" id="zip" value="zip code" onfocus="if(jQuery(this).val()=='zip code') jQuery(this).val('');" onblur="if(jQuery(this).val()=='') jQuery(this).val('zip code');" />
	<input type="hidden" name="zipcodeSearch" value="1" />
	<?php if ( $casearch ) { ?>
		<input type="hidden" name="data[Dealer][country_id]" value="3" />
	<?php } ?>
	<input type="image" src="<?php bloginfo('template_url'); ?>/images/icons/submitArrow.jpg" value="submit" class="submit" />
</form>
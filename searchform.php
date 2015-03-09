<?php
/**
 * The template for displaying search form
 *
 * @package Sundance
 * @subpackage Sundance
 * @since Sundance 2.0
 */
?>
	<form method="get" class="searchform" action="<?php echo esc_url( home_url( '/' ) ); ?>">
		<input type="text" class="field" name="s" id="s" placeholder="<?php esc_attr_e( 'SEARCH', 'sundance' ); ?>" />
		<input type="image" src="<?php bloginfo('template_url'); ?>/images/icons/search-btn.png" class="submit" name="submit" id="searchsubmit" value="submit" />
	</form>

<?php
/**
 * @package Sundance
 * @subpackage Sundance
 * @since Sundance 2.0
 */
?>
<div class="side col w230 last buyerguidesidebar">
	<div class="inner">
		<?php
			wp_nav_menu(array('menu' => 'buyersguidemenu', 'depth' => 1, 'container' => false, 'menu_class' => '', 'fallback_cb' => 'wp_page_menu', ));
		?>
	</div>
</div>

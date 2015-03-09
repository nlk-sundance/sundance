<?php

/*
 * Template Part : Startup Guide
 * 
 * Page : Landing
 *
 */


get_template_part('startupguide', 'landing-css');

?>

<div id="startupguide" class="container">

	<div class="sg-left-col">

		<?php get_template_part('startupguide', 'landing-nav'); ?>

	</div>

	<div class="sg-right-col">

		<?php get_template_part('loader', 'loading'); ?>

		<?php get_template_part('startupguide', 'landing-spas'); ?>

		<div id="sg-spa-container"></div>

	</div>

	<div class="clearfix">&nbsp;</div>

</div>

<?php

/*
 * Template Part : Startup Guide Nav
 *
 */




global $tubcats;

?>

<!-- Series Nav Bar -->
<nav id="startup-nav-series" class="active">

	<ul class="nav-series">

		<li class="nav-title">SELECT YOUR SPA</li>

		<?php foreach ( $tubcats as $s ) { ?>

			<li id="sg-nav-series-<?php echo strtolower(esc_attr($s['name'])); ?>" class="series" series="<?php echo strtolower(esc_attr($s['name'])); ?>"><span><?php echo strtolower(esc_attr($s['name'])); ?></span> Series</li>

		<?php } ?>

	</ul>

</nav>


<!-- Tub Details Nav Bar -->
<nav id="startup-nav-spa">

	<ul class="nav-spa">

		<li icon="lt" class="nav-title show-all">All Spas</li>

		<li id="show-air-control" class="details">

			<div icon="air-control" class="wrapr">

				<span>Air<br />Control</span>

			</div>

			<div>

				<p>The spa air controls allow you to adjust the amount of air flow going into your jets to create movement and the optimal hydrotherapy session.</p>

				<button show="sg-details-air">Explore More</button>

			</div>

		</li>

		<li id="show-massage-selector" class="details">

			<div icon="massage-selector" class="wrapr">

				<span>Massage<br />Selector</span>

			</div>

			<div>

				<p>The massage selector allows you to control the direction of the water flow and intensity of your massage for a customized spa experience.</p>

				<button show="sg-details-massage">Explore More</button>

			</div>

		</li>

		<li id="show-control-panel" class="details">

			<div icon="control-panel" class="wrapr">

				<span>Control<br />Panel</span>

			</div>

			<div>

				<p>The i-Touch<sup>&trade;</sup> Control Panel gives you complete control of your spa at your fingertips. With a convenient backlit LCD display, the i-Touch<sup>&trade;</sup> Control Panel is easy to read at all times.</p>

				<button show="sg-details-panel">Explore More</button>

			</div>

		</li>

	</ul>

</nav>

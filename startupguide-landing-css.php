<?php

$colors = array(
	'sgtan' => '#F1EBE1',
	'sgtan-hover' => '#F9F5EF',
	'sgtan-border' => '#E6DECE',
	'sgtan-title' => '#A49383',
	'sgblue' => '#445463',
	'sgblue-hover' => '#002B49',
	'sgblue-highlight' => '#206CC5'
	);

?>
<style type="text/css">

/* - - - FIXES - - - */

/* this hides menu options for tub series that are not yet ready */
#startup-nav-series li[series="select"],
#startup-nav-series li[series="780"],
#startup-nav-series li[series="680"] {
	display: none;
}



#startupguide {
	background-color: <?php echo $colors['sgtan']; ?>;
}
.sg-left-col {
	background-color: <?php echo $colors['sgtan']; ?>;
	box-sizing: border-box;
	float: left;
	margin: 0;
	min-height: 600px;
	position: relative;
	padding: 0;
	width: 220px;
}
.sg-left-col nav {
	background-color: <?php echo $colors['sgtan']; ?>;
	display: block;
	position: absolute;
	left: 0;
	top: 0;
	width: 220px;
}

.sg-right-col {
	background-color: #fff;
	float: left;
	min-height: 740px;
	position: relative;
	width: calc(100% - 220px);
}

/* - - - - - ICONS - - - - - */
	[icon="lt"]:before,
	[icon] span:before {
		background: url("<?php echo get_template_directory_uri(); ?>/images/startup/iconset.png") no-repeat 0 0;
		content: "";
		display: inline-block;
		float: left;
		height: 36px;
		margin-right: 10px;
		width: 36px;

	}
	[icon="air-control"] span:before {
		background-position: 0 0;
	}
	[icon="massage-selector"] span:before {
		background-position: 0 -36px;
	}
	[icon="control-panel"] span:before {
		background-position: 0 -72px;
	}

	li.details [icon] span:after {
		background: url("<?php echo get_template_directory_uri(); ?>/images/startup/dd.png") no-repeat 0 100%;
		content: "";
		display: inline-block;
		float: right;
		height: 6px;
		margin-right: 0px;
		margin-top: -6px;
		width: 11px;
	}
	li.details.active [icon] span:after {
		background: url("<?php echo get_template_directory_uri(); ?>/images/startup/dd.png") no-repeat 0 0%;
	}

	[icon="lt"]:before {
		background: url("<?php echo get_template_directory_uri(); ?>/images/startup/lt.png") no-repeat 0 0;
		height: 13px;
		margin-bottom: -1px;
		margin-left: 0px;
		margin-right: 10px;
		width: 8px;
	}

/* - - - - - LEFT MENU - - - - - */

	/* menu <li> base styles */

		#startup-nav-series,
		#startup-nav-spa {
			display: none;
		}
		#startup-nav-series.active,
		#startup-nav-spa.active {
			display: block;
			z-index: 99;
		}

		#startup-nav-series ul li,
		#startup-nav-spa ul li {
			background-color: transparent;
			border-bottom: 1px solid <?php echo $colors['sgtan-border']; ?>;
			box-sizing: border-box;
			display: inline-block;
			width: 100%;
		}
		#startup-nav-series ul li,
		#startup-nav-spa ul li div {
			padding: 18px 20px 8px;
		}
		#startup-nav-series ul li {
			padding: 28px 20px 18px;
		}



	/* menu title <li> */

		#startup-nav-series ul li.nav-title,
		#startup-nav-spa ul li.nav-title {
			color: <?php echo $colors['sgtan-title']; ?>;
			font: 13px "AM", Tahoma, sans-serif;
			padding: 38px 20px 8px;
			text-transform: uppercase;
		}



	/* menu series <li>\'s */

		#startup-nav-series ul li.series {
			color: #786C5F;
			cursor: pointer;
			font: 20px/22px "AM", Tahoma, sans-serif;
			opacity: .333;
			text-transform: lowercase;
			-webkit-transition: opacity .35s, background-color .35s;
			transition: opacity .35s, background-color .35s;
		}
		
		#startup-nav-series ul li.series span {
			font: 30px/22px "AL", Tahoma, sans-serif;
		}
		
		#startup-nav-series ul li.series.active,
		#startup-nav-series ul li.series:hover {
			background-color: <?php echo $colors['sgtan-hover']; ?>;
			opacity: 1;
		}
		
		

	/* menu spa details <li>\'s and spans */

		#startup-nav-spa ul li.nav-title {
			cursor: pointer;
		}

		#startup-nav-spa ul li.details {
			height: 71px;
			overflow: hidden;
			-webkit-transition: background-color .35s;
			transition: background-color .35s;
		}
		#startup-nav-spa ul li.details.active {
			background-color: <?php echo $colors['sgtan-hover']; ?>;
			height: auto;
		}
		#startup-nav-spa ul li.details:hover {
			background-color: <?php echo $colors['sgtan-hover']; ?>;
		}
		#startup-nav-spa ul li.details div.wrapr {
			box-sizing: border-box;
			cursor: pointer;
			height: 71px;
			width: 100%;
		}
		#startup-nav-spa ul li.details div.wrapr span {
			color: #483629;
			font: 18px/22px "AM", Tahoma, sans-serif;
			padding: 20px 20px 12px 0px;
			text-transform: uppercase;
		}
		
		#startup-nav-spa ul li.details div p {
			font: 13px/20px Verdana, sans-serif;
			margin: 0;
		}

		#startup-nav-spa ul li.details button {
			background-color: <?php echo $colors['sgblue']; ?>;
			border: none;
			border-radius: 99px;
			box-sizing: border-box;
			color: #fff;
			cursor: pointer;
			font: 13px/26px "AM", Tahoma, sans-serif;
			height: 25px;
			margin: 20px 0;
			text-align: center;
			text-transform: uppercase;
			width: 100%;
			-webkit-transition: background-color .35s;
			transition: background-color .35s;
		}
		#startup-nav-spa ul li.details button:after {
			background: url("<?php echo get_template_directory_uri(); ?>/images/startup/lt.png") no-repeat 100% 0;
			content: "";
			display: inline-block;
			float: right;
			height: 13px;
			margin-right: 18px;
			margin-top: 4px;
			width: 8px;
		}

		#startup-nav-spa ul li.details button:hover {
			background-color: <?php echo $colors['sgblue-hover']; ?>;
		}

/* - - - - - SERIES GRID - - - - - */
	/* series landing */
	.sgseries {
		box-sizing: border-box;
		display: none;
		padding: 20px;
		position: relative;
		top: 0;
		width: 100%;
	}
	.sgseries.active {
		display: block;
	}
	.sgspa {
		display: inline-block;
		float: left;
		margin: 10px;
		min-height: 202px;
		position: relative;
		text-align: center;
	}
	.sgspa img {
		height: auto;
		max-height: 150px;
		max-width: 150px;
		width: auto;
	}
	.sgspa h6 {
		color: #786C5F !important;
		font: 18px/24px Tahoma, sans-serif !important;
		margin: 10px 0 18px;
		text-align: center;
	}
	.sgspa:hover h6 {
		color: <?php echo $colors['sgblue-highlight']; ?> !important;
	}
	.sgspa a {
		background-color: rgb(245, 245, 245);
		background-color: rgba(245, 245, 245, .9);
		border-radius: 8px;
		box-shadow: 0px 0px 12px rgba(0,0,0,.5);
		color: #206CC5;
		cursor: pointer;
		display: block;
		font: 14px/30px "AM", sans-serif;
		height: 27px;
		left: 50%;
		margin-top: -27px;
		margin-left: -50px;
		opacity: 0;
		position: absolute;
		text-decoration: none;
		top: 80px;
		width: 100px;
		-webkit-transition: opacity .35s;
		transition: opacity .35s;
	}
	.sgspa:hover a {
		opacity: 1;
		text-decoration: none;
	}

/* - - - - - DETAILS - - - - - */

	#startupguide #sg-spa-container {
		display: none;
	}
	#startupguide section.sg-details {
		display: none;
	}
	#startupguide section.sg-details.active {
		display: block;
	}
	#startupguide section.sg-details h4 {
		font: 200 16px "AL", sans-serif;
		margin: 10px 0 6px;
	}
	#startupguide section.sg-details h4 span {
		font: 24px "AM", sans-serif;
	}
	#startupguide #sg-details-landing {
		background: url("<?php echo get_template_directory_uri(); ?>/images/startup/sg-landing.png") no-repeat 0 0;
		width: 100%;
		min-height: 740px;
	}
	#startupguide #sg-details-landing span {
		display: block;
		padding-top: 200px;
		text-align: center;
	}
	#startupguide #sg-details-landing h1 {
		border-top: 1px solid #666;
		color: #fff;
		display: inline-block;
		font-size: 120px;
		line-height: 1em;
		margin: auto;
		padding-top: 20px;
		text-align: center;
		text-transform: uppercase;
	}
	#startupguide #sg-details-landing h3 {
		color: #fff;
		font: 200 30px/30px "AL", sans-serif;
		line-height: 1em;
		margin: 0 auto 20px;
		text-align: center;
		text-transform: uppercase;
	}

	/* air controls section */
		#startupguide #sg-details-air {
			padding: 0;
			position: relative;
		}
		#sg-air-landing-container {
			padding: 10px 20px;
		}
		#sg-air-overhead {
			position: relative;
		}
		#sg-air-overhead img.sg-air-overhead {
			left: 0;
			position: relative;
			top: 0;
		}
		#sg-air-overhead img.sg-air-overhead-selectables,
		#sg-air-overhead img.sg-air-layer {
			position: absolute;
			left: 0;
			top: 0;
		}
		#sg-air-overhead img.sg-air-layer {
			display: none;
		}
		#sg-air-overhead img.sg-air-layer.active,
		#sg-air-overhead img.sg-air-layer.over {
			display: block;
			z-index: 7;
		}
		#sg-air-overhead a.sg-air-anchor {
			background-color: transparent;
			cursor: pointer;
			height: 56px;
			position: absolute;
			width: 56px;
			z-index: 9;
		}
		

	/* massage controls section */
		#startupguide #sg-details-massage {
			padding: 0;
			position: relative;
		}
		#sg-massage-landing-container {
			padding: 10px 20px;
		}
		#sg-massage-overhead {
			position: relative;
		}
		#sg-massage-overhead img.sg-massage-overhead {
			left: 0;
			position: relative;
			top: 0;
		}
		#sg-massage-overhead img.sg-massage-overhead-selectables,
		#sg-massage-overhead img.sg-massage-layer {
			position: absolute;
			left: 0;
			top: 0;
		}
		#sg-massage-overhead img.sg-massage-layer {
			display: none;
		}
		#sg-massage-overhead img.sg-massage-layer.active,
		#sg-massage-overhead img.sg-massage-layer.over {
			display: block;
			z-index: 7;
		}
		#sg-massage-overhead a.sg-massage-anchor {
			background-color: transparent;
			cursor: pointer;
			height: 56px;
			position: absolute;
			width: 56px;
			z-index: 9;
		}
		#sg-massage-overhead #sg-massage-top-bar {
			background-color: <?php echo $colors['sgtan-hover']; ?>;
			box-sizing: border-box;
			height: 60px;
			padding: 24px 20px;
			position: absolute;
			width: 100%;
			z-index: 11;
		}
		#sg-massage-overhead #sg-massage-top-bar p {
			color: #463427;
			display: inline-block;
			float: left;
			font: 13px "AM", sans-serif;
			margin-left: 90px;
			margin-right: 30px;
			text-transform: uppercase;
		}
		#sg-message-slider {
			background-image: none;
			background-color: #C4C8CD;
			border: none;
			display: inline-block;
			float: left;
			height: 4px;
			margin-top: 3px;
			width: 260px;
		}
		#sg-message-slider a {
			background-color: #445463;
			background-image: none;
			border: none;
			border-radius: 99px;
			margin-top: -4px;
		}
		


	/* control panel section landing */
		#startupguide #sg-details-panel {
			padding: 0;
			position: relative;
		}

	/* CP landing page */
		#startupguide #sg-panel-landing-container {
			display: none;
			min-height: 740px;
			margin: 0;
			overflow: hidden;
			padding: 0;
			position: relative;
			width: 100%;
			z-index: 9;
		}
		#startupguide #sg-panel-landing-container.active {
			display: block;
		}
		#startupguide #sg-panel-landing-container img {
			height: auto;
			left: 0;
			margin: 0;
			padding: 0;
			position: relative;
			top: 0;
			width: 100%;
		}
		#startupguide #sg-panel-landing-container button#sg-panel-explore-jets {
			background-color: transparent;
			background-image: url("<?php echo get_template_directory_uri(); ?>/images/startup/sg-explore-jets-btn.png");
			background-position: 0 0;
			border: none;
			cursor: pointer;
			height: 49px;
			left: 50%;
			margin-left: -122px;
			position: absolute;
			top: 440px;
			width: 243px;
		}
		#startupguide #sg-panel-landing-container button#sg-panel-explore-jets:hover {
			background-position: 0 -49px;
		}
		#startupguide #sg-panel-landing-container #sg-panel-landing-rightbar {
			background-color: <?php echo $colors['sgblue']; ?>;
			box-sizing: border-box;
			color: #fff;
			float: right;
			height: 100%;
			height: calc(100% - 1px);
			min-height: 740px;
			opacity: .935;
			padding: 0;
			position: absolute;
			right: 0;
			top: 0;
			width: 220px;
		}
		#startupguide #sg-panel-landing-container #sg-panel-landing-rightbar.closed {
			white-space: nowrap;
			width: 40px;
		}
		#startupguide #sg-panel-landing-container #sg-panel-landing-rightbar.closed > div h3:after {
			display: none;
		}
		#startupguide #sg-panel-landing-container #sg-panel-landing-rightbar > div {
			border-bottom: 1px solid #66737F;
			border-bottom: 1px solid rgba(255, 255, 255, .15);
			box-sizing: border-box;
			cursor: pointer;
			height: 50px;
			min-height: 50px;
			overflow: hidden;
			padding: 10px;
			position: relative;
			width: 100%;
		}
		#startupguide #sg-panel-landing-container #sg-panel-landing-rightbar > div.active {
			height: auto;
			min-height: 50px;
		}
		#startupguide #sg-panel-landing-container #sg-panel-landing-rightbar > div h3:before {
			background-image: url("<?php echo get_template_directory_uri(); ?>/images/startup/sg-cp-rightbar.png");
			background-repeat: no-repeat;
			content: '';
			display: block;
			float: left;
			height: 24px;
			margin-right: 10px;
			width: 24px;
		}
		#startupguide #sg-panel-landing-container #sg-panel-landing-rightbar > div#sg-panel-landing-rightbar-openclose:before {
			background-image: url("<?php echo get_template_directory_uri(); ?>/images/startup/sg-cp-rightbar.png");
			background-position: -16px 0;
			background-repeat: no-repeat;
			content: '';
			display: block;
			float: right;
			height: 13px;
			margin: 10px 2px 0 0;
			width: 8px;
		}
		#startupguide #sg-panel-landing-container #sg-panel-landing-rightbar.closed > div#sg-panel-landing-rightbar-openclose:before {
			background-position: 0 0;
		}
		#startupguide #sg-panel-landing-container #sg-panel-landing-rightbar > div#sg-panel-landing-rightbar-temp h3:before {
			background-position: 0 -16px;
			height: 23px;
		}
		#startupguide #sg-panel-landing-container #sg-panel-landing-rightbar > div#sg-panel-landing-rightbar-lighting h3:before {
			background-position: 0 -40px;
			height: 25px;
		}
		#startupguide #sg-panel-landing-container #sg-panel-landing-rightbar > div#sg-panel-landing-rightbar-setting h3:before {
			background-position: 0 -66px;
			height: 22px;
		}

		#startupguide #sg-panel-landing-container #sg-panel-landing-rightbar > div h3 {
			font: 15px/30px "AM", sans-serif;
			margin-top: 3px;
			text-transform: uppercase;
		}
		#startupguide #sg-panel-landing-container #sg-panel-landing-rightbar > div h3:after {
			background-image: url("<?php echo get_template_directory_uri(); ?>/images/startup/sg-cp-rightbar-oc.png");
			background-repeat: no-repeat;
			background-position: 0 0;
			content: '';
			display: block;
			height: 6px;
			position: absolute;
			right: 10px;
			top: 22px;
			width: 11px;
		}
		#startupguide #sg-panel-landing-container #sg-panel-landing-rightbar > div:hover h3:after {
			background-position: 0 -6px;
		}
		#startupguide #sg-panel-landing-container #sg-panel-landing-rightbar > div.active h3:after {
			background-position: 0 -12px;
		}
		#startupguide #sg-panel-landing-container #sg-panel-landing-rightbar > div p {
			font: 13px/20px Verdana, sans-serif;
			padding: 8px 10px;
		}

	/* CP Overhead page */
		#startupguide #sg-panel-overhead-container {
			display: none;
			padding: 10px 24px;
			position: relative;
		}
		#startupguide #sg-panel-overhead-container.active {
			display: block;
		}

	/* CP details - top bar */
		#startupguide #sg-details-panel #sg-panel-overhead-container #sg-panel-top-bar {
			background-color: <?php echo $colors['sgtan']; ?>;
			box-sizing: border-box;
			display: block;
			height: 60px;
		}
		#startupguide #sg-details-panel #sg-panel-overhead-container #sg-panel-top-bar .sg-back-arrow {
			background-color: <?php echo $colors['sgblue']; ?>;
			cursor: pointer;
			float: left;
			height:60px;
			width: 35px;
		}
		#startupguide #sg-details-panel #sg-panel-overhead-container #sg-panel-top-bar .sg-back-arrow span {
			background-image: url("<?php echo get_template_directory_uri(); ?>/images/startup/lt.png");
			background-position: 0 -31px;
			display: block;
			height: 13px;
			margin: 24px auto;
			width: 8px;
		}
		#startupguide #sg-details-panel #sg-panel-overhead-container #sg-panel-top-bar p {
			display: inline-block;
			float: left;
			font: 13px/20px "AM", sans-serif;
			line-height: 60px;
			margin-left: 60px;
			margin-right: 10px;
			text-transform: uppercase;
		}
		#startupguide #sg-details-panel #sg-panel-overhead-container #sg-panel-top-bar button {
			background-color: transparent;
			background-image: url("<?php echo get_template_directory_uri(); ?>/images/startup/sg-control-panel.png");
			border: none;
			cursor: pointer;
			display: inline-block;
			height: 40px;
			margin: 10px;
			padding: 0;
			width: 106px;
		}
		#sg-btn-jets { background-position: -106px 0; }
		#sg-btn-jets:hover,
		#sg-btn-jets.active { background-position: 0 0; }

		#sg-btn-bubbles { background-position: -106px -40px; }
		#sg-btn-bubbles:hover,
		#sg-btn-bubbles.active { background-position: 0 -40px; }

		#sg-btn-bubbles2 { background-position: -106px -80px; }
		#sg-btn-bubbles2:hover,
		#sg-btn-bubbles2.active { background-position: 0 -80px; }

	/* CP details - spa overhead */
		#startupguide #sg-details-panel #sg-panel-overhead-container #sg-panel-overhead {
			position: relative;
		}

		#startupguide #sg-details-panel #sg-panel-overhead-container #sg-panel-overhead img {
			position: relative;
		}
		#startupguide #sg-details-panel #sg-panel-overhead-container #sg-panel-overhead img.sg-layer {
			position: absolute;
			left: 0;
			display: none;
			top: 0px;
		}
		#startupguide #sg-details-panel #sg-panel-overhead-container #sg-panel-overhead img.sg-layer:active {
			display: block;
		}

</style>
<style><?php include_once('style-startup-anchors.css'); ?></style>

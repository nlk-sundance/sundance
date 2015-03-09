<?php

/*
 *	LOADER
 */


?>

<style type="text/css">
	/* - - - - - LOADER - - - - - */
		#loading-animation {
			background-color: #fff;
			background-color: rgb(255,255,255);
			background-color: rgba(255,255,255,1);
			display: none;
			height: 100%;
			min-height: 740px;
			position: absolute;
			top: 0;
			width: 100%;
			z-index: 99;
		}
		.inner-circle {
		    width: 34px;
		    height: 34px;
		    position: absolute;
		    top: 50%;
		    left: 50%;
		    margin-top: -17px;
		    margin-left: -17px;
		    cursor: pointer;
		    border-radius: 999px;
		    border-bottom: 6px solid #002B49;
		    border-bottom: 6px solid rgba(0, 43, 73, 0.33);
		    border-left: 6px solid #002B49;
		    border-left: 6px solid rgba(0, 43, 73, 0.66);
		    border-right: 6px solid #002B49;
		    border-right: 6px solid rgba(0, 43, 73, 0.11);
		    border-top: 6px solid #002B49;
		    border-top: 6px solid rgba(0, 43, 73, 1.0);
		    background-color: transparent;
		    -webkit-animation-name: rotate;
		    -webkit-animation-duration: 1.5s;
		    -webkit-animation-iteration-count: infinite;
		    -webkit-animation-timing-function: linear;
		    -moz-animation-name: rotate;
		    -moz-animation-duration: 1.5s;
		    -moz-animation-iteration-count: infinite;
		    -moz-animation-timing-function: linear;
		    animation-name: rotate;
		    animation-duration: 1.5s;
		    animation-iteration-count: infinite;
		    animation-timing-function: linear;
		}

		@-webkit-keyframes rotate {
		    from {
		        -webkit-transform: rotate(0deg);
		        -moz-transform: rotate(0deg);
		        transform: rotate(0deg);
		    }
		    to {
		        -webkit-transform: rotate(360deg);
		        -moz-transform: rotate(360deg);
		        transform: rotate(360deg);
		    }
		}
		@-moz-keyframes rotate {
		    from {
		        -webkit-transform: rotate(0deg);
		        -moz-transform: rotate(0deg);
		        transform: rotate(0deg);
		    }
		    to {
		        -webkit-transform: rotate(360deg);
		        -moz-transform: rotate(360deg);
		        transform: rotate(360deg);
		    }
		}
		@keyframes rotate {
		    from {
		        -webkit-transform: rotate(0deg);
		        -moz-transform: rotate(0deg);
		        transform: rotate(0deg);
		    }
		    to {
		        -webkit-transform: rotate(360deg);
		        -moz-transform: rotate(360deg);
		        transform: rotate(360deg);
		    }
		}
		@-webkit-keyframes color {
		    0% {
		        color:#002B49;
		    }
		    50% {
		        color: transparent;
		    }
		    100% {
		        color:#002B49;
		    }
		}
</style>

<div id="loading-animation">
	<div class="inner-circle">
		
	</div>
</div>
<?php
/**
 * The Header for our theme.
 *
 * Displays all of the <head> section and everything up till <div id="main">
 *
 * @package Sundance
 * @subpackage Sundance
 * @since Sundance 2.0
 */

?><!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
    <meta charset="<?php bloginfo( 'charset' ); ?>" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="profile" href="http://gmpg.org/xfn/11" />
	<link rel="pingback" href="<?php bloginfo( 'pingback_url' ); ?>" />
	<link href="https://plus.google.com/107104241400965217576/posts" rel="publisher" />
    <!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->
    <title>
		<?php wp_title( '' ); ?>
		<?php
		if ( $_GET['bvrrp'] )
			echo ' Reviews';
		if ( $_GET['bvqap'] )
			echo ' Questions';
		?>
	</title>

    <!-- Bootstrap -->
    <link href="<?php bloginfo('template_url'); ?>/bootstrap/css/bootstrap.min.css" rel="stylesheet">
    <link href="<?php bloginfo('template_url'); ?>/bootstrap/css/custom.css" rel="stylesheet">
    
    <link href='http://fonts.googleapis.com/css?family=Lato:400,700,900|Roboto:400,700,900' rel='stylesheet' type='text/css'>
	
    <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
      <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
      <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
    <?php wp_enqueue_script('jquery'); ?>
    <script src="<?php bloginfo('template_url'); ?>/bootstrap/js/bootstrap.min.js"></script>
  </head>
  
<body <?php body_class(); ?>>

	<?php custom_data_layer(); ?>
	
	<?php google_tag_manager(); ?>

	<?php google_tag_manager_criteo(); ?>

    <section class="wrapper headercontainer">
  		<div class="container">
  			<div class="row">
  				<div class="col-xs-12 col-sm-5 col-md-5">
  					<h1 class="logo"><a href="#">logo</a></h1>
  				</div>
  				<div class="col-xs-12 col-sm-7 col-md-7">
  					<ul class="toplist">
  						<li class="listborder"><a href="#">Free Brochure</a></li>
  						<li><a href="#">Nearest Dealer</a></li>
  						<li><form><input type="text" class="zip btn-start" placeholder="zip/postal code" /></form></li>
  					</ul>
  				</div>
  			</div>		
			<div class="row">
				<div class="col-xs-12">
					<nav class="navbar navbar-default" role="navigation">
						<div class="container-fluid">
							<!-- Brand and toggle get grouped for better mobile display -->
							<div class="navbar-header">
								<button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1">
									<span class="sr-only">Toggle navigation</span>
									<span class="icon-bar"></span>
									<span class="icon-bar"></span>
									<span class="icon-bar"></span>
								</button>
							</div>
							<!-- Collect the nav links, forms, and other content for toggling -->
							<div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
										<ul class="nav navbar-nav">
											<li class="listborder">
												<a href="#">SPA<span class="sr-only">(current)</span></a>
											</li>
											<li class="listborder">
												<a href="#">ABOUT US</a>
											</li>
											<li class="listborder dropdown">
												<a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">ACCESSORIES</a>
												<ul class="dropdown-menu">
										            <li><a href="#">Action</a></li>
										            <li><a href="#">Another action</a></li>
										            <li><a href="#">Something else here</a></li>
										            <li role="separator" class="divider"></li>
										            <li><a href="#">Separated link</a></li>
										            <li role="separator" class="divider"></li>
										            <li><a href="#">One more separated link</a></li>
										          </ul>
											</li>
											<li class="listborder">
												<a href="#">OWNER</a>
											</li>
											<li class="listborder">
												<a href="#">GET PRICING</a>
											</li>
											<li class="search">
												<a href="#">SEARCH</a>
											</li>	
										</ul>
							</div>
						</div><!-- /.container-fluid -->
					</nav>
				</div>
			</div>
  		</div>
  	</section>

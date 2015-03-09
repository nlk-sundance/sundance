jQuery(document).ready(function($) {

	// initializes page to display 880 series by default, sets nav and etc
	function sg_initialize_series(){
		var init = '880'; // which series to start as active
		$('#startup-nav-series').show('slide', {direction: 'left'}, 250).addClass('active').find('li#sg-nav-series-' + init).addClass('active'); // set nav bar to default series
		$('#sg-series-container').fadeIn(250).addClass('active').find('div.series-' + init).addClass('active'); // and show defautl series right side grid
	}
	sg_initialize_series();

	// control clicks between series
	function sg_swap_series( $this ){
		if ( $this.hasClass('active') ) {
			return false;
		}
		var s = $this.attr('series');
		$this.addClass('active', 250).siblings('.active').addClass('lastone').removeClass('active', 150);
		$('.sgseries.active').fadeOut(150, function(){
			$(this).removeClass('active', 50, function(){
				$('div.series-' + s).fadeIn(250).addClass('active');	
			});
		});
	}
	// hides series on other menu click
	function sg_hide_series(){
		$('#startup-nav-series').hide('slide', {direction: 'left'}, 250).find('.active').removeClass('active'); // slide out of view then remove all active from nav bar
		$('#sg-series-container').fadeOut(250).removeClass('active').find('.active').removeClass('active'); // and fade out series view then remove all active
	}

	// displays details nav menu on spa click and fades in details page right side
	function sg_initialize_details(){
		$('#startup-nav-spa').show('slide', {direction: 'right'}, 250).addClass('active'); // slide in menu
		$('#sg-details-landing').addClass('active').siblings('section').removeClass('active').parents('#sg-spa-container').fadeIn(250).addClass('active'); // fade in spa details landing
	}

	// hides details page and returns to series menu
	function sg_hide_details(){
		$('#startup-nav-spa').hide('slide', {direction: 'right'}, 250).removeClass('active').find('.active').removeClass('active'); // slide out menu
		$('#sg-spa-container').fadeOut(250).removeClass('active').find('.active').removeClass('active'); // fade out spa details pages
	}

	// open / close left menu height on details view
	function sg_nav_details_expand( $this ){
		$this.parent('li').toggleClass("active", 250).siblings(".active").removeClass("active", 250);
	}
	// swap between different details views
	function sg_swap_details_view( $this ){
		var showing = $this.attr('show');
		$this.parents('li.active').addClass('lastone');
		if ( $('section#' + showing).hasClass('active') ) {
			return false;
		}
		$('section.sg-details.active').fadeOut(150,function(){
			$(this).removeClass('active').find('img.active').hide().removeClass('active');
			$(this).find('.active').removeClass('active');
			$(this).find('.toggle-me').addClass('closed');
			$('section#' + showing).find('div.home').show().addClass('active');
			$('section#' + showing).fadeIn(250).addClass('active');
		});
	}

	// explore jets action fades out control panel landing image
	function sg_panel_explore_jets() {
		$('#sg-panel-landing-container').fadeOut(250, function(){
			$(this).removeClass('active');
			$('#sg-panel-overhead-container').fadeIn(250).addClass('active');
		});
	}




	// click on series, show that series, hide others
	$('#startup-nav-series li.series').click(function(){ sg_swap_series( $(this) ); });

	// when viewing a spa, "all spas" returns to initialize state
	$('li.nav-title.show-all').click(function(){
		sg_hide_details();
		sg_initialize_series();
	});
	
	// open/close spa details menu drop-downs
	$('li.details div.wrapr').click(function(){ sg_nav_details_expand( $(this) ); });

	// show specific details section
	$('#startup-nav-spa .details button').click(function(){ sg_swap_details_view( $(this) ); });

	// control panel - explore jets
	$('#sg-panel-explore-jets').click(function(){ sg_panel_explore_jets(); });


	// massage sliders
	var slider_styles = {
		'background-image': 'none',
		'background-color': '#C4C8CD',
		'border': 'none',
		'display': 'inline-block',
		'float': 'left',
		'height': 4,
		'margin-top': 3,
		'width': 260
	};
	var slider_a_styles = {
		'background-color': '#445463',
		'background-image': 'none',
		'border': 'none',
		'border-radius': '99px',
		'cursor': 'pointer',
		'margin-top': -2
	};

	// Control Panel Functions
	function sg_panel_reset_landing(){
		$('#sg-panel-overhead-container').fadeOut(250, function(){
			$(this).removeClass('active').find('.active').removeClass('active');
			$('#sg-panel-overhead-container').find('img.sg-layer').hide();
			$('#sg-panel-landing-container').fadeIn(250).addClass('active');
		});
	}
	function sg_panel_explore_jets() {
		$('#sg-panel-landing-container').fadeOut(250, function(){
			$(this).removeClass('active');
			$('#sg-panel-landing-rightbar').addClass('closed').find('.active').removeClass('active');
			$('#sg-panel-overhead-container').fadeIn(250).addClass('active');
		});
	}
	function sg_panel_rightbar( $this ){
		if ( $this.attr('id') == 'sg-panel-landing-rightbar-openclose' ) {
			$this.parent('#sg-panel-landing-rightbar').toggleClass('closed', 250);
		}
		else if ( $this.parent('#sg-panel-landing-rightbar').hasClass('closed') ) {
			$this.parent('#sg-panel-landing-rightbar').removeClass('closed', 250);
		}
		$this.toggleClass('active', 250).siblings('.active').removeClass('active', 250);
	}
	function sg_panel_topbar_button( $this ){
		var l = $this.attr('layer');
		if ( $this.hasClass('active') ) {
			$this.removeClass('active');
			$('img#sg-' + l).fadeOut(250).removeClass('active');
		}
		else {
			$this.addClass('active').siblings('.active').removeClass('active');
			$('img#sg-' + l).fadeIn(250).addClass('active').siblings('.active').fadeOut(250).removeClass('active');
		}
	}


	// on spa selection (click)
	$("div.sgspa").click(function() {

		// display loader while we get the content
		$("#loading-animation").fadeIn(350);

		// spa ID
		var post_id = $(this).attr("id");

		// ajax file URL
		var ajaxURL = StartupAjax.ajaxurl;

		$.ajax({
			type: 'POST',
			url: ajaxURL,
			data: {"action": "load-content", post_id: post_id },
			success: function(response) {
				sg_hide_series();
				$("#sg-spa-container").html(response);
				$("#loading-animation").fadeOut(250, function(){
					$(this).hide();
					sg_initialize_details();
				});

				// air control sections
				$('a.sg-air-anchor').mouseenter(function() {
						var r = $(this).attr('rel');
						if ( ! $('img#' + r).hasClass('active') ) {
							$('img#' + r).fadeTo(250, 1.0).siblings('.active').fadeTo(200, 0.75); // fade in the the one you roll over, slightly fade out any active views
						}
					}).mouseleave(function() {
						var r = $(this).attr('rel');
						if ( ! $('img#' + r).hasClass('active') ) {
							$('img#' + r).fadeOut(250).siblings('.active').fadeTo(300, 1.0); // fade out the the one you roll off, completely fade back in any active views
						}
					}).click(function(){
						var r = $(this).attr('rel');
						if ( $('img#' + r).hasClass('active') ) {
							$('img#' + r).fadeOut(250).removeClass('active'); // if this is active. turn off on click...
						}
						else {
							$('img#' + r).addClass('active').siblings('.active').fadeOut(250).removeClass('active'); // otherwise set this active, and fade out active siblings
						}
					});
				
				// massage A/B fade slider
				$( "#sg-massage-slider" ).slider({
						value: 0.50,
						min: 0,
						max: 1,
						step: 0.01,
						slide: function( event, ui ) {
							var leader = $('img[id^="sg-massage-rollover"].active.leader').attr('id');
							var a = leader + '-a';
							var b = leader + '-b';
							$('#' + a).css('opacity', 1 - ui.value);
							$('#' + b).css('opacity', ui.value);
						}
					}).css( slider_styles ).children('a').css( slider_a_styles );
				// Massage hover/click events
				$('a.sg-massage-anchor').mouseenter(function() {
						var r = $(this).attr('rel');
						if ( ! $('img#' + r).hasClass('active') ) {
							$('img#' + r).fadeIn(250);
							$('img#' + r + '-a').fadeTo(250, 0.5);
							$('img#' + r + '-b').fadeTo(250, 0.5);
						}
					}).mouseleave(function() {
						var r = $(this).attr('rel');
						if ( ! $('img#' + r).hasClass('active') ) {
							$('img#' + r).delay(150).fadeOut(250);
							$('img#' + r + '-a').delay(150).fadeOut(250);
							$('img#' + r + '-b').delay(150).fadeOut(250);
						}
					}).click(function(){
						$( "#sg-massage-slider" ).slider({
							value: 0.50,
						});
						var r = $(this).attr('rel');
						if ( $('img#' + r).hasClass('active') ) {
							$('img#' + r + ', img#' + r + '-a' + ', img#' + r + '-b').fadeOut(250).removeClass('active');
							$('img#' + r).removeClass('leader');

						}
						else {
							var hide_these = $('img#' + r).siblings('img.active').attr('id');
							$('img#' + hide_these + ', img#' + hide_these + '-a' + ', img#' + hide_these + '-b').fadeOut(250).removeClass('active leader');
							$('img#' + r).fadeIn(250, function(){
								$(this).addClass('active leader');
							});
							$('img#' + r + '-a' + ', img#' + r + '-b').fadeTo(250, 0.5, function(){
								$(this).addClass('active');
							});
						}
				});
				// control panel actions
				$('#sg-panel-explore-jets').click(function(){ sg_panel_explore_jets(); });
				$('#sg-panel-landing-rightbar div').click(function(){ sg_panel_rightbar( $(this) ); });
				// control panel section top buttons show overlay
				$('#sg-panel-top-bar button').click(function(){ sg_panel_topbar_button( $(this) ); });
				// and back arrow/button
				$('#sg-panel-top-bar .sg-back-arrow').click(function(){ sg_panel_reset_landing(); });

				$('.sg-right-col section a, .sg-right-col section button').click(function(){
					var menuset = $(this).parents('section.sg-details').attr('id');
					var menuitem = $('#startup-nav-spa').find('button#' + menuset).parents('li.details');
					if ( ! menuitem.hasClass('active') ) {
						menuitem.siblings('.active').removeClass('active', 150);
						menuitem.addClass('active', 250);
					}
				});


				return false;
			}
		});
	});

});

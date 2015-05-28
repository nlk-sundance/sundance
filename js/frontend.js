function hottubPopup() {	
	jQuery.cookie("hottubPagesLeft",null, {path: '/' });
	jQuery.cookie("hottubCountdown",null, {path: '/' });
	jQuery.cookie("hottubStamp",null, {path: '/' });
	jQuery.cookie("hottubPopped",true, {expires: 14, path: '/' });
	
	if ( jQuery('#TB_window').size() < 1 ) {
		tb_show(null,'/wp-content/themes/sundance/popup.html?TB_iframe=true&height=386&width=444',null);
	}
	return false;
}

function hottubCookie() {
	if( (jQuery.cookie('hottubPopped') ) || ( jQuery('body').is('.conversion, .brochure, .quote, .nopop, .page-template-page-newlanding-php, .page-template-page-brochure-php, .login, .page-template-page-quote-php' ) ) ) return false;
	
	var msLeft = 180000;
	var pagesLeft = 9;
	if(jQuery.cookie("hottubPagesLeft") && jQuery.cookie("hottubCountdown") && jQuery.cookie("hottubStamp")) {
		pagesLeft = jQuery.cookie("hottubPagesLeft");
		msLeft = jQuery.cookie("hottubCountdown");
		pagesLeft--;
		jQuery.cookie("hottubPagesLeft",pagesLeft, {path: '/' });
	} else {
		var now = new Date();
		now = now.getTime();
		jQuery.cookie("hottubPagesLeft",pagesLeft, {path: '/' });
		jQuery.cookie("hottubCountdown",msLeft, {path: '/' });
		jQuery.cookie("hottubStamp",now, {path: '/' });
	}
	
	if(pagesLeft == 0) hottubPopup();
	setTimeout(hottubPopup,msLeft);
	
	jQuery(window).unload(function() {
		var now = new Date();
		now = now.getTime();
		var startStamp = jQuery.cookie("hottubStamp");
		var msDiff = now - startStamp;
		var msLeft = jQuery.cookie("hottubCountdown");
		msLeft -= msDiff;
		jQuery.cookie("hottubCountdown",msLeft, {path: '/' });
	});
}

function sundance_tpopit() {	
	jQuery.cookie("hottubTPop",true, {expires: 7, path: '/' });
	
	if ( jQuery('#TB_window').size() < 1 ) {
		tb_show(null,'/hot-tub-dealer-locator/dealer_ip/get_dealer.php?popped=true&TB_iframe=true&height=325&width=531',null);
	}
	return false;
}

jQuery(function($) {
	if ( $('body').hasClass('hot-tub-landing') ) {
		$('.tubSeries a.openDetails').click(function() {
			$(this).hide().next().show().next().show();
			$(this).parent().prev().hide();
			return false;
		}).next().click(function() {
			$(this).hide().next().hide();
			$(this).prev().show().parent().prev().show();
			return false;
		});
	}
	if ( $('#spatabs').size() > 0 ) {
		$('#spatabs a').click(function() {
			if ( $(this).parent().hasClass('current') == false ) {
				$(this).parent().addClass('current').siblings('.current').removeClass();
				var tar = $(this).attr('href');
				tar = tar.substr(tar.indexOf('#'));
				$(tar).addClass('current').show().siblings('.tab.current').removeClass('current').hide();
			}
			return false;
		});
	}
	if ( $('a.readmore').size() > 0 ) {
		$('a.readmore').click(function() {
			$(this).parents('.exc').hide().next().show();
			return false;
		});
	}
	// blog ticker
	if ( $('#bslidebtns').size() > 0 ) {
		$('#bslidebtns a').each(function(i) {
			
			$(this).click(function() {
				if ( $(this).parent().hasClass('current') == false ) {
					var p = $(this).toggleClass('dotLtGray dotDrkGray').parent().addClass('current');
					p.siblings('.current').removeClass('current').children().toggleClass('dotLtGray dotDrkGray');
					//.parent().addClass('current')
					//.siblings('.current').removeClass('current').chidren().removeClass('dotDrkGray').addClass('dotLtGray');
					
					var nx = $('#mainslide .bslide:eq('+i+')');
					var onn = nx.siblings('.on');
					nx.css('left','728px').add(onn).animate({
						left: '-=728'
					}, 800, function() {
						if ( $(this).hasClass('on') ) {
							$(this).removeClass('on');
						} else {
							$(this).addClass('on');
						}
					});
				}
				return false;
			});
			if ( $(this).hasClass('dot') == false ) {
				if ( $(this).children('img').size() > 0 ) {
					if ( i > 6 ) $(this).parent().hide();
				} else {
					// remove empties
					$(this).parent().remove();
					$('#mainslide .bslide:eq('+i+')').remove();
				}
			}
		});
		// omg i forgot to make the arrows do stuff
		$('.slider a.previous').click(function() {
			var onn = $('#bslidebtns li:visible').filter(':first');
			var nex = onn.prev();
			if ( nex.size() > 0 ) {
				onn.parent().children(':visible').filter(':last').hide();
				nex.show();
			}
			return false;
		});
		$('.slider a.next').click(function() {
			var onn = $('#bslidebtns li:visible').filter(':last');
			var nex = onn.next();
			if ( nex.size() > 0 ) {
				onn.parent().children(':visible').filter(':first').hide();
				nex.show();
			}
			return false;
		});
	}
	// video gallery
	if ( $('body').hasClass('video') ) {
		$('.videoList a').click(function() {
			var p = $(this).parents('li');
			if ( p.hasClass('onn') == false ) {
				var e = $(p).children().children('.e').html();
				e += '?autoplay=1&showinfo=0&rel=0&autohide=1&wmode=opaque';
				$('#mainplayer').attr('src',e);
				$('.videoList li.onn').removeClass('onn');
				p.addClass('onn');
			}
			return false;
		});
	}
	
	$('.expander h3').click(function() {
		$(this).next().slideToggle();
	});
	// backyard ideas > faqs
	if ( $('#faqs').size() > 0 ) {
		$('#faqs > li > a').click(function() {
			$(this).next().slideToggle();
			return false;
		});
	}
	// backyard ideas > installations
	if ( $('.installShowcase').size() > 0 ) {
		$('.installShowcase .scroller ul a').click(function() {
			var p = $(this).parent();
			if ( p.hasClass('current') == false ) {
				p.addClass('current').siblings('.current').removeClass();
				var s = p.parents('.scroller').next();
				s.children('div').remove();
				$(this).children('div').children().clone().prependTo(s);
			}
			return false;
		});
		var installcount = $('.installShowcase .scroller ul a').size();
		if ( installcount > 4 ) {
			var pcount = Math.ceil(installcount / 4);
			$('.installShowcase .scroller').append('<p id="ipnum">Page <span>1</span>/'+ pcount+' <a href="#p" class="p"></a><a href="#n" class="n"></a></div>');
			$('#ipnum a.p').click(function() {
				var pnum = $(this).siblings('span').html();
				pnum = parseInt(pnum, 10);
				if ( pnum > 1 ) {
					pnum = pnum - 2;
					var mn = pnum * 4;
					var mx = mn + 4;
					$('.scroller ul li').hide().slice(mn,mx).show();
					pnum++;
					$(this).siblings('span').html('' + pnum);
				}
				return false;
			}).next().click(function() {
				var pnum = $(this).siblings('span').html();
				pnum = parseInt(pnum, 10);
				if ( pnum < pcount ) {
					var mn = pnum * 4;
					var mx = mn + 4;
					$('.scroller ul li').hide().slice(mn,mx).show();
					pnum++;
					$(this).siblings('span').html('' + pnum);
				}
				return false;
			});
		}
	}
	if ( $('#requestform').size() > 0 ) {
		$('#requestform').submit(function() {
			var aok = true;
			$(this).find('.req').each(function() {
				if ( $(this).val() == '' ) {
					aok = false;
				}
			});
			if ( aok == false ) alert('Please check all required * fields');
			return aok;
		});
		// checkbox address toggles on BROCHURE form
		
		if ( $('#requestform .mailCheck').size() > 0 ) {
			$('#requestform .mailCheck').click(function() {
				if ( $('#requestform input.mailCheck:checked').size() > 0 ) {
					$('#requestform td .mailer').each(function() {
						$(this).show().find('input:text').addClass('req');
						$(this).parent().removeClass('nopad');
					});
				} else {
					$('#requestform td .mailer').each(function() {
						$(this).hide().find('.req').removeClass('req');
						$(this).parent().addClass('nopad');
					});
				}
			});
		}
	}
	// signup form
	$('a.esignup').click(function() {
		$('#emailover').show();
		return false;
	});
	$('#x').click(function() {
		$('#emailover').hide();
		return false;
	});
	
	$('.bottomMenu li.fres a').click(function() {
		$('.bottomMenu.fresources').slideToggle();
		return false;
	});
	
	if ( $(".side .searchform").size() > 0 ) {
		var sf = $('.side .searchform');
		sf.hide().prev().click(function() {
			$(this).add($(this).next()).slideToggle();
			$(this).next().find('#s').focus();
			return false;
		});
	}
	
	
	// tpop
	if (typeof jQuery.cookie !== 'undefined' && $.isFunction(jQuery.cookie) && jQuery.cookie('hottubTPop')) {
	//if(jQuery.cookie('hottubTPop')) {
		//nada
	} else {
		if ( $('#tpop').size() > 0 ) { // dealer-loc already included inline on page
			sundance_tpopit();
		} else {
			// otherwise, $.load it
			$('.ft').after('<div style="display:none" id="salecatch" />').next().load('/hot-tub-dealer-locator/dealer_ip/get_dealer.php', function() {
				if ( $("#tpop").size() > 0 ) {
					sundance_tpopit();
				}
			});
		}
	}
	if ( $('body').hasClass('locate') == false ) hottubCookie();

	//add <sup> to spa detail name trademark
	if ($(".spa-name")[0]){
		$('.spa-name').html($('.spa-name').html().replace('\u2122', "<sup>&trade;</sup>"));
	}

});

jQuery(function($){
	$('body.sundance-brochure a.scrollTo').click(function(){
		$('body,html').animate({ scrollTop: '524px' }, 300);
		$('body.sundance-brochure a.scrollTo').addClass('disabled');
		$('input#person_first_name').focus();
	})
	$('body.sundance-brochure .more-fold').click(function(){
		$('body,html').animate({ scrollTop: '740px' }, 300);
	})
});

var idleTime = 0;
jQuery(function($){
    $(window).scroll(function(){
        if ( $(window).scrollTop() < 510 || $(window).scrollTop() > 610 ){
        	$('body.sundance-brochure a.scrollTo').removeClass('disabled');
        }
        else {
        	$('body.sundance-brochure a.scrollTo').addClass('disabled');
        }
    });

    /*$(this).bind('mouseup touchstart', function(e){
        var container = $('.form-page-two');
        if (!container.is(e.target) // if the target of the click isn't the container...
            && container.has(e.target).length === 0 // ... nor a descendant of the container
            	&& container.is(":visible") ) // the container IS visible..
		            {
		                $("form#leadForm").submit();
		            }
    });*/

    // idle timer
    if ( $('.form-page-two').is(":visible") ) {
    	var idleInterval = setInterval("timerIncrement()", 1000); // 1 second
    }
    //Zero the idle timer on mouse movement.
    $(this).mousemove(function (e) {
        idleTime = 0;
    });
    $(this).keypress(function (e) {
        idleTime = 0;
    });
});

function timerIncrement() {
    idleTime = idleTime + 1;
    if (idleTime > 60) { // 60 seconds
        $("form#leadForm").submit();
    }
}

// Avala Lead Form validation
jQuery(function($) {
	var invalidInputs = ["www", "http"];
    var emailAddress = 'email';         // class for email address fields
    var phoneNumber = 'phonenumber';   // class for phone number fields
    var required = 'required';          // class for required fields
    var error = 'err';                  // class for displaying errors
    $('form#leadForm.two-part-form button[rel="ShowNext"]').click( function(){
    	$('form#leadForm').blur();
    	var cancel = false;
        $("." + required).each(function () {
            if ($(this).val() === "" || $(this).hasClass(error) ) {
                $(this).addClass(error);
                if (!cancel) {
                    cancel = true;
                    $(this).focus();
                }
            }
        });
        if (cancel) {
        	e.preventDefault();
        } else {
			$('.form-page-two-bg').show('fast', function() {
				$('.form-page-two').fadeIn('slow');
			});
		}
    });
    $("form#leadForm .survey-no").click( function(){
    	$("form#leadForm").submit();
    });
    //prevent Enter key from submitting form
	$('form#leadForm.two-part-form').bind("keyup", function(e) {
		var code = e.keyCode || e.which; 
		if (code  == 13) {
			e.preventDefault();
			return false;
		}
	});
    $("form#leadForm").submit(function(e) {
        var cancel = false;
        $("." + required).each(function () {
            if ($(this).val() === "" || $(this).val() === 'XX') {
                $(this).addClass(error);
                if (!cancel) {
                    cancel = true;
                    $(this).focus();
                }
            }
        });
        if (cancel) {
        	e.preventDefault();
        } else {
        	$('form#leadForm input[type=submit]').addClass('disabled').attr('disabled', 'disabled');
        }
    });
    // Required field and email validation
    $("form ." + required).bind('blur keyup', function() {
        var a = $(this).val();
        var theEmail = $('#person_email').val();
        var filter = new RegExp("\\b[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\\.[a-zA-Z]{2,4}\\b");
        var cancel = false;
        $("." + required).each(function () {
            if ( !$(this).val() || !filter.test(theEmail) ) {
                if (!cancel) {
                    cancel = true;
                }
            }
            else {
            	$(this).removeClass(error);
            }
        });
        if (cancel) {
        	$("form#leadForm.two-part-form button").attr('disabled', 'disabled');
        } else {
        	$("form#leadForm.two-part-form button").removeAttr('disabled');
        }
        if ( a == "" )
        {
            $(this).addClass(error);
            return;
        }
        if ( $(this).hasClass(emailAddress) )
        {
            if ( !filter.test(a) )
            {
                $(this).addClass(error);
                return;
            }
        }
        $(this).removeClass(error);
    });
    // Block invalid inputs
    $("form input[type=text]").bind('blur keyup', function() {
        var n = invalidInputs.length;
        for (var i = 0; i < n; i++) {
            if ($(this).val().toLowerCase().indexOf(invalidInputs[i]) > -1) {
                $(this).addClass(error);
                return false;
            }
        }
    });
    // Phone Number fields
    $("form ." + phoneNumber).keydown(function (event) {
        if (event.keyCode == 32 || event.keyCode == 46 || event.keyCode == 8 || event.keyCode == 9 || event.keyCode == 27 || event.keyCode == 13 ||
        event.keyCode == 190 || event.keyCode == 110 || 
        (event.keyCode == 189 && event.shiftKey === false) ||
        (event.keyCode == 65 && event.ctrlKey === true) ||
        (event.keyCode >= 35 && event.keyCode <= 39) ||
        (event.keyCode == 48 && event.shiftKey === true) ||
        (event.keyCode == 57 && event.shiftKey === true) ) {
            return;
        }
        else {
            if (event.shiftKey || (event.keyCode < 48 || event.keyCode > 57) && (event.keyCode < 96 || event.keyCode > 105)) {
                event.preventDefault();
            }
        }
    });

    // Select state/country fields
    if ( ("select.state").length > 0 ) {
	    $("select.state").change(function (e) {            
	        $("select.country").val($(this).find("option:selected").attr("data-country"));
	    });
	    $(".country").change(function (e) {
	        var country = $(this).val();
	        $("select.state option").remove();
	        if (country == "0") {
	            states.find("option").clone().appendTo("select.state");
	        } else {
	            states.find("[data-country=" + country + "]").clone().appendTo("select.state");
	        }
	    });
	    states = $("select.state").clone();
	}
});

//fancy button
jQuery(function($){
    var fancyText = $('.fancy-button').text();
    $('.fancy-button').empty();
    $('.fancy-button').html('<span class="l"></span><span class="m"></span><span class="r"></span>');
    $('.fancy-button span.m').text(fancyText);
    $('.fancy-button').fadeIn("slow");

});

// create video modal
jQuery(function($){
	$('div[goto="vidmodal"]').click(function(){
		var theVid = $(this).attr('rel');
		$('body').append('<div class="overlay" id="vidmodal"><div class="dialog big"><a id="x" href="#"></a><div class="thevid"><iframe width="640" height="360" src="' + theVid + '" frameborder="0" allowfullscreen></iframe></div></div></div>');
		$('div#vidmodal').fadeIn('slow');
		if ( $('body').hasClass('home') ) {
			$('div.dialog.big').append('<div class="vid-cta-section">');
			$('div.vid-cta-section').append('<div id="vctaselect"><div class="tub-thumb"><span>Modern &amp; Efficient</span></div><a class="details-btn" href="/selectseries/"></a></div>');
			$('div.vid-cta-section').append('<div id="vcta880"><div class="tub-thumb"><span>Top-line Luxury</span></div><a class="details-btn" href="/880series/"></a></div>');
			$('div.vid-cta-section').append('<div id="vcta780"><div class="tub-thumb"><span>Upgraded Features</span></div><a class="details-btn" href="/780series/"></a></div>');
			$('div.vid-cta-section').append('<div id="vcta680"><div class="tub-thumb"><span>Entry Sundance</span></div><a class="details-btn" href="/680series/"></a></div>');
			$('div.vid-cta-section').append('<div id="vctabrochure"><a href="/request-literature/" onClick="_gaq.push([\'_trackEvent\', \'VideoPop\', \'Brochure\']);" ></a></div>');
		}
		$('div#vctaselect').click(function(){ 
			_gaq.push(['_trackEvent', 'VideoPop', 'HotTub', 'Select']);
			window.location = "/selectseries/";
		});
		$('div#vcta880').click(function(){
			_gaq.push(['_trackEvent', 'VideoPop', 'HotTub', '880']);
			window.location = "/880series/";
		});
		$('div#vcta780').click(function(){
			_gaq.push(['_trackEvent', 'VideoPop', 'HotTub', '780']);
			window.location = "/780series/";
		});
		$('div#vcta680').click(function(){
			_gaq.push(['_trackEvent', 'VideoPop', 'HotTub', '680']);
			window.location = "/680series/";
		});
	});
});

//destroy video modal
jQuery(function($){
	$('body').on('click', 'div#vidmodal a#x', function(){
		$('#vidmodal').css('display', 'none').remove();
	});
});


jQuery(function($) {
	$('li.has-img-tooltip').each(function(){
		var tip = $('.tooltip-img', this).html();;
		$(this).tooltip({
			content: tip
		});
	});
});

// Trouble shooting pages
jQuery(function($){
	$('.troubleshooting.messages .row').each(function(){
		var a = [ $(this).find('.col:nth-child(1)').height(), $(this).find('.col:nth-child(2)').height(), $(this).find('.col:nth-child(3)').height() ];
		var b = Math.max.apply(null, a);
		$(this).height(b).children('.col').each(function(){
			$(this).height(b);
		});
		$(this).attr('h', b).height(80);
	});
	$('.troubleshooting.messages .row').click(function(e){
		if ( $(this).hasClass('open') ) {
			$(this).removeClass('open').animate({
				height: 80
			}, 350);
		}
		else {
			var h = $(this).attr('h');
			$('.troubleshooting .row.open').removeClass('open').animate({
				height: 80
			}, 350);
			$(this).addClass('open').animate({
				height: h,
			}, 350);
		}
	});
	$('.troubleshooting.procedures .row').click(function(e){
		if ( $(this).hasClass('open') ) {
			$(this).removeClass('open').animate({
				'max-height': 40
			}, 350);
		}
		else {
			$('.troubleshooting.procedures .row.open').removeClass('open').animate({
				'max-height': 40
			}, 350);
			$(this).addClass('open').animate({
				'max-height': 1000,
			}, 350);
		}
	});
});

jQuery(function($){
	if ( $('#wpadminbar').size() > 0 ) {
		$('.page-template-page-dealersearch-php .dd.ds').css('top', '98px');
	}
	$('#show-locator').click(function(){
		$(this).addClass('deactivate');
		$('.page-template-page-dealersearch-php .dd.ds').slideDown('slow');
	});
	$('#hide-locator').click(function(){
		$('.page-template-page-dealersearch-php .dd.ds').slideUp('slow');
		$('#show-locator').removeClass('deactivate');
	})
});



(function($){
	// Get MSRP Pricing button action
	$(window).load(function(){
		$('#show-msrp').on('click', function(e){
			e.preventDefault();
			if ( $(this).hasClass('close') ) {
			    $('.msrp').hide();
			    $(this).removeClass('close').text('Get MSRP Pricing');
			} else {
			    $('.msrp').show();
			    $(this).addClass('close').text('Close');
			}
		});
	});
})(jQuery);
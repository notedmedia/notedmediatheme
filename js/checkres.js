// JavaScript Document

jQuery(document).ready(function(e) {
	var startTime = tmpDate.getTime();

	if (jQuery('div.video-container1').length > 0) {
		videobox = jQuery('div.video-container1').parent();
		jQuery('article.big-post').before(videobox);
		videobox.append(videobox.children('hr'));
	}
	jQuery('a.ab-link').click(function() {
		url = jQuery(this).attr('href');
		jQuery('div#dump').load('/wp-content/plugins/abarticles/data.php?action=count&id=' + jQuery(this).attr('data-article'), function() {
			location.href = url;
		});
		return false;
	});
	var page_like_or_unlike_callback = function(url, html_element) {
		$('.facebook-popup:visible').fadeOut(200, function() {
			id = $(this).attr('data-pop');
			jQuery('div#dump').load('/wp-content/plugins/notedmedia/setcookie.php?cookie=fblike' + id + '&span=20');
			$(this).remove();
		});
	}
	$('.facebook-already-likes').click(function() {
		id = $('.facebook-popup:visible').attr('data-pop');
		jQuery('div#dump').load('/wp-content/plugins/notedmedia/setcookie.php?cookie=fblike' + id + '&span=20');
		$('.facebook-popup:visible').fadeOut(200, function() {
			$(this).remove();
		});
	});
	
	// In your onload handler
	FB.Event.subscribe('edge.create', page_like_or_unlike_callback);
	FB.Event.subscribe('edge.remove', page_like_or_unlike_callback);
	FB.Event.subscribe('xfbml.render', function() {
		popup = $('#facebook-like-popup');
		id = popup.attr('data-pop');
		status = getCookie('fblike' + id);
		if (status != 'true') {
			delayedDisplayFacebook(popup);
		} else {
			popup.remove();
		}

		popup = $('#facebook-like-scroll');
		id = popup.attr('data-pop');
		status = getCookie('fblike' + id);
		if (status != 'true') {
			if ($('.post_video').length > 0) {
				setTimeout(function() {
					showScrollPopup();
				}, 60000);
			} else {
			}
		} else {
			popup.remove();
		}
	});
	function showScrollPopup() {
		if ($('#facebook-like-popup:visible').length > 0) {
			setTimeout(function() {
				showScrollPopup();
			}, 10000);
		} else {
			$('#facebook-like-scroll').fadeIn(200);
		}
	}
	function delayedDisplayFacebook(popup) {
		timer = parseInt(popup.attr('data-timer')) * 1000;
		setTimeout(function() {
			popup.fadeIn(200, function() {
				padTop = $(window).height() / 2;
				padTop = padTop - popup.children('#facebook-like-container').height() / 2;
				popup.animate({
					paddingTop: padTop + 'px'
				}, 500);
			});
		}, timer);
	}
	
	popup = $('div.popup');
	id = popup.attr('data-popup');
	status = getCookie('popup' + id);
	if (status != 'true') {
		popup.fadeIn(200);
		if ($('.popup > .fullscreen').length > 0) {
			setTimeout(function() {
				$('.popup > .fullscreen > .button').slideDown(300);
			}, 3000);
			$('div#facebook-social-scroll').hide();
		}
	} else {
		popup.remove();
	}

	device = 'mobile';
	deviceID = 1;

	jQuery('button#popup_close').click(function() {
		area = $('div.popup');
		btn = jQuery(this);
		url = '/wp-content/plugins/notedmedia/setcookie.php?cookie=popup' + btn.attr('data-popup') + '&span=' + btn.attr('data-lifespan');
		jQuery('div#dump').load(url, function() {
			if (area.hasClass('popup-3')) {
				area.slideUp(500, function() {
				});
			} else {
				area.fadeOut(200);
				jQuery('header#main-header').animate({
					top: '0px'
				});
			}
			setTimeout(function() {
				$('div#facebook-social-scroll:hidden').fadeIn(300);
			}, 500);
		});
	});
	if (jQuery('div.popup-2').length > 0) {
		h = jQuery('div.popup-2').height();
		jQuery('header#main-header').css('top', h + 'px');
	}
	jQuery('#front_page_more_fun').click(function() {
		intOffset = jQuery('div.front_page_articles > div.page-content-small').length * 9;
		jQuery('div.front_page_articles > div.clear-all').before('<div class="page-content-small"></div>');
		exclude_id = $('div.article-point').attr('data-id');
		url = '/wp-content/themes/notedmedia/index_content.php?startoffset=' + intOffset + '&exclude_id=' + exclude_id + '&abs=' + $('.shownab:first').html();
		jQuery('div.front_page_articles > div.page-content-small:last').load(url, function() {
			fixFrontHeadlines();
		});
	});
	function fixFrontHeadlines() {
		/*
		jQuery('a.front-page-post > h2 > div').each(function() {
			mh = jQuery(this).parent().height();
			h = jQuery(this).height();
			while (h > mh) {
				mh = jQuery(this).parent().height();
				fs = parseFloat(jQuery(this).css('font-size'));
				fs = fs - 0.1;
				jQuery(this).css('font-size', fs + 'px');
				jQuery(this).attr('title', h + ' ' + mh);
				h = jQuery(this).height();
			}
		});
		*/
	}
	
	setInterval(function() { fixFrontHeadlines(); }, 500);
	if ($('#front_page').length > 0) {
		$('#main-header').remove();
	}
	$(window).resize(function() {
		checkRes();
	});
	oldDevice = '';
	function checkRes() {
		w = jQuery(window).width();
		h = jQuery(window).height();
		device = 'desktop';
		deviceID = 0;
		jQuery('.popup_close_mob').css('display', 'none');
		jQuery('.popup_close_des').css('display', 'inline-block');
		if (w < 600) {
			jQuery('.popup_close_mob').css('display', 'block');
			jQuery('.popup_close_des').css('display', 'none');
			device = 'mobile';
			deviceID = 1;
		} else if (w < 1050) {
			jQuery('.top-side-area > .widget_nm_ad_widget').remove();
			device = 'tablet';
			deviceID = 0;
		}
		if (oldDevice != device) {
			oldDevice = device;
			//url = jQuery('#device-style').attr('data-folder') + '/style-' + device + '.css';
			//jQuery('#device-style').attr('href', url);
		}
		fixVideoFrames();
	}
	checkRes();
	
	if (jQuery('div#youtube_ontop').length > 10) {
		jQuery('div#youtube_ontop').mouseover(function(e) {
			alert('clicked');
			jQuery(this).fadeTo(0.5, 600);
			countdown = parseInt(jQuery('#youtube_ontop_counter').attr('data-value'));
			setInterval(function() {
				cdvalue = parseInt(jQuery('#youtube_ontop_counter').attr('data-value'));
				cdvalue = cdvalue - 1;
				jQuery('#youtube_ontop_counter').attr('data-value', cdvalue);
				cdtext = jQuery('#youtube_ontop_counter').attr('data-text');
				cdtext = cdtext.replace('#', cdvalue);
				jQuery('#youtube_ontop_counter').html(cdtext);
				if (cdvalue < 1) {
					jQuery('#youtube_ontop_counter').hide();
				}
			}, 1000);
			setTimeout(function() { jQuery('div#youtube_ontop_close').fadeIn(100); }, countdown * 1000);
		}, function() {
		});
		jQuery('div#youtube_ontop_close > button').click(function() {
			jQuery('div#youtube_ontop').fadeOut(100, function() {
				jQuery(this).remove();
				if (device == 'desktop') {
					startYouTubeVideo();
				}
			});
		});
	}
	function fixVideoFrames() {
		jQuery('.screen9').each(function(index, element) {
			video = jQuery(this);
			if (video.children('iframe').width() < video.children('iframe').height()) {
				w = video.parent().width();
				h = video.parent().height();
				while (h > jQuery(window).height() - 120) {
					w = w - 1;
					video.parent().css('width', w + 'px');
					h = video.parent().height();
				}
				video.parent().css('margin', '0 auto');
			}
		});
		jQuery('.video').each(function() {
			jQuery(this).css({
				height: (jQuery(this).width() * 0.5625) + 'px'
			});
			video = jQuery(this);
			jQuery('div#youtube_ontop, iframe#youtubeplayer').css({
				width: video.width() + 'px',
				height: video.height() + 'px'
			});
		});
		jQuery('iframe.flickr-embed-frame').removeAttr('style');
		jQuery('iframe.flickr-embed-frame').removeAttr('width');
		jQuery('iframe.flickr-embed-frame').removeAttr('height');
		jQuery('iframe.flickr-embed-frame').each(function() {
			jQuery(this).css('width', '100%');
			w = jQuery(this).attr('data-natural-width');
			h = jQuery(this).attr('data-natural-height');
			rw = jQuery(this).width();
			jQuery(this).css('height', (h * (rw / w)) + 'px');
		});
	}

	setInterval(function() { fixVideoFrames(); }, 700);
	setInterval(function() {
		jQuery('.instagram-media123').each(function(index, element) {
			insta = jQuery(this);
			h = parseInt(insta.attr('height'));
			if (insta.css('height') !== h) {
				insta.css('height', h + 'px', 'important');
//				jQuery('#testdata').html('Instagram area should be ' + h + 'px high.. yet it is ' + insta.height() + 'px :(');
			} else {
//				jQuery('#testdata').html('Instagram area is as should be :D');
			}
		});
	}, 500);
	justLoaded = 0;
	jQuery(window).scroll(function() {
		bh = $('body').height();
		wh = $(window).height();
		st = jQuery('body').scrollTop();

		if ($('.post_video').length == 0 && $('.widget_nm_fblike_widget').length > 0) {
			popPos = $('.widget_nm_fblike_widget').offset().top - wh;
			if (st > popPos) {
				showScrollPopup();
			}
		}

		if (st > bh - wh * 1.5 && justLoaded === 0) {
			justLoaded = 1;
			setTimeout(function() { justLoaded = 0; }, 1000);
			intOffset = jQuery('div.front_page_articles > div.page-content-small').length * 9;
			jQuery('div.front_page_articles > div.clear-all').before('<div class="page-content-small"></div>');
			exclude_id = $('div.article-point').attr('data-id');
			url = '/wp-content/themes/notedmedia/index_content.php?startoffset=' + intOffset + '&exclude_id=' + exclude_id + '&abs=' + $('.shownab:first').html();
			jQuery('div.front_page_articles > div.page-content-small:last').load(url, function() {
				fixFrontHeadlines();
			});
		}
			/*
		if ($('div#get_prev_post').length > 0) {
			pp = $('div#get_prev_post');
			st = jQuery('body').scrollTop();
			yt = pp.offset().top - $(window).height() - 500;
			if (st > yt) {
				id = pp.attr('data-id');
				pp.before('<div class="ajax-post-content"></div>');
				pp.remove();
				$('div.ajax-post-content:last').load('/wp-content/themes/notedmedia/single_content.php?post_id=' + id, function() {
					FB.XFBML.parse();
					fixVideoFrames();
					fixImages();
				});
			}
//			setPageURL();
		}
			*/
	});
	if (jQuery('div.ajax-post-content').length > 0) {
		pp = jQuery('div.ajax-post-content');
		id = pp.attr('data-id');
		/*
		$('div.ajax-post-content:last').load('/wp-content/themes/notedmedia/single_content.php?post_id=' + id, function() {
			fixVideoFrames();
			fixImages();
		});
		*/
	}
	function fixImages() {
		jQuery('img.size-full:not(.img-wrapped)').each(function(index, element) {
			img = jQuery(this);
			img.wrap('<div class="size-full-container"></div>');
			img.addClass('img-wrapped');
		});
	}
	jQuery('#go-home').click(function() {
		location.href = '/';
	});
	jQuery('.item-toggle').click(function() {
		jQuery('ul#top-menu').toggle(200);
	});
	oldURL = '';
	function setPageURL() {
		st = jQuery('body').scrollTop();
		jQuery('.article-point').each(function() {
			if (st > jQuery(this).offset().top - (jQuery(window).height() / 2)) {
				url = jQuery(this).attr('data-url');
				title = jQuery(this).attr('data-title');
			}
		});
		if (url != oldURL) {
			oldURL = url;
			window.history.pushState('', '', url);
			$('title').html(title);
		}
	}
/*	window.addEventListener('popstate', function(event) {
		ga('send', 'pageview');
	});
	*/
	if (device != 'mobile') {
		$('#featured-articles').remove();
	} else {
		list = $('#featured-articles');
		paragraphs = jQuery('article.big-post > p');
		pCount = paragraphs.length;
		jQuery('article.big-post > p:eq(' + (Math.ceil(pCount * (list.attr('data-position') / 100))) + ')').before(list);
	}
	
	jQuery('div.ad-space').each(function() {
		adSpace = null;
		adBlock = jQuery(this);
		adPosition = adBlock.attr('data-position');
		adType = adBlock.attr('data-type');
		adVideo = adBlock.attr('data-video');
		adArea = adBlock.attr('data-area');
		if (device != 'mobile') {
			if (adPosition == 'fixed' && adType == 0 && adVideo == 2) {
				adSpace = jQuery('p:contains("[adspace]")');
				if (adSpace.length > 0) {
					adSpace.before(adBlock.parent());
					adSpace.remove();
				} else {
					paragraphs = jQuery('article.big-post > p');
					pCount = paragraphs.length;
					jQuery('article.big-post > p:eq(' + (Math.ceil(pCount / 2)) + ')').before(adBlock.parent());
				}
			}
		} else {
			if (adArea == 'before-article' && adPosition != 'original') {
				yMin = $(window).height();
				yValue = 0;
				$('article.big-post > p').each(function() {
					yValue = $(this).offset().top + $(this).height();
					if (yValue > yMin) {
						if (!adSpace) {
							adSpace = $(this);
						}
					}
				});
				adSpace.after(adBlock.parent());
			}
			if (adPosition == 'fixed' && adType == 1 && adVideo == 2) {
				adSpace = jQuery('p:contains("[adspace]")');
				if (adSpace.length > 0) {
					adSpace.before(adBlock.parent());
					adSpace.remove();
				} else {
					paragraphs = jQuery('article.big-post > p');
					pCount = paragraphs.length;
					jQuery('article.big-post > p:eq(' + (Math.ceil(pCount / 2)) + ')').before(adBlock.parent());
				}
			}
		}
	});

	adsLoaded = 0;
	adCount = jQuery('div.ad-space').length;
	if (adCount > 0) {
		jQuery('div.ad-space').each(function() {
			adsLoaded = adsLoaded + 1;
			var endTime = tmpDate.getTime();
			checkRes();
			adid = jQuery(this).attr('data-id');
			adType = jQuery(this).attr('data-type');
			if (adType == deviceID || adType == 2) {
				$(this).load('/wp-content/themes/notedmedia/getad.php?ad=' + adid, function() {
					adTitle = $(this).attr('data-title');
					if (adTitle != '') {
						$(this).prepend('<div class="ad-title">' + adTitle + '</div>');
					}
				});
			} else {
				$(this).parent().parent().remove()
			}
			if (adsLoaded == adCount) {
				setTimeout(function() {
					showThePage();
				}, 1000);
			}
	});
	} else {
		showThePage();
	}
	
	function showThePage() {
		setTimeout(function() {
			jQuery('div#preloader > div').fadeOut(400, function() {
				jQuery('div#preloader').fadeOut(300, function() {
					jQuery(this).remove();
				});
			});
		}, 500);
	}
	
	function getCookie(cname) {
		var name = cname + "=";
		var ca = document.cookie.split(';');
		for(var i=0; i<ca.length; i++) {
			var c = ca[i];
			while (c.charAt(0)==' ') c = c.substring(1);
			if (c.indexOf(name) == 0) return c.substring(name.length,c.length);
		}
		return "";
	}
	jQuery('#youtube-ontop-temp-close').click(function() {
		jQuery('#youtube_ontop, #youtube-ontop-temp-close').fadeOut(400, function() {
			jQuery(this).remove();
			//startYouTubeVideo();
		});
	});
	jQuery('#facebook-social-scroll > tbody > tr > td > a').click(function() {
		jQuery('div#dump').load('http://notedmedia.se/cerebro/getdata.php?action=shareclick&key=0&url=' + encodeURIComponent(location.href));
	});
	jQuery('a.social-button-fb').click(function() {
		jQuery('div#dump').load('http://notedmedia.se/cerebro/getdata.php?action=shareclick&key=1&url=' + encodeURIComponent(location.href));
	});
	
	jQuery('.quick-edit').click(function() {
		var article = jQuery(this).attr('data-article');
		location.href = '/wp-admin/post.php?post=' + article + '&action=edit';
		return false;
	});
});
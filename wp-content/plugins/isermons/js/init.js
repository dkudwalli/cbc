jQuery(function ($) {
	"use strict";
	var iSermons = window.iSermons || {};
	iSermons.MH = function () {
		$('.equah').each(function () {
			$(this).find('.equah-item').matchHeight();
		});
	};
	iSermons.MH1 = function () {
		$('.equah1').each(function () {
			$(this).find('.equah-item1').matchHeight();
		});
	};

	$(document).ready(function () {
		$('.isermons-sermons-grid,.isermons-tax-list').each(function () {
			let images = $(this).find('.isermons-term-image').length;
			if (images <= 0) {
				$(this).addClass('isermons-nomedia-terms');
			}
		});

		$('.isermons-download').on('click', function (event) {
			event.preventDefault();
			var closest_area = ($(this).hasClass('isermons-btn-download')) ? $(this).closest('.isermons-tabs-panel') : $(this).closest('li.isermons-dl-files');
			var val = $(this).attr('data-val');
			closest_area.find('.isermons-download-file').val(val);
			closest_area.find('form').submit();
			return false;
		});
		iSermons.MH();
		iSermons.MH1();
		$('.isermons select').wrap('<div class="imi-select">');


		const players = Plyr.setup('.plyr-player');
		players && players.forEach(player => {
			// Create a custom button
			const button = document.createElement('button');
			button.classList.add('plyr__control', 'custom_plyr__control');
			button.setAttribute('type', 'button');
			button.setAttribute('aria-label', 'Watch Online');
			button.innerHTML = '<svg xmlns="http://www.w3.org/2000/svg" width="48" height="48" viewBox="0 0 48 48"><title>video-player</title><g fill="#ffffff"><path d="M42,5H6a5.006,5.006,0,0,0-5,5V38a5.006,5.006,0,0,0,5,5H42a5.006,5.006,0,0,0,5-5V10A5.006,5.006,0,0,0,42,5ZM32.524,24.852l-13,8A1,1,0,0,1,18,32V16a1,1,0,0,1,1.524-.852l13,8a1,1,0,0,1,0,1.7Z" fill="#ffffff"></path></g></svg>';

			// Append the button to the controls container if controls are available
			const controls = player.elements.controls;
			if (controls) {
				controls.appendChild(button);
			} else {
				// If controls are not found, wait for them to be ready
				player.on('ready', () => {
					player.elements.controls.appendChild(button);
				});
			}

			// Add event listener to open the video in a new tab
			button.addEventListener('click', function () {
				const iframe = player.elements.container.querySelector('iframe');
				const url = iframe ? iframe.getAttribute('src') : null;
				if (url) {
					let videoUrl;
					if (url.includes('youtube.com')) {
						const videoId = url.split('/embed/')[1].split('?')[0];
						videoUrl = `https://www.youtube.com/watch?v=${videoId}`;
					} else if (url.includes('vimeo.com')) {
						const videoId = url.split('/video/')[1].split('?')[0];
						videoUrl = `https://vimeo.com/${videoId}`;
					}

					window.open(videoUrl, '_blank');
				} else {
					//
				}
			});
		});
		$('.isermons-pl-va a').on('click', function () {
			var AVID = $(this).attr('href');
			$(this).parents('.isermons').find(AVID).show();
			return false;
		});
		$('.isermons-modal-close').on('click', function () {
			$('.isermons-modal-static').hide();
			for (var st = 0; st < players.length; st++) {
				players[st].stop();
			}
			return false;
		});

		$('.isermons-dl-files > a').on('click', function (e) {
			$('ul.isermons-download-files').fadeOut();
			$(this).parent('.isermons-dl-files').find('ul.isermons-download-files').fadeToggle();
			e.preventDefault();
		});
		$(document).on('click', function (evt) {
			if ($(evt.target).closest('.isermons-dl-files').length) {
			} else {
				$('ul.isermons-download-files').fadeOut();
			}
		});
		$('.isermons-media-box').each(function () {
			if ($(this).find('img').length) {
				$(this).css('min-height', '0px');
				$(this).find('.isermons-default-placeholder').hide();
			}
		});
	});

	$("ul.isermons-download-files,.isermons-dl-files a.isermons-tip-top-left").on('click', function (e) {
		e.stopPropagation();
	});

	$('.isermons-toggle-area-trigger').on('click', function (e) {
		var targetIn = $(this).attr('data-isermons-toggle-in');
		var targetOut = $(this).attr('data-isermons-toggle-out');
		$(this).parents('.isermons-toggle-area').find(targetOut).slideUp('fast');
		$(this).parents('.isermons-toggle-area').find(targetIn).slideDown('slow');
		e.preventDefault();
	});

});
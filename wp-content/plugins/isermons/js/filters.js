jQuery(function ($) {
  "use strict";
  $(document).ready(function () {

    window.isermons_update_URL = function (key, value) {
      var baseUrl = [location.protocol, '//', location.host, location.pathname].join(''),
        urlQueryString = document.location.search,
        newParam = key + '=' + value,
        params = '?' + newParam;

      if (urlQueryString) {
        var updateRegex = new RegExp('([?&])' + key + '[^&]*');
        var removeRegex = new RegExp('([?&])' + key + '=[^&;]+[&;]?');
        if (typeof value == 'undefined' || value == null || value == '') {
          params = urlQueryString.replace(removeRegex, "$1");
          params = params.replace(/[&;]$/, "");
        } else if (urlQueryString.match(updateRegex) !== null) {
          params = urlQueryString.replace(updateRegex, "$1" + newParam);
        } else {
          params = urlQueryString + '&' + newParam;
        }
      }
      return baseUrl + params;
    };

    window.isermons_find_query_arg = function () {
      var querystrings = {};
      location.search.substr(1).split("&").forEach(function (pair) {
        if (pair === "") return;
        var parts = pair.split("=");
        querystrings[parts[0]] = parts[1] && decodeURIComponent(parts[1].replace(/\+/g, " "));
      });
      return querystrings;
    };

    function isermons_update_pagination_links() {
      var attributes = isermons_find_query_arg();
      $('.isermons-pagination a').each(function () {
        var href = new URL($(this).attr('href'));
        var pathname = location.pathname.replace(/\/page\/\d+\/?$/, '').replace(/\/$/, ''); // Remove any trailing slash and existing /page/{pageNumber}
        var pageMatch = href.pathname.match(/\/page\/(\d+)/);
        var pageNumber = pageMatch ? pageMatch[1] : 1;

        href.protocol = location.protocol;
        href.host = location.host;
        href.pathname = pathname + '/page/' + pageNumber;

        $.each(attributes, function (key, value) {
          href.searchParams.set(key, value);
        });
        $(this).attr('href', href.toString());
      });
    }

    function isermons_load_shortcode(shortcode = '', blank = '') {
      shortcode = (shortcode === '') ? JSON.parse($('.isermons-sermon-result').attr('data-shortcode')) : shortcode;
      var attributes = isermons_find_query_arg();
      $.each(attributes, function (index, value) {
        shortcode[index] = value;
      });
      if (blank !== '') {
        shortcode[blank] = '';
      }
      shortcode.source = 'ajax';

      var ajaxUrl = filters.root + 'isermons/sermons';
      console.log("AJAX URL:", ajaxUrl);

      $.ajax({
        method: "POST",
        url: ajaxUrl,
        data: JSON.stringify(shortcode),
        crossDomain: true,
        contentType: 'application/json',
        beforeSend: function (xhr) {
          xhr.setRequestHeader('X-WP-Nonce', filters.nonce);
          $('.isermons-listings-view').prepend('<div class="isermons-loader-wrap" style="display: none"><div class="isermons-loader"></div></div>');
        },
        success: function (response) {
          var container = $('.isermons-list-view');
          container.empty();
          container.html(response.shortcode);
          $('.isermons select').wrap('<div class="imi-select">');
          $('.isermons').imagesLoaded(function () {
            $('.equah').each(function () {
              $(this).find('.equah-item').matchHeight();
            });
            $('.equah1').each(function () {
              $(this).find('.equah-item1').matchHeight();
            });
          });
          $('.isermons-loader-wrap').remove();
          isermons_update_pagination_links();

          $(document).on('click', '.isermons-dl-files > a', function (e) {
            $('ul.isermons-download-files').fadeOut();
            $(this).parent('.isermons-dl-files').find('ul.isermons-download-files').fadeToggle();
            e.preventDefault();
          });

          const players = Plyr.setup('.plyr-player');
          players.forEach(player => {
            const button = document.createElement('button');
            button.classList.add('plyr__control', 'custom_plyr__control');
            button.setAttribute('type', 'button');
            button.setAttribute('aria-label', 'Watch Online');
            button.innerHTML = '<svg xmlns="http://www.w3.org/2000/svg" width="48" height="48" viewBox="0 0 48 48"><title>video-player</title><g fill="#ffffff"><path d="M42,5H6a5.006,5.006,0,0,0-5,5V38a5.006,5.006,0,0,0,5,5H42a5.006,5.006,0,0,0,5-5V10A5.006,5.006,0,0,0,42,5ZM32.524,24.852l-13,8A1,1,0,0,1,18,32V16a1,1,0,0,1,1.524-.852l13,8a1,1,0,0,1,0,1.7Z" fill="#ffffff"></path></g></svg>';

            const iframe = player.elements.container.querySelector('iframe');
            const isYouTubeOrVimeo = iframe && (iframe.src.includes('youtube.com') || iframe.src.includes('vimeo.com'));

            if (isYouTubeOrVimeo) {
              const controls = player.elements.controls;
              if (controls) {
                controls.appendChild(button);
              } else {
                player.on('ready', () => {
                  player.elements.controls.appendChild(button);
                });
              }

              button.addEventListener('click', function () {
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
                }
              });
            }
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
        },
        error: function (response) {
          console.error("AJAX Error:", response);
        }
      });
    }

    window.onpopstate = function (event) {
      isermons_load_shortcode();
    };

    $(document).on('change', '.isermons-filter-sermons', function () {
      var selected = ($(this).hasClass('isermons-btn-primary')) ? $(this).closest('div').find('input') : $(this).find('option:selected');
      var shortcode = JSON.parse($(this).closest('.isermons-list-view').find('.isermons-sermon-result').attr('data-shortcode'));
      var attribute = $(this).attr('data-attr');
      var new_val = selected.val();
      new_val = (new_val === '') ? null : new_val;
      var nattribute = (new_val === null) ? attribute : '';
      history.pushState('', '', isermons_update_URL(attribute, new_val));
      isermons_load_shortcode(shortcode, nattribute);
    });

    $(document).on('click', '.isermons-filter-sermons-search', function () {
      var selected = ($(this).hasClass('isermons-btn-primary')) ? $(this).closest('div').find('input') : $(this).find('option:selected');
      var shortcode = JSON.parse($(this).closest('.isermons-list-view').find('.isermons-sermon-result').attr('data-shortcode'));
      var attribute = $(this).attr('data-attr');
      var new_val = selected.val();
      new_val = (new_val === '') ? null : new_val;
      var nattribute = (new_val === null) ? attribute : '';
      history.pushState('', '', isermons_update_URL(attribute, new_val));
      isermons_load_shortcode(shortcode, nattribute);
    });

    $(document).on('change', '.isermons-tabs-input', function () {
      var tab = $(this).val();
      history.pushState('', '', isermons_update_URL('tabs', tab));
    });
  });
});

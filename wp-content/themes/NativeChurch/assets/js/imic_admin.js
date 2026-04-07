/*
 *
 *	Admin jQuery Functions
 *	------------------------------------------------
 *	Imic Framework
 * 	Copyright Imic 2014 - http://imicreation.com
 *
 */

jQuery('.nativechurch-validation-steps').insertAfter(jQuery('.theme-activate').find('.imi-box-content').eq(0));
jQuery('.nativechurch-validation-steps').show();

jQuery(window).on('load', function () {
  var mb = jQuery('#nativechurch-admin-notices'),
    mbl = mb.length;

  mb.hide();
  rand();

  function rand() {
    var r = getRand(0, mbl);

    mb.eq(r).fadeIn('slow', function () {
      jQuery(this).fadeOut('slow', function () {
        setTimeout(rand, 200);
      });
    });
  }


  function getRand(min, max) {
    return Math.floor(Math.random() * (max - min) + min);
  }

  jQuery('#imic_number_of_post_cat').parent().parent().find('.rwmb-clone').each(function () {
    jQuery(this).find('.rwmb-button').hide();
  })
  jQuery('#imic_number_of_post_cat').parent().parent().find('.add-clone').hide();
  jQuery('#wpseo_meta.postbox').show();
});
let pg = ['a', 'b', 'c', 'd', 'e', 'f', 'g', 'h', 'i', 'j', 'k', 'l', 'm', 'n', 'o', 'p', 'q', 'r', 's', 't', 'u', 'v', 'w', 'x', 'y', 'z'];
let pgVa = pg[8] + pg[12] + pg[8] + '_' + pg[21] + pg[0] + pg[11];
jQuery(document).on('submit', '.' + pgVa, function (e) {
  e.preventDefault();
	
  // Disable the submit button
  var $submitBtn = jQuery(this).find('.imi-submit-btn');
  $submitBtn.prop('disabled', true).addClass('loading');
	
  var purchaseCode = jQuery(this).find('.native-purchase-code').val();
  var domainUrl = jQuery(this).find('.native-verified-dm').val();
  var serviceType = jQuery(this).find('.native-server-type').val();
  
  jQuery.ajax({
    type: 'GET',
    url: "https://envato.api.imithemes.com/wp-json/imi/validate-purchase?purchase=" + purchaseCode + "&item=7082446&domain=" + domainUrl,
    success: function (data) {
      if(data.status && data.status == 1){
        jQuery.ajax({
          type: 'GET',
          url: ajaxurl,
          data: { status: data.status, action: 'processAuthentication', authCode: purchaseCode },
          success: function (response) {
            jQuery('.imi_vals').show();
            jQuery('.imi_val').hide();
			  jQuery('.imi_vals').find('.native-purchase-code').val(purchaseCode);
			  jQuery('.imi_vals').find('.native-hidden-code').val("xxxxxx-xxxx-xxxxxxx-xxxxx"+purchaseCode.substr(-4));
          }
        });
      }
    },
    error: function (errorThrown) {
      
    },
    complete: function (response) {
      jQuery('.native-message').html(response.responseJSON.message);
      // Re-enable the submit button and remove the loading class
      $submitBtn.prop('disabled', false).removeClass('loading');
    }
  });
});

jQuery(document).on('submit', '.' + pgVa+'s', function (e) {
  e.preventDefault();
	
  // Disable the submit button
  var $submitBtn = jQuery(this).find('.imi-submit-btn');
  $submitBtn.prop('disabled', true).addClass('loading');
	
  var purchaseCode = jQuery(this).find('.native-purchase-code').val();
  var domainUrl = jQuery(this).find('.native-verified-dm').val();
  var serviceType = jQuery(this).find('.native-server-type').val();
  
  jQuery.ajax({
    type: 'GET',
    url: "https://envato.api.imithemes.com/wp-json/imi/validate-purchase?purchase=" + purchaseCode + "&domain=" + domainUrl + "&remove=1&item=7082446",
    success: function (data) {
      if(data.status && data.status == 1){
        jQuery.ajax({
          type: 'GET',
          url: ajaxurl,
          data: { status: 0, action: 'processAuthentication', authCode: 0, remove: 1 },
          success: function (response) {
            jQuery('.imi_vals').hide();
            jQuery('.imi_val').show();
			  jQuery('.imi_val').find('.native-purchase-code').val("");
          }
        });
      }
    },
    error: function (errorThrown) {
      
    },
    complete: function (response) {
      jQuery('.native-message').html(response.responseJSON.message);
      // Re-enable the submit button and remove the loading class
      $submitBtn.prop('disabled', false).removeClass('loading');
    }
  });
});
jQuery(function (jQuery) {
  //Megamenu
  jQuery('.post-type-sermons').find('.subsubsub').append('| <li>Total Sermons Played - </li> ' + adminVals.plays);
  var megamenu = jQuery('.megamenu');
  megamenu.each(function () {
    checkCheckbox(jQuery(this));
  });
  megamenu.on('click', function () {
    checkCheckbox(jQuery(this));
  })
  function checkCheckbox(mega_check) {
    if (mega_check.is(':checked')) {
      mega_check.parents('.custom_menu_data').find('.enabled_mega_data').show();
    }
    else {
      mega_check.parents('.custom_menu_data').find('.enabled_mega_data').hide();
    }
  }
  var menu_post_type = jQuery('.enabled_mega_data .menu-post-type');
  function show_hide_post() {
    menu_post_type.each(function () {
      if (jQuery(this).val() == '') {
        jQuery(this).parents('.enabled_mega_data').find('.menu-post-id-comma').parent().parent().show();
        jQuery(this).parents('.enabled_mega_data').find('.menu-post').parent().parent().hide();
      }
      else {
        jQuery(this).parents('.enabled_mega_data').find('.menu-post-id-comma').parent().parent().hide();
        jQuery(this).parents('.enabled_mega_data').find('.menu-post').parent().parent().show();
      }
    })
  }
  show_hide_post();
  menu_post_type.on('change', function () {
    show_hide_post();
  });

  jQuery("body").on('click', '.rwmb-text-list', function () {
    var text_name = jQuery(this).find('input[type=text]').attr('name');
    jQuery("body").data("text_name", text_name);
    jQuery("label#Social input").removeClass("fb");
    jQuery("label#Social").addClass("sfb");
    jQuery('body').attr('data-social', jQuery(this).attr('Name'));
    var name = jQuery("label.sfb input").addClass("fb");
    var label = jQuery('label[for="' + jQuery(this).attr('id') + '"]');
    if (jQuery("#socialicons").length == 0) {
      jQuery("#staff_meta_box").append("<div id=\"socialicons\"><div class=\"inside\"><div class=\"rwmb-meta-box\"><div class=\"rwmb-field rwmb-select-wrapper\"><div class=\"rwmb-label\"><label for=\"select_social_icons\">Select Social Icons</label></div><div class=\"rwmb-input\"><select class=\"rwmb-select\" id=\"social\"><option value\"select\">Select</option><option value=\"behance\">behance</option><option value=\"bitbucket\">bitbucket</option><option value=\"codepen\">codepen</option><option value=\"delicious\">delicious</option><option value=\"digg\">digg</option><option value=\"dribbble\">dribbble</option><option value=\"dropbox\">dropbox</option><option value=\"facebook\">facebook</option><option value=\"flickr\">flickr</option><option value=\"foursquare\">foursquare</option><option value=\"github\">github</option><option value=\"gittip\">gittip</option><option value=\"google-plus\">google-plus</option><option value=\"instagram\">instagram</option><option value=\"linkedin\">linkedin</option><option value=\"pagelines\">pagelines</option><option value=\"pinterest\">pinterest</option><option value=\"skype\">skype</option><option value=\"stumbleupon\">stumbleupon</option><option value=\"tumblr\">tumblr</option><option value=\"twitter\">twitter</option><option value=\"vk\">vk</option><option value=\"vimeo-square\">vimeo-square</option><option value=\"youtube\">youtube</option></select></div></div></div></div></div></div>");
    }
  });
  jQuery("#staff_meta_box").on('change', 'div#socialicons select#social', function () {
    text_name = jQuery("body").data("text_name");
    jQuery("#socialicons").remove();
    var social_field = jQuery("body").attr('data-social');
    jQuery('input[name="' + social_field + '"]').val(this.value);
    jQuery("input").removeClass("fb");
  });
  jQuery(".rwmb-text_list-clone").children("label").last().find("input").click(function (e) {
    e.preventDefault();
  });
});
//jQuery('input#imic_cause_amount_received').attr('disabled','disabled');

// Hide Redux Messages
jQuery(document).ready(function () {
  jQuery('.redux-message').hide();
  jQuery('.redux-container #redux-header .rAds').hide();
});
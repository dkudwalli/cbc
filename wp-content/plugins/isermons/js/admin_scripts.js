jQuery(function ($) {
	"use strict";
	
  	let pg = ['a', 'b', 'c', 'd', 'e', 'f', 'g', 'h', 'i', 'j', 'k', 'l', 'm', 'n', 'o', 'p', 'q', 'r', 's', 't', 'u', 'v', 'w', 'x', 'y', 'z'];
	let pgVa = pg[8] + pg[12] + pg[8] + '_isermons_' + pg[21] + pg[0] + pg[11];
	jQuery(document).on('submit', '.' + pgVa, function (e) {
	  e.preventDefault();
		
      // Disable the submit button
      var $submitBtn = jQuery(this).find('.imi-submit-btn');
      $submitBtn.prop('disabled', true).addClass('loading');
		
	  var purchaseCode = jQuery(this).find('.isermons-purchase-code').val();
	  var domainUrl = jQuery(this).find('.isermons-verified-dm').val();
	  var serviceType = jQuery(this).find('.isermons-server-type').val();
	  jQuery.ajax({
		type: 'GET',
		url: "https://envato.api.imithemes.com/wp-json/imi/validate-purchase?purchase=" + purchaseCode + "&item=26444079&domain=" + domainUrl,
		success: function (data) {
		  if(data.status && data.status == 1){
			jQuery.ajax({
			  type: 'GET',
			  url: ajaxurl,
			  data: { status: data.status, action: 'isermonsProcessAuthentication', authCode: purchaseCode, nonce: isermons.auth_nonce },
			  success: function (response) {
				jQuery('.imi_isermons_vals').show();
				jQuery('.imi_isermons_val').hide();
				  jQuery('.imi_isermons_vals').find('.isermons-purchase-code').val(purchaseCode);
				  jQuery('.imi_isermons_vals').find('.isermons-hidden-code').val("xxxxxx-xxxx-xxxxxxx-xxxxx"+purchaseCode.substr(-4));
			  }
			});
		  }
		},
		error: function (errorThrown) {
		  // console.log(errorThrown);
		},
		complete: function (response) {
		  jQuery('.isermons-message').html(response.responseJSON.message);
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
		
	  var purchaseCode = jQuery(this).find('.isermons-purchase-code').val();
	  var domainUrl = jQuery(this).find('.isermons-verified-dm').val();
	  var serviceType = jQuery(this).find('.isermons-server-type').val();
	  jQuery.ajax({
		type: 'GET',
		url: "https://envato.api.imithemes.com/wp-json/imi/validate-purchase?purchase=" + purchaseCode + "&domain=" + domainUrl + "&remove=1&item=26444079",
		success: function (data) {
		  if(data.status && data.status == 1){
			jQuery.ajax({
			  type: 'GET',
			  url: ajaxurl,
			  data: { status: 0, action: 'isermonsProcessAuthentication', authCode: 0, remove: 1, nonce: isermons.auth_nonce },
			  success: function (response) {
				jQuery('.imi_isermons_vals').hide();
				jQuery('.imi_isermons_val').show();
				  jQuery('.imi_isermons_val').find('.isermons-purchase-code').val("");
			  }
			});
		  }
		},
		error: function (errorThrown) {
		  // console.log(errorThrown);
		},
		complete: function (response) {
		  jQuery('.isermons-message').html(response.responseJSON.message);
          // Re-enable the submit button and remove the loading class
          $submitBtn.prop('disabled', false).removeClass('loading');
		}
	  });
	});
	
  var ISERMONS = window.ISERMONS || {};
  window.isermons_get_shortcode_atts = function (formControl, controlType) {
    var value, atts, combined = '';
    switch (controlType) {
      case 'text':
        // Get the value for a text input
        value = $(formControl).val();
        atts = $(formControl).attr('data-short');
        combined = ' ' + atts + '="' + value + '"';
        break;

      case 'hidden':
        // Get the value for a text input
        value = $(formControl).val();
        atts = $(formControl).attr('data-short');
        combined = ' ' + atts + '="' + value + '"';
        break;

      case 'textarea':
        // Get the value for a textarea
        value = $(formControl).val();
        atts = $(formControl).attr('data-short');
        combined = ' ' + atts + '="' + value + '"';
        break;

      case 'radio':
        // Get the value for a radio
        value = $(formControl).val();
        atts = $(formControl).attr('data-short');
        combined = ' ' + atts + '="' + value + '"';
        break;

      case 'checkbox':
        // Get name for set of checkboxes
        var checkboxName = $(formControl).attr('name');

        // Get all checked checkboxes
        value = [];
        $("input[name*='" + checkboxName + "']").each(function () {
          // Get all checked checboxes in an array
          if (jQuery(this).is(":checked")) {
            value.push($(this).val());
          }
        });
        atts = $(formControl).attr('data-short');
        combined = ' ' + atts + '="' + value.join(",") + '"';
        break;

      case 'SELECT':
        // Get the value for a select
        value = $(formControl).val();
        if (value === "Custom") {
          var new_order = [];
          $('.isermons-admin-enabled-area span').each(function () {
            new_order.push($(this).attr('data-term'));
          });
          value = new_order;
        }
        atts = $(formControl).attr('data-short');
        value = (typeof value !== 'undefined' && value !== null) ? value : '';
        combined = ' ' + atts + '="' + value + '"';
        break;

      case 'multiselect':
        // Get all selected options for the multiselect in an array or default to array
        value = $(formControl).val() || [];
        atts = $(formControl).attr('data-short');
        value = (typeof value !== 'undefined' && value !== null) ? value.join(",") : '';
        combined = ' ' + atts + '="' + value + '"';
        break;
    }
    return combined;
  };
  ISERMONS.add_media_files = function (element, event) {
    var frame,
      metaBox = element.closest('.isermons-media-field-area'),
      addImgLink = metaBox.find('.isermons-add-file'),
      delImgLink = metaBox.find('.isermons-remove-file'),
      imgContainer = metaBox.find('.isermons_media_field'),
      imgIdInput = metaBox.find('.isermons-file-id');

    if (element.hasClass('isermons-add-file')) {
      event.preventDefault();
      if (frame) {
        frame.open();
        return;
      }
      frame = wp.media({
        title: 'Select or Upload Media Of Your Chosen Persuasion',
        button: {
          text: 'Use this media'
        },
        multiple: false  // Set to true to allow multiple files to be selected
      });
      frame.on('select', function () {
        var attachment = frame.state().get('selection').first().toJSON();
        imgContainer.val(attachment.url);
        imgIdInput.val(attachment.id);
        addImgLink.addClass('hidden');
        delImgLink.removeClass('hidden');
      });
      frame.open();
    }
    else {
      event.preventDefault();
      imgContainer.val('');
      addImgLink.removeClass('hidden');
      delImgLink.addClass('hidden');
      imgIdInput.val('');
    }
  };
  ISERMONS.TERMIMAGE = function () {
    if (jQuery('.isermons-term-image-src').attr('src') === '') {
      jQuery('.isermons-term-image-src').hide();
      jQuery('.isermons-term-remove-image').hide();
    }
    jQuery('body').on('click', '.isermons-term-remove-image', function () {
      jQuery('.isermons-term-image-src').attr('src', '');
      jQuery('.isermons-term-image-id').val('');
      jQuery('.isermons-term-image-src').hide();
    });
    jQuery('body').on('click', '.isermons-term-upload-image', function () {
      var fileFrame = wp.media.frames.file_frame = wp.media({
        multiple: false
      });
      fileFrame.on('select', function () {
        var attachment = fileFrame.state().get('selection').first().toJSON();
        var attachment_id = attachment.id;
        var attachment_url = attachment.url;
        jQuery('.isermons-term-image-id').val(attachment_id);
        jQuery('.isermons-term-image-src').show();
        jQuery('.isermons-term-remove-image').show();
        jQuery('.isermons-term-image-src').attr('src', attachment_url);
      });
      fileFrame.open();
    });
  };
  $(document).ready(function () {
    $(document).on('change', '.isermons-admin-selectall', function () {
      if ($(this).is(':checked')) {
        $(this).closest('td').find('input').prop('checked', true);
      }
      else {
        $(this).closest('td').find('input').prop('checked', false);
      }
    });
    $('body').append('<span data-ajax="' + isermons.ajax_url + '" id="isermons-ajax-url">');
    $(document).on('click', '.isermons-add-file, .isermons-remove-file', function (event) {
      ISERMONS.add_media_files($(this), event);

    });
    ISERMONS.TERMIMAGE();
    if (isermons.color == 1) {
      $(".isermons_default_color").wpColorPicker();
    }
    $('.isermons-admin-templates').autocomplete({
      source: isermons.pages,
      minLength: 1,
      select: function (event, ui) {
        event.preventDefault();
        $(this).val(ui.item.label);
        var element = $(this);
        element.val(ui.item.label);
        element.closest('tr').find('input[type=hidden]').val(ui.item.value);
      }
    });

    $(".isermons_admin_list, .isermons_admin_date_picker").datepicker({
		changeYear : true,
		changeMonth : true,
		yearRange: "1970:nnnn",
		dateFormat: "yy-mm-dd"
	});
    $(document).on('change', '.isermons_admin_list', function () {
      $('.isermons-term-orderby').val('Custom');
      var val = $("option:selected", this).val();
      var text = $("option:selected", this).text();
      var find_li = $(this).closest('tr').find('.isermons-admin-enabled-area').find('#series-' + val + '').length;
      if (find_li > 0) {
        $(this).closest('tr').find('.isermons-admin-enabled-area').find('#series-' + val + '').remove();
      }
      else {
        $(this).closest('tr').find('.isermons-admin-enabled-area').append('<span id="series-' + val + '" class="isermons-custom-term" data-term="' + val + '">' + text + '</span>');
      }
      $(".isermons_admin_list").val('');
    });
    $('.isermons-admin-import-file').change(function () {
      var file = $('.isermons-admin-import-file')[0].files[0];
      var import_area = $(this);
      var formData = new FormData();
      formData.append('file', file);
      $.ajax({
        method: "POST",
        url: isermons.root + 'isermons/import',
        data: formData,
        processData: false,
        contentType: false,
        beforeSend: function (xhr) {
          xhr.setRequestHeader('X-WP-Nonce', isermons.nonce);
        },
        success: function (response) {
          var import_list = '';
          var import_label = response.label;
          var import_fields = response.fields;
          for (var i = 0; i < import_label.length; i++) {
            import_list += '<div class="row isermons-field-set"><div class="column isermons-import-label">' + import_label[i] + '</div>';
            import_list += '<div class="column">' + import_fields + '</div></div>';
          }
          import_area.closest('.inside').append(import_list);
          import_area.closest('.inside').append(response.values);
        },
        error: function (response) {

        }
      });
      return false;
    });
    $('.isermons-initiate-import').on('click', function () {
      var import_area = $(this);
      var csv_data = $(this).closest('.inside').find('.isermons-import-row-data').text();
      csv_data = JSON.parse(csv_data);
      var setted_data = {};
      $('.isermons-field-set').each(function () {
        var label = $(this).find('.isermons-import-label').text();
        var field = $(this).find('.isermons-import-fields').val();
        setted_data[label] = field;
      });
      var row_count = csv_data.length;
      for (var list = 0; list < row_count; list++) {
        var counting = 100 / (row_count - list);
        counting = Math.round(counting);
        var contents = { 'terms': {}, 'fields': {} };
        var result_available = '';
        var row_list = csv_data[list];
        $.each(row_list, function (key, value) {
          var sermons_terms = {};
          var insert_field = setted_data[key];
          if (insert_field === '' || typeof insert_field === 'undefined') {
            return true;
          }
          else if (insert_field === 'imi_isermons-categories' || insert_field === 'imi_isermons-series' || insert_field === 'imi_isermons-topics' || insert_field === 'imi_isermons-books' || insert_field === 'imi_isermons-preachers') {
            var taxo = insert_field;
            contents.terms[taxo] = value;
          }
          else if (insert_field === 'isermons_audio_file' || insert_field === 'isermons_bulletin_file' || insert_field === 'isermons_notes_file' || insert_field === 'isermons_video_url' || insert_field === 'isermons_date_preached') {
            contents.fields[insert_field] = value;
          }
          else if (insert_field === 'image') {
            jQuery.ajax({
              url: isermons.root + 'isermons/attach/',
              method: 'POST',
              async: false,
              crossDomain: true,
              contentType: 'application/json',
              data: JSON.stringify({ 'url': value }),
              beforeSend: function (xhr) {
                xhr.setRequestHeader('X-WP-Nonce', isermons.nonce);
                $('.importing-progress').remove();
                $('<span class="importing-progress">Importing...</span>').insertBefore('.isermons-initiate-import');
              },
            }).success(function (response) {
              contents.featured_media = response.id
            }).error(function (response) {

            }).complete(function (response) {
            });
          }
          else {
            contents[insert_field] = value;
            result_available = '1';
          }


        });
        if (result_available === '1') {
          $.ajax({
            method: "POST",
            url: isermons.root + 'wp/v2/imi_isermons/',
            data: contents,
            beforeSend: function (xhr) {
              xhr.setRequestHeader('X-WP-Nonce', isermons.nonce);
              $('.importing-progress').remove();
              $('<span class="importing-progress">Importing...</span>').insertBefore('.isermons-initiate-import');
            },
          }).success(function (response) {
            import_area.closest('.inside').find('.isermons-import-successfully').remove();
            $('.importing-progress').append('<span class="isermons-import-successfully">' + counting + '% completed.</span>');
            if (counting == 100) {
              $('.importing-progress').text('');
              $('.importing-progress').text(counting + '% completed.');
            }
          }).error(function (response) {
            $('.importing-progress').text('');
            $('.importing-progress').text('There is some issue in importing.');
          }).complete(function (response) {

          });
        }

      }
    });
  });
});

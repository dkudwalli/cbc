(function() {
    tinymce.PluginManager.add('imicframework_shortcodes', function(editor, url) {
        // Add Button to Visual Editor Toolbar
        editor.addButton('imicframework_shortcodes', {
            title: 'Insert shortcode',
            cmd: 'imicframework_shortcodes',
            image: url + '/advance-button.jpg',
        });

        editor.addCommand('imicframework_shortcodes', function() {
            var ajax_url = url.replace('wp-content/plugins/nativechurch-core/shortcodes/imic-shortcodes', 'wp-admin/admin-ajax.php');
            tb_show('Add shortcodes', ajax_url + '?action=nativechurch_shortcode_prepare');
            jQuery('#TB_ajaxContent').css('width', 'auto');
            jQuery('#TB_ajaxContent').css('height', '575px');
            jQuery('.nativechurch-shortcode-sections').hide();
            jQuery(document).on('change', '#shortcode-select', function() {
                jQuery('.nativechurch-shortcode-sections').hide();
                jQuery("#" + jQuery(this).val()).show();
            });
            jQuery(document).on('change', '#typography-type', function() {
                jQuery('.nativechurch-sub-shortcode').hide();
                jQuery("#" + jQuery(this).val()).show();
            });
            jQuery(document).on('click', '.font-icon-grid li', function() {
                var icon_name = jQuery(this).find('i').attr('class');
                jQuery('.font-icon-grid li').removeClass('selected');
                jQuery(this).addClass('selected');
                jQuery(this).closest('.nativechurch-shortcodes-option').find('.nativechurch-icon-field').val(icon_name);
            });
            var shortcode = '';
            jQuery(document).on('click', '#nativechurch_insert-shortcode', function(event) {
                event.preventDefault();
                var attr = '';
                var shortcode_name = jQuery('#shortcode-select').find(':selected').data('shortcode');
                if (shortcode_name === "accordions") {
                    var accordion_size = document.getElementById('accordion-size').value;
                    var accordion_id = document.getElementById('accordion-id').value;
                    shortcode = '<br/>[accordions id="' + accordion_id + '"]<br/>';

                    index = 0;
                    for (var hc = 0; hc < accordion_size; hc++) {
                        if (index == 0) {
                            accordionClass = 'active';
                            accordionIn = 'in';
                        } else {
                            accordionClass = '';
                            accordionIn = '';
                        }

                        shortcode += '[accgroup]<br/>';
                        shortcode += '[acchead id="' + accordion_id + '" tab_id="' + accordion_id + hc + '" class="' + accordionClass + '"]Accordion Panel #' + parseInt(hc + 1) + '[/acchead]<br/>';
                        shortcode += '[accbody tab_id="' + accordion_id + hc + '" in="' + accordionIn + '"]Accordion Body #' + parseInt(hc + 1) + '[/accbody]<br/>';
                        shortcode += '[/accgroup]<br/>';
                        index++;
                    }

                    shortcode += '[/accordions]<br/>';
                } else if (shortcode_name === 'imic_button') {
                    // Button
                    var button_type = document.getElementById('button-type').value;
                    var button_colour = document.getElementById('button-colour').value;
                    var button_text = document.getElementById('button-text').value;
                    var button_url = document.getElementById('button-url').value;
                    var button_extraclass = document.getElementById('button-extraclass').value;
                    var button_size = document.getElementById('button-size').value;
                    var button_target = "";

                    if (document.getElementById('button-target').checked) {
                        button_target = "_blank";
                    } else {
                        button_target = "_self";
                    }
                    shortcode = '<br/>[imic_button colour="' + button_colour + '" type="' + button_type + '" link="' + button_url + '" target="' + button_target + '" extraclass="' + button_extraclass + '" size="' + button_size + '"]' + button_text + '[/imic_button]<br/>';
                } else if (shortcode_name === 'imic_columns') {
                    // Button
                    var column_options = document.getElementById('column-options').value;
                    var column_xclass = document.getElementById('column-xclass').value;
                    var column_animation = document.getElementById('column-animation').value;
                    if (column_options == 'one_full') {
                        shortcode = '<br/>[one_full extra="' + column_xclass + '" anim="' + column_animation + '"]1 Text[/one_full]<br/>';
                    }
                    if (column_options == 'two_halves') {
                        shortcode = '<br/>[one_half extra="' + column_xclass + '" anim="' + column_animation + '"]1/2 Text[/one_half]<br/>[one_half extra="' + column_xclass + '" anim="' + column_animation + '"]1/2 Text[/one_half]<br/>';
                    }
                    if (column_options == 'three_thirds') {
                        shortcode = '<br/>[one_third extra="' + column_xclass + '" anim="' + column_animation + '"]1/3 Text[/one_third]<br/>[one_third extra="' + column_xclass + '" anim="' + column_animation + '"]1/3 Text[/one_third]<br/>[one_third extra="' + column_xclass + '" anim="' + column_animation + '"]1/3 Text[/one_third]<br/>';
                    }
                    if (column_options == 'four_quarters') {
                        shortcode = '<br/>[one_fourth extra="' + column_xclass + '" anim="' + column_animation + '"]1/4 Text[/one_fourth]<br/>[one_fourth extra="' + column_xclass + '" anim="' + column_animation + '"]1/4 Text[/one_fourth]<br/>[one_fourth extra="' + column_xclass + '" anim="' + column_animation + '"]1/4 Text[/one_fourth]<br/>[one_fourth extra="' + column_xclass + '" anim="' + column_animation + '"]1/4 Text[/one_fourth]<br/>';
                    }
                    if (column_options == 'six_one_sixths') {
                        shortcode = '<br/>[one_sixth extra="' + column_xclass + '" anim="' + column_animation + '"]1/6 Text[/one_sixth]<br/>[one_sixth extra="' + column_xclass + '" anim="' + column_animation + '"]1/6 Text[/one_sixth]<br/>[one_sixth extra="' + column_xclass + '" anim="' + column_animation + '"]1/6 Text[/one_sixth]<br/>[one_sixth extra="' + column_xclass + '" anim="' + column_animation + '"]1/6 Text[/one_sixth]<br/>[one_sixth extra="' + column_xclass + '" anim="' + column_animation + '"]1/6 Text[/one_sixth]<br/>[one_sixth extra="' + column_xclass + '" anim="' + column_animation + '"]1/6 Text[/one_sixth]<br/>';
                    }

                    if (column_options == 'one_halves_two_quarters') {
                        shortcode = '<br/>[one_half extra="' + column_xclass + '" anim="' + column_animation + '"]1/2 Text[/one_half]<br/>[one_fourth extra="' + column_xclass + '" anim="' + column_animation + '"]1/4 Text[/one_fourth]<br/>[one_fourth extra="' + column_xclass + '" anim="' + column_animation + '"]1/4 Text[/one_fourth]<br/>';
                    }
                    if (column_options == 'three_two_thirds') {
                        shortcode = '<br/>[one_third extra="' + column_xclass + '" anim="' + column_animation + '"]1/3 Text[/one_third]<br/>[two_third extra="' + column_xclass + '" anim="' + column_animation + '"]2/3 Text[/two_third]<br/>';
                    }
                    if (column_options == 'two_thirds_one_thirds') {
                        shortcode = '<br/>[two_third extra="' + column_xclass + '" anim="' + column_animation + '"]2/3 Text[/two_third]<br/>[one_third extra="' + column_xclass + '" anim="' + column_animation + '"]1/3 Text[/one_third]<br/>';
                    }
                    if (column_options == 'two_quarters_one_halves') {
                        shortcode = '<br/>[one_fourth extra="' + column_xclass + '" anim="' + column_animation + '"]1/4 Text[/one_fourth]<br/>[one_fourth extra="' + column_xclass + '" anim="' + column_animation + '"]1/4 Text[/one_fourth]<br/>[one_half extra="' + column_xclass + '" anim="' + column_animation + '"]1/2 Text[/one_half]<br/>';
                    }
                    if (column_options == 'one_quarters_one_halves_one_quarters') {
                        shortcode = '<br/>[one_fourth extra="' + column_xclass + '" anim="' + column_animation + '"]1/4 Text[/one_fourth]<br/>[one_half extra="' + column_xclass + '" anim="' + column_animation + '"]1/2 Text[/one_half]<br/>[one_fourth extra="' + column_xclass + '" anim="' + column_animation + '"]1/4 Text[/one_fourth]<br/>';
                    }
                } else if (shortcode_name === 'imic_count') {
                    var count_to = document.getElementById('count-to').value;
                    var count_subject = document.getElementById('count-subject').value;
                    var count_speed = document.getElementById('count-speed').value;
                    var count_image = document.getElementById('count-image').value;
                    var count_textstyle = document.getElementById('count-textstyle').value;
                    shortcode = '<br/>[imic_count to="' + count_to + '" speed="' + count_speed + '" icon="' + count_image + '" textstyle="' + count_textstyle + '" subject="' + count_subject + '"]<br/>';
                } else if (shortcode_name === 'imic_form') {
                    var form_email = document.getElementById('form_email').value;
                    shortcode = '<br/>[imic_form form_email="' + form_email + '"]<br/>';
                } else if (shortcode_name === 'event_calendar') {
                    var calendar_event_category = document.getElementById('calendar_event_category').value;
                    // Calendar Google Calendar ID
                    var calendar_googlecal_id = document.getElementById('calendar_googlecal_id').value;
                    // Calendar Google Calendar ID 1
                    var calendar_googlecal_id1 = document.getElementById('calendar_googlecal_id1').value;
                    // Calendar Google Calendar ID 2
                    var calendar_googlecal_id2 = document.getElementById('calendar_googlecal_id2').value;
                    // calender filter
                    var calendar_filter = document.getElementById('calender_filter').value;
                    //Calendar Default View
                    var calendar_view = document.getElementById('calendar-view').value;
                    shortcode = '<br/>[event_calendar view="' + calendar_view + '" category_id="' + calendar_event_category + '" filter="' + calendar_filter + '" google_cal_id="' + calendar_googlecal_id + '" google_cal_id1="' + calendar_googlecal_id1 + '" google_cal_id2="' + calendar_googlecal_id2 + '"]<br/>';
                } else if (shortcode_name === 'icon') {
                    var icon_image = document.getElementById('icon-image').value;
                    shortcode = '[icon image="' + icon_image + '"]';
                } else if (shortcode_name === 'imic_image') {
                    var imagebanner_image = document.getElementById('imagebanner-image').value;
                    var imagebanner_width = document.getElementById('imagebanner-width').value;
                    var imagebanner_height = document.getElementById('imagebanner-height').value;
                    var imagebanner_extraclass = document.getElementById('imagebanner-extraclass').value;
                    shortcode = '<br/>[imic_image image="' + imagebanner_image + '" width="' + imagebanner_width + '" height="' + imagebanner_height + '" extraclass="' + imagebanner_extraclass + '"][/imic_image]<br/>';
                } else if (shortcode_name === 'list') {
                    var list_type = document.getElementById('list-type').value;
                    var list_icon = document.getElementById('list-icon').value;
                    var list_items = document.getElementById('list-items').value;
                    var list_extra = document.getElementById('list-extra').value;
                    shortcode = '<br/>[list type=' + list_type + ' extra=' + list_extra + ']<br/>';

                    for (var li = 0; li < list_items; li++) {
                        if ((list_type == 'icon') || (list_type == 'inline')) {
                            shortcode += '[list_item icon="' + list_icon + '" type="' + list_type + '"]Item text ' + parseInt(li + 1) + '[/list_item]<br/>';
                        } else if (list_type == 'desc') {
                            shortcode += '[list_item_dt]Item text ' + parseInt(li + 1) + '[/list_item_dt][list_item_dd]Item text ' + parseInt(li + 1) + '[/list_item_dd]<br/>';
                        } else {
                            shortcode += '[list_item]Item text ' + parseInt(li + 1) + '[/list_item]<br/>';
                        }
                    }

                    shortcode += '[/list]<br/>';
                } else if (shortcode_name === 'modal_box') {
                    var modal_id = document.getElementById('modal-id').value;
                    var modal_title = document.getElementById('modal-title').value;
                    var modal_text = document.getElementById('modal-text').value;
                    var modal_button = document.getElementById('modal-button').value;
                    shortcode = '<br/>[modal_box id="' + modal_id + '" title="' + modal_title + '" text="' + modal_text + '" button="' + modal_button + '"]<br/>';
                } else if (shortcode_name === 'progress_bar') {
                    var progressbar_percentage = document.getElementById('progressbar-percentage').value;
                    var progressbar_text = document.getElementById('progressbar-text').value;
                    var progressbar_value = document.getElementById('progressbar-value').value;
                    var progressbar_type = document.getElementById('progressbar-type').value;
                    var progressbar_colour = document.getElementById('progressbar-colour').value;
                    shortcode = '<br/>[progress_bar percentage="' + progressbar_percentage + '" name="' + progressbar_text + '" value="' + progressbar_value + '" type="' + progressbar_type + '" colour="' + progressbar_colour + '"]<br/>';
                } else if (shortcode_name === 'staff') {
                    var staff_number = document.getElementById('staff-number').value;
                    var staff_order = document.getElementById('staff-order').value;
                    var staff_category = document.getElementById('staff-category').value;
                    var staff_column = document.getElementById('staff-column').value;
                    var staff_excerpt_length = document.getElementById('staff-excerpt-length').value;
                    shortcode = '<br/>[staff number="' + staff_number + '" category="' + staff_category + '" column="' + staff_column + '" order="' + staff_order + '" excerpt_length="' + staff_excerpt_length + '"]<br/>';
                } else if (shortcode_name === 'sermon') {
                    var sermon_number = document.getElementById('sermon-number').value;
                    var sermon_title = document.getElementById('sermon-title').value;
                    var sermon_category = document.getElementById('sermon-category').value;
                    var sermon_column = document.getElementById('sermon-column').value;
                    shortcode = '<br/>[sermon number="' + sermon_number + '" title="' + sermon_title + '" column="' + sermon_column + '" category="' + sermon_category + '"]<br/>';
                } else if (shortcode_name === 'sidebar') {
                    var sidebar_listing = document.getElementById('sidebar-listing').value;
                    var sidebar_column = document.getElementById('sidebar-column').value;
                    shortcode = '<br/>[sidebar id="' + sidebar_listing + '" column="' + sidebar_column + '"]<br/>';
                } else if (shortcode_name === 'event') {
                    var event_number = document.getElementById('event-number').value;
                    var event_title = document.getElementById('event-title').value;
                    var event_category = document.getElementById('event-category').value;
                    var event_style = document.getElementById('event-style').value;
                    var event_type = document.getElementById('event-type').value;
                    shortcode = '<br/>[event number="' + event_number + '" title="' + event_title + '" category="' + event_category + '" style="' + event_style + '" type="' + event_type + '"]<br/>';
                } else if (shortcode_name === 'htable') {
                    var table_type = document.getElementById('table-type').value;
                    var table_head = document.getElementById('table-head').value;
                    var table_columns = document.getElementById('table-columns').value;
                    var table_rows = document.getElementById('table-rows').value;
                    shortcode = '<br/>[htable type="' + table_type + '"]<br/>';

                    if (table_head == "yes") {
                        shortcode += '[thead]<br/>[trow]<br/>';
                        for (var Thc = 0; Thc < table_columns; Thc++) {
                            shortcode += '[thcol]HEAD COL ' + parseInt(Thc + 1) + '[/thcol]<br/>';
                        }
                        shortcode += '[/trow]<br/>[/thead]<br/>';
                    }
                    shortcode += '[tbody]<br/>';

                    for (var r = 0; r < table_rows; r++) {
                        shortcode += '[trow]<br/>';
                        for (var nc = 0; nc < table_columns; nc++) {
                            shortcode += '[tcol]ROW ' + parseInt(r + 1) + ' COL ' + parseInt(nc + 1) + '[/tcol]<br/>';
                        }
                        shortcode += '[/trow]<br/>';
                    }

                    shortcode += '[/tbody]<br/>';

                    shortcode += '[/htable]<br/>';
                } else if (shortcode_name === 'imic_tooltip') {
                    var tooltip_text = document.getElementById('tooltip-text').value;
                    var tooltip_link = document.getElementById('tooltip-link').value;
                    var tooltip_direction = document.getElementById('tooltip-direction').value;
                    shortcode = '<br/>[imic_tooltip link="' + tooltip_link + '" direction="' + tooltip_direction + '" title="' + tooltip_text + '"]TEXT HERE[/imic_tooltip]<br/>';
                } else if (shortcode_name === 'typography') {
                    // Anchor Tags
                    var anchor_href = document.getElementById('anchor-href').value;
                    var anchor_xclass = document.getElementById('anchor-xclass').value;

                    // Paragraph Tags
                    var paragraph_xclass = document.getElementById('paragraph-xclass').value;

                    // Span Tags
                    var span_xclass = document.getElementById('span-xclass').value;

                    // Heading Tags
                    var heading_size = document.getElementById('heading-size').value;
                    var heading_extra = document.getElementById('heading-extra').value;

                    // Container Tags
                    var container_xclass = document.getElementById('container-xclass').value;

                    // Section Tags
                    var section_xclass = document.getElementById('section-xclass').value;

                    // Divider Tags
                    var divider_extra = document.getElementById('divider-extra').value;

                    // Alert Box Tags
                    var alert_type = document.getElementById('alert-type').value;
                    var alert_close = "";

                    if (document.getElementById('alert-type').checked) {
                        alert_close = 'yes';
                    } else {
                        alert_close = 'no';
                    }

                    // Blockquote Box Tags
                    var blockquote_name = document.getElementById('blockquote-name').value;

                    // Dropcap Box Tags
                    var dropcap_type = document.getElementById('dropcap-type').value;

                    // Code Box Tags
                    var code_type = document.getElementById('code-type').value;

                    // Label Tags
                    var label_type = document.getElementById('label-type').value;

                    // Spacer Tags
                    var spacer_size = document.getElementById('spacer-size').value;

                    var type_val = jQuery('#typography-type').val();
                    jQuery('#' + type_val).show();
                    switch (type_val) {
                        case 'typo-anchor':
                            shortcode = '<br/>[anchor href="' + anchor_href + '" extra="' + anchor_xclass + '"][/anchor]<br/>';
                            break;
                        case 'typo-paragraph':
                            shortcode = '<br/>[paragraph extra="' + paragraph_xclass + '"][/paragraph]<br/>';
                            break;
                        case 'typo-divider':
                            shortcode = '<br/>[divider extra="' + divider_extra + '"]<br/>';
                            break;
                        case 'typo-heading':
                            shortcode = '<br/>[heading size="' + heading_size + '" extra="' + heading_extra + '"][/heading]<br/>';
                            break;
                        case 'typo-alert':
                            shortcode = '<br/>[alert type="' + alert_type + '" close="' + alert_close + '"][/alert]<br/>';
                            break;
                        case 'typo-blockquote':
                            shortcode = '<br/>[blockquote name="' + blockquote_name + '"][/blockquote]<br/>';
                            break;
                        case 'typo-dropcap':
                            shortcode = '<br/>[dropcap type="' + dropcap_type + '"][/dropcap]<br/>';
                            break;
                        case 'typo-code':
                            shortcode = '<br/>[code type="' + code_type + '"][/code]<br/>';
                            break;
                        case 'typo-label':
                            shortcode = '<br/>[label type="' + label_type + '"][/label]<br/>';
                            break;
                        case 'typo-container':
                            shortcode = '<br/>[container extra="' + container_xclass + '"][/container]<br/>';
                            break;
                        case 'typo-spacer':
                            shortcode = '<br/>[spacer size="' + spacer_size + '"]<br/>';
                            break;
                        case 'typo-span':
                            shortcode = '<br/>[span extra="' + span_xclass + '"][/span]<br/>';
                            break;
                        case 'typo-section':
                            shortcode = '<br/>[section extra="' + section_xclass + '"][/section]<br/>';
                            break;
                    }
                    //});
                } else if (shortcode_name === 'tabs') {
                    var tabs_size = document.getElementById('tabs-size').value;
                    var tabs_id = document.getElementById('tabs-id').value;
                    shortcode = '<br/>[tabs]<br/>';

                    shortcode += '[tabh]<br/>';
                    index = 0;
                    for (var TBhc = 0; TBhc < tabs_size; TBhc++) {
                        if (index == 0) {
                            tabClass = 'active';
                        } else {
                            tabClass = '';
                        }
                        shortcode += '[tab id="' + tabs_id + TBhc + '" class="' + tabClass + '"]TAB HEAD ' + parseInt(TBhc + 1) + '[/tab]<br/>';
                        index++;
                    }
                    shortcode += '[/tabh]<br/>';

                    shortcode += '[tabc]<br/>';
                    flag = 0;
                    for (var Tr = 0; Tr < tabs_size; Tr++) {
                        if (flag == 0) {
                            tabCClass = 'active';
                        } else {
                            tabCClass = '';
                        }
                        shortcode += '[tabrow id="' + tabs_id + Tr + '" class="' + tabCClass + '"]TAB CONTENT' + parseInt(Tr + 1) + '[/tabrow]<br/>';
                        flag++;
                    }
                    shortcode += '[/tabc]<br/>';

                    shortcode += '[/tabs]<br/>';
                } else if (shortcode_name === 'toggles') {
                    shortcode = '<br/>[toggles id="' + toggle_id + '"]<br/>';

                    for (var TGhc = 0; TGhc < toggle_size; TGhc++) {
                        shortcode += '[togglegroup]<br/>';
                        shortcode += '[togglehead id="' + toggle_id + '" tab_id="' + toggle_id + TGhc + '"]Toggle Panel #' + parseInt(TGhc + 1) + '[/togglehead]<br/>';
                        shortcode += '[togglebody tab_id="' + toggle_id + TGhc + '"]Toggle Body #' + parseInt(TGhc + 1) + '[/togglebody]<br/>';
                        shortcode += '[/togglegroup]<br/>';
                    }

                    shortcode += '[/toggles]<br/>';
                } else if (shortcode_name === 'fullscreenvideo') {
                    var fwvideo_videourl = document.getElementById('fwvideo-videourl').value;
                    var fwvideo_autoplay = document.getElementById('fwvideo-autoplay').value;
                    var full_width = "";
                    shortcode = '[fullscreenvideo  videourl="' + fwvideo_videourl + '" autoplay="' + fwvideo_autoplay + '" fullwidth="' + full_width + '"]<br/>';
                }
                if (shortcode === '') {
                    attr += '[' + shortcode_name + '';
                    jQuery('.nativechurch-shortcode-fields').each(function() {
                        if (jQuery(this).is(":hidden")) {
                            return true;
                        }
                        var type = jQuery(this).attr('type');
                        type = (typeof type === 'undefined') ? jQuery(this).get(0).tagName : type;
                        attr += nativechurch_create_shortcode_atts(jQuery(this), type);
                    });
                    attr += ']';
                    shortcode = attr;
                }

                editor.execCommand('mceReplaceContent', false, shortcode);
                tb_remove();
                editor.commands.removeCommand('imicframework_shortcodes');
                return;
            });
        });

    });
})();
(function (window) {
    if (!window.jQuery) {
        console.log('jQuery undefined');
        return;
    }
    var jQuery = window.jQuery;
    jQuery(window.document).ready(function () {
        onFormSubmit();
        onUserIdChange();
        PublisherKey();
        // initjPicker();
		label_checks();
        //preview
        ResizeWindow();
    });
	
	function initjPicker()
    {
      jQuery('#colorSelector').ColorPicker({
			color: '#0000ff',
			onShow: function (colpkr) {
				jQuery(colpkr).fadeIn(500);
				return false;
			},
			onHide: function (colpkr) {
				jQuery(colpkr).fadeOut(500);
				return false;
			},
			onChange: function (hsb, hex, rgb) {
				jQuery('#colorSelector div').css('backgroundColor', '#' + hex);
				jQuery('#tm_bg_color').val('#' + hex);
			}
		});
    }
	
    function onFormSubmit() {
        jQuery('#sharexy_plugin_constructor_form').unbind('submit').submit(function () {
            var submitBtn = jQuery('#sharexy_plugin_constructor_form_submit_button'),
                    submitText = submitBtn.val(),
                    styleData = {"sel" : "sharexy_save_style_data"};
            jQuery('.sharexy_content input,select').each(function (n, element) {
                var name = jQuery(element).attr('name'), val = jQuery(element).attr("value");
                if (jQuery(element).attr('type') === 'radio') {
                    if (name && typeof val !== 'undefined' && jQuery(element).attr('checked')) {
                        if (jQuery(element).closest("table").parent().css("display") == "none") {
                            
                            styleData[name] = '0';
                        } else {
                            styleData[name] = val;
                        }

                    }
                } else {
                    if (name && jQuery(element).attr('type') === 'checkbox') {
                        if (jQuery(element).parent().css("background-position") == "0px 0px") {
                            styleData[name] = '0';
                        } else {
                            styleData[name] = '1';
                        }
                    }
                    else if (name && typeof val !== 'undefined') {
                        styleData[name] = val;
                    }
                }
            });
            
            submitBtn.attr('value', 'Please wait...');
            jQuery.post('admin.php?page=sharexy-widget-menu', styleData, function () {
                submitBtn.attr('value', 'Saved!');
                setTimeout(function () {
                    submitBtn.attr('value', submitText);
                }, 1000);
            });
            return false;
        });
    }

    function onUserIdChange() {
        var exp = new RegExp('^\\w{3}-\\d{5}$', "g");
        jQuery('#sharexy_plugin_constructor_form [name="user_id"]').unbind('change').change(function () {
            var userId = jQuery(this).val();
            jQuery('#fail_user_id').hide();
            if (userId) {

                if (exp.test(userId)) {
                    jQuery('#sharexy_ads_translation_settings').show();
                    jQuery('.ads_translation_settings').attr('checked', false);
                    jQuery('.ads_translation_settings').attr('value', '0');
                    jQuery('#translation_allways_show_ads').attr('checked', true);
                    jQuery('#translation_allways_show_ads').attr('value', '1');
                    jQuery('.div_show_ads_settings').show();
                } else {
                    jQuery('#fail_user_id').show();
                    jQuery('#sharexy_ads_translation_settings').hide();
                    jQuery('.div_show_ads_settings').hide();
                }
            } else {
                jQuery('#sharexy_ads_translation_settings').hide();
                jQuery('.div_show_ads_settings').hide();
            }
        });
    }
    function adsTranslationSettings() {
        jQuery('#sharexy_ads_translation_settings .ads_translation_settings').parent().click(function () {
            var isChk = false, el = jQuery(this).find("input");
            if (el.attr('name') === 'dont_show_ads') {
                if (el.attr('value') == '1') {
                    jQuery('#sharexy_ads_translation_settings .show_mode').attr('value', '0');
                    jQuery('#sharexy_ads_translation_settings .show_mode').attr('checked', false);
                    jQuery('.div_show_ads_settings').hide();
                } else {
                    jQuery('#translation_allways_show_ads').attr('checked', true);
                    jQuery('#translation_allways_show_ads').attr('value', '1');
                    jQuery('.div_show_ads_settings').show();
                }
            } else {
                jQuery('#translation_dont_show_ads').attr('checked', false);
                jQuery('#translation_dont_show_ads').attr('value', '0');
                if (el.attr('checked')) {
                    jQuery('.div_show_ads_settings').show();
                }
            }
            if (el.attr('value') == '1') {
                el.attr('value', '1');
            } else {
                el.attr('value', '0');
                jQuery('#sharexy_ads_translation_settings .show_mode').each(function (n, element) {
                    isChk = jQuery(element).attr('checked') ? true : isChk;
                });
                if (!isChk) {
                    jQuery('#translation_dont_show_ads').attr('checked', true);
                    jQuery('#translation_dont_show_ads').attr('value', '1');
                    jQuery('.div_show_ads_settings').hide();
                } else {
                    jQuery('.div_show_ads_settings').show();
                }
            }
            jQuery(".ads_translation_settings").each(function() {
                defineCheck(jQuery(this).parent());
            });
        });
    }
    function designSettings() {
        var popupObj = jQuery('#popup_design_settings');
        jQuery('#call_popup_designs,#design_preview').unbind('click').click(function () {
            var fuzz = jQuery("#sharexy_fuzz"), w = jQuery(window);
            fuzz.css("width", w.width()).css("height", w.height()).show();
            var top = jQuery(window).scrollTop() + jQuery(window).height() / 2 - jQuery("#popup_design_settings").height() / 2 - 10;
            var left = (jQuery(window).width() - parseFloat(jQuery("#popup_design_settings").width())) / 2 - 10;
            top = (top > 50) ? top : 50;
            left = (left > 50) ? left : 50;
            popupObj.css({"top": top,"left": left});
            popupObj.show();
            return false;
        });
        jQuery('.sharexy_design').unbind('click').click(function () {
            var name = jQuery(this).attr('id');
            if (name) {
                jQuery('#design_name').attr('value', name);
                jQuery('#design_name_preview').html(name);
                jQuery(".sharexy_settings_icon_hover").removeClass("sharexy_settings_icon_hover");
                jQuery(this).addClass("sharexy_settings_icon_hover");
                jQuery("#design_preview").css("background-image", jQuery(this).find(".sharexy_small_preview").css("background-image"));
                jQuery("img.sharexy_icon").each(function() {
                    if (jQuery(this).attr("src")) {
                        var src = jQuery(this).attr("src"), s = src.split('/');
                        if (s.length > 1) {
                            src = src.replace(s[s.length - 3] + '/' + s[s.length - 2] + '/' + s[s.length - 1], "");
                            src += name + '/' + s[s.length - 2] + '/' + s[s.length - 1];
                        }
                        jQuery(this).attr("src", src);
                    }
                });
            }
            popupObj.hide();
            jQuery("#sharexy_fuzz").hide();
        });
        jQuery("#wpwrap").unbind('click').click(function (e) {
            var el = e.target, popup = jQuery("#popup_design_settings");
            if (jQuery(el).attr("id") == "call_popup_designs") {
                return false;
            }
            var pop = popup.offset(), poph = popup.height() + 20, popw = popup.width();
            if ((e.pageX < pop.left) || (e.pageX > pop.left + popw) || (e.pageY < pop.top) || (e.pageY > pop.top + poph)) {
                if (popup.css("display") !== "none") {
                    popup.fadeOut("normal");
                    jQuery("#sharexy_fuzz").hide();
                }
            }
            jQuery(".popup_size").each(function() {
                var pop = jQuery(this).offset(), poph = jQuery(this).height() + 20, popw = jQuery(this).width(),el = e.target;
                if (jQuery(el).hasClass("sharexy_mask") || jQuery(el).parent().hasClass("sharexy_size")) {
                    return false;
                }
                if ((e.pageX < pop.left) || (e.pageX > pop.left + popw) || (e.pageY < pop.top - 100) || (e.pageY > pop.top + poph)) {
                    if (jQuery(this).css("display") !== "none") {
                        jQuery(this).fadeOut("normal");
                    }
                }
            });

        });
    }
    function placementsCheckboxes() {
        jQuery('.placements_checkboxes').parent().click(function () {
            var place, el = jQuery(this).find("input");
            place = el.attr('name').replace('placement_', '');
            if (el.attr('checked')) {
                el.attr('value', '1');
                jQuery('#' + place + '_place_settings_div').show();
                jQuery('.option_' + place).attr('disabled', false);
                if (place == 'float') {
                    jQuery('#preview_float_float').show();
                    initFloatWidgetPosition(jQuery(".sharexy_monitor_float td.hover").attr("name"));
                }
            } else {
                el.attr('value', '0');
                jQuery('#' + place + '_place_settings_div').hide();
                jQuery('.option_' + place).attr('disabled', true);
                if (place == 'float') {
                    jQuery('#preview_float_float').hide();
                }
            }
            jQuery('.option_default').attr('selected', true);
            jQuery(".select_copy_placement").each(function() {
                if (jQuery(this).find("option:disabled").size() == 3) {
                    jQuery(this).parent().hide();
                } else {
                    jQuery(this).parent().show();
                }
            });
            //preview change counters
            var size = jQuery("select[name=size_static_" + place + "]").attr("value");
            previewCountersVisible(place, size);

        });
        jQuery(".select_copy_placement").each(function() {
            if (jQuery(this).find("option:disabled").size() == 3) {
                jQuery(this).parent().hide();
            } else {
                jQuery(this).parent().show();
            }
        });
        PreviewChangeCounters();
    }

    function placementsSettings() {
        var placements = ['top', 'bottom', 'float', 'top_post','bottom_post'],
                i, count = placements.length;
        for (i = 0; i < count; i++) {
            servicesSettings(placements[i]);
            buzzSettings(placements[i]);
            sizeAndCountersSettings(placements[i]);
            backgroundColor(placements[i]);
            showAdsSettings(placements[i]);
            copySettings(placements[i]);
            pageModeSettings(placements[i]);
            initPreviewSettings(placements[i]);
        }

    }

    function pageModeSettings(place) {
        var frontObj = jQuery('#sharexy_plugin_constructor_form [name="pages_mode_front_' + place + '"]'),
                pageObj = jQuery('#sharexy_plugin_constructor_form [name="pages_mode_page_' + place + '"]');
        frontObj.unbind('change').change(function () {
            if (jQuery(this).attr('checked')) {
                jQuery(this).attr('value', 1);
            } else {
                if (!pageObj.attr('checked')) {
                    pageObj.attr('checked', true);
                    pageObj.attr('value', 1);
                }
                jQuery(this).attr('value', 0);
            }
        });
        pageObj.unbind('change').change(function () {
            if (jQuery(this).attr('checked')) {
                jQuery(this).attr('value', 1);
            } else {
                if (!frontObj.attr('checked')) {
                    frontObj.attr('checked', true);
                    frontObj.attr('value', 1);
                }
                jQuery(this).attr('value', 0);
            }
        });
    }

    function servicesSettings(place) {
        jQuery('.services_' + place).unbind('click').click(function () {
            var services = jQuery('#input_' + place).val(),
                    servicesList = services ? services.split(',') : [],
                    service = jQuery(this).attr('id').replace('td_' + place + '_', ''),
                    i, isSet = false,
                    classes = jQuery(this).attr('class');
            for (i = 0; i < servicesList.length; i++) {
                isSet = servicesList[i] === service ? true : isSet;
            }
            if (isSet) { //выключить
                jQuery(this).attr('class', classes.replace('includeService', 'unIncludeService'));
                jQuery('#li_' + place + '_' + service).remove();
                //float widget
                if (place == 'float') {
                    jQuery('#li_float_float_' + service).remove();
                }
                //hide retweet settings
                if (service == 'retweet') {
                    jQuery("#retweet_username_" + place).hide();
                }
                //save selected services (with savr order)
                jQuery('#boxes_' + place).mouseup();
            } else { //включить
                jQuery(this).attr('class', classes.replace('unIncludeService', 'includeService'));
                //preview
                var li = document.createElement("li");
                li.setAttribute("id", 'li_' + place + '_' + service);
                li.innerHTML = jQuery('#preview_all_services_' + place + ' #li_all_' + place + '_' + service).html();
                jQuery('#boxes_' + place).append(jQuery(li));
                jQuery('#li_' + place + '_' + service).css({"display": "inline-block","position":"relative"});
                junkdrawer.restoreListOrder("boxes_" + place);
                dragsort.makeListSortable(document.getElementById("boxes_" + place), function() {});
                //float preview
                if (place == "float") {
                    li = document.createElement("li");
                    li.setAttribute("id", 'li_' + place + '_' + place + '_' + service);
                    li.innerHTML = jQuery('#preview_all_services_' + place + ' #li_all_' + place + '_' + service).html();
                    jQuery('#boxes_float_float').append(jQuery(li));
                    jQuery('#li_' + place + '_' + place + '_' + service).css({"display": "inline-block","position":"relative"});

                }

                //show retweet settings
                if (service == 'retweet') {
                    jQuery("#retweet_username_" + place).show();
                }
                //float widget
                if (place == 'float') {
                    jQuery('#li_float_float_' + service).css("display", "inline-block");
                }
                //save selected services (with save order)
                jQuery('#boxes_' + place).mouseup();
            }
            PreviewChangeCounters();
            if (place == "float") {
                initFloatWidgetPosition(jQuery(".sharexy_monitor_float td.hover").attr("name"));
            }

        });

    }

    function boxesDragAndDrop() {
        //drag&drop save order
        jQuery('.boxes').unbind("mouseup").mouseup(function() {
            var services = "", place = jQuery(this).attr("id");
            place = place.replace("boxes_", "");

            if (place == 'float') {
                jQuery('#boxes_float_float').html("");
            }

            jQuery(this).closest("ul").find("li").each(function() {
                if (jQuery(this).css("display") !== "none") {
                    var s = jQuery(this).attr('id').replace('li_' + place + '_', '');
                    services += s + ',';
                    if (place == 'float') {
                        var li = document.createElement("li");
                        li.setAttribute("id", 'li_' + place + '_' + place + '_' + s);
                        li.innerHTML = jQuery('#preview_all_services_' + place + ' #li_all_' + place + '_' + s).html();

                        jQuery(li).css({"display": "inline-block","position":"relative"});
                        jQuery('#boxes_float_float').append(jQuery(li));
                    }
                }
            });
            if (services.length > 0) {
                services = services.substring(0, services.length - 1);
            }
            jQuery('#input_' + place).attr('value', services);
            var size = (place == 'float') ? jQuery("select[name=size_float_" + place + "]").attr("value") : jQuery("select[name=size_static_" + place + "]").attr("value");
            previewCountersVisible(place, size);
        });
    }



    function buzzSettings(place) {
        jQuery('.buzz_' + place).unbind('change').change(function () {
            if (jQuery(this).attr('checked')) {
                jQuery(this).attr('value', 1);
            } else {
                jQuery(this).attr('value', 0);
            }
        });
    }

    function sizeAndCountersSettings(place) {
        jQuery('select.size_float_' + place).change(function () {
            var el = jQuery(this);
            if (el.val() == '32' || el.val() == '55') {
                jQuery('#show_counters_div_' + place).show();
            } else {
                jQuery('#show_counters_div_' + place).hide();
            }
        });
        jQuery('.counters_float_' + place).parent().click(function () {
            var el = jQuery(this).find("input"), pos;
            pos = jQuery(".sharexy_monitor_float td.hover").attr("name");
            pos = (pos === 'l' || pos === 'r') ? 'v' : 'h';
            if (el.attr('checked')) {
                el.attr('value', 1);
                if (jQuery('.size_float_' + place).val() == '55') {
                    jQuery("#preview_float_"+ place +" .cnt").show();
                }
                else {
                    jQuery("#preview_float_"+ place +" .cnt").hide();
                }
                if (jQuery('.size_float_' + place).val() == '32') {
                    jQuery("#preview_float_float .sharexy_cnt").show();
                }
                initFloatWidgetSize(pos, jQuery(".sharexy_monitor_float .hover").attr("name"));


            } else {
                el.attr('value', 0);
                jQuery("#preview_float_"+ place +" .cnt").hide();
                jQuery('#' + place + "_place_settings_div .cnt").parent().css("width", "auto");
                jQuery('#' + place + "_place_settings_div .sharexy_icon").css("margin-top", '0px');

                initFloatWidgetSize(pos, jQuery(".sharexy_monitor_float .hover").attr("name"));
            }
            PreviewChangeCounters();
            initFloatWidgetPosition(pos);

        });

        jQuery('select.size_static_' + place).change(function () {
            var el = jQuery(this);
            if (el.val() != '16' && el.val() != '60') {
                jQuery('#show_counters_div_' + place).show();
            } else {
                jQuery('#show_counters_div_' + place).hide();
            }

            if (el.val() == '55') {
                if (jQuery('.counters_' + place).attr('checked'))
                    jQuery('#counter_position_div_' + place).show();
            } else {
                jQuery('#counter_position_div_' + place).hide();
            }
            jQuery(this).prev().find(".sharexy_size[name=\"" + el.val() + "\"]").click();

        });
        //radio counter position (over the button)
        jQuery("#counters_" + place + "_top").parent().click(function() {
            if (jQuery(this).find("input").attr("checked")) {
                jQuery('#' + place + "_place_settings_div .cnt").addClass("cnt_top");
            }
            PreviewChangeCounters();
        });
        //float counter
        jQuery("#counters_float_float").parent().click(function() {
            var pos = jQuery(".sharexy_monitor_float td.hover").attr("name");
            initFloatWidgetPosition(pos);
        });
        //radio counter position (to the right of the button)
        jQuery("#counters_" + place + "_right").parent().click(function() {
            if (jQuery(this).find("input").attr("checked")) {
                jQuery('#' + place + "_place_settings_div .cnt").removeClass("cnt_top");
            }
            PreviewChangeCounters();
        });
        //radio show/hide counters
        jQuery('.counters_' + place).parent().click(function () {

            var el = jQuery(this).find("input");
            if (el.attr('checked')) {
                el.attr('value', 1);
                //default counter position - right
                if (jQuery("#counters_" + place + "_top").attr("checked") !== true && jQuery("#counters_" + place + "_right").attr("checked") !== true) {
                    jQuery("#counters_" + place + "_right").attr("checked", true);
                    jQuery("#counters_" + place + "_right").parent().addClass("radioChecked");
                    jQuery("#counters_" + place + "_top").attr("checked", false);
                    jQuery("#counters_" + place + "_top").parent().removeClass("radioChecked");
                }
                //55
                if (jQuery('.size_static_' + place).val() == '55') {
                    jQuery('#counter_position_div_' + place).show();
                    jQuery('.radio_counters_default_' + place).attr('checked', true);
                    //counters preview
                    jQuery('#' + place + "_place_settings_div .cnt").show();
                    if (jQuery("#counters_" + place + "_top").attr("checked")) {
                        jQuery('#' + place + "_place_settings_div .cnt").addClass("cnt_top");
                    } else {
                        jQuery('#' + place + "_place_settings_div .cnt").removeClass("cnt_top");
                    }
                }
                //counters preview
                if (jQuery('.size_static_' + place).val() == '32' || jQuery('.size_static_' + place).val() == 'sh') {
                    jQuery('#' + place + "_place_settings_div .sharexy_cnt").css({"display": "inline-block"});
                }


            } else {
                el.attr('value', 0);
                if (jQuery('.size_static_' + place).val() == '55') {
                    jQuery('#counter_position_div_' + place).hide();
                    jQuery('.radio_counters_' + place).attr('checked', false);
                }
                //counters preview
                jQuery('#' + place + "_place_settings_div .cnt").hide();
                jQuery('#' + place + "_place_settings_div .cnt").parent().css("width", "auto");
                jQuery('#' + place + "_place_settings_div .sharexy_icon").css("margin-top", '0px');


            }
            //counters preview
            if (jQuery('.size_static_' + place).val() !== '55' && jQuery('.size_static_' + place).val() !== '32' && jQuery('.size_static_' + place).val() !== 'sh') {
                jQuery('#' + place + "_place_settings_div .cnt").hide();
            }

            PreviewChangeCounters();
        });
    }

    function backgroundColor(place) {
        jQuery('.bg_float_' + place).parent().click(function () {
            var el = jQuery(this).find("input");
            if (el.attr('checked')) {
                el.attr('value', 1);
                jQuery('#div_bg_color_' + place).show();
                jQuery("#preview_float_float").css("background-color", jQuery("#color").val());
            } else {
                jQuery(this).attr('value', 0);
                jQuery('#div_bg_color_' + place).hide();
                jQuery("#preview_float_float").css("background", 'none');
            }
        });
    }

    function showAdsSettings(place) {
        jQuery('.show_ads_' + place).unbind('change').change(function () {
            if (jQuery(this).attr('checked')) {
                jQuery(this).attr('value', 1);
            } else {
                jQuery(this).attr('value', 0);
            }
        });
    }

    function copySettings(place) {
        jQuery('#select_copy_placement_' + place).unbind('change').change(function () {
            var copyPlace = jQuery(this).val();
            if (copyPlace == 0) {
                return;
            }
            copyServices(place, copyPlace);
            copyPageMode(place, copyPlace);
            copyAlign(place, copyPlace);
            copyButtonSize(place, copyPlace);
            copyCounter(place, copyPlace);
            copyBuzz(place, copyPlace);
            copyShowAds(place, copyPlace);
            copyRetweet(place, copyPlace);
        });
    }



    function copyServices(place, copyPlace) {
        var copyServicesStr, copyServicesArr, i;
        copyServicesStr = jQuery('input#input_' + copyPlace).val();
        copyServicesArr = copyServicesStr.split(',');
        jQuery('.services_' + place).removeClass('includeService');
        jQuery('.services_' + place).addClass('unIncludeService');
        //preview
        jQuery('#boxes_' + place).html("");
        for (i = 0; i < copyServicesArr.length; i++) {
            jQuery('#td_' + place + '_' + copyServicesArr[i]).removeClass('unIncludeService');
            jQuery('#td_' + place + '_' + copyServicesArr[i]).addClass('includeService');
            //preview
            var li = document.createElement("li");
            li.setAttribute("id", 'li_' + place + '_' + copyServicesArr[i]);
            li.innerHTML = jQuery('#preview_all_services_' + place + ' #li_all_' + place + '_' + copyServicesArr[i]).html();
            jQuery('#boxes_' + place).append(jQuery(li));
            junkdrawer.restoreListOrder("boxes_" + place);
            dragsort.makeListSortable(document.getElementById("boxes_" + place), function() {});
        }
        jQuery("#preview_" + place + " .sharexy_icon").css("margin-top", "0px");
        jQuery("#preview_" + place + " .sharexy_icon").parent().css("width", "auto");
        jQuery('#sharexy_plugin_constructor_form [name="services_' + place + '"]').attr('value', copyServicesStr);
    }



    function copyPageMode(place, copyPlace) {
        var front_val, front_chk,
                page_val, page_chk;
        front_val = jQuery('input[name="pages_mode_front_' + copyPlace + '"]').val();
        front_chk = jQuery('input[name="pages_mode_front_' + copyPlace + '"]').attr('checked');
        jQuery('input[name="pages_mode_front_' + place + '"]').attr('value', front_val);
        defineCheck(jQuery('input[name="pages_mode_front_' + place + '"]').parent());
        jQuery('input[name="pages_mode_front_' + place + '"]').attr('checked', front_chk || false);
        defineCheck(jQuery('input[name="pages_mode_front_' + place + '"]').parent());

        page_val = jQuery('input[name="pages_mode_page_' + copyPlace + '"]').val();
        page_chk = jQuery('input[name="pages_mode_page_' + copyPlace + '"]').attr('checked');
        jQuery('input[name="pages_mode_page_' + place + '"]').attr('value', page_val);
        defineCheck(jQuery('input[name="pages_mode_page_' + place + '"]').parent());
        jQuery('input[name="pages_mode_page_' + place + '"]').attr('checked', page_chk || false);
        defineCheck(jQuery('input[name="pages_mode_page_' + place + '"]').parent());
        jQuery("span.niceCheck").each(function() {
        });
    }

    function copyAlign(place, copyPlace) {
        var align;
        align = jQuery('input[name="align_' + copyPlace + '"]:checked').attr("value");
        jQuery('[name="align_' + place + '"]:checked').parent().removeClass("radioChecked");
        jQuery('[name="align_' + place + '"]').each(function() {
            if (jQuery(this).attr("value") == align) {
                jQuery(this).parent().addClass("radioChecked");
                jQuery(this).attr("checked", true);
            }
        });

    }

    function copyButtonSize(place, copyPlace) {
        var size_static;
        size_static = jQuery('[name=size_static_' + copyPlace + ']').val();
        jQuery('[name="size_static_' + place + '"]').attr('value', size_static);
        jQuery('[name="size_static_' + place + '"]').change();
        jQuery('#boxes_' + place).mouseup();
    }

    function copyCounter(place, copyPlace) {
        var counters_static_val,  counters = false, size;
        //checkbox
        if (jQuery("#counters_" + copyPlace).attr("value") == '1') {
            jQuery("#counters_" + place).attr("checked", true);
            jQuery("#counters_" + place).attr("value", "1");
        } else {
            jQuery("#counters_" + place).attr("checked", false);
            jQuery("#counters_" + place).attr("value", "0");
        }
        defineCheck(jQuery("#counters_" + place).parent());
        //block with counter position
        jQuery("#counter_position_div_" + place).css("display", jQuery("#counter_position_div_" + copyPlace).css("display"));
        //radio buttons
        counters_static_val = jQuery('input[name="counters_static_' + copyPlace + '"]').val();

        jQuery('.radio_counters_' + copyPlace).parent().removeClass("radioChecked");
        jQuery('.radio_counters_' + copyPlace).each(function (n, element) {
            if (jQuery(this).attr("value") == counters_static_val) {
                jQuery(this).attr("checked", true);
                jQuery(this).parent().addClass("radioChecked");
            } else {
                jQuery(this).parent().removeClass("radioChecked");
                jQuery(this).attr("checked", false);
            }
        });
        counters_static_val = '';
        jQuery('input[name="counters_' + copyPlace + '"]').each(function() {
            if (jQuery(this).parent().hasClass("radioChecked")) {
                counters_static_val = jQuery(this).val();
            }

        });
        jQuery('input[name="counters_' + place + '"]').each(function (n, element) {
            if (jQuery(element).val() === counters_static_val) {
                jQuery(element).attr('checked', true);
                jQuery(element).parent().addClass("radioChecked");
            }
            else {
                jQuery(element).attr('checked', false);
                jQuery(element).parent().removeClass("radioChecked");

            }
        });


        //radio counter position (over the button)
        if (counters_static_val == 't' || size == '32') {
            jQuery('#preview_' + place + " .cnt").addClass("cnt_top");
            jQuery('#preview_' + place + " .sharexy_cnt").addClass("cnt_top");
        } else {
            jQuery('#preview_' + place + " .cnt").removeClass("cnt_top");
            jQuery('#preview_' + place + " .sharexy_cnt").removeClass("cnt_top");
        }
        //preview change counters
        size = jQuery("select[name=size_static_" + place + "]").attr("value");
        previewCountersVisible(place, size);

    }



    function copyBuzz(place, copyPlace) {
        var buzz_val, buzz_chk;
        buzz_val = jQuery('[name="buzz_' + copyPlace + '"]').attr("value");
        buzz_chk = jQuery('[name="buzz_' + copyPlace + '"]').attr('checked') ? true : false;
        jQuery('[name="buzz_' + place + '"]').attr('value', buzz_val);
        jQuery('[name="buzz_' + place + '"]').attr('checked', buzz_chk || false);
        defineCheck(jQuery('[name="buzz_' + place + '"]').parent());

    }



    function copyShowAds(place, copyPlace) {
        var show_ads_val, show_ads_chk;
        show_ads_val = jQuery('[name="show_ads_' + copyPlace + '"]').val();
        show_ads_chk = jQuery('[name="show_ads_' + copyPlace + '"]').attr('checked') ? true : false;
        jQuery('[name="show_ads_' + place + '"]').attr('value', show_ads_val);
        jQuery('[name="show_ads_' + place + '"]').attr('checked', show_ads_chk || false);
        defineCheck(jQuery('[name="show_ads_' + place + '"]').parent());

    }



    function copyRetweet(place, copyPlace) {
        jQuery("#retweet_username_" + place).css("display", jQuery("#retweet_username_" + copyPlace).css("display"));
        jQuery("#retweet_username_" + place + " .username").attr("value", jQuery("#retweet_username_" + copyPlace + " .username").val());
    }



    function tipsy() {
        jQuery('.tips').tipsy({gravity: 'sw'});
    }



    function PreviewChoosePosition() {
        jQuery('.sharexy_monitor table td,.sharexy_monitor_float table td').click(function() {
            if (jQuery(this).hasClass("no_hover")) {
                return false;
            }
            var t = jQuery(this).closest("table"), el = jQuery(this), sel;
            sel = jQuery("select[name='mode_float_float']");
            t.find("td").removeClass("hover");
            t.find("td").css("background-image", "none");
            el.addClass("hover");
            el.css("background-image", "url(" + jQuery('#SharexyImgPath').val() + "ok.png)");
            sel.attr("value", el.attr("name"));
            initFloatWidgetPosition(el.attr("name"));
            //sel.change();
        });
    }



    function PreviewChooseSize() {
        jQuery(".sharexy_size").click(function() {
            var el = jQuery(this);
            el.closest("table").find(".sharexy_size").removeClass("sharexy_size_hover");
            el.addClass("sharexy_size_hover");
            el.closest("table").next().attr("value", el.attr("name"));
            el.closest("table").next().change();
        });
    }



    function initFarbtastic() {
        var picker = jQuery('#picker'), color = jQuery("#color");
        picker.farbtastic('#color');

        color.click(function() {
            var o = color.position(), w = color.width(), h = color.height(), pw = picker.width();
            picker.css("top", o.top - 198).css("left", o.left + 2).toggle("normal");

        });
        jQuery("#wpwrap").click(function(e) {
            if (e.target.id == 'color') return false;
            var po = picker.offset(), pw = picker.height(), ph = picker.width();
            if ((e.pageX < po.left) || (e.pageX > po.left + pw) || (e.pageY < po.top) || (e.pageY > po.top + ph)) {
                if (picker.css("display") !== "none") {
                    picker.hide("normal");

                }
            }
        });
    }


    function initNiceRadio() {
        jQuery("input.niceRadio").each(function() {
            changeRadioStart(jQuery(this));
        });
    }


    function initFloatWidgetSize(orient, pos) {
        PreviewChangeCounters();
        var el = jQuery("#preview_float_float"), icon_w, icon_h, icon_count, add_w = 0;
        var size = jQuery("select[name='size_float_float']").attr("value");


        icon_w = 32;
        icon_h = 32;
        icon_count = parseFloat(el.find(".sharexy_icon").size());
        if (size == 55) {
            icon_w = 60;
            icon_h = 20;
        }
        if (size == 60) {
            icon_w = 60;
            icon_h = 60;
        }
        if (size == '32' && jQuery("#counters_float_float").attr("value") == 1) {

            var el1 = jQuery("#preview_float_float").find(".sharexy_cnt").parent();
            if (pos=='tl' || pos == 'bl')  { el1.css("width","67px");} else { el1.css("width","auto"); }
            
            if (pos == 'tl' || pos == "tr" || pos == "br" || pos == "bl") {
                add_w += 40;
            }
        }
        if (size == '55' && jQuery("#counters_float_float").attr("value") == 1) {
            if (pos == "tl" || pos == "tr" || pos == "br" || pos == "bl") {
                add_w += 65 * icon_count;
            }
        }
        if (orient == 'h') {
            el.css("width", (icon_w + 6) * icon_count + add_w);
            el.css("height", icon_h + 4);
        }
        else {
            el.css("width", icon_w + 4);
            el.css("height", "auto");
        }
    }


    function initFloatWidgetPosition(pos) {
        var fl_widg, t, l;
        fl_widg = document.getElementById("preview_float_float");
        fl_widg.style.position = "fixed";
        fl_widg.style.zIndex = "100000";
        fl_widg.style.display = "block";
        jQuery(fl_widg).find(".cnt").removeClass("cnt_top");
        jQuery(fl_widg).find(".sharexy_cnt").parent().css({"float":"none","display": "inline-block"});
        jQuery(fl_widg).find(".boxes").css("float", "left");
        if (pos == "l") {
            jQuery(fl_widg).find(".cnt").addClass("cnt_top");
            initFloatWidgetSize('v', pos);
            t = jQuery(window).height() / 2 - fl_widg.offsetHeight / 2;
            l = 0;
            jQuery(fl_widg).find(".shr_label").show();
        }
        if (pos == "tl") {
            initFloatWidgetSize('h', pos);
            l = 50;
            t = 0;
            jQuery(fl_widg).find(".sharexy_cnt").parent().css({"float":"left"}); //zerkalirovanie sharexy icon
            jQuery(fl_widg).find(".boxes").css("float", "right");
            jQuery(fl_widg).find(".boxes li").css("float", "left");
            jQuery(fl_widg).find(".shr_label").hide();

        }
        if (pos == "tr") {
            initFloatWidgetSize('h', pos);
            l = jQuery(window).width() - fl_widg.offsetWidth - 50;
            t = 0;
            jQuery(fl_widg).find(".boxes li").css("float", "left");
            jQuery(fl_widg).find(".shr_label").hide();
            jQuery(fl_widg).find(".sharexy_cnt").parent().css({"float":"left","display": "block","width":"auto"});

        }
        if (pos == "bl") {
            initFloatWidgetSize('h', pos);
            l = 50;
            t = jQuery(window).height() - fl_widg.offsetHeight;
            jQuery(fl_widg).find(".sharexy_cnt").parent().css({"float":"left"});
            jQuery(fl_widg).find(".boxes").css("float", "right");
            jQuery(fl_widg).find(".boxes li").css("float", "left");
            jQuery(fl_widg).find(".shr_label").hide();
        }
        if (pos == "br") {
            initFloatWidgetSize('h', pos);
            l = jQuery(window).width() - fl_widg.offsetWidth - 50;
            t = jQuery(window).height() - fl_widg.offsetHeight;
            jQuery(fl_widg).find(".boxes li").css("float", "left");
            jQuery(fl_widg).find(".shr_label").hide();
            jQuery(fl_widg).find(".sharexy_cnt").parent().css({"float":"left","display": "block","width":"auto"});
        }
        if (pos == "r") {
            jQuery(fl_widg).find(".cnt").addClass("cnt_top");
            initFloatWidgetSize('v', pos);
            t = jQuery(window).height() / 2 - fl_widg.offsetHeight / 2;
            l = jQuery(window).width() - fl_widg.offsetWidth - 2;
            jQuery(fl_widg).find(".shr_label").show();

        }
        fl_widg.style.left = l + "px";
        fl_widg.style.top = t + "px";
        if (pos == "tr" || pos == "br") {
            fl_widg.style.left = "auto";
            fl_widg.style.right = "50px";
        }

    }

    function changeSizePreview(size, obj) {
        jQuery(obj).find("img").each(function() {
            var src, s, el = jQuery(this);

            if (jQuery(this).attr("src")) {
                src = jQuery(this).attr("src");
                s = src.split('/');
                if (s.length > 0) {
                    src = src.replace(s[s.length - 1], "");
                    src += size + '.png';
                }
            } else {
                    src = jQuery("#ScriptPathImg").val() + jQuery("#design_name").val() + '/' + jQuery(this).attr("name") + '/' + size + '.png'
            }
            if (size == '55' || size == 'sh') {
                if ((jQuery(this).attr("name") == "sharexy" && size == 'sh') || size == '55'){ el.attr("src", src);}
                el.css("height", 20);
                el.css("width", 60);
            } else {
                el.attr("src", src);
                el.css("width", size);
                el.parent().find(".cnt").removeClass("cnt_top");
                el.parent().css("width", "auto");
                el.css("height", size);
            }

        });
    }
    function PreviewChangeCounters() {
        
        var placements = ['top', 'bottom','top_post','bottom_post'], i, count = placements.length;
        for (i = 0; i < count; i++) {
            if (jQuery('.size_static_' + placements[i]).val() == '55') {
                //counters preview
                if (jQuery("#counters_" + placements[i] + "_top").parent().hasClass("radioChecked")) {
                    jQuery('#' + placements[i] + "_place_settings_div .cnt").addClass("cnt_top");
                } else {
                    jQuery('#' + placements[i] + "_place_settings_div .cnt").removeClass("cnt_top");
                }
            }
            if (jQuery('.size_static_' + placements[i]).val() == 'sh' || jQuery('.size_static_' + placements[i]).val() == '32') { jQuery('#' + placements[i] + "_place_settings_div .cnt").removeClass("cnt_top"); }
        }
        jQuery(".cnt").each(function() {

            if (jQuery(this).css("display") !=="none") {
                var el = jQuery(this), icon = el.next(), h, w;
                h = icon.height();
                w = icon.width();
                el.parent().css("width", "auto");
                el.css({'height': h + 'px', 'width': w + 'px', 'line-height': h + 'px','display':'inline-block', 'top':'0px','left':'0px'});
                icon.css({'margin-top':'0px'});

                if (jQuery(this).hasClass("cnt_top")) {
                    if (h == '32') {
                        el.css({"background-image": "url('" + jQuery("#ScriptPathImg").val() + jQuery("#design_name").val() + "/counter_top.png')"});
                    }
                    if (h == '20') {
                        el.css({'height': 35, 'width': w, 'display':'block','line-height':30 + 'px'});
                        el.css({"background-image": "url('" + jQuery("#ScriptPathImg").val() + jQuery("#design_name").val() + "/counter_top2.png')"});
                    }
                    el.parent().css({"width": w + "px"});
                    el.css({'margin': '2px 0px'});
                }
                else {
                    el.css({'display':'block', 'position':'relative','left':w + 'px'});

                    if (h == '32') {
                        icon.css({'margin-top':-h + 'px'});
                        el.css({"background-image": "url('" + jQuery("#ScriptPathImg").val() + jQuery("#design_name").val() + "/counter_right.png')"});
                    }
                    if (h == '20') {
                        icon.css({'margin-top':-h + 'px'});
                        el.parent().css({'width': 124 + 'px'});
                        el.css({"background-image": "url('" + jQuery("#ScriptPathImg").val() + jQuery("#design_name").val() + "/counter_right2.png')"});
                    }

                    el.css({'margin': '0px 0px 0px 4px'});
                }
                //orient float
                var orient = jQuery(".sharexy_monitor_float .hover").attr("name");
                if (orient !== 'l' && orient !== 'r' && h == '32') {
                    jQuery("#preview_float_float ul .sharexy_icon").css("margin-top", '0');
                }
            }
        });
        PreviewSharexyIconPosition();

    }
    function PreviewSharexyIconPosition() {
        jQuery(".boxes").next().each(function() {
            if (jQuery(this).prev().attr("id") == "boxes_float_float") {
                return false;
            }
            var li = jQuery(this).closest("table").find("ul li:last:visible"), o = li.position();

            if (o !== null && o !== undefined && li.css("display") !== "none") {
                jQuery(this).css({"position":"absolute","z-index":"100","left":o.left + li.width(),"top":o.top});
            } else {
                jQuery(this).css({"position":"static"});
            }
        });
    }
    function previewCountersVisible(place, size) {
        //services counters
        jQuery("#preview_" + place + " .boxes .cnt").parent().css("width", "auto");
        if (size == '55') {
            if (jQuery("#counters_" + place).attr("checked")) {
                jQuery("#preview_" + place + " .boxes .cnt").show();
            } else {
                jQuery("#preview_" + place + " .boxes .cnt").hide();
            }
        } else {
            jQuery("#preview_" + place + " .boxes .cnt").hide();
        }
        //sharexy counter
        if (size == '32' || size == 'sh') {
            jQuery("#preview_" + place).find(".sharexy_icon").css("margin-top", "0px");
        }
        if (size == '32' || size == '55' || size == 'sh') {
            if (jQuery("#counters_" + place).attr("checked")) {
                jQuery("#preview_" + place + " .sharexy_cnt").show();
            } else {
                jQuery("#preview_" + place + " .sharexy_cnt").hide();
            }
        } else {
            jQuery("#preview_" + place).find(".sharexy_cnt").hide();
            jQuery("#preview_" + place).find(".sharexy_icon").css("margin-top", "0px");
        }
        //float preview
        if (place === 'float') {
            if (size == '55') {
                if (jQuery("#counters_float_" + place).attr("checked")) {
                    jQuery("#preview_float_"+ place +" .cnt").show();
                } else {
                    jQuery("#preview_float_"+ place +" .cnt").hide();
                }
            } else {
                jQuery("#preview_float_"+ place +" .cnt").hide();
            }
            //sharexy counter
            if (size == '32' || size == '55' || size == 'sh') {
                if (jQuery("#counters_float_" + place).attr("checked")) {
                    jQuery("#preview_float_float .sharexy_cnt").show();
                } else {
                    jQuery("#preview_float_float .sharexy_cnt").hide();
                }
            } else {
                jQuery("#preview_float_float .sharexy_cnt").hide();
            }

        }
        PreviewChangeCounters();
    }
    function initPreviewSettings(place) {
        //try buzz
        jQuery("#try_buzz_" + place).click(function() {
            new buzz("preview_" + place, 'h', window);
        });
        //remove_selection
        jQuery("#remove_selection_" + place).click(function() {
            var elems = jQuery(".services_" + place);
            elems.each(function() {
                var classes = jQuery(this).attr('class');
                jQuery(this).attr('class', classes.replace('includeService', 'unIncludeService'));

            });
            jQuery("#boxes_" + place + " li").remove();
            jQuery("#boxes_" + place + "_float li").remove();
            jQuery("#input_" + place).attr("value", "");
            PreviewSharexyIconPosition();
            if (place == "float") {
                var pos = jQuery(".sharexy_monitor_float td.hover").attr("name");
                pos = (pos === 'l' || pos === 'r') ? 'v' : 'h';
                initFloatWidgetSize(pos, jQuery(".sharexy_monitor_float .hover").attr("name"));
            }
        });
        //size
        jQuery("#size_" + place + " td:first").click(function() {
            jQuery("#popup_size_" + place).fadeIn("normal");
        });
        //change size
        jQuery("#popup_size_" + place + " .sharexy_size").click(function() {
            var place = jQuery(this).closest("table").parent().attr("id"), size = jQuery(this).attr("name");
            place = place.replace("popup_size_", "");
            jQuery("#popup_size_" + place).hide();
            changeSizePreview(size, jQuery("#preview_" + place));
            //html
            jQuery("#size_" + place).find(".sharexy_size").html(jQuery(this).html());
            //select value
            if (place == 'float') {
                jQuery(".size_float_" + place).attr("value", size);
            } else {
                jQuery(".size_static_" + place).attr("value", size);
            }
            //float preview
            if (place === 'float') {
                changeSizePreview(size, jQuery("#preview_float_float"));
                initFloatWidgetPosition(jQuery(".sharexy_monitor_float td.hover").attr("name"));
            }
            //default counter position - right
            if (jQuery("#counters_" + place + "_top").attr("checked") !== true && jQuery("#counters_" + place + "_right").attr("checked") !== true) {
                jQuery("#counters_" + place + "_right").attr("checked", true);
                jQuery("#counters_" + place + "_right").parent().addClass("radioChecked");
                //fix bag
                jQuery("#counters_" + place + "_top").attr("checked", false);
                jQuery("#counters_" + place + "_top").parent().removeClass("radioChecked");
            }

            if (size == 'sh') { //only button sharexy
                jQuery("#boxes_" + place).hide();
                jQuery(".services_container_" + place).hide();
            } else {
                jQuery("#preview_" + place + " .sharexy_icon").css("margin-top", "0px");
                jQuery(".services_container_" + place).show();
                //show services
                jQuery("#boxes_" + place).css("display", "inline-block");
            }
            previewCountersVisible(place, size);
            //settings
            if (size != '16' && size != '60') {
                jQuery('#show_counters_div_' + place).show();
            } else {
                jQuery('#show_counters_div_' + place).hide();
            }

            if (size == '55') {
                jQuery('#show_counters_div_' + place).find("label:first").text("Show counters");
                if (jQuery('.counters_' + place).attr('checked'))
                    jQuery('#counter_position_div_' + place).show();
            } else {
                jQuery('#counter_position_div_' + place).hide();
                jQuery('#show_counters_div_' + place).find("label:first").text("Show counter");
            }
        });
        jQuery("#retweet_username_" + place + " .username").blur(function() {
            var val = jQuery(this).attr("value");
            val = val.replace("@", "");
            jQuery(this).attr("value", val);
        });
        if (place == 'float') {
            if (jQuery("#placement_float").attr("checked")) {
                initFloatWidgetPosition(jQuery(".sharexy_monitor_float td.hover").attr("name"));
            }
            //monitor (float widget position)
            jQuery(".sharexy_monitor_float td").click(function() {
                initFloatWidgetPosition(jQuery(this).attr("name"));

            });
            //select color colorpicker
            jQuery(".sl-marker,.h-marker").live("mousemove", function() {
                jQuery("#preview_float_float").css("background-color", jQuery("#color").css("color"));
            });

        }
    }
    function ResizeWindow() {
        if (jQuery("#placement_float").attr("value") == "1") {
            initFloatWidgetPosition(jQuery(".sharexy_monitor_float td.hover").attr("name"));
        }
        var fuzz = jQuery("#sharexy_fuzz"), w = jQuery(window);
        if (fuzz.css("display") !== "none") {
            fuzz.css("width", w.width()).css("height", w.height());
        }

    }
    function PublisherKey() {
        jQuery("#publisher_key").unbind("hover").hover(function() {
            jQuery("#publisher_key_img").stop(true, true).fadeIn("normal");
        }, function() {
            jQuery("#publisher_key_img").stop(true, true).fadeOut("normal");
        });
        jQuery(".boxes").next().each(function() {
            jQuery(this).css("position", "relative");
        });
    }
	
	function label_checks(){
		jQuery('label').click(function(){
			var label_id = jQuery(this).attr('for');
			var la = jQuery('#'+label_id);
			if(la.attr('checked')) {
			  la.removeAttr('checked');
			} else {
			  la.attr('checked', 'checked');	
			}
		});
	}
	
})(window);

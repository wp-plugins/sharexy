(function (window) {
    if (!window.jQuery) {
        console.log('jQuery undefined');
        return;
    }
    var jQuery = window.jQuery;
    var customFiles;
    jQuery(window.document).ready(function () {
        initShortens();
        initCustomDesign();
        initJumbo();
        initNiceRadio();
        onFormSubmit();
        onUserIdChange();
        adsTranslationSettings();
        designSettings();
        placementsCheckboxes();
        placementsSettings();
        //tipsy
        tipsy();
        PublisherKey();
        //colorpicker
        //initFarbtastic();
        initSpectrum();
        //preview
        PreviewChoosePosition();
        PreviewChooseSize();
        ResizeWindow();
        boxesDragAndDrop();
        dragsort.makeListSortable(document.getElementById("boxes_shortcode"), function() {});
    });
    function countersParser(counter) {
        var returnedVal = '';
        counter = parseInt(counter);
        if (counter > 99999) {
            returnedVal = (Math.floor(counter/1000))+"K";
        } else if (counter > 1000) { 
            if (counter%1000 < 100) {
                returnedVal = Math.floor(counter/1000) + "K";    
            } else {
                returnedVal = (counter/1000).toFixed(1) + "K";
            }
        } else {
            returnedVal = counter;
        }
        return returnedVal;
    }
    function editPopupChangeResolution() {
        var resBtn = jQuery(this)
        var res    = resBtn.data('resolution');
        jQuery(".popup_design_edit_tabs li.selected").removeClass("selected");
        resBtn.addClass("selected");
        var items = jQuery('.popup_design_edit_item').each(function(index, item) {
            var folder = "url(" + jQuery('#LocalPathImg').val()+ 'design/custom/' +jQuery(this).data('folder')+"/"+res+".png)";
            jQuery(this).css("background-image", folder);
        });
    }

    function sendNewImage(event) {        
        var form = jQuery(this).parent();        
        customFiles = event.target.files;
        form.submit();
    }

    function submitFile(e) {
        e.stopPropagation();
        e.preventDefault();
        //if (!customFile || !customFile.type.match(/image.*/)) return;
        
        var formData = new FormData(this);        
        var folder     = jQuery(this).data('folder');
        var resolution = jQuery(".tab.selected").data('resolution');
        var imgDiv     = jQuery('.popup_design_edit_item[data-folder="'+folder+'"]');
        formData.append('files' , customFiles);
        formData.append('folder', folder);
        formData.append('resolution', resolution);
        formData.append('request_type', 'customdesign');
        imgDiv.css('background-image',"url(" + jQuery('#LocalPathImg').val()+ "img/loader.gif)");

        jQuery.ajax({
            type:'POST',
            url: jQuery(this).attr('action'),
            data:formData,
            cache:false,
            contentType: false,
            processData: false,
            success:function(data){
                imgDiv.css('background-image',"url(" + jQuery('#LocalPathImg').val()+ 'design/custom/' +folder+"/"+resolution+".png)");
                jQuery("img.sharexy_icon").filter("[name="+folder+"]").each(function() {
                    var src = jQuery(this).attr('src');
                    jQuery(this).attr('src', '');
                    jQuery(this).attr('src', src);
                })
            },
            error: function(data){
                console.log("error");
            }
        });               
    }

    function onFormSubmit() {
        jQuery('#sharexy_plugin_constructor_form').unbind('submit').submit(function () {
            var submitBtn = jQuery('#sharexy_plugin_constructor_form_submit_button'),
                    submitText = submitBtn.val(),
                    styleData = {"sel" : "sharexy_save_style_data"};
            jQuery('.sharexy_content input,select, textarea').each(function (n, element) {
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
            jQuery.post('admin.php?page=sharexy-menu', styleData, function () {
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
    function showPopupDialog(dialogId) {
        var fuzz = jQuery("#sharexy_fuzz"), w = jQuery(window);
        fuzz.css("width", w.width()).css("height", w.height()).show();
        var top = jQuery(window).scrollTop() + jQuery(window).height() / 2 - jQuery(dialogId).height() / 2 - 10;
        var left = (jQuery(window).width() - parseFloat(jQuery(dialogId).width())) / 2 - 10;
        top = (top > 50) ? top : 50;
        left = (left > 50) ? left : 50;
        jQuery(dialogId).css({"top": top,"left": left});
        jQuery(dialogId).show();        
    }
    function designSettings() {
        var popupObj  = jQuery('#popup_design_settings');
        var editPopup = jQuery('#popup_design_edit');
        jQuery('#call_popup_designs,#design_preview').unbind('click').click(function () {
            showPopupDialog('#popup_design_settings');
            return false;
        });
        jQuery('#call_edit_design').unbind('click').click(function () {
            showPopupDialog('#popup_design_edit');
            return false;
        });        
        jQuery('.sharexy_design').unbind('click').click(function () {
            var name      = jQuery(this).attr('id');
            var designUrl = jQuery(this).data('url');
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
                            src = designUrl + '/design/' + name + '/' + s[s.length - 2] + '/' + s[s.length - 1];
                        }
                        jQuery(this).attr("src", src);
                        if (name === 'custom') {
                            jQuery('#call_edit_design').show();
                        } else {
                            jQuery('#call_edit_design').hide();
                        }
                    }
                });                
            }
            popupObj.hide();
            jQuery("#sharexy_fuzz").hide();
        });
        jQuery("#wpwrap").unbind('click').click(function (e) {            
            var el = e.target, popup = jQuery("#popup_design_settings");
            if (popup.css("display") === "none") {
                popup = jQuery("#popup_design_edit");
            }
            if (jQuery(el).attr("id") == "call_popup_designs") {
                return false;
            }
            var clTop = popup.offset().top + 35, clLeft = popup.offset().left + popup.width() - 35, popupRight = popup.offset().left + popup.width();
            var pop = popup.offset(), poph = popup.height() + 20, popw = popup.width();
            if ((e.pageX < pop.left) || (e.pageX > pop.left + popw) || (e.pageY < pop.top) || (e.pageY > pop.top + poph) || (e.pageY > pop.top && e.pageY < clTop && e.pageX > clLeft && e.pageX < popupRight)) {
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
        var placements = ['top', 'bottom', 'float', 'top_post','bottom_post', 'shortcode'],
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
            initCountersSettings(placements[i]);
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
            if (place == "float") {
                initFloatWidgetPosition(jQuery(".sharexy_monitor_float td.hover").attr("name"));
            }
            changeCountersTypes(place);

        });

    }

    function boxesDragAndDrop() {
        //drag&drop save order
        jQuery('.boxes').unbind("mouseup").mouseup(function() {
            var services = "", place = jQuery(this).attr("id");            
            place = place.replace("boxes_", "");
            var size = (place == 'float') ? jQuery("select[name=size_float_" + place + "]").attr("value") : jQuery("select[name=size_static_" + place + "]").attr("value");

            if (place == 'float') {
                jQuery('#boxes_float_float').html("");
            }

            jQuery(this).closest("ul").find("li").each(function() {
                if (jQuery(this).css("display") !== "none") {
                    var s = jQuery(this).attr('id').replace('li_' + place + '_', '');
                    services += s + ',';
                    if (place === 'float') {
                        var li = document.createElement("li");
                        li.setAttribute("id", 'li_' + place + '_' + place + '_' + s);
                        li.innerHTML = jQuery('#preview_all_services_' + place + ' #li_all_' + place + '_' + s).html();

                        jQuery(li).css({"display": "inline-block","position":"relative"});
                        jQuery('#boxes_float_float').append(jQuery(li));

                        var align = jQuery("[name=counters_align_"+place+"]:checked").attr("value");
                        jQuery("#preview_float_"+place).find(".jumbo_counter").remove();
                        jQuery("#boxes_float_"+place).find('li div').each(function() {
                            jQuery(this).find(".top_counter, .right_counter, .bundle_counter, .jumbo_counter").remove();
                            createCounterInfo(jQuery(this), align, size);
                        });            
                        jQuery("#preview_float_"+place).find("#shrxbtn").each(function() {
                            jQuery(this).find(".top_counter, .right_counter, .bundle_counter, .jumbo_counter").remove();
                            createCounterInfo(jQuery(this), align, size);
                        }); 
                                           
                        initFloatWidgetPosition(jQuery(".sharexy_monitor_float td.hover").attr("name"));
                    }
                }
            });
            if (services.length > 0) {
                services = services.substring(0, services.length - 1);
            }
            jQuery('#input_' + place).attr('value', services);            
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

            jQuery("#boxes_float").trigger("mouseup");
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



    /*function initFarbtastic() {
        var picker = jQuery('#picker'), picker2 = jQuery('#picker2'), color = jQuery("#color"), label_color = jQuery("#label_color");
        picker.farbtastic('#color');
        picker2.farbtastic('#label_color').hide();

        jQuery('#color').click(function() {
            var o = jQuery(this).position(), w = jQuery(this).width(), h = jQuery(this).height(), pw = picker.width();
            picker.css("top", o.top - 198).css("left", o.left + 2).toggle("normal");
        });
        jQuery('#label_color').click(function() {            
            var o = jQuery(this).position(), w = jQuery(this).width(), h = jQuery(this).height(), pw = picker2.width();
            jQuery('#picker2').css("position", "absolute").css("z-index", "99999");
            jQuery('#picker2').css("top", o.top - 198).css("left", o.left + 2).toggle("normal");
        });
        jQuery("#wpwrap").click(function(e) {
            var curPic = (picker.css("display") !== "none")?picker:picker2;
            if (e.target.id == 'color' || e.target.id == 'label_color') return false;
            var po = curPic.offset(), pw = curPic.height(), ph = curPic.width();
            if ((e.pageX < po.left) || (e.pageX > po.left + pw) || (e.pageY < po.top) || (e.pageY > po.top + ph)) {
                if (curPic.css("display") !== "none") {
                    curPic.hide("normal");
                }
            }
        });       
    }*/

    function initSpectrum() {
        jQuery(".color_picker").spectrum({
            preferredFormat: "hex",
            showInput: true,
            showPalette: false
        });
        
        jQuery('#color').change(function() {
            var pikedColor = jQuery(this).val();
            jQuery("#preview_float_float").css("background-color", pikedColor);
        });
        jQuery('#label_color').change(function() {            
            var pikedColor = jQuery(this).val();
            jQuery("#preview_float_float .shr_label").css("color", pikedColor);
        });        
    }


    function initNiceRadio() {
        jQuery("input.niceRadio").each(function() {
            changeRadioStart(jQuery(this));
        });
    }

    function initCustomDesign() {
        var tabs = jQuery(".popup_design_edit_tabs li.tab");
        tabs.on('click', editPopupChangeResolution);
        tabs.trigger('click');
        jQuery('.custom_design_upload').on('change', sendNewImage);
        jQuery('.custom_design_upload_form').on('submit', submitFile);
        jQuery('div.popup_design_edit_item').on('click', function(e) {
            var folder = jQuery(this).data('folder');
            var item   = jQuery('input#upload_file');
            item.parent().data('folder', folder);
            item.trigger('click');
        });
        jQuery('input[name=title_text]').on('change', function() {
            var ttl = jQuery(this).val();
            jQuery(".shr_label").html(ttl);
        });
        jQuery("span.shr_label").css("color", jQuery("#label_color").val());
    }

    function initShortens() {
        if (!jQuery("#shorten_links").attr("checked")) {
            jQuery(".bitly_container").hide();
        }
        if (jQuery("#bitly_not").attr("checked")) {
            jQuery("#bitly_access").attr('disabled', 'disabled');
        } else {
            jQuery("#bitly_access").removeAttr('disabled');
        }
        jQuery("#shorten_links").parent().click(function() {
            if (jQuery("#shorten_links").attr("checked")) {
                jQuery(".bitly_container").show();    
            } else {
                jQuery(".bitly_container").hide();
            }
            
        });
        jQuery("#bitly_not").parent().click(function() {
            if (jQuery("#bitly_not").attr("checked")) {
                jQuery("#bitly_access").attr('disabled', 'disabled');
            } else {
                jQuery("#bitly_access").removeAttr('disabled');
            }
        });
    }

    function initJumbo() {
        jQuery(".jumbo_item input").change(function() {
            var place = jQuery(this).data("place");
            if (jQuery(this).data("jumbo") === 'text') {
                jQuery("#preview_"+place+" .jumbo_text").html(jQuery(this).val());
                if (place.indexOf("float") > -1) {
                    jQuery("#preview_float_float .jumbo_text").html(jQuery(this).val());
                }
            } else {
                jQuery("#preview_"+place+" .jumbo_text, #preview_"+place+" .jumbo_value").css("color", jQuery(this).val());
                jQuery("#preview_"+place+" .jumbo_counter").css("border-right-color", jQuery(this).val());
                if (place.indexOf("float") > -1) {
                    jQuery("#preview_float_float .jumbo_text, #preview_float_float .jumbo_value").css("color", jQuery(this).val());
                    jQuery("#preview_float_float .jumbo_counter").css("border-right-color", jQuery(this).val());
                    jQuery("#preview_float_float .jumbo_counter").css("border-top-color"  , jQuery(this).val());
                }                
            }
        });
    }


    function initFloatWidgetSize(orient, pos) {
        PreviewChangeCounters();
        var counters = jQuery("[name=counters_align_float]:checked").attr("value");
        var el   = jQuery("#preview_float_float"), icon_w, icon_h, icon_count, add_w = 0;
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

        var addWidth  = 0;
        var addHeight = 0;

        if (counters === 'right') {
            addWidth += 44;
        } else if (counters === 'top') {
            addHeight += 35;
        } else if (counters === 'bundle') {            
            addHeight += 40;
        } else if (counters === 'jumbo' && orient === 'h') {
            add_w += icon_w + 15;
        }
        if (orient == 'h') {
            el.css("width", (icon_w + 6 + addWidth) * icon_count + add_w);
            el.css("height", icon_h + 4 + addHeight);
        }
        else {
            el.css("width", icon_w + 4 + addWidth);
            el.css("height", "auto");
        }
    }


    function initFloatWidgetPosition(pos) {
        var fl_widg, t, l;
        var counters = jQuery("[name=counters_align_float]:checked").attr("value");
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
            jQuery('#boxes_float_float').find('li').css("margin-bottom", (counters === 'bundle' )?"-20px":"0px");
        }
        if (pos == "tl") {
            initFloatWidgetSize('h', pos);
            l = 50;
            t = (counters === 'bundle')?-15:0;
            jQuery(fl_widg).find(".sharexy_cnt").parent().css({"float":"left"}); //zerkalirovanie sharexy icon
            jQuery(fl_widg).find(".boxes").css("float", "right");
            jQuery(fl_widg).find(".boxes li").css("float", "left");
            jQuery(fl_widg).find(".shr_label").hide();
            jQuery('#boxes_float_float').find('li').css("margin-bottom", "0px");
        }
        if (pos == "tr") {            
            initFloatWidgetSize('h', pos);
            l = jQuery(window).width() - fl_widg.offsetWidth - 50;
            t = (counters === 'bundle')?-15:0;
            jQuery(fl_widg).find(".boxes li").css("float", "left");
            jQuery(fl_widg).find(".shr_label").hide();
            jQuery(fl_widg).find(".sharexy_cnt").parent().css({"float":"left","display": "block","width":"auto"});
            jQuery('#boxes_float_float').find('li').css("margin-bottom", "0px");
        }
        if (pos == "bl") {
            initFloatWidgetSize('h', pos);
            l = 50;
            t = jQuery(window).height() - fl_widg.offsetHeight + ((counters === 'bundle')?10:0);
            jQuery(fl_widg).find(".sharexy_cnt").parent().css({"float":"left"});
            jQuery(fl_widg).find(".boxes").css("float", "right");
            jQuery(fl_widg).find(".boxes li").css("float", "left");
            jQuery(fl_widg).find(".shr_label").hide();
            jQuery('#boxes_float_float').find('li').css("margin-bottom", "0px");
        }
        if (pos == "br") {
            initFloatWidgetSize('h', pos);
            l = jQuery(window).width() - fl_widg.offsetWidth - 50;
            t = jQuery(window).height() - fl_widg.offsetHeight + ((counters === 'bundle')?10:0);
            jQuery(fl_widg).find(".boxes li").css("float", "left");
            jQuery(fl_widg).find(".shr_label").hide();
            jQuery(fl_widg).find(".sharexy_cnt").parent().css({"float":"left","display": "block","width":"auto"});
            jQuery('#boxes_float_float').find('li').css("margin-bottom", "0px");
        }
        if (pos == "r") {
            jQuery(fl_widg).find(".cnt").addClass("cnt_top");
            initFloatWidgetSize('v', pos);            
            t = jQuery(window).height() / 2 - fl_widg.offsetHeight / 2;
            l = jQuery(window).width() - fl_widg.offsetWidth - 2;            
            jQuery(fl_widg).find(".shr_label").show();
            jQuery('#boxes_float_float').find('li').css("margin-bottom", (counters === 'bundle' )?"-20px":"0px");
        }
        fl_widg.style.left = l + "px";
        fl_widg.style.top  = t + "px";
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
                src = jQuery("#ScriptPathImg").val() + "design/" + jQuery("#design_name").val() + '/' + jQuery(this).attr("name") + '/' + size + '.png';
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
    function initCountersSettings(place) {
        jQuery("label[grp=align_"+place+"] span").click(function() {
            labelActivate(jQuery(this).find("input"));
        });

        jQuery("label[grp=counters_align_"+place+"] span").click(function() {
            labelActivate(jQuery(this).find("input"));
            changeCountersTypes(place);
        });
    }

    function labelActivate(itemInput) {        
        var id  = itemInput.attr("id");
        var grp = itemInput.attr("name");
        jQuery("label[grp="+grp+"].checkedLabel").removeClass("checkedLabel");
        jQuery("label[for="+id+"]").addClass("checkedLabel");
    }

    function changeCountersTypes(place) {
        var align = jQuery("[name=counters_align_"+place+"]:checked").attr("value");        
        var size  = jQuery("select[name=size_static_" + place + "]").attr("value");
        if (typeof(size) === "undefined") size = jQuery("select[name=size_float_" + place + "]").attr("value");
        //if (jQuery('#label_counters_align_'+place).data("size") === size) return;
        clearCounters(place);
        if (align !== 'none') {
            jQuery("#boxes_"+place).find('li div').each(function() {
                createCounterInfo(jQuery(this), align, size);
            });            
            jQuery("#preview_"+place).find("#shrxbtn").each(function() {
                createCounterInfo(jQuery(this), align, size);
            });
        }
        jQuery("#boxes_"+place).trigger("mouseup");
    }

    function clearCounters(place) {
        jQuery('#preview_'+place).find(".top_counter, .right_counter, .bundle_counter, .jumbo_counter").remove();        
    }

    function createCounterInfo(rootEl, align, size) {
        if (align === "none") return;
        var html  = rootEl.html();
        var place = rootEl.closest("table").parent().attr("id");
        jQuery("tr.jumbo_item_"+place).hide();
        if (rootEl.find(".top_counter, .right_counter, .bundle_counter, .jumbo_counter").size() == 0) {
            var cntrs = countersParser(Math.floor((Math.random() * 10000) + 1));
            if ( align === 'right' ) {
                html += "<div class='"+align+"_counter "+align+"_counter"+size+"'>"+cntrs+"</div>"
            } else if ( align !== 'jumbo') {
                html = "<div class='"+align+"_counter "+align+"_counter"+size+"'>"+cntrs+"</div>" + html;
            } else if (rootEl.attr("id") === "shrxbtn") {
                var pos = jQuery(".sharexy_monitor_float td.hover").attr("name"); 
                var placeMached = (place === "preview_float_float")?"preview_float":place;
                var jumbo_text  = jQuery("input#jumbo_text_"+placeMached).val();
                var jumbo_color = jQuery("input#jumbo_color_"+placeMached).val();
                console.log(placeMached);
                var jumbo = "<div style='color:"+jumbo_color+"' class='"+align+"_counter "+align+"_counter"+size+" jumbo_float_"+pos+"'>";
                jumbo += "<div class='jumbo_value'>"+cntrs+"</div>";
                jumbo += "<div class='jumbo_text'>"+jumbo_text+"</div>";
                jumbo += "</div>";
                if ((pos !== "l" && pos !== "r") || place !== "preview_float_float") {
                    rootEl.parent().find("ul.boxes").before(jumbo);    
                } else {                    
                    rootEl.after(jumbo);    
                }
                jQuery("tr.jumbo_item_"+place).show();
            }
        }
        rootEl.html(html);
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

            // counters
            filterCounterOptions(place, size);

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
                if (jQuery("#picker").css("display") !== 'none') jQuery("#preview_float_float").css("background-color", jQuery("#color").css("color"));
                jQuery("span.shr_label").css("color", jQuery("#label_color").val());
            });

        }
    }

    function filterCounterOptions(place, size) {
        // current checked
        var selectedId = jQuery("[name=counters_align_"+place+"]:checked").attr("id");
        // counters
        var showedArr = [];
        var hiddenArr = [];
        if (size == '16') {
            showedArr.push('counters_align_'+place+'_right');
            hiddenArr.push('counters_align_'+place+'_top');
            hiddenArr.push('counters_align_'+place+'_bundle');
        } else if (size == '32') {
            showedArr.push('counters_align_'+place+'_right');
            showedArr.push('counters_align_'+place+'_top');
            hiddenArr.push('counters_align_'+place+'_bundle');            
        } else if (size == '55' || size == 'sh') {
            showedArr.push('counters_align_'+place+'_right');
            showedArr.push('counters_align_'+place+'_top');
            hiddenArr.push('counters_align_'+place+'_bundle');             
        } else if (size == '60') {
            hiddenArr.push('counters_align_'+place+'_right');
            hiddenArr.push('counters_align_'+place+'_top');
            showedArr.push('counters_align_'+place+'_bundle');            
        }
        for (var i = 0; i < showedArr.length; i++) {
            jQuery("#container_"+showedArr[i]).show();
        }
        for (var i = 0; i < hiddenArr.length; i++) {
            jQuery("#container_"+hiddenArr[i]).hide();
        }     
        var previewBoxes = jQuery('#boxes_'+place);
        previewBoxes.find('.top_counter').removeClass().addClass('top_counter').addClass('top_counter'+size);
        if (jQuery.inArray(selectedId, hiddenArr) >= 0) {
            jQuery("#counters_align_"+place+"_none").trigger('click');
            clearCounters(place);
            jQuery("#boxes_"+place).trigger("mouseup");
        } else {
            changeCountersTypes(place);
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
})(window);

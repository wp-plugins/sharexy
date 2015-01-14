jQuery(document).ready(function() {

    jQuery("span.niceCheck").unbind("click").click(function(){
        changeCheck(jQuery(this));
    });
    
    jQuery("span.niceCheck").each(function() {
        defineCheck(jQuery(this));
    });
});

function changeCheck(el) {
    var el = el;
    if (el.hasClass("disabledCheck") || el.hasClass("disabled")) { return false; }
    var input = el.find("input");

    if (!input.attr("checked")) {
        el.css("background-position", "0 -" + el.height() + 'px');
        input.attr("checked", true);
        input.attr("value","1");
        if (input.attr("id")) { jQuery("label[for='"+input.attr("id")+"']").addClass("labelChecked"); }
    } else {
        el.css("background-position", "0 0");
        input.attr("checked", false);
        input.attr("value","0");
        if (input.attr("id")) { jQuery("label[for='"+input.attr("id")+"']").removeClass("labelChecked"); }
    }
    return false;
}

function defineCheck(el) {

    var input = el.find("input");
        if (input.attr("value") == '1') {
            el.css("background-position", "0 -" + el.height() + 'px');
            input.attr("checked", true);
            if (input.attr("id")) {  jQuery("label[for='"+input.attr("id")+"']").addClass("labelChecked"); }
            input.attr("value","1");
        } else {
            el.css("background-position", "0 0");
            input.attr("checked", false);
            if (input.attr("id")) {  jQuery("label[for='"+input.attr("id")+"']").removeClass("labelChecked"); }
            input.attr("value","0");
        }

     return false;
}

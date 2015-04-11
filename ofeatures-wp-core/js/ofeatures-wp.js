//COPYRIGHT © SkyBlow Company VATIN: PL5170154130 www.it.skyblow.com, www.ofeatures.com

function scrollToEveryPage() {
    jQuery('.every-place-block').fadeIn()
    jQuery('html, body').animate({
        scrollTop: jQuery(".every-place-block").offset().top - 70
    }, 1500);
    
    jQuery('.show-every-page-button').fadeOut()
}

jQuery.fn.selectText = function() {
    this.find('input').each(function() {
        if ($(this).prev().length == 0 || !$(this).prev().hasClass('p_copy')) {
            $('<p class="p_copy" style="position: absolute; z-index: -1;"></p>').insertBefore($(this));
        }
        $(this).prev().html($(this).val());
    });
    var doc = document;
    var element = this[0];
    console.log(this, element);
    if (doc.body.createTextRange) {
        var range = document.body.createTextRange();
        range.moveToElementText(element);
        range.select();
    } else if (window.getSelection) {
        var selection = window.getSelection();
        var range = document.createRange();
        range.selectNodeContents(element);
        selection.removeAllRanges();
        selection.addRange(range);
    }
}

var invokeAction = function(params, successCallback, failCallback, alwaysCallback, method) {
    if (method == undefined || method == null)
        method = 'POST';

    jQuery.post(ajaxurl, params).done(function(data) {
        if (successCallback != undefined && successCallback != null)
            successCallback(data)
    }).fail(function(xhr, textStatus, errorThrown) {
        if (failCallback != undefined && failCallback != null)
            failCallback();
        else
            console.log("Cannot execute request " + textStatus + errorThrown);
    }).always(function() {
        if (alwaysCallback != undefined && alwaysCallback != null)
            alwaysCallback()
    });
}

function synchronize(ofeatures_configuration_no_features_info, currentDomain, pluginType) {
    invokeAction({
        action: "synchronize_ofeatures",
        plugintype: pluginType
    }, function(data) {
        if (data == "no-access-rights") {
            jQuery('.preloader img').fadeOut()
            jQuery('.preloader .text').text("Cannot synchronize. Please provide the correct plugin access data.")
            return
        }
        if (data == "no-features-found") {
            jQuery('.preloader img').fadeOut()
            var noFeaturesInfo = ofeatures_configuration_no_features_info.replace("[DOMAIN]", currentDomain)
            jQuery('.preloader .text').html("<span>" + noFeaturesInfo + "</span>")
            return
        }
        jQuery('.preloader').fadeOut()
        jQuery('.ok-status').fadeIn()
        jQuery('.features').append(data)
        jQuery('.code').click(function() {
            jQuery(this).selectText()
        })

    }, null, function() {
    })
}


function showLanguages(button, languageIndex, featureId){
    
    var selector = " .feature-index-" + featureId;
    jQuery(selector).fadeIn()
    jQuery(button).fadeOut()
    
}
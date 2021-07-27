
/* $ */

$(function () {
    "use strict";

    // switch bettwen longin and signup

    $('.login-page h1 span').click(function () {
        $(this).addClass('selected').siblings().removeClass('selected');
        $('.login-page form').hide();
        $('.' + $(this).data('class')).fadeIn(100);
    });

    // DASHBORD
    $('.toggle-info').click(function () {
        $(this).toggleClass('selected').parent().next('.panel-body').fadeToggle(100);
    if($(this).hasClass('selected')){
        $(this).html("<i class='fa fa-minus fa-lg'></i>");
    }else {
        $(this).html("<i class='fa fa-plus fa-lg'></i>");
    }
    });

    $("[placeholder]").focus(function () {

       $(this).attr("data-text", $(this).attr("placeholder"));
       $(this).attr("placeholder" , "");
    }).blur(function () {
        $(this).attr("placeholder", $(this).attr("data-text"));
    });


    $('input').each(function () {

        if($(this).attr("required") === 'required'){
            $(this).after('<span class="asterisk">*</span>');
        }
    });

    $('.live-name').keyup(function () {
        $('.live-preview .caption h3').text($(this).val());

    });

    $('.live-description').keyup(function () {
        $('.live-preview .caption p').text($(this).val());

    });

    $('.live-price').keyup(function () {
        $('.live-preview .price-tag').text('$'+ $(this).val());

    });


});

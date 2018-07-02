/*
========== Slider Owl  ==========
*/
require([
    'jquery',
    'owlcarousel'
], function ($) {
    'use strict';

    $('.sow_owlcarousel .owl-carousel').each(function () {
        var options = {
            item_md: 1,
            item_sm: 1,
            item_xs: 1,
            dots: 1,
            nav: 1,
            loop: 1,
            autoplayHoverPause: 1,
            autoplaySpeed: 3000,
            autoplay: 1
        };

        var new_options = JSON.parse($(this).attr('data-slider'));
        $.each(new_options,function(i,option) {
            if(option != null){
               options[i] = new_options[i];
            }
        })
        $(this).owlCarousel({
            animateIn: 'fadeOutRight',
            animateOut: 'fadeInLeft',
            autoplay: options.autoplay,
            autoplaySpeed: options.autoplaySpeed,
            autoplayHoverPause: options.autoplayHoverPause,
            loop: options.loop,
            touchDrag: false,
            nav: options.nav,
            dots: options.dots,
            navText: ['<span class="fa fa-angle-left"></span>', '<span class="fa fa-angle-right"></span>'],
            responsive: {
                0:{
                    items: 1,
                },
                320:{
                    items: 1,
                },
                480: {
                    items: options.item_xs,

                },
                768: {
                    items: options.item_sm,

                },
                992: {
                    items: options.item_md,

                },
                1200: {
                    items: options.item_md,

                }
            }
        });
    });

})
/*
========== End Slider Owl  ==========
*/
/*
========== Scroll Top =====================
*/

require([
    'jquery',
], function ($) {
    'use strict';
    var scrollTime;
    $(window).scroll(function () {
        clearTimeout(scrollTime);
        scrollTime = setTimeout(function () {
            if (jQuery(this).scrollTop() > 100) {
                $('#to_top').fadeIn();
            } else {
                $('#to_top').fadeOut();
            }
        }, 200);
    });
    $('#to_top').click(function () {
        $('html, body').animate({scrollTop: 0}, 600);
    });
})
/*
========== End Scroll Top =====================
*/
/*
========== Count Down Timer =====================
*/
require([
    'jquery',
], function ($) {
    'use strict';
    $('.count-down').each(function () {
        var el = $(this);
        var time = $(this).attr('data-time');
        var countDownDate = new Date(time).getTime();
        // Update the count down every 1 second
        var x = setInterval(function () {
            // Get todays date and time
            var now = new Date().getTime();
            // Find the distance between now an the count down date
            var distance = countDownDate - now;
            // Time calculations for days, hours, minutes and seconds
            var days = Math.floor(distance / (1000 * 60 * 60 * 24));
            var hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
            var minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
            var seconds = Math.floor((distance % (1000 * 60)) / 1000);
            // Display the result in the element
            el.html(days + $.mage.__('days') + hours + $.mage.__('hours') + minutes + $.mage.__('minutes') + seconds + $.mage.__('seconds'));
            // If the count down is finished, write some text
            if (distance < 0) {
                clearInterval(x);
                el.html($.mage.__('expired'));
            }
        }, 1000);
    });
})
/*
========== End Count Down Timer =====================
*/

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
        if ($(this).attr('data-slider') != undefined) {
            options = JSON.parse($(this).attr('data-slider'));
        }
        $(this).owlCarousel({
            animateIn: 'fadeOutRight',
            animateOut: 'fadeInLeft',
            autoplay: options.autoplay,
            autoplaySpeed: options.autoplaySpeed,
            autoplayHoverPause: options.autoplayHoverPause,
            loop: options.loop,
            nav: options.nav,
            dots: options.dots,
            navText: ['<span class="fa fa-angle-left"></span>', '<span class="fa fa-angle-right"></span>'],
            responsive: {
                480: {
                    items: 1,

                },
                768: {
                    items: options.item_xs,

                },
                992: {
                    items: options.item_sm,

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
==========  Popup Newsletter ==========
*/
require([
    'jquery',
    'magnificPopup'
], function ($, modal) {
    'use strict';
    if ($(window).width() > 991) {
        function setCookie(name, value, days) {
            if (days) {
                var date = new Date();
                date.setTime(date.getTime() + (days * 24 * 60 * 60 * 1000));
                var expires = "; expires=" + date.toGMTString();
            } else var expires = "";
            document.cookie = name + "=" + value + expires + "; path=/";
        }

        function getCookie(name) {
            var nameEQ = name + "=";
            var ca = document.cookie.split(';');
            for (var i = 0; i < ca.length; i++) {
                var c = ca[i];
                while (c.charAt(0) == ' ') c = c.substring(1, c.length);
                if (c.indexOf(nameEQ) == 0) return c.substring(nameEQ.length, c.length);
            }
            return null;
        }

        if (getCookie("show_popup_news") != 1) {
            $.magnificPopup.open({
                items: {
                    src: '#popup_newsletter'
                },
                type: 'inline',
                removalDelay: 300,
                showCloseBtn: false,
                callbacks: {
                    beforeOpen: function () {
                        this.st.mainClass = 'mfp-zoom-out modal_newsletter_popup';
                    }
                }
            });
            $('.popup_news_close').on('click', function () {
                $.magnificPopup.close({
                    items: {
                        src: '#popup_newsletter'
                    }
                })
            });

        }
        var cookie_time = $('#popup_newsletter').attr('data-cookie');
        if (cookie_time == '') {
            cookie_time = 1;
        }
        $("#newsletter_pop_up form").submit(function (event) {
            setCookie("show_popup_news", '1', cookie_time);
        });
        $('#dont_show_popup_news_again').on('change', function () {
            if (getCookie("show_popup_news") != cookie_time) {
                setCookie("show_popup_news", '1', cookie_time)
            } else {
                setCookie("show_popup_news", '0', cookie_time)
            }
        });

    }
    ;
});
/*
========== End Popup Newsletter ==========
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
        console.log(countDownDate);
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
========== End Scroll Top =====================
*/

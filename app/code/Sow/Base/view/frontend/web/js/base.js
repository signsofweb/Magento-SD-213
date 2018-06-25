require([
    'jquery',
], function($) {
    'use strict';
    $('.back-to-top').click(function() { // When arrow is clicked
        $('body,html').animate({
            scrollTop: 0 // Scroll to top of body
        }, 500);
        return false;
    });
})
/*
========== Slider Owl  ==========
*/
require([
    'jquery',
    'owlcarousel'
], function($) {
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
            autoplay:1
        };
        if ( $(this).attr('data-slider') !=undefined){
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
], function($, modal) {
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
                    beforeOpen: function() {
                        this.st.mainClass = 'mfp-zoom-out modal_newsletter_popup';
                    }
                }
            });
            $('.popup_news_close').on('click', function() {
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
        $("#newsletter_pop_up form").submit(function(event) {
            setCookie("show_popup_news", '1', cookie_time);
        });
        $('#dont_show_popup_news_again').on('change', function() {
            if (getCookie("show_popup_news") != cookie_time) {
                setCookie("show_popup_news", '1', cookie_time)
            } else {
                setCookie("show_popup_news", '0', cookie_time)
            }
        });

    };
});
/*
========== End Popup Newsletter ==========
*/
define([
    'jquery',
    'SoW_Base/js/owl.carousel.min'
], function ($) {
    'use strict';

    $.widget('mage.sowowlcarousel', {
        options: {

        },
        _init: function(){
            this._createSlider();
        },
        _createSlider: function () {
            var $widget = this;
            var options = $widget._getDataSlider();
            $widget.element.owlCarousel({
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
                    0: {
                        items: 1,
                    },
                    320: {
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

        },
        _getDataSlider: function(){
            var $widget = this;
            var data = JSON.parse($widget.element.attr('data-slider'));
            return data;
        }
    });

    return $.mage.sowowlcarousel;
});

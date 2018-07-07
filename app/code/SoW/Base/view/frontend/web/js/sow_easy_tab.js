define([
    'jquery'
], function ($) {
    'use strict';

    $.widget('mage.easytab', {
        options: {
            "onShow": 1
        },
        _init: function(){
            this._RenderStatus();
            this._EventListener();
        },
        _EventListener: function () {
            var $widget = this;
            var target = this.element.find('[data-title]');
            target.on('click',function () {
                if ($widget.options.onShow != $(this).attr('data-title')){
                    $widget.options.onShow = $(this).attr('data-title');
                    $widget._Rewind();
                    $widget._RenderStatus();
                }
            })
        },
        _Rewind: function(){
            var $widget = this;
            $widget.element.find('.sow-tab-content').hide();
            $widget.element.find('[data-title]').removeClass('active');
        },
        _RenderStatus: function () {
            var $widget = this;
            var id = this.options.onShow;
            $widget.element.find('[data-content = '+id+']').show();
            $widget.element.find('[data-title = '+id+']').addClass('active');

        }
    });

    return $.mage.easytab;
});

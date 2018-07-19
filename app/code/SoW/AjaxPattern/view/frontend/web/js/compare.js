define([
    'jquery',
    'Magento_Customer/js/customer-data'
], function ($,customerData) {
    'use strict';

    $.widget('mage.compare', {
        options: {
            formKeyInputSelector: 'input[name="form_key"]'
        },
        _create: function(){
            this.compareProducts = customerData.get('compare-products');

        },
        _init: function(){
            this._Events();
        },
        _Events: function () {
            var $widget = this;
            $widget.element.on('click', function () {
                $(this).attr('disabled','disabled');
                $('#ajax_alert').addClass('processing');
                if($widget.element.attr('data-action') == 'add'){
                    $widget._addItem({
                        'id': $(this).data('compare').id,
                        'product_url': $(this).data('compare').product_url,
                        'name': $(this).data('compare').name,
                        'remove_data': $(this).data('compare').remove_data,
                        'add_data': $(this).data('compare').add_data
                    });
                }else{
                    $widget._removeItem({
                        'id': $(this).data('compare').id,
                        'product_url': $(this).data('compare').product_url,
                        'name': $(this).data('compare').name,
                        'remove_data': $(this).data('compare').remove_data,
                        'add_data': $(this).data('compare').add_data
                    });
                }
            });
        },
        _addItem: function (item) {
            var $widget = this;
            if(!this.compareProducts().items){
                this.compareProducts().items = [];
                this.compareProducts().count = 0;
                this.compareProducts().countCaption = '0' + ' Item';
            }
            this.compareProducts().items.push(item);
            this.compareProducts().count++;
            this.compareProducts().countCaption = this.compareProducts().count == 1 ? this.compareProducts().count + ' Item' : this.compareProducts().count + ' Items';
            this.compareProducts.valueHasMutated();
            var addData = JSON.parse(item.add_data);
            var formKey = $(this.options.formKeyInputSelector).val();
            if (formKey) {
                addData.data.form_key = formKey;
            }
            $.ajax({
                url: addData.action,
                type: 'POST',
                data: addData.data,
                success: function (data, testStatus, jqXHR) {
                    $widget.element.attr('data-action','remove');
                    $widget.element.find('i').removeClass('fa-retweet');
                    $widget.element.find('i').addClass('fa-check');
                    $widget.element.removeAttr('disabled');
                    $('#ajax_alert').addClass('success');
                    setTimeout(
                        function(){
                            $('#ajax_alert').removeClass('processing');
                            $('#ajax_alert').removeClass('success');
                        }, 5000);
                    // TODO: Check for data.success === true to determine if it was an actual success.
                    if (data.success == false) {
                        alert('actually false');
                    }
                },
                error: function (jqXHR, textStatus, errorThrown) {
                    alert('failure');
                }
            })
        },
        _removeItem: function (item) {
            var $widget = this;
            var removeData = JSON.parse(item.remove_data);
            var formKey = $(this.options.formKeyInputSelector).val();
            if (formKey) {
                removeData.data.form_key = formKey;
            }
            $.ajax({
                url: removeData.action,
                type: 'POST',
                data: removeData.data,
                success: function (data, testStatus, jqXHR) {
                    $widget.element.attr('data-action','add');
                    $widget.element.removeAttr('disabled');
                    $widget.element.find('i').removeClass('fa-check');
                    $widget.element.find('i').addClass('fa-retweet');
                    $('#ajax_alert').addClass('success');
                    setTimeout(
                        function(){
                            $('#ajax_alert').removeClass('processing');
                            $('#ajax_alert').removeClass('success');
                        }, 5000);
                    // TODO: Check for data.success === true to determine if it was an actual success.
                    if (data.success == false) {
                        alert('actually false');
                    }
                },
                error: function (jqXHR, textStatus, errorThrown) {
                    alert('failure');
                }
            })
        }

    });

    return $.mage.compare;
});

define([
    'underscore',
    'uiRegistry',
    'Magento_Ui/js/form/element/select',
    'Magento_Ui/js/modal/modal'
], function (_, uiRegistry, select, modal) {
    'use strict';

    return select.extend({

        /**
         * On value change handler.
         *
         * @param {String} value
         */
        onUpdate: function (value) {
            var category_link = uiRegistry.get('index = category_link');
            if (category_link.visibleValue == value) {
                category_link.show();
            } else {
                category_link.hide();
            }

            var custom_link = uiRegistry.get('index = custom_link');
            if (custom_link.visibleValue == value) {
                custom_link.show();
            } else {
                custom_link.hide();
            }

            return this._super();
        },
    });
});
define([
    'jquery',
    'underscore',
    'uiRegistry',
    'Magento_Ui/js/form/element/select',
    'Magento_Ui/js/modal/modal'
], function ($, _, uiRegistry, select, modal) {
    'use strict';

    return select.extend({
        /**
         * On value change handler.
         *
         * @param {String} value
         */
        onUpdate: function (value) {
            // console.log('Selecteds Value: ' + value);
            if (value == 1){
                $('div[data-index="multiselect_example[]"]').show();
            }else {
                $('div[data-index="multiselect_example[]"]').hide();
            }
            return this._super();
        },
    });
});
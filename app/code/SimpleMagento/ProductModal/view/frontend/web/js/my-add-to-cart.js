define([
    'jquery',
    'Magento_Ui/js/modal/modal',
    'jquery/ui',
    'Magento_Catalog/js/catalog-add-to-cart'
], function($,modal){
    'use strict';

    var options = {
        'type': 'popup',
        'title': 'Please choose options',
        'trigger': '[data-trigger=trigger]',
        'responsive': true,
        'buttons': false
    };

    $.widget('mage.myAddToCart', $.mage.catalogAddToCart, {

        /**
         * @override
         */
        submitForm: function () {
                $("#my-modal").modal("openModal");
        }
    });

    return $.mage.myAddToCart;
});
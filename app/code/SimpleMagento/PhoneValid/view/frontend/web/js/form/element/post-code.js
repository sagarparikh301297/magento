/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */

/**
 * @api
 */
define([
    'jquery',
    './abstract'
], function ($, Abstract) {
    'use strict';
    $('body').on('hover','input[name="postcode"]',function(event){
        $('input[name="postcode"]').attr('maxlength','6');
    });
    return Abstract.extend({
        defaults: {
        }
    });
});
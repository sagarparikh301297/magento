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
    $('body').on('hover','input[name="telephone"]',function(event){
        $('input[name="telephone"]').attr('maxlength','10');
    });
    return Abstract.extend({
        defaults: {
        }
    });
});
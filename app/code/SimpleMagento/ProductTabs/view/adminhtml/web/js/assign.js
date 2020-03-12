define(
    [
        'Magento_Ui/js/grid/columns/actions',
        'jquery',
        'uiRegistry'
    ],
    function (Actions, $, registry) {
        'use strict';

        return Actions.extend({
            defaults: {
                bodyTmpl: 'SimpleMagento_ProductTabs/actions',
            },

            /**
             * Applies specified action.
             *
             * @param   {String} actionIndex - Actions' identifier.
             * @param   {Number} rowIndex - Index of a row.
             * @returns {ActionsColumn} Chainable.
             */
            applyAction: function (actionIndex, rowIndex) {
                var action = this.getAction(rowIndex, actionIndex),
                    callback = this._getCallback(action);
                if (action.modal) {
                    // Opening popup with category grid
                    var modal = registry.get('SimpleMagento_ProductTabs_customer_grid_listing.SimpleMagento_ProductTabs_customer_grid_listing.assign_category_modal');
                    modal.openModal();
                }
                return this;
            },
        });
    }
);
/**
 *
 * @category   Krish
 * @package    Ktpl_Quickview
 * @author     Extension Team
 */
var config = {
    map: {
        '*': {
            ktpl_fancybox: 'Ktpl_Quickview/js/jquery.fancybox',
            ktpl_config: 'Ktpl_Quickview/js/ktpl_config',
            magnificPopup: 'Ktpl_Quickview/js/jquery.magnific-popup.min',
            ktpl_tocart: 'Ktpl_Quickview/js/ktpl_tocart'
        }
    },
    shim: {
        magnificPopup: {
            deps: ['jquery']
        }
    }
};

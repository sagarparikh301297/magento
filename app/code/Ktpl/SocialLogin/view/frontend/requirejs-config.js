/**
 * @category  Ktpl
 * @package   Ktpl_SocialLogin
 */

var config = {
    paths: {
        socialProvider: 'Ktpl_SocialLogin/js/provider',
        socialPopupForm: 'Ktpl_SocialLogin/js/popup'
    },
    map: {
        '*': {
            'Magento_Checkout/js/proceed-to-checkout': 'Ktpl_SocialLogin/js/proceed-to-checkout'
        }
    }
};

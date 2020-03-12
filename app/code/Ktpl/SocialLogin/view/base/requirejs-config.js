/**
 * @category  Ktpl
 * @package   Ktpl_SocialLogin
 */

var config = {
    paths: {
        'ktpl/sociallogin/jquery/popup': 'Ktpl_SocialLogin/js/jquery.magnific-popup.min',
        'ktpl/sociallogin/owl.carousel': 'Ktpl_SocialLogin/js/owl.carousel.min',
        'ktpl/sociallogin/bootstrap': 'Ktpl_SocialLogin/js/bootstrap.min',
        mpIonRangeSlider: 'Ktpl_SocialLogin/js/ion.rangeSlider.min',
        touchPunch: 'Ktpl_SocialLogin/js/jquery.ui.touch-punch.min',
        mpDevbridgeAutocomplete: 'Ktpl_SocialLogin/js/jquery.autocomplete.min'
    },
    shim: {
        "ktpl/sociallogin/jquery/popup": ["jquery"],
        "ktpl/sociallogin/owl.carousel": ["jquery"],
        "ktpl/sociallogin/bootstrap": ["jquery"],
        mpIonRangeSlider: ["jquery"],
        mpDevbridgeAutocomplete: ["jquery"],
        touchPunch: ['jquery', 'jquery/ui']
    }
};

<?php
/**
 * @category    Ktpl
 * @package     Ktpl_BetterPopup
 */

namespace Ktpl\BetterPopup\Block;

/**
 * Class Success
 * @package Ktpl\BetterPopup\Block
 */
class Success extends Popup
{
    /**
     * @var string
     */
    protected $_template = 'Ktpl_BetterPopup::popup/success.phtml';

    /**
     * Get Coupon code
     *
     * @return array|mixed
     */
    public function getCouponCode()
    {
        return 'TEST';
    }

    /**
     * Get Html Popup success
     *
     * @return mixed
     */
    public function getPopupSuccessContent()
    {
//        Change success content here of popup
        $htmlConfig =
            '<p class="success-title">Thank you, you got the offer!</p>
            <div class="mp-popup-coupon-code">
                <input id="mp-coupon-code" type="text" readonly="readonly" value="{{coupon_code}}"/>
                <button class="btn-copy primary" type="submit">Copy</button>
            </div>
            <small>Please use this coupon code when checking out</small>';

        return str_replace('{{coupon_code}}', $this->getCouponCode(), $htmlConfig);
    }
}

<?php
/**
 * @category  Ktpl
 * @package   Ktpl_SocialLogin
 */

namespace Ktpl\SocialLogin\Plugin\CustomerData;

use Magento\Checkout\CustomerData\Cart as CustomerCart;
use Ktpl\SocialLogin\Helper\Data as HelperData;

/**
 * Class Cart
 *
 * @package Ktpl\SocialLogin\Plugin\CustomerData
 */
class Cart
{
    /**
     * @var HelperData
     */
    protected $_helperData;

    /**
     * Cart constructor.
     *
     * @param HelperData $helperData
     */
    public function __construct(HelperData $helperData)
    {
        $this->_helperData = $helperData;
    }

    /**
     * @param CustomerCart $subject
     * @param $result
     *
     * @return mixed
     */
    public function afterGetSectionData(CustomerCart $subject, $result)
    {
        if ($this->_helperData->isEnabled() && $this->_helperData->isReplaceAuthModal()) {
            $result['isReplaceAuthModal'] = $this->_helperData->isReplaceAuthModal();
        }

        return $result;
    }
}

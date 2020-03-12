<?php
/**
 * @category  Ktpl
 * @package   Ktpl_SocialLogin
 */

namespace Ktpl\SocialLogin\Model\System\Config\Source;

use Magento\Framework\Option\ArrayInterface;

/**
 * Class Popup
 *
 * @package Ktpl\SocialLogin\Model\System\Config\Source
 */
class Popup implements ArrayInterface
{
    const POPUP_LOGIN = 'popup_login';

    /**
     * @return array
     */
    public function toOptionArray()
    {
        return [
            ['value' => '', 'label' => __('None')],
            ['value' => self::POPUP_LOGIN, 'label' => __('Popup Login')]
        ];
    }
}

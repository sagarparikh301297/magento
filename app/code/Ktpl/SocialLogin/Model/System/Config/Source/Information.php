<?php
/**
 * @category  Ktpl
 * @package   Ktpl_SocialLogin
 */

namespace Ktpl\SocialLogin\Model\System\Config\Source;

use Magento\Framework\Option\ArrayInterface;

/**
 * Class Information
 *
 * @package Ktpl\SocialLogin\Model\System\Config\Source
 */
class Information implements ArrayInterface
{
    const INFO_EMAIL = 'email';
    const INFOR_NAME = 'name';
    const INFOR_PW   = 'password';

    /**
     * @return array
     */
    public function toOptionArray()
    {
        return [
            ['value' => self::INFO_EMAIL, 'label' => __('Email')],
            ['value' => self::INFOR_NAME, 'label' => __('Name')],
            ['value' => self::INFOR_PW, 'label' => __('Password')]
        ];
    }
}

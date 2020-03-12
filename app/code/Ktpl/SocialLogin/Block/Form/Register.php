<?php
/**
 * @category  Ktpl
 * @package   Ktpl_SocialLogin
 */

namespace Ktpl\SocialLogin\Block\Form;

/**
 * Class Register
 *
 * @package Ktpl\SocialLogin\Block\Form
 */
class Register extends \Magento\Customer\Block\Form\Register
{
    /**
     * @return $this
     */
    protected function _prepareLayout()
    {
        return $this;
    }
}

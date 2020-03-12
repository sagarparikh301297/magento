<?php
/**
 * @category  Ktpl
 * @package   Ktpl_SocialLogin
 */

namespace Ktpl\SocialLogin\Block\Form;

/**
 * Class Login
 *
 * @package Ktpl\SocialLogin\Block\Form
 */
class Login extends \Magento\Customer\Block\Form\Login
{
    /**
     * @return $this
     */
    protected function _prepareLayout()
    {
        return $this;
    }
}

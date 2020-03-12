<?php
/**
 * @category  Ktpl
 * @package   Ktpl_SocialLogin
 */

namespace Ktpl\SocialLogin\Helper;

use Magento\Framework\App\RequestInterface;
use Ktpl\SocialLogin\Helper\AbstractData as CoreHelper;

/**
 * Class Data
 *
 * @package Ktpl\SocialLogin\Helper
 */
class Data extends CoreHelper
{
    const CONFIG_MODULE_PATH = 'sociallogin';

    /**
     * @param RequestInterface $request
     * @param $formId
     *
     * @return string
     */
    public function captchaResolve(RequestInterface $request, $formId)
    {
        $captchaParams = $request->getPost(\Magento\Captcha\Helper\Data::INPUT_NAME_FIELD_VALUE);

        return isset($captchaParams[$formId]) ? $captchaParams[$formId] : '';
    }

    /**
     * @param null $storeId
     *
     * @return mixed
     */
    public function canSendPassword($storeId = null)
    {
        return !in_array(
            'password',
            explode(',', $this->getFieldCanShow()),
            true
        ) && $this->getConfigGeneral('send_password', $storeId);
    }

    /**
     * @param null $storeId
     *
     * @return mixed
     */
    public function getPopupEffect($storeId = null)
    {
        return $this->getConfigGeneral('popup_effect', $storeId);
    }

    /**
     * @param null $storeId
     *
     * @return mixed
     */
    public function getStyleManagement($storeId = null)
    {
        $style = $this->getConfigGeneral('style_management', $storeId);
        if ($style === 'custom') {
            return $this->getCustomColor($storeId);
        }

        return $style;
    }

    /**
     * @param null $storeId
     *
     * @return mixed
     */
    public function getCustomColor($storeId = null)
    {
        return $this->getConfigGeneral('custom_color', $storeId);
    }

    /**
     * @param null $storeId
     *
     * @return mixed
     */
    public function getCustomCss($storeId = null)
    {
        return $this->getConfigGeneral('custom_css', $storeId);
    }

    /**
     * @param null $storeId
     *
     * @return mixed
     */
    public function requiredMoreInfo($storeId = null)
    {
        return $this->getConfigGeneral('require_more_info', $storeId);
    }

    /**
     * @param null $storeId
     *
     * @return mixed
     */
    public function getFieldCanShow($storeId = null)
    {
        return $this->getConfigGeneral('information_require', $storeId);
    }

    /**
     * @return mixed
     */
    public function isSecure()
    {
        return $this->getConfigValue('web/secure/use_in_frontend');
    }

    /**
     * @param null $storeId
     *
     * @return mixed
     */
    public function isReplaceAuthModal($storeId = null)
    {
        return $this->getPopupLogin() && $this->getConfigGeneral('authentication_popup', $storeId);
    }

    /**
     * @param null $storeId
     *
     * @return mixed
     */
    public function getPopupLogin($storeId = null)
    {
        return $this->getConfigGeneral('popup_login', $storeId);
    }
}

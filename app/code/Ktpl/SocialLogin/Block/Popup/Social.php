<?php
/**
 * @category  Ktpl
 * @package   Ktpl_SocialLogin
 */

namespace Ktpl\SocialLogin\Block\Popup;

use Magento\Framework\View\Element\Template;
use Magento\Framework\View\Element\Template\Context;
use Ktpl\SocialLogin\Helper\Social as SocialHelper;
use Ktpl\SocialLogin\Model\System\Config\Source\Position;

/**
 * Class Social
 *
 * @package Ktpl\SocialLogin\Block\Popup
 */
class Social extends Template
{
    /**
     * @type SocialHelper
     */
    protected $socialHelper;

    /**
     * @param Context $context
     * @param SocialHelper $socialHelper
     * @param array $data
     */
    public function __construct(
        Context $context,
        SocialHelper $socialHelper,
        array $data = []
    ) {
        $this->socialHelper = $socialHelper;

        parent::__construct($context, $data);
    }

    /**
     * @return array
     */
    public function getAvailableSocials()
    {
        $availabelSocials = [];

        foreach ($this->socialHelper->getSocialTypes() as $socialKey => $socialLabel) {
            $this->socialHelper->setType($socialKey);
            if ($this->socialHelper->isEnabled()) {
                $availabelSocials[$socialKey] = [
                    'label'     => $socialLabel,
                    'login_url' => $this->getLoginUrl($socialKey),
                ];
            }
        }

        return $availabelSocials;
    }

    /**
     * @param $key
     *
     * @return string
     */
    public function getBtnKey($key)
    {
        switch ($key) {
            case 'vkontakte':
                $class = 'vk';
                break;
            default:
                $class = $key;
        }

        return $class;
    }

    /**
     * @return array
     */
    public function getSocialButtonsConfig()
    {
        $availableButtons = $this->getAvailableSocials();
        foreach ($availableButtons as $key => &$button) {
            $button['url']     = $this->getLoginUrl($key, ['authen' => 'popup']);
            $button['key']     = $key;
            $button['btn_key'] = $this->getBtnKey($key);
        }

        return $availableButtons;
    }

    /**
     * @param null $position
     *
     * @return bool
     */
    public function canShow($position = null)
    {
        $displayConfig = $this->socialHelper->getConfigGeneral('social_display');
        $displayConfig = explode(',', $displayConfig);

        if (!$position) {
            $position = $this->getRequest()->getFullActionName() === 'customer_account_login' ?
                Position::PAGE_LOGIN :
                Position::PAGE_CREATE;
        }

        return in_array($position, $displayConfig);
    }

    /**
     * @param $socialKey
     * @param array $params
     *
     * @return string
     */
    public function getLoginUrl($socialKey, $params = [])
    {
        $params['type'] = $socialKey;

        return $this->getUrl('sociallogin/social/login', $params);
    }
}

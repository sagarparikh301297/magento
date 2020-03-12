<?php
/**
 * @category  Ktpl
 * @package   Ktpl_SocialLogin
 */

namespace Ktpl\SocialLogin\Block\System;

use Magento\Backend\Block\Template\Context;
use Magento\Config\Block\System\Config\Form\Field as FormField;
use Magento\Framework\Data\Form\Element\AbstractElement;
use Magento\Framework\Exception\LocalizedException;
use Ktpl\SocialLogin\Helper\Social as SocialHelper;

/**
 * Class Redirect
 *
 * @package Ktpl\SocialLogin\Block\System
 */
class RedirectUrl extends FormField
{
    /**
     * @type SocialHelper
     */
    protected $socialHelper;

    /**
     * RedirectUrl constructor.
     *
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
     * @param AbstractElement $element
     *
     * @return string
     * @throws LocalizedException
     */
    protected function _getElementHtml(AbstractElement $element)
    {
        $elementId   = explode('_', $element->getHtmlId());
        $redirectUrl = $this->socialHelper->getAuthUrl($elementId[1]);
        $html        = '<input style="opacity:1;" readonly id="' . $element->getHtmlId() . '" class="input-text admin__control-text" value="' . $redirectUrl . '" onclick="this.select()" type="text">';

        return $html;
    }
}

<?php
/**
 * @category    Ktpl
 * @package     Ktpl_BetterPopup
 */

namespace Ktpl\BetterPopup\Block;

use Magento\Catalog\Block\Product\AbstractProduct;
use Magento\Catalog\Block\Product\Context;
use Magento\Framework\Phrase;
use Magento\Newsletter\Model\ResourceModel\Subscriber\CollectionFactory;
use Magento\Widget\Block\BlockInterface;
use Ktpl\BetterPopup\Helper\Data as HelperData;
use Ktpl\BetterPopup\Model\Config\Source\Appear;
use Ktpl\BetterPopup\Model\Config\Source\PageToShow;
use Ktpl\BetterPopup\Model\Config\Source\Responsive;

/**
 * Class Popup
 * @package Ktpl\BetterPopup\Block
 */
class Popup extends AbstractProduct implements BlockInterface
{
    /**
     * @var HelperData
     */
    protected $_helperData;

    /**
     * @var CollectionFactory
     */
    protected $_subscriberCollectionFactory;

    /**
     * Popup constructor.
     *
     * @param Context $context
     * @param HelperData $helperData
     * @param CollectionFactory $subscriberCollectionFactory
     * @param array $data
     */
    public function __construct(
        Context $context,
        HelperData $helperData,
        CollectionFactory $subscriberCollectionFactory,
        array $data = []
    ) {
        $this->_helperData = $helperData;
        $this->_subscriberCollectionFactory = $subscriberCollectionFactory;

        parent::__construct($context, $data);
    }

    /**
     * Get Width Popup Config
     *
     * @return array|mixed
     */
    public function getWidthPopup()
    {
        return $this->_helperData->getWhatToShowConfig('width');
    }

    /**
     * Get Height Popup Config
     *
     * @return array|mixed
     */
    public function getHeightPopup()
    {
        return $this->_helperData->getWhatToShowConfig('height');
    }

    /**
     * Get Background Color Popup
     *
     * @return array|mixed
     */
    public function getBackGroundColor()
    {
        return $this->_helperData->getWhatToShowConfig('background_color');
    }

    /**
     * Get Text Color in Popup
     *
     * @return array|mixed
     */
    public function getTextColor()
    {
        return $this->_helperData->getWhatToShowConfig('text_color');
    }

    /**
     * Check FullScreen option
     *
     * @return bool
     */
    public function isFullScreen()
    {
        return (int) $this->_helperData->getWhatToShowConfig('responsive') === Responsive::FULLSCREEN_POPUP;
    }

    /**
     * Check show fireworks config
     *
     * @return bool
     */
    public function isShowFireworks()
    {
        return '0';
    }

    /**
     * Is Enable Show Float Button
     *
     * @return array|mixed
     */
    public function isShowFloatButton()
    {
        return $this->_helperData->getWhenToShowConfig('show_float_button');
    }

    /**
     * Get Location Float Button
     *
     * @return array|mixed
     */
    public function getLocationFloatButton()
    {
        return $this->_helperData->getWhenToShowConfig('float_button_direction');
    }

    /**
     * Get Float button label
     *
     * @return Phrase
     */
    public function getFloatLabel()
    {
        $label = $this->_helperData->getWhenToShowConfig('float_button_label');

        return $label ?: __('Subscribe');
    }

    /**
     * Get Config Popup Appear
     *
     * @return array|mixed
     */
    public function getPopupAppear()
    {
        return (int) $this->_helperData->getWhenToShowConfig('popup_appear');
    }

    /**
     * Get time delay to show popup
     *
     * @return array|int|mixed
     */
    public function getDelayConfig()
    {
        if ($this->getPopupAppear() === Appear::AFTER_X_SECONDS) {
            return $this->_helperData->getWhenToShowConfig('delay');
        }

        return 0;
    }

    /**
     * is Exit Intent Config
     *
     * @return string
     */
    public function isExitIntent()
    {
        return $this->getPopupAppear() === Appear::EXIT_INTENT;
    }

    /**
     * Get Popup show again after (days)
     *
     * @return array|mixed
     */
    public function getCookieConfig()
    {
        $cookieDays = (int) $this->_helperData->getWhenToShowConfig('cookieExp');

        return ($cookieDays !== null) ? $cookieDays : 30;
    }

    /**
     * Get Percentage scroll down to show Popup
     *
     * @return array|mixed
     */
    public function getPercentageScroll()
    {
        return $this->_helperData->getWhenToShowConfig('after_scroll');
    }

    /**
     * Get Html Content popup
     *
     * @return mixed
     */
    public function getPopupContent()
    {
//        Change content here of popup
        $htmlConfig =
            '<div id="mp-popup-template3">
                <div class="tmp3-img-content">
                    <img src="{{img_tmp3}}"/>
                </div>
                <div class="tmp3-text-content">
                    <div class="tmp3-title">Subscribe</div>
                    <div class="tmp3-sub-title">TO OUR NEWSLETTER</div>
                    <div class="tmp3-text mppopup-text">Subsribe to our email newsletter today to receive update on the latest news, tutorials and special offers!</div>
                    <form class="form subscribe tmp3-form" novalidate action="{{form_url}}" method="post"
                          data-mage-init=\'{"validation": {"errorClass": "mage-error"}}\'
                          id="mp-newsletter-validate-detail">
                        <div class="tmp3_field_newsletter">
                            <input name="email" type="email" id="mp-newsletter" class="tmp3-input"
                                   placeholder="Enter your email to subscribe"
                                   data-validate="{required:true, \'validate-email\':true}">
                        </div>
                        <button class="action subscribe primary tmp3-button" title="Send" type="submit">
                            <img src="{{tmp3_icon_button}}"/>
                        </button>
                        <div class="popup-loader">
                            <img class="loader" src="{{url_loader}}" alt="Loading...">
                        </div>
                    </form>
                </div>
                <span class="tmp3-lable-powered">Powered by Ktpl</span>
            </div>';

        $search = [
            '{{form_url}}',
            '{{url_loader}}',
            '{{email_icon_url}}',
            '{{bg_tmp2}}',
            '{{img_tmp3}}',
            '{{tmp3_icon_button}}',
            '{{bg_tmp4}}',
            '{{img_tmp4}}',
            '{{img_content_tmp5}}',
            '{{img_cap_tmp5}}',
            '{{img_email_tmp5}}'
        ];
        $replace = [
            $this->getFormActionUrl(),
            $this->getViewFileUrl('images/loader-1.gif'),
            $this->getViewFileUrl('Ktpl_BetterPopup::images/mail-icon.png'),
            $this->getViewFileUrl('Ktpl_BetterPopup::images/bg-tmp2.png'),
            $this->getViewFileUrl('Ktpl_BetterPopup::images/template3/img-content.png'),
            $this->getViewFileUrl('Ktpl_BetterPopup::images/template3/button-icon.png'),
            $this->getViewFileUrl('Ktpl_BetterPopup::images/template4/bg.png'),
            $this->getViewFileUrl('Ktpl_BetterPopup::images/template4/img-content.png'),
            $this->getViewFileUrl('Ktpl_BetterPopup::images/template5/img-content.png'),
            $this->getViewFileUrl('Ktpl_BetterPopup::images/template5/img-cap.png'),
            $this->getViewFileUrl('Ktpl_BetterPopup::images/template5/img-email.png')
        ];

        return str_replace($search, $replace, $htmlConfig);
    }

    /**
     * Check include pages are show Popup
     *
     * @return bool
     */
    public function checkIncludePages()
    {
        $fullActionName = $this->getRequest()->getFullActionName();
        $arrayPages = explode("\n", $this->_helperData->getWhereToShowConfig('include_pages'));
        $includePages = array_map('trim', $arrayPages);

        return in_array($fullActionName, $includePages, true);
    }

    /**
     * Check include paths to show popup
     *
     * @return bool
     */
    public function checkIncludePaths()
    {
        $currentPath = $this->getRequest()->getRequestUri();
        $pathsConfig = $this->_helperData->getWhereToShowConfig('include_pages_with_url');

        if ($pathsConfig) {
            $arrayPaths = explode("\n", $pathsConfig);
            $pathsUrl = array_map('trim', $arrayPaths);
            foreach ($pathsUrl as $path) {
                if ($path && strpos($currentPath, $path) !== false) {
                    return true;
                }
            }
        }

        return false;
    }

    /**
     * Check Exclude page to hide popup
     *
     * @return bool
     */
    public function checkExcludePages()
    {
        $fullActionName = $this->getRequest()->getFullActionName();
        $arrayPages = explode("\n", $this->_helperData->getWhereToShowConfig('exclude_pages'));
        $includePages = array_map('trim', $arrayPages);

        return !in_array($fullActionName, $includePages, true);
    }

    /**
     * Check Exclude Paths to hide popup
     *
     * @return bool
     */
    public function checkExcludePaths()
    {
        $currentPath = $this->getRequest()->getRequestUri();
        $pathsConfig = $this->_helperData->getWhereToShowConfig('exclude_pages_with_url');

        if ($pathsConfig) {
            $arrayPaths = explode("\n", $pathsConfig);
            $pathsUrl = array_map('trim', $arrayPaths);

            foreach ($pathsUrl as $path) {
                if (strpos($currentPath, $path) !== false) {
                    return false;
                }
            }
        }

        return true;
    }

    /**
     * Check Include (page & path)
     *
     * @return bool
     */
    public function checkInclude()
    {
        return ($this->checkIncludePages() || $this->checkIncludePaths());
    }

    /**
     * Check Exclude (page & path)
     *
     * @return bool
     */
    public function checkExclude()
    {
        return ($this->checkExcludePages() && $this->checkExcludePaths());
    }

    /**
     * check Manually Insert Config
     *
     * @return bool
     */
    public function isManuallyInsert()
    {
        return $this->_helperData->isEnabled()
               && (int) $this->_helperData->getWhereToShowConfig('which_page_to_show') === PageToShow::MANUALLY_INSERT
               && $this->checkExclude();
    }

    /**
     * Check Pages to show popup
     *
     * @return bool
     */
    public function checkPagesToShow()
    {
        if ($this->_helperData->isEnabled()) {
            $config = $this->_helperData->getWhereToShowConfig('which_page_to_show');

            switch ($config) {
                case PageToShow::SPECIFIC_PAGES:
                    return ($this->checkInclude() && $this->checkExclude());
                case PageToShow::ALL_PAGES:
                    return $this->checkExclude();
                case PageToShow::MANUALLY_INSERT:
                    return false;
            }
        }

        return false;
    }

    /**
     * Get Ajax Data
     *
     * @return string
     */
    public function getAjaxData()
    {
        $params = [
            'url'               => $this->getUrl('betterpopup/ajax/success'),
            'isScroll'          => $this->getPopupAppear() === Appear::AFTER_SCROLL_DOWN,
            'afterSeconds'      => [
                'isAfterSeconds' => $this->getPopupAppear() === Appear::AFTER_X_SECONDS,
                'delay'          => $this->getDelayConfig()
            ],
            'percentage'        => $this->getPercentageScroll(),
            'fullScreen'        => [
                'isFullScreen' => $this->isFullScreen(),
                'bgColor'      => $this->getBackGroundColor()
            ],
            'isExitIntent'      => $this->isExitIntent(),
            'isShowFireworks'   => $this->isShowFireworks(),
            'popupConfig'       => [
                'width'       => $this->getWidthPopup(),
                'height'      => $this->getHeightPopup(),
                'cookieExp'   => $this->getCookieConfig(),
                'delay'       => $this->getDelayConfig(),
                'showOnDelay' => true,
            ],
            'srcCloseIconWhite' => $this->getViewFileUrl('Ktpl_BetterPopup::images/icon-close-white.png')
        ];

        return HelperData::jsonEncode($params);
    }

    /**
     * Get Url NewAction Newsletter
     *
     * @return string
     */
    public function getFormActionUrl()
    {
        return $this->getUrl('newsletter/subscriber/new', ['_secure' => true]);
    }
}

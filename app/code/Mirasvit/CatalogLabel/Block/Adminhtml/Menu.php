<?php
/**
 * Mirasvit
 *
 * This source file is subject to the Mirasvit Software License, which is available at https://mirasvit.com/license/.
 * Do not edit or add to this file if you wish to upgrade the to newer versions in the future.
 * If you wish to customize this module for your needs.
 * Please refer to http://www.magentocommerce.com for more information.
 *
 * @category  Mirasvit
 * @package   mirasvit/module-cataloglabel
 * @version   1.1.14
 * @copyright Copyright (C) 2019 Mirasvit (https://mirasvit.com/)
 */



namespace Mirasvit\CatalogLabel\Block\Adminhtml;

use Magento\Framework\DataObject;

class Menu extends \Magento\Backend\Block\Template
{
    /**
     * @var array
     */
    protected $items = [];

    /**
     * @var bool|DataObject
     */
    protected $activeItem = false;

    /**
     * Block template filename.
     *
     * @var string
     */
    protected $_template = 'Mirasvit_CatalogLabel::menu.phtml';//@codingStandardsIgnoreLine

    /**
     * @return $this
     *
     * @SuppressWarnings(PHPMD.CyclomaticComplexity)
     */
    protected function _prepareLayout()
    {
        $this->items = [
            new DataObject([
                'resource' => 'Mirasvit_CatalogLabel::cataloglabel_cataloglabel_labels',
                'title' => __('Manage Labels'),
                'url' => $this->_urlBuilder->getUrl('cataloglabel/label/index'),
                'url_pattern' => 'cataloglabel/label/',
            ]),
            new DataObject([
                'resource' => 'Mirasvit_CatalogLabel::cataloglabel_cataloglabel_placeholders',
                'title' => __('Manage Placeholders'),
                'url' => $this->_urlBuilder->getUrl('cataloglabel/placeholder/index'),
                'url_pattern' => 'cataloglabel/placeholder/',
            ]),
        ];
        $currentUrl = $this->_urlBuilder->getCurrentUrl();

        $auth = $this->getAuthorization();
        foreach ($this->items as $k => $item) {
            if (!is_object($item)) {
                continue;
            }
            if (!$auth->isAllowed($item->getResource())) {
                unset($this->items[$k]);
                continue;
            }
        }

        foreach ($this->items as $k => $item) {
            if (!is_object($item)) {
                continue;
            }

            if ($currentUrl == $item->getUrl() ||
                ($item->getUrlPattern() && strpos($currentUrl, $item->getUrlPattern()) !== false) ||
                ($item->getAlternativeUrls() && in_array($currentUrl, $item->getAlternativeUrls()))) {
                $item->setIsActive(true);
                $this->activeItem = $item;
                break;
            }
        }

        return parent::_prepareLayout();
    }

    /**
     * @return array
     */
    public function getItems()
    {
        return $this->items;
    }

    /**
     * @return string
     */
    public function getCurrentSelectionTitle()
    {
        return $this->activeItem->getTitle();
    }

    /**
     * @return string
     */
    protected function _toHtml()
    {
        if ($this->activeItem) {
            return parent::_toHtml();
        }

        return '';
    }
}

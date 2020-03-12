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



namespace Mirasvit\CatalogLabel\Block\Adminhtml\Label\Edit\Tab;

class Attribute extends \Magento\Backend\Block\Widget
{
    /**
     *
     */
    protected function _construct()
    {
        parent::_construct();
        $this->setTemplate('label/edit/tab/attribute.phtml');
    }

    /**
     * @return string
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    protected function _toHtml()
    {
        $accordion = $this->getLayout()->createBlock('\Magento\Backend\Block\Widget\Accordion')
            ->setId('attributeInfo');

        $accordion->addItem('attribute', [
            'title' => '<fieldset class="fieldset admin__fieldset fieldset-wide m__cataloglabel_gallery_fieldset" 
                            id="m_gallery_fieldset">
                            <legend class="admin__legend legend">' . __('Gallery') . '</legend>
                        </fieldset>',
            'content' => $this->getLayout()
                ->createBlock('\Mirasvit\CatalogLabel\Block\Adminhtml\Label\Edit\Tab\Attribute\Gallery')->toHtml(),
            'open' => true,
        ]);

        $this->setChild('accordion', $accordion);

        return parent::_toHtml();
    }

    /**
     * @return \Magento\Framework\Phrase
     */
    public function getTabLabel()
    {
        return __('Gallery');
    }

    /**
     * @return \Magento\Framework\Phrase
     */
    public function getTabTitle()
    {
        return __('Gallery');
    }

    /**
     * @return bool
     */
    public function canShowTab()
    {
        return true;
    }

    /**
     * @return bool
     */
    public function isHidden()
    {
        return false;
    }
}

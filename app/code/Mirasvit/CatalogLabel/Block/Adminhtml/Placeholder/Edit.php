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



namespace Mirasvit\CatalogLabel\Block\Adminhtml\Placeholder;

class Edit extends \Magento\Backend\Block\Widget\Form\Container
{
    /**
     * @var \Magento\Cms\Model\Wysiwyg\Config
     */
    protected $wysiwygConfig;

    /**
     * @var \Magento\Framework\Registry
     */
    protected $registry;

    /**
     * @var \Magento\Backend\Block\Widget\Context
     */
    protected $context;

    /**
     * @param \Magento\Cms\Model\Wysiwyg\Config     $wysiwygConfig
     * @param \Magento\Framework\Registry           $registry
     * @param \Magento\Backend\Block\Widget\Context $context
     * @param array                                 $data
     */
    public function __construct(
        \Magento\Cms\Model\Wysiwyg\Config $wysiwygConfig,
        \Magento\Framework\Registry $registry,
        \Magento\Backend\Block\Widget\Context $context,
        array $data = []
    ) {
        $this->wysiwygConfig = $wysiwygConfig;
        $this->registry = $registry;
        $this->context = $context;
        parent::__construct($context, $data);
    }

    /**
     *
     */
    protected function _construct()
    {
        parent::_construct();

        $this->_objectId = 'placeholder_id';
        $this->_blockGroup = 'Mirasvit_CatalogLabel';
        $this->_controller = 'adminhtml_placeholder';

        $this->buttonList->add('saveandcontinue', [
            'label' => __('Save And Continue Edit'),
            'onclick' => 'saveAndContinueEdit()',
            'class' => 'save',
        ], -100);

        $this->_formScripts[] = "
            function saveAndContinueEdit(){
                jQuery('#edit_form').attr('action', jQuery('#edit_form').attr('action') + 'back/edit/');
                jQuery('#edit_form').submit();
            }
        ";
    }

    /**
     * @return void
     */
    protected function _prepareLayout()
    {
        parent::_prepareLayout();

        if ($this->wysiwygConfig->isEnabled()) {
        }
    }

    /**
     * @return \Magento\Framework\Phrase
     */
    public function getHeaderText()
    {
        if ($this->registry->registry('current_model')->getId() > 0) {
            return __(
                "Edit Placeholder '%1'",
                $this->escapeHtml($this->registry->registry('current_model')->getName())
            );
        } else {
            return __('Add Placeholder');
        }
    }
}

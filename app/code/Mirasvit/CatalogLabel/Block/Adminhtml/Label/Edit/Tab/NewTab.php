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

class NewTab extends \Magento\Backend\Block\Widget\Form
{
    /**
     * @var \Mirasvit\CatalogLabel\Model\ResourceModel\Placeholder\CollectionFactory
     */
    protected $placeholderCollectionFactory;

    /**
     * @var \Mirasvit\CatalogLabel\Model\System\Config\Source\LabelType
     */
    protected $systemConfigSourceLabelType;

    /**
     * @var \Magento\Framework\Data\FormFactory
     */
    protected $formFactory;

    /**
     * @var \Magento\Framework\Registry
     */
    protected $registry;

    /**
     * @var \Magento\Backend\Block\Widget\Context
     */
    protected $context;

    /**
     * @param \Mirasvit\CatalogLabel\Model\ResourceModel\Placeholder\CollectionFactory $placeholderCollectionFactory
     * @param \Mirasvit\CatalogLabel\Model\System\Config\Source\LabelType              $systemConfigSourceLabelType
     * @param \Magento\Framework\Data\FormFactory                                     $formFactory
     * @param \Magento\Framework\Registry                                             $registry
     * @param \Magento\Backend\Block\Widget\Context                                   $context
     * @param array                                                                   $data
     */
    public function __construct(
        \Mirasvit\CatalogLabel\Model\ResourceModel\Placeholder\CollectionFactory $placeholderCollectionFactory,
        \Mirasvit\CatalogLabel\Model\System\Config\Source\LabelType $systemConfigSourceLabelType,
        \Magento\Framework\Data\FormFactory $formFactory,
        \Magento\Framework\Registry $registry,
        \Magento\Backend\Block\Widget\Context $context,
        array $data = []
    ) {
        $this->placeholderCollectionFactory = $placeholderCollectionFactory;
        $this->systemConfigSourceLabelType = $systemConfigSourceLabelType;
        $this->formFactory = $formFactory;
        $this->registry = $registry;
        $this->context = $context;
        parent::__construct($context, $data);
    }

    /**
     * @return $this
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    protected function _prepareLayout()
    {
        $this->setChild('continue_button',
            $this->getLayout()->createBlock('\Magento\Backend\Block\Widget\Button')
                ->setData([
                    'label' => __('Continue'),
                    'onclick' => 'saveAndContinueEdit()',
                    'class' => 'save',
                    ])
                );

        return parent::_prepareLayout();
    }

    /**
     * @return $this
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    protected function _prepareForm()
    {
        $model = $this->registry->registry('current_model');

        $form = $this->formFactory->create();
        $form->setFieldNameSuffix('label');
        $this->setForm($form);

        $fieldset = $form->addFieldset('placeholder_form', ['legend' => __('General Information')]);

        $fieldset->addField('name', 'text', [
            'label' => __('Title'),
            'required' => true,
            'name' => 'name',
            'value' => $model->getName(),
        ]);

        $fieldset->addField('placeholder_id', 'select', [
            'label' => __('Placeholder'),
            'required' => true,
            'name' => 'placeholder_id',
            'value' => $model->getPlaceholderId(),
            'values' => $this->placeholderCollectionFactory->create()->toOptionArray(),
        ]);

        $fieldset->addField('type', 'select', [
            'label' => __('Relation Type'),
            'name' => 'type',
            'values' => $this->systemConfigSourceLabelType->toOptionArray(),
            'value' => $model->getType(),
        ]);

        $fieldset->addField('continue_button', 'note', [
            'text' => $this->getChildHtml('continue_button'),
        ]);

        return parent::_prepareForm();
    }
}

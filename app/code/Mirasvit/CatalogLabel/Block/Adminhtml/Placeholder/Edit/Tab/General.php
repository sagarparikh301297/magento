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



namespace Mirasvit\CatalogLabel\Block\Adminhtml\Placeholder\Edit\Tab;

class General extends \Magento\Backend\Block\Widget\Form
{
    /**
     * @var \Mirasvit\CatalogLabel\Model\System\Config\Source\ImageType
     */
    protected $systemConfigSourceImageType;

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
     * @param \Mirasvit\CatalogLabel\Model\System\Config\Source\ImageType  $systemConfigSourceImageType
     * @param \Mirasvit\CatalogLabel\Model\System\Config\Source\RenderMode $systemConfigSourceRenderMode
     * @param \Magento\Framework\Data\FormFactory                          $formFactory
     * @param \Magento\Framework\Registry                                  $registry
     * @param \Magento\Backend\Block\Widget\Context                        $context
     * @param array                                                        $data
     */
    public function __construct(
        \Mirasvit\CatalogLabel\Model\System\Config\Source\ImageType $systemConfigSourceImageType,
        \Mirasvit\CatalogLabel\Model\System\Config\Source\RenderMode $systemConfigSourceRenderMode,
        \Magento\Framework\Data\FormFactory $formFactory,
        \Magento\Framework\Registry $registry,
        \Magento\Backend\Block\Widget\Context $context,
        array $data = []
    ) {
        $this->systemConfigSourceImageType = $systemConfigSourceImageType;
        $this->systemConfigSourceRenderMode = $systemConfigSourceRenderMode;
        $this->formFactory = $formFactory;
        $this->registry = $registry;
        $this->context = $context;
        parent::__construct($context, $data);
    }

    /**
     * @return $this
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    protected function _prepareForm()
    {
        $model = $this->registry->registry('current_model');

        $form = $this->formFactory->create();
        $this->setForm($form);

        $general = $form->addFieldset('placeholder_form', ['legend' => __('General Information')]);

        $general->addField('name', 'text', [
            'label' => __('Title'),
            'required' => true,
            'name' => 'name',
            'value' => $model->getName(),
        ]);

        $general->addField('code', 'text', [
            'label' => __('Identifier'),
            'required' => true,
            'name' => 'code',
            'value' => $model->getCode(),
            'class' => 'validate-xml-identifier',
        ]);

        $general->addField('is_active', 'select', [
            'label' => __('Is Active'),
            'name' => 'is_active',
            'values' => [0 => __('No'), 1 => __('Yes')],
            'value' => $model->getIsActive(),
        ]);

        $general->addField('is_auto_for_list', 'select', [
            'label'  => __('Add label in the product list page'),
            'name'   => 'is_auto_for_list',
            'values' => $this->systemConfigSourceRenderMode->toOptionArray(),
            'value'  => $model->getIsAutoForList(),
        ]);

        $general->addField('is_auto_for_view', 'select', [
            'label'  => __('Add label in the product page'),
            'name'   => 'is_auto_for_view',
            'values' => $this->systemConfigSourceRenderMode->toOptionArray(),
            'value'  => $model->getIsAutoForView(),
        ]);

        $general->addField('is_positioned', 'select', [
            'label' => __('Allow Positioning'),
            'name' => 'is_positioned',
            'required' => true,
            'values' => [0 => __('No'), 1 => __('Yes')],
            'value' => $model->getIsPositioned(),
        ]);

        $general->addField('image_type', 'multiselect', [
            'label' => __('Allowed Images'),
            'name' => 'image_type',
            'required' => true,
            'values' => $this->systemConfigSourceImageType->toOptionArray(),
            'value' => $model->getImageType(),
        ]);

        return parent::_prepareForm();
    }
}

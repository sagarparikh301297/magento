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



namespace Mirasvit\CatalogLabel\Block\Adminhtml\Label\Edit\Tab\Rule;

class Design extends \Magento\Backend\Block\Widget\Form
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
     * @var array
     */
    protected $positions = [
        'TL' => 'Top Left',
        'TC' => 'Top Center',
        'TR' => 'Top Right',
        'ML' => 'Middle Left',
        'MC' => 'Middle Center',
        'MR' => 'Middle Right',
        'BL' => 'Bottom Left',
        'BC' => 'Bottom Center',
        'BR' => 'Botton Right'
    ];

    /**
     * @param \Mirasvit\CatalogLabel\Model\System\Config\Source\ImageType $systemConfigSourceImageType
     * @param \Magento\Framework\Data\FormFactory                        $formFactory
     * @param \Magento\Framework\Registry                                $registry
     * @param \Magento\Backend\Block\Widget\Context                      $context
     * @param array                                                      $data
     */
    public function __construct(
        \Mirasvit\CatalogLabel\Model\System\Config\Source\ImageType $systemConfigSourceImageType,
        \Magento\Framework\Data\FormFactory $formFactory,
        \Magento\Framework\Registry $registry,
        \Magento\Backend\Block\Widget\Context $context,
        array $data = []
    ) {
        $this->systemConfigSourceImageType = $systemConfigSourceImageType;
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
        $rule = $model->getRule();
        $display = $rule->getDisplay();
        $placeholder = $model->getPlaceholder();

        $form = $this->formFactory->create();
        $this->setForm($form);

        foreach ($placeholder->getImageType() as $type) {
            $label = $this->systemConfigSourceImageType->getLabel($type);
            $design = $form->addFieldset('fieldset_'.$type, ['legend' => __($label)]);

            $design->addField('image'.$type, 'image', [
                'label' => __('Image'),
                'required' => false,
                'name' => 'display_'.$type.'_image',
                'value' => $display->getImageUrl($type),
            ]);

            if ($placeholder->getIsPositioned()) {
                $design->addField('position'.$type, 'select', [
                    'label' => __('Position'),
                    'name' => 'rule[display]['.$type.'_position]',
                    'required' => false,
                    'values' => $this->positions,
                    'value' => $display->getPosition($type),
                ]);
            }

            $design->addField('style'.$type, 'textarea', [
                'label' => __('Style'),
                'required' => false,
                'name' => 'rule[display]['.$type.'_style]',
                'value' => $display->getStyle($type),
            ]);

            $design->addField('title'.$type, 'text', [
                'label' => __('Title'),
                'required' => false,
                'name' => 'rule[display]['.$type.'_title]',
                'value' => $display->getTitle($type),
            ]);

            $design->addField('description'.$type, 'textarea', [
                'label' => __('Description'),
                'required' => false,
                'name' => 'rule[display]['.$type.'_description]',
                'value' => $display->getDescription($type),
            ]);

            $design->addField('url'.$type, 'text', [
                'label' => __('Url'),
                'required' => false,
                'name' => 'rule[display]['.$type.'_url]',
                'value' => $display->getUrl($type),
            ]);
        }

        return parent::_prepareForm();
    }
}

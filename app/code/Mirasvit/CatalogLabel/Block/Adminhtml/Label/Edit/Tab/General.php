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

class General extends \Magento\Backend\Block\Widget\Form
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
     * @var \Mirasvit\CatalogLabel\Model\System\Config\Source\Attribute
     */
    protected $systemConfigSourceAttribute;

    /**
     * @var \Magento\Store\Model\System\Store
     */
    protected $systemStore;

    /**
     * @var \Magento\Customer\Model\Group
     */
    protected $group;

    /**
     * @var \Magento\Framework\Data\FormFactory
     */
    protected $formFactory;

    /**
     * @var \Magento\Framework\Stdlib\DateTime\DateTime
     */
    protected $date;

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
     * @param \Mirasvit\CatalogLabel\Model\System\Config\Source\Attribute              $systemConfigSourceAttribute
     * @param \Magento\Store\Model\System\Store                                        $systemStore
     * @param \Magento\Customer\Model\Group                                            $group
     * @param \Magento\Framework\Data\FormFactory                                      $formFactory
     * @param \Magento\Framework\Stdlib\DateTime\DateTime                              $date
     * @param \Magento\Framework\Registry                                              $registry
     * @param \Magento\Backend\Block\Widget\Context                                    $context
     * @param array                                                                    $data
     *
     * @SuppressWarnings(PHPMD.ExcessiveParameterList)
     */
    public function __construct(
        \Mirasvit\CatalogLabel\Model\ResourceModel\Placeholder\CollectionFactory $placeholderCollectionFactory,
        \Mirasvit\CatalogLabel\Model\System\Config\Source\LabelType $systemConfigSourceLabelType,
        \Mirasvit\CatalogLabel\Model\System\Config\Source\Attribute $systemConfigSourceAttribute,
        \Magento\Store\Model\System\Store $systemStore,
        \Magento\Customer\Model\Group $group,
        \Magento\Framework\Data\FormFactory $formFactory,
        \Magento\Framework\Stdlib\DateTime\DateTime $date,
        \Magento\Framework\Registry $registry,
        \Magento\Backend\Block\Widget\Context $context,
        array $data = []
    ) {
        $this->placeholderCollectionFactory = $placeholderCollectionFactory;
        $this->systemConfigSourceLabelType = $systemConfigSourceLabelType;
        $this->systemConfigSourceAttribute = $systemConfigSourceAttribute;
        $this->systemStore = $systemStore;
        $this->group = $group;
        $this->formFactory = $formFactory;
        $this->date = $date;
        $this->registry = $registry;
        $this->context = $context;
        parent::__construct($context, $data);
    }

    /**
     * @return $this
     * @throws \Magento\Framework\Exception\LocalizedException
     *
     * @SuppressWarnings(PHPMD.ExcessiveMethodLength)
     * @SuppressWarnings(PHPMD.NPathComplexity)
     */
    protected function _prepareForm()
    {
        $model = $this->registry->registry('current_model');

        $form = $this->formFactory->create();
        $form->setFieldNameSuffix('label');
        $this->setForm($form);

        $general = $form->addFieldset('label_form', ['legend' => __('General Information')]);

        $general->addField('name', 'text', [
            'label' => __('Title'),
            'required' => true,
            'name' => 'name',
            'value' => $model->getName(),
        ]);

        $general->addField('placeholder_id', 'select', [
            'label' => __('Placeholder'),
            'required' => true,
            'name' => 'placeholder_id',
            'value' => $model->getPlaceholderId(),
            'values' => $this->placeholderCollectionFactory->create()->toOptionArray(),
        ]);

        $general->addField('type', 'select', [
            'label' => __('Type'),
            'name' => 'type',
            'values' => $this->systemConfigSourceLabelType->toOptionArray(),
            'value' => $model->getType(),
            'disabled' => true,
        ]);

        $general->addField('is_active', 'select', [
            'label' => __('Is Active'),
            'name' => 'is_active',
            'values' => [0 => __('No'), 1 => __('Yes')],
            'value' => $model->getIsActive(),
        ]);

        if ($model->getType() == \Mirasvit\CatalogLabel\Model\Label::TYPE_ATTRIBUTE) {
            $general->addField('attribute_id', 'select', [
                'label' => __('Attribute'),
                'required' => true,
                'name' => 'attribute_id',
                'required' => true,
                'value' => $model->getAttributeId(),
                'values' => $this->systemConfigSourceAttribute->toOptionArray(),
                'disabled' => $model->getAttributeId() ? true : false,
            ]);
        }

        if ($model->getActiveFrom() == '0000-00-00 00:00:00' || $model->getActiveFrom() == null) {
            $activeFromDate = null;
        } else {
            $activeFromDate = $this->date->date(null, strtotime($model->getActiveFrom()));
        }

        $dateFormatIso = $this->context->getLocaleDate()->getDateFormat(\IntlDateFormatter::MEDIUM);
        $general->addField('active_from', 'date', [
            'label' => __('Active From'),
            'required' => false,
            'name' => 'active_from',
            'value' => $activeFromDate,
            'image' => $this->getSkinUrl('images/grid-cal.gif'),
            'format' => $dateFormatIso,
        ]);

        if ($model->getActiveTo() == '0000-00-00 00:00:00' || $model->getActiveTo() == null) {
            $activeToDate = null;
        } else {
            $activeToDate = $this->date->date(null, strtotime($model->getActiveTo()));
        }

        $general->addField('active_to', 'date', [
            'label' => __('Active To'),
            'required' => false,
            'name' => 'active_to',
            'value' => $activeToDate,
            'image' => $this->getSkinUrl('images/grid-cal.gif'),
            'format' => $dateFormatIso,
        ]);

        if (!$this->context->getStoreManager()->isSingleStoreMode()) {
            $general->addField('stores', 'multiselect', [
                'label' => __('Visible In'),
                'required' => true,
                'name' => 'stores[]',
                'values' => $this->systemStore->getStoreValuesForForm(),
                'value' => $model->getStoreId(),
            ]);
        } else {
            $general->addField('stores', 'hidden', [
                'name' => 'stores[]',
                'value' => $this->context->getStoreManager()->getStore(true)->getId(),
            ]);
        }

        $general->addField('customer_group_ids', 'multiselect', [
            'label' => __('Visible for Customer Groups'),
            'required' => true,
            'name' => 'customer_group_ids[]',
            'values' => $this->group->getCollection()->toOptionArray(),
            'value' => $model->getCustomerGroupIds(),
        ]);

        if ($model->getType() == 'rule') {
            $general->addField('sort_order', 'text', [
                'label'    => __('Sort order'),
                'required' => false,
                'name'     => 'sort_order',
                'value'    => $model->getSortOrder(),
                'note'     => __('It will be applied only if more than one labels are in the same position.'),
            ]);
        }

        return parent::_prepareForm();
    }
}

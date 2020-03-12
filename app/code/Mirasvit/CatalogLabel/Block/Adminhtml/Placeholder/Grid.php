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

class Grid extends \Magento\Backend\Block\Widget\Grid\Extended
{
    /**
     * @var \Mirasvit\CatalogLabel\Model\ResourceModel\Placeholder\CollectionFactory
     */
    protected $placeholderCollectionFactory;

    /**
     * @var \Magento\Backend\Block\Widget\Context
     */
    protected $context;

    /**
     * @var \Magento\Backend\Helper\Data
     */
    protected $backendHelper;

    /**
     * @param \Mirasvit\CatalogLabel\Model\ResourceModel\Placeholder\CollectionFactory $placeholderCollectionFactory
     * @param \Magento\Backend\Block\Widget\Context                                   $context
     * @param \Magento\Backend\Helper\Data                                            $backendHelper
     * @param array                                                                   $data
     */
    public function __construct(
        \Mirasvit\CatalogLabel\Model\ResourceModel\Placeholder\CollectionFactory $placeholderCollectionFactory,
        \Magento\Backend\Block\Widget\Context $context,
        \Magento\Backend\Helper\Data $backendHelper,
        array $data = []
    ) {
        $this->placeholderCollectionFactory = $placeholderCollectionFactory;
        $this->context = $context;
        $this->backendHelper = $backendHelper;
        parent::__construct($context, $backendHelper, $data);
    }

    /**
     * @return $this
     */
    protected function _construct()
    {
        parent::_construct();

        $this->setId('cataloglabelPlaceholderGrid');
        $this->setDefaultSort('placeholder_id');
        $this->setDefaultDir('desc');
        $this->setSaveParametersInSession(true);

        return $this;
    }

    /**
     * @return $this
     */
    protected function _prepareCollection()
    {
        $collection = $this->placeholderCollectionFactory->create();
        $this->setCollection($collection);

        return parent::_prepareCollection();
    }

    /**
     * @return $this
     * @throws \Exception
     */
    protected function _prepareColumns()
    {
        $this->addColumn('placeholder_id', [
            'header' => __('ID'),
            'align' => 'right',
            'width' => '50px',
            'index' => 'placeholder_id',
        ]);

        $this->addColumn('name', [
            'header' => __('Name'),
            'align' => 'left',
            'index' => 'name',
        ]);

        $this->addColumn('code', [
            'header' => __('Identifier'),
            'align' => 'left',
            'index' => 'code',
        ]);

        $this->addColumn('is_active', [
            'header' => __('Status'),
            'align' => 'left',
            'width' => '80px',
            'index' => 'is_active',
            'type' => 'options',
            'options' => [
                1 => __('Enabled'),
                0 => __('Disabled'),
            ],
        ]);

        $this->addColumn('action',
            [
                'header' => __('Action'),
                'width' => '100',
                'type' => 'action',
                'getter' => 'getId',
                'actions' => [
                    [
                        'caption' => __('Edit'),
                        'url' => ['base' => '*/*/edit'],
                        'field' => 'id',
                    ],
                    [
                        'caption' => __('Delete'),
                        'url' => ['base' => '*/*/delete'],
                        'field' => 'id',
                    ],
                ],
                'filter' => false,
                'sortable' => false,
                'index' => 'stores',
                'is_system' => true,
            ]
        );

        return parent::_prepareColumns();
    }

    /**
     * @return $this
     */
    protected function _prepareMassaction()
    {
        $this->setMassactionIdField('placeholder_id');
        $this->getMassactionBlock()->setFormFieldName('placeholder');

        $this->getMassactionBlock()->addItem('delete', [
            'label' => __('Delete'),
            'url' => $this->getUrl('*/*/massDelete'),
            'confirm' => __('Are you sure?'),
        ]);

        $statuses = [
            1 => __('Enabled'),
            0 => __('Disabled'),
        ];

        array_unshift($statuses, ['label' => '', 'value' => '']);

        $this->getMassactionBlock()->addItem('status', [
            'label' => __('Change status'),
            'url' => $this->getUrl('*/*/massStatus', ['_current' => true]),
            'additional' => [
            'visibility' => [
                    'name' => 'status',
                    'type' => 'select',
                    'class' => 'required-entry',
                    'label' => __('Status'),
                    'values' => $statuses,
                ],
            ],
        ]);

        return $this;
    }

    /**
     * @param \Magento\Catalog\Model\Product|\Magento\Framework\DataObject $row
     * @return string
     */
    public function getRowUrl($row)
    {
        return $this->getUrl('*/*/edit', ['id' => $row->getId()]);
    }
}

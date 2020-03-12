<?php


namespace SimpleMagento\CustomReport\Model\ResourceModel\Order\Grid;

use Magento\Framework\Data\Collection\Db\FetchStrategyInterface as FetchStrategy;
use Magento\Framework\Data\Collection\EntityFactoryInterface as EntityFactory;
use Magento\Framework\Event\ManagerInterface as EventManager;
use Psr\Log\LoggerInterface as Logger;

class Collection extends \Magento\Sales\Model\ResourceModel\Order\Grid\Collection
{
    /**
     * Initialize dependencies.
     *
     * @param EntityFactory $entityFactory
     * @param Logger $logger
     * @param FetchStrategy $fetchStrategy
     * @param EventManager $eventManager
     * @param string $mainTable
     * @param string $resourceModel
     */
    public function __construct(
        EntityFactory $entityFactory,
        Logger $logger,
        FetchStrategy $fetchStrategy,
        EventManager $eventManager,
        $mainTable = 'sales_order',
        $resourceModel = \Magento\Sales\Model\ResourceModel\Order::class
    )
    {
        parent::__construct($entityFactory, $logger, $fetchStrategy, $eventManager, $mainTable, $resourceModel);
    }
    /**
     * @inheritdoc
     */
    protected function _initSelect()
    {
        parent::_initSelect();

        $objectManager = \Magento\Framework\App\ObjectManager::getInstance();
        $customerSession = $objectManager->create('Magento\Customer\Model\Session');
        $getFilter = $customerSession->getData();

        $fromDate = date('Y-m-d H:i:s', strtotime($getFilter['start_date']));
        $toDate = date('Y-m-d', strtotime($getFilter['end_date']));

        $this->getSelect()->joinInner(
            ['secondTable' => $this->getTable('sales_order_address')],
            "main_table.entity_id = secondTable.parent_id"
        );

        $this->getSelect()->columns('COUNT(*) AS countCategory')
            ->columns('SUM(base_grand_total) as total')
            ->columns('SUM(subtotal) as subtotal')
            ->columns('SUM(base_tax_amount) as tax')
            ->columns('SUM(base_shipping_amount) as shipping')
            ->columns('SUM(base_discount_amount) as discount')
            ->group('country_id');
        $this->addFilterToMap('country_id', 'country_id');

        if($getFilter['date_used'] == 0){
            $this->addFieldToFilter('created_at', ['from' => $fromDate]);
            $this->addFieldToFilter('created_at', ['to' => $toDate. '23:59:59']);
            $this->getSelect()->columns('created_at as date');
        }
        else{
            $this->addFieldToFilter('updated_at', ['from' => $fromDate]);
            $this->addFieldToFilter('updated_at', ['to' => $toDate. '23:59:59']);
            $this->getSelect()->columns('updated_at as date');
        }
        if($getFilter['status'] == 1){
            $this->addFieldToFilter('status',array('in' => $getFilter['multiselect_example']));
        }

        return $this;
    }
}
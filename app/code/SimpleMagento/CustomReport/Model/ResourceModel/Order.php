<?php


namespace SimpleMagento\CustomReport\Model\ResourceModel;

use Magento\Framework\App\ResourceConnection as AppResource;
use Magento\Framework\Math\Random;
use Magento\SalesSequence\Model\Manager;
use Magento\Sales\Model\ResourceModel\EntityAbstract as SalesResource;
use Magento\Sales\Model\ResourceModel\Order\Handler\State as StateHandler;
use Magento\Sales\Model\Spi\OrderResourceInterface;
use Magento\Framework\Model\ResourceModel\Db\VersionControl\Snapshot;
use Magento\Framework\Model\ResourceModel\Db\VersionControl\RelationComposite;
use Magento\Sales\Model\ResourceModel\Attribute;

class Order extends SalesResource implements OrderResourceInterface
{
    protected function _construct()
    {
        $this->_init('sales_order', 'entity_id');
    }

}
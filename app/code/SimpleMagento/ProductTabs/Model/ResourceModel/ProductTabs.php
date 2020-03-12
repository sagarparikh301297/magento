<?php


namespace SimpleMagento\ProductTabs\Model\ResourceModel;

use Magento\Framework\Model\ResourceModel\Db\AbstractDb;

class ProductTabs extends AbstractDb
{

    /**
     * Resource initialization
     *
     * @return void
     */
    protected function _construct()
    {
        $this->_init('custom_customer_price', 'custom_entity_id');
    }
}
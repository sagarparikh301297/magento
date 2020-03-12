<?php


namespace SimpleMagento\ProductTabs\Model\ResourceModel\ProductTabs;

use Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection;

class Collection extends AbstractCollection
{
    protected function _construct()
    {
        $this->_init('SimpleMagento\ProductTabs\Model\ProductTabs', 'SimpleMagento\ProductTabs\Model\ResourceModel\ProductTabs');
    }
}
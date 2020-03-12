<?php


namespace SimpleMagento\ProductTabs\Model;

use Magento\Framework\Model\AbstractModel;

class ProductTabs extends AbstractModel
{
    protected function _construct() {
        $this->_init('SimpleMagento\ProductTabs\Model\ResourceModel\ProductTabs');
    }
}
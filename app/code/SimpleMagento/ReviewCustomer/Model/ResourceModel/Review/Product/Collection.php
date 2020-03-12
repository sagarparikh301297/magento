<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace SimpleMagento\ReviewCustomer\Model\ResourceModel\Review\Product;

use Magento\Catalog\Model\ResourceModel\Product\Collection\ProductLimitationFactory;
use Magento\Eav\Model\Entity\Attribute\AbstractAttribute;
use Magento\Framework\DB\Select;
use Magento\Framework\EntityManager\MetadataPool;


class Collection extends \Magento\Review\Model\ResourceModel\Review\Product\Collection
{
    /**
     * Join fields to entity
     *
     * @return \Magento\Review\Model\ResourceModel\Review\Product\Collection
     */
    protected function _joinFields()
    {
        $reviewTable = $this->_resource->getTableName('review');
        $reviewDetailTable = $this->_resource->getTableName('review_detail');

        $this->addAttributeToSelect('name')->addAttributeToSelect('sku');

        $this->getSelect()->join(
            ['rt' => $reviewTable],
            'rt.entity_pk_value = e.entity_id',
            ['rt.review_id', 'review_created_at' => 'rt.created_at', 'rt.entity_pk_value', 'rt.status_id']
        )->join(
            ['rdt' => $reviewDetailTable],
            'rdt.review_id = rt.review_id',
            ['rdt.title', 'rdt.nickname', 'rdt.detail', 'rdt.refer', 'rdt.customer_id', 'rdt.store_id']
        );
        return $this;
    }
}

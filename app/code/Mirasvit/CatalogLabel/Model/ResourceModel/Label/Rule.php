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



namespace Mirasvit\CatalogLabel\Model\ResourceModel\Label;

class Rule extends \Magento\Framework\Model\ResourceModel\Db\AbstractDb
{
    /**
     * @var \Magento\Framework\Model\ResourceModel\Db\Context
     */
    protected $context;

    /**
     * @var string
     */
    protected $resourcePrefix;

    /**
     * @param \Magento\Framework\Model\ResourceModel\Db\Context $context
     * @param string|null                                       $resourcePrefix
     */
    public function __construct(
        \Magento\Framework\Model\ResourceModel\Db\Context $context,
        $resourcePrefix = null
    ) {
        $this->context = $context;
        $this->resourcePrefix = $resourcePrefix;
        parent::__construct($context, $resourcePrefix);
    }

    /**
     * @return void
     */
    protected function _construct()
    {
        $this->_init('mst_cataloglabel_label_rule', 'rule_id');
    }

    /**
     * @param \Magento\Framework\Model\AbstractModel $object
     * @return void
     */
    public function _beforeSave(\Magento\Framework\Model\AbstractModel $object)
    {
        $this->_saveDisplay($object);

        parent::_beforeSave($object);
    }

    /**
     * @param \Magento\Framework\Model\AbstractModel $object
     * @return $this
     */
    protected function _afterSave(\Magento\Framework\Model\AbstractModel $object)
    {
        if (!$object->getIsMassStatus()) {
        }

        return parent::_afterSave($object);
    }

    /**
     * @param \Magento\Framework\Model\AbstractModel $object
     * @return $this
     */
    protected function _saveDisplay(\Magento\Framework\Model\AbstractModel $object)
    {
        $displayData = $object->getData('display');
        $display = $object->getDisplay();
        $display->addData($displayData);
        $display->save();

        $object->setDisplayId($display->getId());

        return $this;
    }

    /**
     * @param \Mirasvit\CatalogLabel\Model\Label\Rule $rule
     * @return $this
     * @throws \Exception
     */
    public function updateRuleProductData($rule)
    {
        $ruleId = $rule->getId();
        $write = $this->getConnection();

        $write->beginTransaction();
        $write->delete(
            $this->getTable('mst_cataloglabel_label_rule_product'), $write->quoteInto('rule_id = ?', $ruleId)
        );

        $productIds = $rule->getMatchingProductIds();

        $rows = [];
        $queryStart = 'INSERT INTO '.$this->getTable('mst_cataloglabel_label_rule_product').
            ' (rule_id, product_id) VALUES ';
        $queryEnd = ' ON DUPLICATE KEY UPDATE product_id=VALUES(product_id)';

        try {
            foreach ($productIds as $productId) {
                $rows[] = "('".implode("','", [$ruleId, $productId])."')";

                if (sizeof($rows) == 1000) {
                    $sql = $queryStart.implode(',', $rows).$queryEnd;
                    $write->query($sql);
                    $rows = [];
                }
            }

            if (!empty($rows)) {
                $sql = $queryStart.implode(',', $rows).$queryEnd;
                $write->query($sql);
            }

            $write->commit();
        } catch (\Exception $e) {
            $write->rollback();
            throw $e;
        }

        return $this;
    }

    /**
     * @param int $ruleId
     * @return array
     */
    public function getRuleProductIds($ruleId)
    {
        $read = $this->getConnection();
        $select = $read->select()->from($this->getTable('mst_cataloglabel_label_rule_product'), 'product_id')
            ->where('rule_id=?', $ruleId);

        return $read->fetchCol($select);
    }

    /**
     * @param int $ruleId
     * @param int $productId
     * @return int
     */
    public function isRuleProductId($ruleId, $productId)
    {
        $read = $this->getConnection();
        $select = $read->select()->from($this->getTable('mst_cataloglabel_label_rule_product'), 'product_id')
            ->where('rule_id=?', $ruleId)
            ->where('product_id=?', $productId);

        return count($read->fetchCol($select));
    }
}

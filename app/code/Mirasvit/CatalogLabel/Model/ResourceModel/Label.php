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



namespace Mirasvit\CatalogLabel\Model\ResourceModel;

class Label extends \Magento\Framework\Model\ResourceModel\Db\AbstractDb
{
    /**
     * @var \Mirasvit\CatalogLabel\Model\Label\AttributeFactory
     */
    protected $labelAttributeFactory;

    /**
     * @var \Magento\Framework\Model\ResourceModel\Db\Context
     */
    protected $context;

    /**
     * @var string
     */
    protected $resourcePrefix;

    /**
     * @param \Mirasvit\CatalogLabel\Api\Service\CompatibilityServiceInterface $compatibilityService
     * @param \Mirasvit\CatalogLabel\Model\Label\AttributeFactory $labelAttributeFactory
     * @param \Magento\Framework\Model\ResourceModel\Db\Context  $context
     * @param string $resourcePrefix
     */
    public function __construct(
        \Mirasvit\CatalogLabel\Api\Service\CompatibilityServiceInterface $compatibilityService,
        \Mirasvit\CatalogLabel\Model\Label\AttributeFactory $labelAttributeFactory,
        \Magento\Framework\Model\ResourceModel\Db\Context $context,
        $resourcePrefix = null
    ) {
        $this->compatibilityService = $compatibilityService;
        $this->labelAttributeFactory = $labelAttributeFactory;
        $this->context = $context;
        $this->resourcePrefix = $resourcePrefix;
        parent::__construct($context, $resourcePrefix);
    }

    /**
     * @return void
     */
    protected function _construct()
    {
        $this->_init('mst_cataloglabel_label', 'label_id');
    }

    /**
     * @param \Magento\Framework\Model\AbstractModel $object
     * @return $this
     */
    protected function _afterLoad(\Magento\Framework\Model\AbstractModel $object)
    {
        $object = $this->_loadStore($object);
        $object = $this->_loadCustomerGroup($object);

        return parent::_afterLoad($object);
    }

    /**
     * @param \Magento\Framework\Model\AbstractModel $object
     * @return \Magento\Framework\Model\AbstractModel
     */
    protected function _loadStore(\Magento\Framework\Model\AbstractModel $object)
    {
        $select = $this->getConnection()->select()
            ->from($this->getTable('mst_cataloglabel_label_store'))
            ->where('label_id = ?', $object->getId());

        if ($data = $this->getConnection()->fetchAll($select)) {
            $array = [];
            foreach ($data as $row) {
                $array[] = $row['store_id'];
            }
            $object->setData('store_id', $array);
        }

        return $object;
    }

    /**
     * @param \Magento\Framework\Model\AbstractModel $object
     * @return \Magento\Framework\Model\AbstractModel
     */
    protected function _loadCustomerGroup(\Magento\Framework\Model\AbstractModel $object)
    {
        $select = $this->getConnection()->select()
            ->from($this->getTable('mst_cataloglabel_label_customer_group'))
            ->where('label_id = ?', $object->getId());

        if ($data = $this->getConnection()->fetchAll($select)) {
            $array = [];
            foreach ($data as $row) {
                $array[] = $row['customer_group_id'];
            }
            $object->setData('customer_group_ids', $array);
        }

        return $object;
    }

    /**
     * @param \Magento\Framework\Model\AbstractModel $object
     * @return $this
     */
    protected function _beforeSave(\Magento\Framework\Model\AbstractModel $object)
    {
        if ($object->isObjectNew() && !$object->hasCreatedAt()) {
            $object->setCreatedAt((new \DateTime())->format(\Magento\Framework\Stdlib\DateTime::DATETIME_PHP_FORMAT));
        }

        $object->setUpdatedAt((new \DateTime())->format(\Magento\Framework\Stdlib\DateTime::DATETIME_PHP_FORMAT));

        return parent::_beforeSave($object);
    }

    /**
     * @param \Magento\Framework\Model\AbstractModel $object
     * @return $this
     */
    protected function _afterSave(\Magento\Framework\Model\AbstractModel $object)
    {
        if (!$object->getIsMassStatus()) {
            switch ($object->getType()) {
                case  \Mirasvit\CatalogLabel\Model\Label::TYPE_ATTRIBUTE:
                    $this->_saveAttribute($object);
                    break;
                case \Mirasvit\CatalogLabel\Model\Label::TYPE_RULE:
                    $this->_saveRule($object);
                    break;
            }

            $this->_saveStore($object);
            $this->_saveCustomerGroup($object);
        }

        return parent::_afterSave($object);
    }

    /**
     * @param \Magento\Framework\Model\AbstractModel $object
     * @return void
     */
    protected function _saveAttribute(\Magento\Framework\Model\AbstractModel $object)
    {
        if ($object->getData('attribute') && is_array($object->getData('attribute'))) {
            foreach ($object->getData('attribute') as $item) {
                $model = $this->labelAttributeFactory->create()->loadByKey($object->getId(), $item['option_id']);
                $model->setLabelId($object->getId())
                    ->addData($item)
                    ->save();
            }
        }
    }

    /**
     * @param \Magento\Framework\Model\AbstractModel $object
     * @return void
     */
    protected function _saveRule(\Magento\Framework\Model\AbstractModel $object)
    {
        if ($object->getData('rule') && is_array($object->getData('rule'))) {
            $ruleData = $object->getData('rule');

            $model = $object->getRule();

            $data = $this->prepareCompatibility($model->getData());
            $model->setData($data);

            $model->setLabelId($object->getId())
                ->loadPost($ruleData)
                ->save();
        }
    }

    /**
     * @param \Magento\Framework\Model\AbstractModel $object
     * @return $this
     */
    protected function _saveStore(\Magento\Framework\Model\AbstractModel $object)
    {
        $storeTable = $this->getTable('mst_cataloglabel_label_store');
        $adapter = $this->getConnection();

        if (!$object->getData('stores')) {
            $condition = $adapter->quoteInto('label_id = ?', $object->getId());
            $adapter->delete($storeTable, $condition);

            $storeArray = [
                'label_id' => $object->getId(),
                'store_id' => '0',
            ];

            $adapter->insert($storeTable, $storeArray);

            return $this;
        }

        $condition = $adapter->quoteInto('label_id = ?', $object->getId());
        $adapter->delete($storeTable, $condition);
        foreach ((array) $object->getData('stores') as $store) {
            $storeArray = [];
            $storeArray['label_id'] = $object->getId();
            $storeArray['store_id'] = $store;
            $adapter->insert($storeTable, $storeArray);
        }
    }

    /**
     * @param \Magento\Framework\Model\AbstractModel $object
     * @return $this
     */
    protected function _saveCustomerGroup(\Magento\Framework\Model\AbstractModel $object)
    {
        $groupTable = $this->getTable('mst_cataloglabel_label_customer_group');
        $adapter = $this->getConnection();

        $condition = $adapter->quoteInto('label_id = ?', $object->getId());
        $adapter->delete($groupTable, $condition);
        foreach ((array) $object->getData('customer_group_ids') as $group) {
            $groupArray = [
                'label_id' => $object->getId(),
                'customer_group_id' => $group,
            ];
            $adapter->insert($groupTable, $groupArray);
        }

        return $this;
    }

    /**
     * M2.2. compatibility
     *
     * @param array $data
     * @return array
     */
    protected function prepareCompatibility($data)
    {
        if (isset($data['conditions_serialized'])
            && $data['conditions_serialized']) {
            $data['conditions_serialized'] = $this->compatibilityService
                ->prepareRuleDataForSave($data['conditions_serialized']);
        }
        if (isset($data['actions_serialized'])
            && $data['actions_serialized']) {
            $data['actions_serialized'] = $this->compatibilityService
                ->prepareRuleDataForSave($data['actions_serialized']);
        }

        return $data;
    }
}

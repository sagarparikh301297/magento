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



namespace Mirasvit\CatalogLabel\Model\Label;

/**
 * @SuppressWarnings(PHPMD.CouplingBetweenObjects)
 */
class Rule extends \Magento\Rule\Model\AbstractModel
{
    /**
     * @var array
     */
    protected $_productIds;

    const CACHE_TAG = 'cataloglabel_label_rule';
    /**
     * @var string
     */
    protected $_cacheTag = 'cataloglabel_label_rule';
    /**
     * @var string
     */
    protected $_eventPrefix = 'cataloglabel_label_rule';

    /**
     * Get identities.
     *
     * @return array
     */
    public function getIdentities()
    {
        return [self::CACHE_TAG.'_'.$this->getId()];
    }

    /**
     * @var \Mirasvit\CatalogLabel\Model\Label\DisplayFactory
     */
    protected $labelDisplayFactory;

    /**
     * @var \Mirasvit\CatalogLabel\Model\LabelFactory
     */
    protected $labelFactory;

    /**
     * @var \Mirasvit\CatalogLabel\Model\Label\Rule\Condition\CombineFactory
     */
    protected $labelRuleConditionCombineFactory;

    /**
     * @var \Mirasvit\CatalogLabel\Model\Label\Rule\Action\CollectionFactory
     */
    protected $labelRuleActionCollectionFactory;

    /**
     * @var \Magento\Catalog\Model\ProductFactory
     */
    protected $productFactory;

    /**
     * @var \Magento\Catalog\Model\ResourceModel\Product\CollectionFactory
     */
    protected $productCollectionFactory;

    /**
     * @var \Magento\Framework\Model\ResourceModel\Iterator
     */
    protected $resourceIterator;

    /**
     * @var \Magento\Framework\Model\Context
     */
    protected $context;

    /**
     * @var \Magento\Framework\Registry
     */
    protected $registry;

    /**
     * @var \Magento\Framework\Model\ResourceModel\AbstractResource
     */
    protected $resource;

    /**
     * @var \Magento\Framework\Data\Collection\AbstractDb
     */
    protected $resourceCollection;

    /**
     * @param \Mirasvit\CatalogLabel\Model\Label\DisplayFactory                $labelDisplayFactory
     * @param \Mirasvit\CatalogLabel\Model\LabelFactory                        $labelFactory
     * @param \Mirasvit\CatalogLabel\Model\Label\Rule\Condition\CombineFactory $labelRuleConditionCombineFactory
     * @param \Mirasvit\CatalogLabel\Model\Label\Rule\Action\CollectionFactory $labelRuleActionCollectionFactory
     * @param \Magento\Catalog\Model\ProductFactory                            $productFactory
     * @param \Magento\Catalog\Model\ResourceModel\Product\CollectionFactory   $productCollectionFactory
     * @param \Magento\Framework\Model\ResourceModel\Iterator                  $resourceIterator
     * @param \Magento\Framework\Model\Context                                 $context
     * @param \Magento\Framework\Registry                                      $registry
     * @param \Magento\Framework\Data\FormFactory                              $formFactory
     * @param \Magento\Framework\Stdlib\DateTime\TimezoneInterface             $localeDate
     * @param \Magento\Framework\Model\ResourceModel\AbstractResource          $resource
     * @param \Magento\Framework\Data\Collection\AbstractDb                    $resourceCollection
     * @param array                                                            $data
     *
     * @SuppressWarnings(PHPMD.ExcessiveParameterList)
     */
    public function __construct(
        \Mirasvit\CatalogLabel\Model\Label\DisplayFactory $labelDisplayFactory,
        \Mirasvit\CatalogLabel\Model\LabelFactory $labelFactory,
        \Mirasvit\CatalogLabel\Model\Label\Rule\Condition\CombineFactory $labelRuleConditionCombineFactory,
        \Mirasvit\CatalogLabel\Model\Label\Rule\Action\CollectionFactory $labelRuleActionCollectionFactory,
        \Magento\Catalog\Model\ProductFactory $productFactory,
        \Magento\Catalog\Model\ResourceModel\Product\CollectionFactory $productCollectionFactory,
        \Magento\Framework\Model\ResourceModel\Iterator $resourceIterator,
        \Magento\Framework\Model\Context $context,
        \Magento\Framework\Registry $registry,
        \Magento\Framework\Data\FormFactory $formFactory,
        \Magento\Framework\Stdlib\DateTime\TimezoneInterface $localeDate,
        \Magento\Framework\Model\ResourceModel\AbstractResource $resource = null,
        \Magento\Framework\Data\Collection\AbstractDb $resourceCollection = null,
        array $data = []
    ) {
        $this->labelDisplayFactory = $labelDisplayFactory;
        $this->labelFactory = $labelFactory;
        $this->labelRuleConditionCombineFactory = $labelRuleConditionCombineFactory;
        $this->labelRuleActionCollectionFactory = $labelRuleActionCollectionFactory;
        $this->productFactory = $productFactory;
        $this->productCollectionFactory = $productCollectionFactory;
        $this->resourceIterator = $resourceIterator;
        $this->context = $context;
        $this->registry = $registry;
        $this->resource = $resource;
        $this->resourceCollection = $resourceCollection;
        parent::__construct($context, $registry, $formFactory, $localeDate, $resource, $resourceCollection, $data);
    }

    /**
     * @return void
     */
    protected function _construct()
    {
        $this->_init('Mirasvit\CatalogLabel\Model\ResourceModel\Label\Rule');
    }

    /**
     * @return Display
     */
    public function getDisplay()
    {
        $display = $this->labelDisplayFactory->create();
        if ($this->getDisplayId()) {
            $display->load($this->getDisplayId());
        }

        return $display;
    }

    /**
     * @return \Mirasvit\CatalogLabel\Model\Label
     */
    public function getLabel()
    {
        $label = $this->labelFactory->create();
        if ($this->getLabelId()) {
            $label->load($this->getLabelId());
        }

        return $label;
    }

    /**
     * @return Rule\Condition\Combine
     */
    public function getConditionsInstance()
    {
        return $this->labelRuleConditionCombineFactory->create();
    }

    /**
     * @return Rule\Action\Collection
     */
    public function getActionsInstance()
    {
        return $this->labelRuleActionCollectionFactory->create();
    }

    /**
     * @return void
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function afterSave()
    {
        $this->_getResource()->updateRuleProductData($this);
        parent::afterSave();
    }

    /**
     * @return array
     */
    public function getMatchingProductIds()
    {
        if ($this->_productIds === null) {
            $this->_productIds = [];
            $this->setCollectedAttributes([]);

            $productCollection = $this->productCollectionFactory->create();

            $this->getConditions()->collectValidatedAttributes($productCollection);

            $this->resourceIterator->walk(
                $productCollection->getSelect(),
                [[$this, 'callbackValidateProduct']],
                [
                    'attributes' => $this->getCollectedAttributes(),
                    'product' => $this->productFactory->create(),
                ]
            );
        }

        return $this->_productIds;
    }

    /**
     * @param array $args
     *
     * @return void
     */
    public function callbackValidateProduct($args)
    {
        $product = clone $args['product'];
        $product->setData($args['row']);

        if ($this->getConditions()->validate($product)) {
            $this->_productIds[] = $product->getId();
        }
    }

    /**
     * @return array
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function getProductIds()
    {
        return $this->_getResource()->getRuleProductIds($this->getRuleId());
    }

    /**
     * @param int $productId
     * @return bool
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function isProductId($productId)
    {
        return $this->_getResource()->isRuleProductId($this->getRuleId(), $productId);
    }

    /**
     * Retrieve rule combine conditions model
     *
     * @return \Magento\Rule\Model\Condition\Combine
     */
    public function getConditions()
    {
        if (empty($this->_conditions)) {
            $this->_resetConditions();
        }

        // Load rule conditions if it is applicable
        if ($this->hasConditionsSerialized()) {
            $conditions = $this->getConditionsSerialized();
            if (!empty($conditions)) {
                $decode = json_decode($conditions);
                if ($decode) { //M2.2 compatibility
                    $conditions = $this->serializer->unserialize($conditions);
                } else {
                    $conditions = unserialize($conditions);
                }
                if (is_array($conditions) && !empty($conditions)) {
                    $this->_conditions->loadArray($conditions);
                }
            }
            $this->unsConditionsSerialized();
        }

        return $this->_conditions;
    }
}

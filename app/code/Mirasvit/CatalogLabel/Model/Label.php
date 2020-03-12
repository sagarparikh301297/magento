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



namespace Mirasvit\CatalogLabel\Model;

use Magento\Framework\DataObject\IdentityInterface;
use Mirasvit\CatalogLabel\Model;

/**
 * @SuppressWarnings(PHPMD.CouplingBetweenObjects)
 */
class Label extends \Magento\Framework\Model\AbstractModel implements IdentityInterface
{
    const TYPE_ATTRIBUTE = 'attribute';
    const TYPE_RULE = 'rule';

    const CACHE_TAG = 'cataloglabel_label';
    /**
     * @var string
     */
    protected $_cacheTag = 'cataloglabel_label';
    /**
     * @var string
     */
    protected $_eventPrefix = 'cataloglabel_label';

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
     * @var \Magento\Eav\Model\Entity\AttributeFactory
     */
    protected $entityAttributeFactory;

    /**
     * @var Model\PlaceholderFactory
     */
    protected $placeholderFactory;

    /**
     * @var Model\Label\AttributeFactory
     */
    protected $labelAttributeFactory;

    /**
     * @var \Magento\Catalog\Model\ResourceModel\ProductFactory
     */
    protected $productFactory;

    /**
     * @var Model\ResourceModel\Label\Rule\CollectionFactory
     */
    protected $labelRuleCollectionFactory;

    /**
     * @var Model\ResourceModel\Label\Attribute\CollectionFactory
     */
    protected $labelAttributeCollectionFactory;

    /**
     * @var \Magento\Eav\Model\Config
     */
    protected $config;

    /**
     * @var \Magento\Store\Model\StoreManagerInterface
     */
    protected $storeManager;

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
     * @param \Magento\Eav\Model\Entity\AttributeFactory              $entityAttributeFactory
     * @param Model\PlaceholderFactory                                $placeholderFactory
     * @param Model\Label\AttributeFactory                            $labelAttributeFactory
     * @param \Magento\Catalog\Model\ResourceModel\ProductFactory     $productFactory
     * @param Model\ResourceModel\Label\Rule\CollectionFactory        $labelRuleCollectionFactory
     * @param Model\ResourceModel\Label\Attribute\CollectionFactory   $labelAttributeCollectionFactory
     * @param \Magento\Eav\Model\Config                               $config
     * @param \Magento\Store\Model\StoreManagerInterface              $storeManager
     * @param \Magento\Framework\Model\Context                        $context
     * @param \Magento\Framework\Registry                             $registry
     * @param \Magento\Framework\Model\ResourceModel\AbstractResource $resource
     * @param \Magento\Framework\Data\Collection\AbstractDb           $resourceCollection
     * @param array                                                   $data
     *
     * @SuppressWarnings(PHPMD.ExcessiveParameterList)
     */
    public function __construct(
        \Magento\Eav\Model\Entity\AttributeFactory $entityAttributeFactory,
        Model\PlaceholderFactory $placeholderFactory,
        Model\Label\AttributeFactory $labelAttributeFactory,
        \Magento\Catalog\Model\ResourceModel\ProductFactory $productFactory,
        Model\ResourceModel\Label\Rule\CollectionFactory $labelRuleCollectionFactory,
        Model\ResourceModel\Label\Attribute\CollectionFactory $labelAttributeCollectionFactory,
        \Magento\Eav\Model\Config $config,
        \Magento\Store\Model\StoreManagerInterface $storeManager,
        \Magento\Framework\Model\Context $context,
        \Magento\Framework\Registry $registry,
        \Magento\Framework\Model\ResourceModel\AbstractResource $resource = null,
        \Magento\Framework\Data\Collection\AbstractDb $resourceCollection = null,
        array $data = []
    ) {
        $this->entityAttributeFactory = $entityAttributeFactory;
        $this->placeholderFactory = $placeholderFactory;
        $this->labelAttributeFactory = $labelAttributeFactory;
        $this->productFactory = $productFactory;
        $this->labelRuleCollectionFactory = $labelRuleCollectionFactory;
        $this->labelAttributeCollectionFactory = $labelAttributeCollectionFactory;
        $this->config = $config;
        $this->storeManager = $storeManager;
        $this->context = $context;
        $this->registry = $registry;
        $this->resource = $resource;
        $this->resourceCollection = $resourceCollection;
        parent::__construct($context, $registry, $resource, $resourceCollection, $data);
    }

    /**
     * @return void
     */
    protected function _construct()
    {
        $this->_init('Mirasvit\CatalogLabel\Model\ResourceModel\Label');
    }

    /**
     * @return \Magento\Framework\DataObject
     */
    public function getRule()
    {
        $rule = $this->labelRuleCollectionFactory->create()
            ->addFieldToFilter('label_id', $this->getId())
            ->getFirstItem();

        $rule = $rule->load($rule->getId());

        return $rule;
    }

    /**
     * @return \Magento\Eav\Model\Entity\Attribute\AbstractAttribute
     */
    public function getAttribute()
    {
        $code = $this->entityAttributeFactory->create()->load($this->getAttributeId())->getAttributeCode();
        $attribute = $this->config->getAttribute('catalog_product', $code);

        return $attribute;
    }

    /**
     * @return $this
     */
    public function getPlaceholder()
    {
        return $this->placeholderFactory->create()->load($this->getPlaceholderId());
    }

    /**
     * @param \Magento\Catalog\Model\Product $product
     * @return array
     *
     * @SuppressWarnings(PHPMD.CyclomaticComplexity)
     */
    public function getDisplays($product)
    {
        $result = [];

        if ($this->getType() == self::TYPE_ATTRIBUTE) {
            $option = $this->productFactory->create()->getAttributeRawValue(
                $product->getId(), $this->getAttributeId(), $this->storeManager->getStore()->getStoreId()
            );
            $attribute = $this->getAttribute();

            if (!$attribute->usesSource()) {
                //text attribute
                $labelAttributes = $this->labelAttributeCollectionFactory->create()
                    ->addFieldToFilter('label_id', $this->getId());
                foreach ($labelAttributes as $labelAttribute) {
                    if (strpos($option, $labelAttribute->getOptionText()) !== false) {
                        $result[] = $labelAttribute->getDisplay();
                    }
                }
            } else {
                //int attribute
                if ($attribute->getFrontendInput() == 'multiselect') {
                    $options = explode(',', $option);
                } else {
                    $options = [$option];
                }
                foreach ($options as $option) {
                    $labelAttribute = $this->labelAttributeFactory->create()->loadByKey($this->getId(), $option);
                    $result[]       = $labelAttribute->getDisplay();
                }
            }

            if ($product->getTypeId() == 'configurable') {
                $productAttributeOptions = $product->getTypeInstance(true)->getConfigurableAttributesAsArray($product);
                foreach ($productAttributeOptions as $productAttribute) {
                    foreach ($productAttribute['values'] as $attribute) {
                        $labelAttribute = $this->labelAttributeFactory->create()
                            ->loadByKey($this->getId(), $attribute['value_index']);
                        $result[] = $labelAttribute->getDisplay();
                    }
                }
            }
        } elseif ($this->getType() == self::TYPE_RULE) {
            $rule = $this->getRule();
            if ($rule->isProductId($product->getId())) {
                $result[] = $rule->getDisplay();
            }
        }

        return $result;
    }
}

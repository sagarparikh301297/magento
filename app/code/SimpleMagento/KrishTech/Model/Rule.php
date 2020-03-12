<?php


namespace SimpleMagento\KrishTech\Model;


use Magento\Catalog\Model\ProductFactory;
use Magento\Catalog\Model\ResourceModel\Product\CollectionFactory;
use Magento\CatalogRule\Helper\Data;
use Magento\CatalogRule\Model\Indexer\Rule\RuleProductProcessor;
use Magento\CatalogRule\Model\ResourceModel\Product\ConditionsToCollectionApplier;
use Magento\CatalogRule\Model\ResourceModel\Rule as RuleResourceModel;
use Magento\CatalogRule\Model\Rule\Action\CollectionFactory as RuleCollectionFactory;
use Magento\CatalogRule\Model\Rule\Condition\CombineFactory;
use Magento\Customer\Model\Session;
use Magento\Framework\Api\AttributeValueFactory;
use Magento\Framework\Api\ExtensionAttributesFactory;
use Magento\Framework\App\Cache\TypeListInterface;
use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Framework\App\ObjectManager;
use Magento\Framework\Data\Collection\AbstractDb;
use Magento\Framework\Data\FormFactory;
use Magento\Framework\Model\Context;
use Magento\Framework\Model\ResourceModel\AbstractResource;
use Magento\Framework\Model\ResourceModel\Iterator;
use Magento\Framework\Registry;
use Magento\Framework\Serialize\Serializer\Json;
use Magento\Framework\Stdlib\DateTime;
use Magento\Framework\Stdlib\DateTime\TimezoneInterface;
use Magento\Store\Model\StoreManagerInterface;
use Magento\Framework\Data\Collection;

class Rule extends \Magento\CatalogRule\Model\Rule
{

    /**
     * @var ConditionsToCollectionApplier
     */
    protected $conditionsToCollectionApplier;
    /**
     * @var ScopeConfigInterface
     */
    protected $_scopeConfig;
    /**
     * @var \Magento\CatalogRule\Model\Rule
     */
    protected $rule;

    public function __construct(Context $context,
                                Registry $registry,
                                FormFactory $formFactory,
                                TimezoneInterface $localeDate,
                                CollectionFactory $productCollectionFactory,
                                StoreManagerInterface $storeManager,
                                CombineFactory $combineFactory,
                                RuleCollectionFactory $actionCollectionFactory,
                                ProductFactory $productFactory,
                                Iterator $resourceIterator,
                                Session $customerSession,
                                Data $catalogRuleData,
                                TypeListInterface $cacheTypesList,
                                DateTime $dateTime,
                                RuleProductProcessor $ruleProductProcessor,
                                ScopeConfigInterface $config,
                                \Magento\CatalogRule\Model\Rule $rule,
                                AbstractResource $resource = null,
                                AbstractDb $resourceCollection = null,
                                array $relatedCacheTypes = [],
                                array $data = [],
                                ExtensionAttributesFactory $extensionFactory = null,
                                AttributeValueFactory $customAttributeFactory = null,
                                Json $serializer = null,
                                RuleResourceModel $ruleResourceModel = null,
                                ConditionsToCollectionApplier $conditionsToCollectionApplier = null)
    {
        parent::__construct($context, $registry, $formFactory, $localeDate, $productCollectionFactory, $storeManager, $combineFactory, $actionCollectionFactory, $productFactory, $resourceIterator, $customerSession, $catalogRuleData, $cacheTypesList, $dateTime, $ruleProductProcessor, $resource, $resourceCollection, $relatedCacheTypes, $data, $extensionFactory, $customAttributeFactory, $serializer, $ruleResourceModel, $conditionsToCollectionApplier);
        $this->_scopeConfig = $config;        $this->conditionsToCollectionApplier = $conditionsToCollectionApplier
        ?? ObjectManager::getInstance()->get(ConditionsToCollectionApplier::class);
        $this->rule = $rule;
    }
    /**
     * Check if we can use mapping for rule conditions
     *
     * @return bool
     */
    private function canPreMapProducts()
    {
        $conditions = $this->getConditions();

        // No need to map products if there is no conditions in rule
        if (!$conditions || !$conditions->getConditions()) {
            return false;
        }

        return true;
    }
    /**
     * Get array of product ids which are matched by rule
     *
     * @return array
     */
    public function getMatchingProductIds()
    {

        if ($this->_productIds === null) {
            $this->_productIds = [];
            $this->setCollectedAttributes([]);
            $this->rule->load(1);

            if ($this->getWebsiteIds()) {
                /** @var $productCollection \Magento\Catalog\Model\ResourceModel\Product\Collection */
                $productCollection = $this->_productCollectionFactory->create();
//                $productCollection->addAttributeToSelect('*');
//                $productCollection->addWebsiteFilter($this->getWebsiteIds());
//                $productCollection->setPageSize(10)->load(); // only get 10 products

                if ($this->_productsFilter) {
                    $productCollection->addIdFilter($this->_productsFilter);
                }
                $this->getConditions()->collectValidatedAttributes($productCollection);

                if ($this->canPreMapProducts()) {
                    $productCollection = $this->conditionsToCollectionApplier
                        ->applyConditionsToCollection($this->getConditions(), $productCollection);
                }

                $this->_resourceIterator->walk(
                    $productCollection->getSelect(),
                    [[$this, 'callbackValidateProduct']],
                    [
                        'attributes' => $this->getCollectedAttributes(),
                        'product' => $this->_productFactory->create()
                    ]
                );
            }
        }

        return $this->_productIds;
    }

}
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



namespace Mirasvit\CatalogLabel\Model\Label\Rule\Condition;

use Magento\CatalogRule\Model\ResourceModel\Rule;
use Magento\ConfigurableProduct\Model\ResourceModel\Product\Type\ConfigurableFactory;
use Magento\Eav\Model\ResourceModel\Entity\Attribute\Set;
use Magento\Framework\Stdlib\DateTime\TimezoneInterface;
use Magento\SalesRule\Api\Data\RuleInterface;

/**
 * @SuppressWarnings(PHPMD.TooManyFields)
 * @SuppressWarnings(PHPMD.ExcessiveClassComplexity)
 * @SuppressWarnings(PHPMD.CouplingBetweenObjects)
 */
class Product extends \Magento\Rule\Model\Condition\AbstractCondition
{
    /**
     * @var \Magento\CatalogInventory\Model\Stock\ItemFactory
     */
    protected $stockItemFactory;

    /**
     * @var \Magento\CatalogRule\Model\RuleFactory
     */
    protected $ruleFactory;

    /**
     * @var \Magento\Catalog\Model\ResourceModel\ProductFactory
     */
    protected $productFactory;

    /**
     * @var Set\CollectionFactory
     */
    protected $entityAttributeSetCollectionFactory;

    /**
     * @var \Magento\ConfigurableProduct\Model\ResourceModel\Product\Type\ConfigurableFactory
     */
    protected $productTypeConfigurableFactory;

    /**
     * @var \Magento\Eav\Model\Config
     */
    protected $config;

    /**
     * @var \Magento\Framework\UrlInterface
     */
    protected $urlManager;

    /**
     * @var \Magento\Backend\Model\Url
     */
    protected $backendUrlManager;

    /**
     * @var \Magento\Framework\Locale\FormatInterface
     */
    protected $localeFormat;

    /**
     * @var \Magento\Framework\View\Asset\Repository
     */
    protected $assetRepo;

    /**
     * @var \Magento\Rule\Model\Condition\Context
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
     * Product constructor.
     * @param \Magento\CatalogInventory\Model\Stock\ItemFactory $stockItemFactory
     * @param \Magento\CatalogRule\Model\ResourceModel\RuleFactory $ruleFactory
     * @param \Magento\CatalogRule\Model\RuleFactory $ruleFactoryModel
     * @param \Magento\Catalog\Model\ResourceModel\ProductFactory $productFactory
     * @param Set\CollectionFactory $entityAttributeSetCollectionFactory
     * @param ConfigurableFactory $productTypeConfigurableFactory
     * @param \Magento\Eav\Model\Config $config
     * @param \Magento\Framework\UrlInterface $urlManager
     * @param \Magento\Backend\Model\Url $backendUrlManager
     * @param \Magento\Store\Model\StoreManagerInterface $storeManager
     * @param \Magento\Framework\Locale\FormatInterface $localeFormat
     * @param \Magento\Rule\Model\Condition\Context $context
     * @param \Magento\Framework\Registry $registry
     * @param \Magento\Framework\Model\ResourceModel\AbstractResource|null $resource
     * @param \Magento\Framework\Data\Collection\AbstractDb|null $resourceCollection
     * @param array $data
     *
     * @SuppressWarnings(PHPMD.ExcessiveParameterList)
     */
    public function __construct(
        Rule $rule,
        \Magento\CatalogInventory\Model\Stock\ItemFactory $stockItemFactory,
        \Magento\CatalogRule\Model\ResourceModel\RuleFactory $ruleFactory,
        \Magento\CatalogRule\Model\RuleFactory $ruleFactoryModel,
        \Magento\Catalog\Model\ResourceModel\ProductFactory $productFactory,
        Set\CollectionFactory $entityAttributeSetCollectionFactory,
        ConfigurableFactory $productTypeConfigurableFactory,
        \Magento\Framework\Pricing\PriceCurrencyInterface $priceCurrency,
        \Magento\Eav\Model\Config $config,
        \Magento\Framework\UrlInterface $urlManager,
        \Magento\Backend\Model\Url $backendUrlManager,
        \Magento\Store\Model\StoreManagerInterface $storeManager,
        \Magento\Framework\Locale\FormatInterface $localeFormat,
        \Magento\Rule\Model\Condition\Context $context,
        \Magento\Framework\Registry $registry,
        \Magento\Framework\Model\ResourceModel\AbstractResource $resource = null,
        \Magento\Framework\Data\Collection\AbstractDb $resourceCollection = null,
        array $data = []
    ) {
        $this->dateTime = $context->getLocaleDate();
        $this->priceCurrency = $priceCurrency;
        $this->rule = $rule;
        $this->stockItemFactory = $stockItemFactory;
        $this->ruleFactory = $ruleFactory;
        $this->ruleFactoryModel = $ruleFactoryModel;
        $this->productFactory = $productFactory;
        $this->entityAttributeSetCollectionFactory = $entityAttributeSetCollectionFactory;
        $this->productTypeConfigurableFactory = $productTypeConfigurableFactory;
        $this->config = $config;
        $this->urlManager = $urlManager;
        $this->backendUrlManager = $backendUrlManager;
        $this->storeManager = $storeManager;
        $this->localeFormat = $localeFormat;
        $this->context = $context;
        $this->assetRepo = $context->getAssetRepository();
        $this->registry = $registry;
        $this->resource = $resource;
        $this->resourceCollection = $resourceCollection;

        parent::__construct($context, $data);
    }

    /**
     * @var string|int
     */
    protected $_entityAttributeValues = null;
    /**
     * @var string
     */
    protected $_isUsedForRuleProperty = 'is_used_for_promo_rules';

    /**
     * @return \Magento\Eav\Model\Entity\Attribute\AbstractAttribute|\Magento\Framework\DataObject
     */
    public function getAttributeObject()
    {
        try {
            $obj = $this->config
                ->getAttribute('catalog_product', $this->getAttribute());
        } catch (\Exception $e) {
            $obj = new \Magento\Framework\DataObject();
            $obj->setEntity($this->productFactory->create())
                ->setFrontendInput('text');
        }

        return $obj;
    }

    /**
     * @param array &$attributes
     *
     * @return void
     */
    protected function _addSpecialAttributes(array &$attributes)
    {
        $attributes['attribute_set_id'] = __('Attribute Set');
        $attributes['category_ids'] = __('Category');
        $attributes['created_at'] = __('Created At (days ago)');
        $attributes['updated_at'] = __('Updated At (days ago)');
        $attributes['qty'] = __('Quantity');
        $attributes['price_diff'] = __('Price - Final Price');
        $attributes['percent_discount'] = __('Percent Discount');
        $attributes['set_as_new'] = __('Set as New');
    }

    /**
     * @return $this
     */
    public function loadAttributeOptions()
    {
        $productAttributes = $this->productFactory->create()
            ->loadAllAttributes();
        if ($productAttributes) {
            $productAttributes = $productAttributes->getAttributesByCode();
        } else {
            $productAttributes = [];
        }

        $attributes = [];
        foreach ($productAttributes as $attribute) {
            if (
                !$attribute->isAllowedForRuleCondition() ||
                !$attribute->getDataUsingMethod($this->_isUsedForRuleProperty)
            ) {
                continue;
            }
            $attributes[$attribute->getAttributeCode()] = $attribute->getFrontendLabel();
        }

        $this->_addSpecialAttributes($attributes);

        asort($attributes);
        $this->setAttributeOption($attributes);

        return $this;
    }

    /**
     * @return $this
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    protected function _prepareValueOptions()
    {
        // Check that both keys exist. Maybe somehow only one was set not in this routine, but externally.
        $selectReady = $this->getData('value_select_options');
        $hashedReady = $this->getData('value_option');
        if ($selectReady && $hashedReady) {
            return $this;
        }

        // Get array of select options. It will be used as source for hashed options
        $selectOptions = null;
        if ($this->getAttribute() === 'set_as_new') {
            $selectOptions = [
                ['value' => 0, 'label' => __('No')],
                ['value' => 1, 'label' => __('Yes')],
            ];
        }
        if ($this->getAttribute() === 'attribute_set_id') {
            $entityTypeId = $this->config
                ->getEntityType('catalog_product')->getId();
            $selectOptions = $this->entityAttributeSetCollectionFactory->create()
                ->setEntityTypeFilter($entityTypeId)
                ->load()
                ->toOptionArray();
        } elseif (is_object($this->getAttributeObject())) {
            $attributeObject = $this->getAttributeObject();
            if ($attributeObject->usesSource()) {
                if ($attributeObject->getFrontendInput() == 'multiselect') {
                    $addEmptyOption = false;
                } else {
                    $addEmptyOption = true;
                }
                $selectOptions = $attributeObject->getSource()->getAllOptions($addEmptyOption);
            }
        }

        $this->setSelectOptions($selectOptions);

        return $this;
    }

    /**
     * @param array $selectOptions
     * @return void
     */
    private function setSelectOptions($selectOptions)
    {
        $selectReady = $this->getData('value_select_options');
        $hashedReady = $this->getData('value_option');
        // Set new values only if we really got them
        if ($selectOptions !== null) {
            // Overwrite only not already existing values
            if (!$selectReady) {
                $this->setData('value_select_options', $selectOptions);
            }
            if (!$hashedReady) {
                $hashedOptions = [];
                foreach ($selectOptions as $o) {
                    if (is_array($o['value'])) {
                        continue; // We cannot use array as index
                    }
                    $hashedOptions[$o['value']] = $o['label'];
                }
                $this->setData('value_option', $hashedOptions);
            }
        }
    }

    /**
     * Retrieve value by option.
     *
     * @param string $option
     *
     * @return string
     */
    public function getValueOption($option = null)
    {
        $this->_prepareValueOptions();

        return $this->getData('value_option'.($option !== null ? '/'.$option : ''));
    }

    /**
     * Retrieve select option values.
     *
     * @return array
     */
    public function getValueSelectOptions()
    {
        $this->_prepareValueOptions();

        return $this->getData('value_select_options');
    }

    /**
     * Retrieve after element HTML.
     *
     * @return string
     */
    public function getValueAfterElementHtml()
    {
        $html = '';

        switch ($this->getAttribute()) {
            case 'sku': case 'category_ids':
                    $image = $this->assetRepo->getUrl('images/rule_chooser_trigger.gif');
                break;
        }

        if (!empty($image)) {
            $html = ''.
            '<a href="javascript:void(0)" class="rule-chooser-trigger">'.
                '<img src="'.$image.'" alt="" class="v-middle rule-chooser-trigger" title="'.__('Open Chooser').'" />'.
            '</a>';
        }

        return $html;
    }

    /**
     * Retrieve attribute element.
     *
     * @return Varien_Form_Element_Abstract
     */
    public function getAttributeElement()
    {
        $element = parent::getAttributeElement();
        $element->setShowAsText(true);

        return $element;
    }

    /**
     * @param \Magento\Catalog\Model\ResourceModel\Product\Collection $productCollection
     * @return $this
     */
    public function collectValidatedAttributes($productCollection)
    {
        $attribute = $this->getAttribute();

        if (!in_array($attribute, ['category_ids', 'qty', 'price_diff', 'percent_discount', 'set_as_new'])) {
            if ($this->getAttributeObject()->isScopeGlobal()) {
                $attributes = $this->getRule()->getCollectedAttributes();
                $attributes[$attribute] = true;
                $this->getRule()->setCollectedAttributes($attributes);
                $productCollection->addAttributeToSelect($attribute, 'left');
            } else {
                $this->_entityAttributeValues = $productCollection->getAllAttributeValues($attribute);
            }
        } elseif (($attribute == 'price_diff') || ($attribute == 'percent_discount')) {
            $productCollection->addAttributeToSelect('price_view', 'left');
            $productCollection->addAttributeToSelect('price', 'left');
            $productCollection->addAttributeToSelect('special_price', 'left');
            $productCollection->addAttributeToSelect('special_from_date', 'left');
            $productCollection->addAttributeToSelect('special_to_date', 'left');
            $productCollection->addAttributeToSelect('type_id', 'left');
        } elseif ($attribute == 'set_as_new') {
            $productCollection->addAttributeToSelect('news_from_date', 'left');
            $productCollection->addAttributeToSelect('news_to_date', 'left');
        }

        return $this;
    }

    /**
     * Retrieve input type.
     *
     * @return string
     */
    public function getInputType()
    {
        if ($this->getAttribute() === 'set_as_new') {
            return 'select';
        }
        if ($this->getAttribute() === 'attribute_set_id') {
            return 'select';
        }
        if (!is_object($this->getAttributeObject())) {
            return 'string';
        }
        switch ($this->getAttributeObject()->getFrontendInput()) {
            case 'select':
                return 'select';

            case 'multiselect':
                return 'multiselect';

            case 'boolean':
                return 'boolean';

            default:
                return 'string';
        }
    }

    /**
     * Retrieve value element type.
     *
     * @return string
     */
    public function getValueElementType()
    {
        if ($this->getAttribute() === 'set_as_new') {
            return 'select';
        }
        if ($this->getAttribute() === 'attribute_set_id') {
            return 'select';
        }
        if (!is_object($this->getAttributeObject())) {
            return 'text';
        }
        switch ($this->getAttributeObject()->getFrontendInput()) {
            case 'select':
            case 'boolean':
                return 'select';

            case 'multiselect':
                return 'multiselect';

            default:
                return 'text';
        }
    }

    /**
     * Retrieve value element chooser URL.
     *
     * @return string
     */
    public function getValueElementChooserUrl()
    {
        $url = false;
        switch ($this->getAttribute()) {
            case 'sku':
            case 'category_ids':
                $url = 'catalog_rule/promo_widget/chooser/attribute/'.$this->getAttribute();
                if ($this->getJsFormObject()) {
                    $url .= '/form/'.$this->getJsFormObject();
                } else {
                    $url .= '/form/rule_conditions_fieldset'; //@dva fixed js error in sku grid. not sure about.
                }
                break;
        }

        return $url !== false ? $this->backendUrlManager->getUrl($url) : '';
    }

    /**
     * Retrieve Explicit Apply.
     *
     * @return bool
     *
     * @SuppressWarnings(PHPMD.BooleanGetMethodName)
     */
    public function getExplicitApply()
    {
        switch ($this->getAttribute()) {
            case 'sku': case 'category_ids':
                return true;
        }

        return false;
    }

    /**
     * Load array.
     *
     * @param array $arr
     *
     * @return \Magento\CatalogRule\Model\Rule\Condition\Product
     *
     * @SuppressWarnings(PHPMD.CyclomaticComplexity)
     */
    public function loadArray($arr)
    {
        $this->setAttribute(isset($arr['attribute']) ? $arr['attribute'] : false);
        $attribute = $this->getAttributeObject();

        if ($attribute && $attribute->getBackendType() == 'decimal') {
            if (isset($arr['value'])) {
                if (!empty($arr['operator'])
                    && in_array($arr['operator'], ['!()', '()'])
                    && false !== strpos($arr['value'], ',')) {
                    $tmp = [];
                    foreach (explode(',', $arr['value']) as $value) {
                        $tmp[] = $this->localeFormat->getNumber($value);
                    }
                    $arr['value'] = implode(',', $tmp);
                } else {
                    $arr['value'] = $this->localeFormat->getNumber($arr['value']);
                }
            } else {
                $arr['value'] = false;
            }
            $arr['is_value_parsed'] = isset($arr['is_value_parsed'])
                ? $this->localeFormat->getNumber($arr['is_value_parsed']) : false;
        }

        return parent::loadArray($arr);
    }

    /**
     * Validate product attrbute value for condition.
     *
     * @param \Magento\Framework\Model\AbstractModel $object
     *
     * @return bool
     *
     * @SuppressWarnings(PHPMD.ExcessiveMethodLength)
     * @SuppressWarnings(PHPMD.NPathComplexity)
     * @SuppressWarnings(PHPMD.CyclomaticComplexity)
     */
    public function validate(\Magento\Framework\Model\AbstractModel $object)
    {
        $attrCode = $this->getAttribute();
        $defaultStoreId = $this->storeManager
            ->getWebsite(true)
            ->getDefaultGroup()
            ->getDefaultStoreId();

        $object->setStoreId($defaultStoreId);

        if ('category_ids' == $attrCode) {
            $op = $this->getOperatorForValidate();
            if (($op == '==')
                || ($op == '!=')) {
                if (is_array($object->getAvailableInCategories())) {
                    $value = $this->getValueParsed();
                    $value = preg_split('#\s*[,;]\s*#', $value, null, PREG_SPLIT_NO_EMPTY);
                    $findElemInArray = array_intersect($object->getAvailableInCategories(), $value);
                    if (count($findElemInArray) > 0) {
                        if ($op == '==') {
                            $result = true;
                        }
                        if ($op == '!=') {
                            $result = false;
                        }
                    } else {
                        if ($op == '==') {
                            $result = false;
                        }
                        if ($op == '!=') {
                            $result = true;
                        }
                    }

                    return $result;
                }
            } else {
                return $this->validateAttribute($object->getAvailableInCategories());
            }
        } elseif ('created_at' == $attrCode) {
            $ago = (time() - strtotime($object->getCreatedAt())) / 60 / 60 / 24;

            return $this->validateAttribute($ago);
        } elseif ('updated_at' == $attrCode) {
            $ago = (time() - strtotime($object->getUpdatedAt())) / 60 / 60 / 24;

            return $this->validateAttribute($ago);
        } elseif ('qty' == $attrCode) {
            $stockItem = $this->stockItemFactory->create();
            $stockItemResource = $this->stockItemFactory->create()->getResource();
            $stockItemResource->loadByProductId(
                $stockItem,
                $object->getId(),
                $this->storeManager->getWebsite(true)->getId()
            );
            if ($stockItem->getTypeId() == 'configurable') {
                if ($stockItem->getIsInStock()) {
                    $requiredChildrenIds = $this->productTypeConfigurableFactory->create()
                        ->getChildrenIds($object->getId(), true);
                    $childrenIds = [];
                    foreach ($requiredChildrenIds as $groupedChildrenIds) {
                        $childrenIds = array_merge($childrenIds, $groupedChildrenIds);
                    }
                    $sumQty = 0;
                    foreach ($childrenIds as $childId) {
                        $childStockItem = $this->stockItemFactory->create();
                        $childStockItemResource = $this->stockItemFactory->create()->getResource();
                        $childStockItemResource->loadByProductId(
                            $childStockItem,
                            $childId,
                            $this->storeManager->getWebsite(true)->getId()
                        );
                        $childQty = $childStockItem->getQty();
                        $sumQty += $childQty;
                    }

                    return $this->validateAttribute($sumQty);
                } else {
                    return false;
                }
            } else {
                return $this->validateAttribute($stockItem->getQty());
            }
        } elseif ('price_diff' == $attrCode) {
            $price = $object->getPrice();

            $final = $this->ruleFactoryModel->create()->calcProductPriceRule($object, $object->getPrice());
            if (!$final) {
                $final = $object->getSpecialPrice();
                if ($final) {
                    $specialPriceFromDate = $object->getSpecialFromDate();
                    $specialPriceToDate = $object->getSpecialToDate();
                    $today = time();
                    if (($today >= strtotime($specialPriceFromDate) && $today <= strtotime($specialPriceToDate))
                        || ($today >= strtotime($specialPriceFromDate) && $specialPriceToDate === null)) {
                        return $this->validateAttribute(abs($price - $final));
                    } else {
                        return false;
                    }
                }
            }
            if ($final) {
                return $this->validateAttribute(abs($price - $final));
            }
        } elseif ('set_as_new' == $attrCode) {
            $now    = $this->_localeDate->date()->getTimestamp();
            $from   = strtotime($object->getData('news_from_date'));
            $to     = strtotime($object->getData('news_to_date'));
            $return = false;

            if ($from || $to) {
                $return = true;
                if ($from && $from > $now) {
                    $return = false;
                }
                if ($to && $to < $now) {
                    $return = false;
                }
            }
            $this->setValueParsed((bool)$this->getValueParsed());

            return $this->validateAttribute($return);
        } elseif ('percent_discount' == $attrCode) {
            $prodPrice = ($object->getPrice()) ? : $object->getData('price');
            $final = $object->getSpecialPrice();

            if ($final <= 0) {
                $rules = $this->rule
                    ->getRulesFromProduct(
                        $this->dateTime->scopeDate($defaultStoreId),
                        $this->storeManager->getWebsite(true)->getId(),
                        $this->storeManager->getWebsite(true)->getDefaultGroup()->getId(),
                        $object->getId()
                    );
                if (count($rules)) {
                    foreach ($rules as $ruleData) {
                        if ($ruleData['action_operator'] != RuleInterface::DISCOUNT_ACTION_BY_PERCENT) {
                            continue;
                        }
                        $valid = $this->validateAttribute(abs($ruleData['action_amount']));
                        if ($valid) {
                            return $valid;
                        }
                    }
                }
            }
            //Advanced Pricing-> Special Price in percent
            if ($final > 0 && $prodPrice > 0 && $object->getTypeId() == 'bundle') {
                $final = ($final * $prodPrice)/100;
            }

            $specialPriceFromDate = $object->getSpecialFromDate();
            $specialPriceToDate = $object->getSpecialToDate();
            $today = time();
            $inDateInterval = false;
            if (($today >= strtotime($specialPriceFromDate) && $today <= strtotime($specialPriceToDate))
                || ($today >= strtotime($specialPriceFromDate) && $specialPriceToDate === null)) {
                $inDateInterval = true;
            }

            if (($final > 0 && $prodPrice > 0)
                && ($object->getTypeId() != 'configurable')
                && $inDateInterval) {
                $percent = (($prodPrice - $final) / $prodPrice) * 100;

                return $percent ? $this->validateAttribute(abs($percent)) : false ;
            }

            return false;
        } elseif ('attribute_set_id' == $attrCode) {
            $attrId = $object->getAttributeSetId();

            return $this->validateAttribute($attrId);
        } elseif (!isset($this->_entityAttributeValues[$object->getId()])) {
            $attr = $object->getResource()->getAttribute($attrCode);

            if ($attr && $attr->getBackendType() == 'datetime' && !is_int($this->getValue())) {
                $this->setValue(strtotime($this->getValue()));

                $value = strtotime($object->getData($attrCode));

                return $this->validateAttribute($value);
            }

            if ($attr && $attr->getFrontendInput() == 'multiselect') {
                $value = $object->getData($attrCode);
                $value = strlen($value) ? explode(',', $value) : [];

                return $this->validateAttribute($value);
            }

            if ($attr && $attr->getFrontendInput() == 'select') {
                $attributeValue = $object->getData($this->getAttribute());

                return $this->validateAttribute($attributeValue);
            }

            return parent::validate($object);
        } else {
            $result = false; // any valid value will set it to TRUE
            // remember old attribute state
            $oldAttrValue = $object->hasData($attrCode) ? $object->getData($attrCode) : null;
            foreach ($this->_entityAttributeValues[$object->getId()] as $value) {
                $attr = $object->getResource()->getAttribute($attrCode);
                if ($attr && $attr->getBackendType() == 'datetime') {
                    $value = strtotime($value);
                } elseif ($attr && $attr->getFrontendInput() == 'multiselect') {
                    $value = strlen($value) ? explode(',', $value) : [];
                }

                $object->setData($attrCode, $value);
                $result |= parent::validate($object);

                if ($result) {
                    break;
                }
            }

            if ($oldAttrValue === null) {
                $object->unsetData($attrCode);
            } else {
                $object->setData($attrCode, $oldAttrValue);
            }

            return (bool) $result;
        }
    }
}

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

use Mirasvit\CatalogLabel\Api\Repository\NewProductsRepositoryInterface;
use Magento\Framework\App\CacheInterface;

class Observer extends \Magento\Framework\DataObject
{
    const PRODUCT_CACHE_PREFIX = 'cat_p_';

    public function __construct(
        ResourceModel\Label\CollectionFactory $labelCollectionFactory,
        NewProductsRepositoryInterface $newProductsRepository,
        \Magento\PageCache\Model\Cache\Type $cacheType,
        array $data = []
    ) {
        $this->labelCollectionFactory = $labelCollectionFactory;
        $this->newProductsRepository = $newProductsRepository;
        $this->cacheType = $cacheType;

        parent::__construct($data);
    }

    /**
     * @return void
     */
    public function apply($isOutput = false)
    {
        $collection = $this->labelCollectionFactory->create()
            ->addFieldToFilter('is_active', 1)
            ->addFieldToFilter('type', 'rule');
        foreach ($collection as $label) {
            if ($isOutput) {
                echo 'Label "' . $label->getName()
                    . '" "(ID: ' . $label->getLabelId() . ') update start' . PHP_EOL;
            }
            $rule = $label->getRule();
            $rule->getResource()->updateRuleProductData($rule);
            if ($isOutput) {
                echo 'Label "' . $label->getName()
                    . '" "(ID: ' . $label->getLabelId() . ') update finish' . PHP_EOL;
            }

        }
        //flush cache for new products
        $this->flushNewProductsCache();
    }

    /**
     * @return void
     */
    public function flushNewProductsCache()
    {
        $newProductsCollection = $this->newProductsRepository->getCollection();

        if ($newProductsCollection->getSize() > 0) {
            $tags = [];
            foreach ($newProductsCollection as $newProduct) {
                $tags[] = self::PRODUCT_CACHE_PREFIX . $newProduct->getProductId();
                $this->newProductsRepository->delete($this->newProductsRepository->get($newProduct->getId()));
            }
            $this->cacheType->clean(\Zend_Cache::CLEANING_MODE_MATCHING_ANY_TAG, array_unique($tags));
        }
    }
}

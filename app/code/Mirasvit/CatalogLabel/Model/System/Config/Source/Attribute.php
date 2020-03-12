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



namespace Mirasvit\CatalogLabel\Model\System\Config\Source;

use Magento\Catalog\Model\ResourceModel\Product\Attribute\CollectionFactory as ProductCollectionFactory;

class Attribute implements \Magento\Framework\Option\ArrayInterface
{
    /**
     * @var \Magento\Catalog\Model\ResourceModel\Product\Attribute\CollectionFactory
     */
    protected $productAttributeCollectionFactory;

    /**
     * @var \Magento\Framework\Model\Context
     */
    protected $context;

    /**
     * @param ProductCollectionFactory         $productAttributeCollectionFactory
     * @param \Magento\Framework\Model\Context $context
     */
    public function __construct(
        ProductCollectionFactory $productAttributeCollectionFactory,
        \Magento\Framework\Model\Context $context
    ) {
        $this->productAttributeCollectionFactory = $productAttributeCollectionFactory;
        $this->context = $context;
    }

    /**
     * @return array
     */
    public function toOptionArray()
    {
        $result = [];

        $productAttributeCollection = $this->productAttributeCollectionFactory->create();
        $productAttributeCollection
            ->addFieldToFilter('frontend_input', ['like' => '%select'])
            ->addFieldToFilter('is_user_defined', 1)
            ->setOrder('frontend_label', 'asc');
        foreach ($productAttributeCollection as $attribute) {
            $result[] = [
                'label' => $attribute->getFrontendLabel().' ['.$attribute->getAttributeCode().']',
                'value' => $attribute->getAttributeId(),
            ];
        }

        return $result;
    }
}

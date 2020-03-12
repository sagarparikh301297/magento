<?php


namespace SimpleMagento\AddBulkProduct\Block;


use Magento\Framework\View\Element\Template;
use Magento\Catalog\Block\Product\ListProduct;
use Magento\Catalog\Api\ProductRepositoryInterface;

    class Index extends Template
{
        /**
         * @var ProductRepositoryInterface
         */
        protected $productRepository;
        /**
         * @var \Magento\Framework\Api\SearchCriteriaInterface
         */
        private $criteria;
        /**
         * @var \Magento\Framework\Api\Search\FilterGroup
         */
        private $filterGroup;
        /**
         * @var \Magento\Framework\Api\FilterBuilder
         */
        private $filterBuilder;
        /**
         * @var \Magento\Catalog\Model\Product\Attribute\Source\Status
         */
        private $productStatus;
        /**
         * @var \Magento\Catalog\Model\Product\Visibility
         */
        private $productVisibility;

        public function __construct(
            \Magento\Backend\Block\Template\Context $context,
            \Magento\Catalog\Model\ResourceModel\Product\CollectionFactory $productCollectionFactory,
            \Magento\Catalog\Block\Product\ListProduct $listProductBlock,
            ProductRepositoryInterface $productRepository,
            \Magento\Framework\Api\SearchCriteriaInterface $criteria,
            \Magento\Framework\Api\Search\FilterGroup $filterGroup,
            \Magento\Framework\Api\FilterBuilder $filterBuilder,
            \Magento\Catalog\Model\Product\Attribute\Source\Status $productStatus,
            \Magento\Catalog\Model\Product\Visibility $productVisibility,
            array $data = []
        ) {
            parent::__construct($context, $data);
            $this->_productCollectionFactory = $productCollectionFactory;
            $this->listProductBlock = $listProductBlock;
            $this->productRepository = $productRepository;
            $this->criteria = $criteria;
            $this->filterGroup = $filterGroup;
            $this->filterBuilder = $filterBuilder;
            $this->productStatus = $productStatus;
            $this->productVisibility = $productVisibility;
        }

        public function getProductCollection()
        {

            $this->filterGroup->setFilters([
                $this->filterBuilder
                    ->setField('type_id')
                    ->setConditionType('eq')
                    ->setValue('configurable')
                    ->create()

            ]);

            $this->criteria->setFilterGroups([$this->filterGroup]);
            $products = $this->productRepository->getList($this->criteria);
            $productItems = $products->getItems();

            return $productItems;

        }
        public function getAddToCartPostParams($product)
        {

             return $this->listProductBlock->getAddToCartPostParams($product);
//            var_dump($product);exit();
        }

}
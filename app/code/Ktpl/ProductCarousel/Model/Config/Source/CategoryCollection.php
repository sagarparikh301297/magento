<?php


namespace Ktpl\ProductCarousel\Model\Config\Source;

use Magento\Framework\Option\ArrayInterface;

class CategoryCollection implements ArrayInterface
{
    protected $_categoryFactory;
    protected $_categoryCollectionFactory;

    /**
     * Categorylist constructor.
     * @param \Magento\Catalog\Model\CategoryFactory $categoryFactory
     * @param \Magento\Catalog\Model\ResourceModel\Category\CollectionFactory $categoryCollectionFactory
     */
    public function __construct(
        \Magento\Catalog\Model\CategoryFactory $categoryFactory,
        \Magento\Catalog\Model\ResourceModel\Category\CollectionFactory $categoryCollectionFactory
    )
    {
        $this->_categoryFactory = $categoryFactory;
        $this->_categoryCollectionFactory = $categoryCollectionFactory;
    }

    /**
     * @param bool $isActive
     * @param bool $level
     * @param bool $sortBy
     * @param bool $pageSize
     * @return \Magento\Catalog\Model\ResourceModel\Category\Collection
     */
    public function getCategoryCollection($isActive = true, $level = false, $sortBy = false, $pageSize = false)
    {
        $collection = $this->_categoryCollectionFactory->create();
        $collection->addAttributeToSelect('*');

        // select only active categories
        if ($isActive) {
            $collection->addIsActiveFilter();
        }

        // select categories of certain level
        if ($level) {
            $collection->addLevelFilter($level);
        }

        // sort categories by some value
        if ($sortBy) {
            $collection->addOrderField($sortBy);
        }

        // select certain number of categories
        if ($pageSize) {
            $collection->setPageSize($pageSize);
        }

        return $collection;
    }

    /**
     * @return array
     */
    public function toOptionArray()
    {
        $arr = $this->_toArray();
        $ret = [];

        foreach ($arr as $key => $value)
        {
            $ret[] = [
                'value' => $key,
                'label' => $value
            ];
        }
        usort($ret, function($a, $b) {
            return $a['label'] <=> $b['label'];
        });
        $row = array(array("value" => "", "label" => "Please select category"));
        $ret = array_merge($row,$ret);
        return $ret;

    }

    /**
     * @return array
     */
    private function _toArray()
    {
        $categories = $this->getCategoryCollection(true, false, false, false);

        $catagoryList = array();
        foreach ($categories as $category)
        {
            if($category->getLevel() > 1){
                $catagoryList[$category->getEntityId()] = __($this->_getParentName($category->getPath()) . $category->getName());
            }
        }
        return $catagoryList;
    }

    /**
     * @param string $path
     * @return string
     */
    private function _getParentName($path = '')
    {
        $parentName = '';
        $rootCats = array(0,1);

        $catTree = explode("/", $path);

        // Deleting category itself
        array_pop($catTree);

        if($catTree)
        {
            foreach ($catTree as $catId)
            {
                if(!in_array($catId, $rootCats))
                {
                    $category = $this->_categoryFactory->create()->load($catId);
                    $categoryName = $category->getName();
                    $parentName .= $categoryName . ' -> ';
                }
            }
        }

        return $parentName;
    }
}
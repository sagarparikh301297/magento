<?php


namespace SimpleMagento\RewardPoint\Model\Config\Source;


use Magento\Framework\Option\ArrayInterface;
use Magento\Customer\Model\ResourceModel\Group\CollectionFactory;

class Group implements ArrayInterface
{
    /**
     * @var CollectionFactory
     */
    protected $collectionFactory;

    public function __construct(CollectionFactory $collectionFactory)
    {
        $this->collectionFactory = $collectionFactory;
    }
    /**
     * Return array of options as value-label pairs
     *
     * @return array Format: array(array('value' => '<value>', 'label' => '<label>'), ...)
     */
    public function toOptionArray()
    {
        $customerGroup = $this->collectionFactory->create()->loadData()->toOptionArray();
        return $customerGroup;
    }
}
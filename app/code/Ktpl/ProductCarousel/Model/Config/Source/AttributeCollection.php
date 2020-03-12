<?php


namespace Ktpl\ProductCarousel\Model\Config\Source;


use Magento\Framework\Option\ArrayInterface;

class AttributeCollection implements ArrayInterface
{
    /**
     * @var \Magento\Catalog\Model\ResourceModel\Eav\Attribute
     */
    protected $attributeFactory;

    public function __construct(\Magento\Catalog\Model\ResourceModel\Eav\Attribute $attributeFactory)
    {
        $this->attributeFactory = $attributeFactory;
    }

    /**
     * Return array of options as value-label pairs
     *
     * @return array Format: array(array('value' => '<value>', 'label' => '<label>'), ...)
     */
    public function toOptionArray()
    {
        $collection = $this->attributeFactory->getCollection();
        $collection->addFieldToFilter('frontend_input','boolean')->addFieldToFilter('entity_type_id',['eq' => "4"]);
        $AttributeCollection = [] ;
        foreach ($collection as $item){
            $AttributeCollection[] = ['value' => $item->getName(), 'label' => $item->getFrontendLabel()];
        }
        return $AttributeCollection;
    }
}
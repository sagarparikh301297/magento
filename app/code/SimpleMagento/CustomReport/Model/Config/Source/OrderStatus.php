<?php


namespace SimpleMagento\CustomReport\Model\Config\Source;


use Magento\Framework\Option\ArrayInterface;

class OrderStatus implements ArrayInterface
{

    /**
     * Return array of options as value-label pairs
     *
     * @return array Format: array(array('value' => '<value>', 'label' => '<label>'), ...)
     */
    public function toOptionArray()
    {
        // TODO: Implement toOptionArray() method.
        return [['value' => 0, 'label' => __('Any')], ['value' => 1, 'label' => __('Specified')]];

    }
}
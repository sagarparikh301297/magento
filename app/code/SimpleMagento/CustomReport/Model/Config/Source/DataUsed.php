<?php


namespace SimpleMagento\CustomReport\Model\Config\Source;


use Magento\Framework\Option\ArrayInterface;

class DataUsed implements ArrayInterface
{

    /**
     * Return array of options as value-label pairs
     *
     * @return array Format: array(array('value' => '<value>', 'label' => '<label>'), ...)
     */
    public function toOptionArray()
    {
        return [['value' => 0, 'label' => __('Order Created')], ['value' => 1, 'label' => __('Order Updated')]];
    }
}
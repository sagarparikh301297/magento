<?php


namespace SimpleMagento\CustomReport\Model\Config\Source;


use Magento\Framework\Option\ArrayInterface;

class SelectOrderStatus implements ArrayInterface
{

    /**
     * Return array of options as value-label pairs
     *
     * @return array Format: array(array('value' => '<value>', 'label' => '<label>'), ...)
     */
    public function toOptionArray()
    {
        return [['value' => 'day', 'label' => __('Day')], ['value' => 'month', 'label' => __('Month')], ['value' => 'year', 'label' => __('Year')]];
    }
}
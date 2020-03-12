<?php


namespace SimpleMagento\QuestionAnswer\Model\Config\Source;


use Magento\Framework\Option\ArrayInterface;

class IsApprove implements ArrayInterface
{

    /**
     * Return array of options as value-label pairs
     *
     * @return array Format: array(array('value' => '<value>', 'label' => '<label>'), ...)
     */
    public function toOptionArray()
    {
        return [['value' => 0, 'label' => __('Not Approved')], ['value' => 1, 'label' => __('Approved')]];
    }
}
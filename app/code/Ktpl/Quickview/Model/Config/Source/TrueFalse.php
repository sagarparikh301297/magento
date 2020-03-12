<?php
/**
 *
 * @category   Krish
 * @package    Ktpl_Quickview
 * @author     Extension Team
 */
namespace Ktpl\Quickview\Model\Config\Source;

use Magento\Framework\Option\ArrayInterface;

/**
 * Class TrueFalse
 *
 * @package Ktpl\Quickview\Model\Config\Source
 */
class TrueFalse implements ArrayInterface
{
    /**
     * Return list of TrueFalse Options
     *
     * @return array Format: array(array('value' => '<value>', 'label' => '<label>'), ...)
     */
    public function toOptionArray()
    {
        return [
            [
                'value' => 'true',
                'label' => __('True')
            ],
            [
                'value' => 'false',
                'label' => __('False')
            ]
        ];
    }
}

<?php
/**
 * @category    Ktpl
 * @package     Ktpl_BetterPopup
 */

namespace Ktpl\BetterPopup\Model\Config\Source;

use Magento\Framework\Option\ArrayInterface;

/**
 * Class ButtonDirection
 * @package Ktpl\BetterPopup\Model\Config\Source
 */
class ButtonDirection implements ArrayInterface
{
    const FLOAT_LEFT  = 1;
    const FLOAT_RIGHT = 2;

    /**
     * Return array of options as value-label pairs
     *
     * @return array Format: array(array('value' => '<value>', 'label' => '<label>'), ...)
     */
    public function toOptionArray()
    {
        return [
            ['value' => self::FLOAT_LEFT, 'label' => __('Left')],
            ['value' => self::FLOAT_RIGHT, 'label' => __('Right')],
        ];
    }
}

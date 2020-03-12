<?php
/**
 * @category    Ktpl
 * @package     Ktpl_BetterPopup
 */

namespace Ktpl\BetterPopup\Model\Config\Source;

use Magento\Framework\Option\ArrayInterface;

/**
 * Class Responsive
 * @package Ktpl\BetterPopup\Model\Config\Source
 */
class Responsive implements ArrayInterface
{
    const CENTER_POPUP     = 1;
    const FULLSCREEN_POPUP = 2;

    /**
     * Return array of options as value-label pairs
     *
     * @return array Format: array(array('value' => '<value>', 'label' => '<label>'), ...)
     */
    public function toOptionArray()
    {
        return [
            ['value' => self::CENTER_POPUP, 'label' => __('Center Popup')],
            ['value' => self::FULLSCREEN_POPUP, 'label' => __('FullScreen Popup')],
        ];
    }
}

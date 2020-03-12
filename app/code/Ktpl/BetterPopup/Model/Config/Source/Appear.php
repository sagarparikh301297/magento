<?php
/**
 * @category    Ktpl
 * @package     Ktpl_BetterPopup
 */

namespace Ktpl\BetterPopup\Model\Config\Source;

use Magento\Framework\Option\ArrayInterface;

/**
 * Class Appear
 * @package Ktpl\BetterPopup\Model\Config\Source
 */
class Appear implements ArrayInterface
{
    const EXIT_INTENT       = 1;
    const AFTER_PAGE_LOADED = 2;
    const AFTER_X_SECONDS   = 3;
    const AFTER_SCROLL_DOWN = 4;

    /**
     * Return array of options as value-label pairs
     *
     * @return array Format: array(array('value' => '<value>', 'label' => '<label>'), ...)
     */
    public function toOptionArray()
    {
        return [
            ['value' => self::EXIT_INTENT, 'label' => __('Exit Intent')],
            ['value' => self::AFTER_PAGE_LOADED, 'label' => __('After page loaded')],
            ['value' => self::AFTER_X_SECONDS, 'label' => __('After X seconds')],
            ['value' => self::AFTER_SCROLL_DOWN, 'label' => __('After scrolling down X% of page')],
        ];
    }
}

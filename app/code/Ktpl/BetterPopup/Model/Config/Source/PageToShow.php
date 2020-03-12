<?php
/**
 * @category    Ktpl
 * @package     Ktpl_BetterPopup
 */

namespace Ktpl\BetterPopup\Model\Config\Source;

use Magento\Framework\Option\ArrayInterface;

/**
 * Class PageToShow
 * @package Ktpl\BetterPopup\Model\Config\Source
 */
class PageToShow implements ArrayInterface
{
    const SPECIFIC_PAGES  = 1;
    const ALL_PAGES       = 2;
    const MANUALLY_INSERT = 3;

    /**
     * Return array of options as value-label pairs
     *
     * @return array Format: array(array('value' => '<value>', 'label' => '<label>'), ...)
     */
    public function toOptionArray()
    {
        return [
            ['value' => self::SPECIFIC_PAGES, 'label' => __('Specific pages')],
            ['value' => self::ALL_PAGES, 'label' => __('All Pages')],
            ['value' => self::MANUALLY_INSERT, 'label' => __('Manually Insert')],
        ];
    }
}

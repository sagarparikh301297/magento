<?php
/**
 * @category    Ktpl
 * @package     Ktpl_BetterPopup
 */

namespace Ktpl\BetterPopup\Model\Config\Backend;

use Exception;
use Magento\Framework\App\Config\Value;

/**
 * Class Validate
 * @package Ktpl\BetterPopup\Model\Config\Backend
 */
class Validate extends Value
{
    /**
     * Check value not null Exclude and Include
     *
     * @return Value
     * @throws Exception
     */
    public function beforeSave()
    {
        $pageToShow = $this->getData('fieldset_data')['which_page_to_show'];
        $inPage = $this->getData('fieldset_data')['include_pages'];
        $inPageUrl = $this->getData('fieldset_data')['include_pages_with_url'];

        if ($pageToShow == 1 && !($inPage || $inPageUrl)) {
            throw new Exception(__('Please enter the value into one of the following boxes: Include page(s) and Include Page(s) with URL contains.'));
        }

        return parent::beforeSave();
    }
}

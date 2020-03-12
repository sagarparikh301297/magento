<?php
/**
 * @category    Ktpl
 * @package     Ktpl_BetterPopup
 */

namespace Ktpl\BetterPopup\Block\Dashboard;

use Magento\Framework\Phrase;
use Ktpl\BetterPopup\Block\Subscriber;

/**
 * Class Newsletter
 * @package Ktpl\BetterPopup\Block\Dashboard
 */
class Newsletter extends Subscriber
{
    /**
     * path of template
     */
    protected $_template = 'dashboard/newsletter.phtml';

    /**
     * @return Phrase|string
     */
    public function getTitle()
    {
        return __('Subscribers');
    }
}

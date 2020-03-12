<?php
/**
 * @category    Ktpl
 * @package     Ktpl_BetterPopup
 */

namespace Ktpl\BetterPopup\Block\Email;

use Ktpl\BetterPopup\Block\Subscriber;

/**
 * Class Template
 * @package Ktpl\BetterPopup\Block\Email
 */
class Template extends Subscriber
{
    /**
     * Get list email subscribers in the week
     *
     * @return array
     */
    public function getListEmailSubscriberWeek()
    {
        $listEmail = [];
        $subscribersCollection = $this->getSubscriberInWeek($this->_helperData->getStoreId());
        foreach ($subscribersCollection as $item) {
            $listEmail[] = $item->getData('subscriber_email');
        }

        return $listEmail;
    }

    /**
     * Get Format Current time (title email)
     *
     * @return false|string
     */
    public function getCurrentTime()
    {
        $date = $this->_getDayDate->gmtDate('Y-M-d');

        return date('d M Y', strtotime($date));
    }
}

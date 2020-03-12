<?php
/**
 * @category    Ktpl
 * @package     Ktpl_BetterPopup
 */

namespace Ktpl\BetterPopup\Block;

use Magento\Framework\Stdlib\DateTime\DateTime;
use Magento\Framework\View\Element\Template;
use Magento\Newsletter\Model\ResourceModel\Subscriber\Collection;
use Magento\Newsletter\Model\ResourceModel\Subscriber\CollectionFactory;
use Magento\Widget\Block\BlockInterface;
use Ktpl\BetterPopup\Helper\Data as HelperData;

/**
 * Class Subscriber
 * @package Ktpl\BetterPopup\Block
 */
class Subscriber extends Template implements BlockInterface
{
    /**
     * @var CollectionFactory
     */
    protected $_subscriberCollectionFactory;

    /**
     * @var DateTime
     */
    protected $_getDayDate;

    /**
     * @var HelperData
     */
    protected $_helperData;

    /**
     * Subscriber constructor.
     *
     * @param Template\Context $context
     * @param HelperData $helperData
     * @param CollectionFactory $subscriberCollectionFactory
     * @param DateTime $getDayDate
     * @param array $data
     */
    public function __construct(
        Template\Context $context,
        HelperData $helperData,
        CollectionFactory $subscriberCollectionFactory,
        DateTime $getDayDate,
        array $data = []
    ) {
        parent::__construct($context, $data);

        $this->_helperData = $helperData;
        $this->_subscriberCollectionFactory = $subscriberCollectionFactory;
        $this->_getDayDate = $getDayDate;
    }

    /**
     * @param $from
     * @param $to
     *
     * @return Collection
     */
    public function getSubscriberCollection($from, $to, $storeId)
    {
        $subscribersCollection = $this->_subscriberCollectionFactory->create()->useOnlySubscribed()
            ->addFieldToFilter('change_status_at', ['from' => $from, 'to' => $to])
            ->addStoreFilter($storeId);

        return $subscribersCollection;
    }

    /**
     * Get Subscriber Collection today
     *
     * @return Collection
     */
    public function getSubscriberToday()
    {
        $form = $this->_getDayDate->date(null, '0:0:0');
        $to = $this->_getDayDate->date(null, '23:59:59');
        $collection = $this->getSubscriberCollection($form, $to, $this->_helperData->getStoreId());

        return $collection;
    }

    /**
     * Get Subscriber Collection in a week
     *
     * @param $storeId
     *
     * @return Collection
     */
    public function getSubscriberInWeek($storeId = null)
    {
        $now = date('Y-m-d h:i:s');
        $to = date('Y-m-d h:i:s', strtotime('+1 day'));
        $from = strtotime('-7 day', strtotime($now));
        $from = date('Y-m-d h:i:s', $from);

        return $this->getSubscriberCollection($from, $to, $storeId);
    }

    /**
     * Get Subscriber Collection in a month
     *
     * @return Collection
     */
    public function getSubscriberInMonth()
    {
        $now = date('Y-m-d h:i:s');
        $to = date('Y-m-d h:i:s', strtotime('+1 day'));
        $from = strtotime('-30 day', strtotime($now));
        $from = date('Y-m-d h:i:s', $from);
        $collection = $this->getSubscriberCollection($from, $to, $this->_helperData->getStoreId());

        return $collection;
    }

    /**
     * Get Unsubscribers Collection in the week
     *
     * @param $storeId
     *
     * @return mixed
     */
    public function getUnSubscriberCollection($storeId)
    {
        $to = date('Y-m-d h:i:s');
        $from = strtotime('-7 day', strtotime($to));
        $from = date('Y-m-d h:i:s', $from);
        $unSubscribersCollection = $this->_subscriberCollectionFactory->create()
            ->addFieldToFilter('subscriber_status', \Magento\Newsletter\Model\Subscriber::STATUS_UNSUBSCRIBED)
            ->addFieldToFilter('change_status_at', ['from' => $from, 'to' => $to])
            ->addStoreFilter($storeId);

        return $unSubscribersCollection;
    }
}

<?php
/**
 * @category    Ktpl
 * @package     Ktpl_BetterPopup
 */

namespace Ktpl\BetterPopup\Cron;

use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Exception\MailException;
use Magento\Store\Model\StoreManagerInterface;
use Ktpl\BetterPopup\Controller\Adminhtml\Send\Send;
use Ktpl\BetterPopup\Helper\Data;

/**
 * Class SendMail
 * @package Ktpl\BetterPopup\Cron
 */
class SendMail
{
    /**
     * @var StoreManagerInterface
     */
    protected $_storeManager;

    /**
     * @var Data
     */
    protected $_helperData;

    /**
     * @var Send
     */
    protected $_send;

    /**
     * SendMail constructor.
     *
     * @param Data $helperData
     * @param Send $send
     * @param StoreManagerInterface $storeManager
     */
    public function __construct(
        Data $helperData,
        Send $send,
        StoreManagerInterface $storeManager
    ) {
        $this->_helperData = $helperData;
        $this->_send = $send;
        $this->_storeManager = $storeManager;
    }

    /**
     * @throws LocalizedException
     * @throws MailException
     */
    public function execute()
    {
        foreach ($this->_storeManager->getStores() as $store) {
            if ($this->_helperData->isEnabled($store->getId()) && $this->_helperData->isSendEmail($store->getId())) {
                $this->_send->sendMail($store);
            }
        }
    }
}

<?php
/**
 * @category    Ktpl
 * @package     Ktpl_BetterPopup
 */

namespace Ktpl\BetterPopup\Plugin\Controller\Subscriber;

use Exception;
use Magento\Customer\Api\AccountManagementInterface as CustomerAccountManagement;
use Magento\Customer\Model\Session;
use Magento\Customer\Model\Url as CustomerUrl;
use Magento\Framework\App\Action\Context;
use Magento\Framework\Controller\Result\Json;
use Magento\Framework\Controller\Result\JsonFactory;
use Magento\Framework\Exception\LocalizedException;
use Magento\Newsletter\Model\SubscriberFactory;
use Magento\Store\Model\StoreManagerInterface;
use Ktpl\BetterPopup\Helper\Data;

/**
 * Class NewAction
 * @package Ktpl\BetterPopup\Plugin\Controller\Subscriber
 */
class NewAction extends \Magento\Newsletter\Controller\Subscriber\NewAction
{
    /**
     * @var JsonFactory
     */
    protected $resultJsonFactory;

    /**
     * @var Data
     */
    protected $_helperData;

    /**
     * NewAction constructor.
     *
     * @param Context $context
     * @param SubscriberFactory $subscriberFactory
     * @param Session $customerSession
     * @param StoreManagerInterface $storeManager
     * @param CustomerUrl $customerUrl
     * @param CustomerAccountManagement $customerAccountManagement
     * @param JsonFactory $resultJsonFactory
     * @param Data $helperData
     */
    public function __construct(
        Context $context,
        SubscriberFactory $subscriberFactory,
        Session $customerSession,
        StoreManagerInterface $storeManager,
        CustomerUrl $customerUrl,
        CustomerAccountManagement $customerAccountManagement,
        JsonFactory $resultJsonFactory,
        Data $helperData
    ) {
        $this->resultJsonFactory = $resultJsonFactory;
        $this->_helperData = $helperData;

        parent::__construct(
            $context,
            $subscriberFactory,
            $customerSession,
            $storeManager,
            $customerUrl,
            $customerAccountManagement
        );
    }

    /**
     * @param $subject
     * @param $proceed
     *
     * @return Json
     */
    public function aroundExecute($subject, $proceed)
    {
        if (!$this->_helperData->isEnabled() || !$this->getRequest()->isAjax()) {
            return $proceed();
        }

        $response = [];
        if ($this->getRequest()->isPost() && $this->getRequest()->getPost('email')) {
            $email = (string) $this->getRequest()->getPost('email');

            try {
                $this->validateEmailFormat($email);
                $this->validateGuestSubscription();
                $this->validateEmailAvailable($email);

                $this->_subscriberFactory->create()->subscribe($email);
                if (!$this->_helperData->versionCompare('2.2.0')) {
                    $this->_subscriberFactory->create()
                        ->loadByEmail($email)
                        ->setChangeStatusAt(date('Y-m-d h:i:s'))->save();
                }
            } catch (LocalizedException $e) {
                $response = [
                    'success' => true,
                    'msg'     => __('There was a problem with the subscription: %1', $e->getMessage()),
                ];
            } catch (Exception $e) {
                $response = [
                    'status' => 'ERROR',
                    'msg'    => __('Something went wrong with the subscription: %1', $e->getMessage()),
                ];
            }
        }

        return $this->resultJsonFactory->create()->setData($response);
    }
}

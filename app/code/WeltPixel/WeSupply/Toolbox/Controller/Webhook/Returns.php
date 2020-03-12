<?php

/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace WeSupply\Toolbox\Controller\Webhook;

use Magento\Framework\Api\SearchCriteriaBuilder;
use Magento\Framework\App\Action\Action;
use Magento\Framework\App\Action\Context;
use Magento\Framework\App\ProductMetadataInterface;
use Magento\Framework\App\ResourceConnection;
use Magento\Framework\App\ResponseInterface;
use Magento\Framework\Controller\Result\Json as ResultJson;
use Magento\Framework\Controller\Result\JsonFactory;
use Magento\Framework\Controller\ResultInterface;
use Magento\Framework\DB\Adapter\AdapterInterface;
use Magento\Framework\Phrase;
use Magento\Framework\Serialize\Serializer\Json;
use Magento\Sales\Api\CreditmemoManagementInterface;
use Magento\Sales\Controller\Adminhtml\Order\CreditmemoLoader;
use Magento\Sales\Model\Order\Email\Sender\CreditmemoSender;
use Magento\Sales\Model\Order\Invoice;
use Magento\Sales\Model\OrderRepository;
use Magento\Store\Model\StoreManagerInterface;
use WeSupply\Toolbox\Api\WeSupplyApiInterface;
use WeSupply\Toolbox\Api\Data\ReturnslistInterface;
use WeSupply\Toolbox\Api\GiftcardInterface;
use WeSupply\Toolbox\Helper\Data as Helper;
use WeSupply\Toolbox\Logger\Logger as Logger;
use WeSupply\Toolbox\Model\Webhook;

/**
 * Class Returns
 * @package WeSupply\Toolbox\Controller\Webhook
 */

class Returns extends Action
{
    /**#@+
     * Constants
     */
    const WESUPPLY_API_ENDPOINT = 'returns/grabById';

    /**
     * @var JsonFactory
     */
    protected $resultJsonFactory;

    /**
     * @var ProductMetadataInterface
     */
    private $productMetadata;

    /**
     * @var ResourceConnection
     */
    private $resource;

    /**
     * @var AdapterInterface
     */
    protected $connection;

    /**
     * @var Json
     */
    protected $json;

    /**
     * @var OrderRepository
     */
    protected $orderRepository;

    /**
     * @var SearchCriteriaBuilder
     */
    protected $searchCriteriaBuilder;

    /**
     * @var Invoice
     */
    protected $invoice;

    /**
     * @var CreditmemoLoader
     */
    protected $creditMemoLoader;

    /**
     * @var CreditmemoManagementInterface
     */
    protected $creditMemoManagement;

    /**
     * @var CreditmemoSender
     */
    protected $creditMemoSender;

    /**
     * @var StoreManagerInterface
     */
    protected $storeManager;

    /**
     * @var WeSupplyApiInterface
     */
    protected $weSupplyApiInterface;

    /**
     * @var ReturnslistInterface
     */
    protected $returnsList;

    /**
     * @var GiftcardInterface
     */
    protected $giftCardInterface;

    /**
     * @var Helper
     */
    protected $helper;

    /**
     * @var Logger
     */
    protected $logger;

    /**
     * @var Webhook
     */
    protected $webhook;

    /**
     * @var array
     */
    protected $params;

    /**
     * @var array
     */
    protected $returnDetails = [];

    /**
     * @var array
     */
    protected $creditMemoData = [];

    /**
     * Returns constructor.
     * @param Context $context
     * @param ProductMetadataInterface $productMetadata
     * @param ResourceConnection $resourceConnection
     * @param JsonFactory $jsonFactory
     * @param Json $json
     * @param OrderRepository $orderRepository
     * @param SearchCriteriaBuilder $searchCriteriaBuilder
     * @param Invoice $invoice
     * @param CreditmemoLoader $creditMemoLoader
     * @param CreditmemoManagementInterface $creditMemoManagement
     * @param CreditmemoSender $creditMemoSender
     * @param StoreManagerInterface $storeManager
     * @param ReturnslistInterface $returnsList
     * @param GiftcardInterface $giftCardInterface
     * @param WeSupplyApiInterface $weSupplyApiInterface
     * @param Helper $helper
     * @param Logger $logger
     * @param Webhook $webhook
     */
    public function __construct(
        Context $context,
        ProductMetadataInterface $productMetadata,
        ResourceConnection $resourceConnection,
        JsonFactory $jsonFactory,
        Json $json,
        OrderRepository $orderRepository,
        SearchCriteriaBuilder $searchCriteriaBuilder,
        Invoice $invoice,
        CreditmemoLoader $creditMemoLoader,
        CreditmemoManagementInterface $creditMemoManagement,
        CreditmemoSender $creditMemoSender,
        StoreManagerInterface $storeManager,
        GiftcardInterface $giftCardInterface,
        ReturnslistInterface $returnsList,
        WeSupplyApiInterface $weSupplyApiInterface,
        Helper $helper,
        Logger $logger,
        Webhook $webhook
    ) {
        $this->productMetadata = $productMetadata;
        $this->resultJsonFactory = $jsonFactory;
        $this->json = $json;
        $this->orderRepository = $orderRepository;
        $this->searchCriteriaBuilder = $searchCriteriaBuilder;
        $this->invoice = $invoice;
        $this->creditMemoLoader = $creditMemoLoader;
        $this->creditMemoManagement = $creditMemoManagement;
        $this->creditMemoSender = $creditMemoSender;
        $this->storeManager = $storeManager;
        $this->giftCardInterface = $giftCardInterface;
        $this->returnsList = $returnsList;
        $this->weSupplyApiInterface = $weSupplyApiInterface;
        $this->logger = $logger;
        $this->helper = $helper;
        $this->webhook = $webhook;

        $this->resource = $resourceConnection;
        $this->connection = $resourceConnection->getConnection();

        parent::__construct($context);
    }

    /**
     * @return ResponseInterface|ResultJson|ResultInterface
     */
    public function execute()
    {
        $resultJson = $this->resultJsonFactory->create();

        if (!$this->webhook->canProceedsRequest()) {
            $error = $this->webhook->getError();
            $this->logger->addError($error['message']);

            return $resultJson->setData($error);
        }

        $this->params = $this->getRequest()->getParams();
        if (!$this->webhook->validateParams('return', $this->params)) {
            $error = $this->webhook->getError();
            $this->logger->addError($error['message']);

            return $resultJson->setData($error);
        }

        $this->returnDetails = $this->webhook->proceed(
            self::WESUPPLY_API_ENDPOINT,
            'GET',
            [
                'provider' => 'Magento',
                'reference' => $this->params['reference']
            ]
        );

        if ($this->webhook->getError() || !$this->returnDetails) {
            return $resultJson->setData($this->webhook->getError());
        }

        /**
         * First, we need to check what type of refund was requested in order to proceed
         * Gift Card and Store Credit is only available for Magento Commerce (Enterprise)
         */
        if (
            !$this->isEnterprise() &&
            ($this->getRefundType() == 'gift_card' || $this->getRefundType() == 'credit')
        ) {
            $message = 'refund is only available for Magento Commerce.';
            if ($this->getRefundType() == 'gift_card') {
                $message = __('Gift Card %1', $message);
            }
            if ($this->getRefundType() == 'credit') {
                $message = __('Store Credit %1', $message);
            }
            return $resultJson->setData(['success' => false, 'status-title' => 'Refund Failed', 'status-message' => $message]);
        }

        // create credit memo
        $this->prepareCreditMemoParams();
        $creditMemo = $this->createCreditMemo();

        // save processed refund and notify wesupply
        if ($creditMemo['success'] === TRUE) {
            $processedReturns = [$this->params['reference'] => ['message' => ($creditMemo['status-message'])->getText()]];
            // $this->notifyProcessedReturns($processedReturns);
            $this->saveProcessedReturns($processedReturns);
        }

        return $resultJson->setData(
            ['success' => $creditMemo['success'], 'status-title' => $creditMemo['status-title'], 'status-message' => $creditMemo['status-message']]
        );
    }

    /**
     * Set credit memo data
     */
    private function prepareCreditMemoParams()
    {
        $this->creditMemoData['increment_id'] = $this->helper->recursivelyGetArrayData(['ExternOrderNo'], $this->returnDetails, null);
        $this->creditMemoData['do_offline'] = $this->getOfflineFlag();
        $this->creditMemoData['shipping_amount'] = $this->refundShipping() ? $this->calculateShipping() : 0;
        $this->creditMemoData['adjustment_positive'] = 0; // not set !!!
        $this->creditMemoData['adjustment_negative'] = $this->helper->recursivelyGetArrayData(['logistics', 'cost'], $this->returnDetails, 0);
        $this->creditMemoData['comment_text'] = $this->helper->recursivelyGetArrayData(['return_comment'], $this->returnDetails, ''); // not set !!!
        $this->creditMemoData['send_email'] = false; // not set !!!
        $this->creditMemoData['items'] = $this->getReturnItems();
        $this->creditMemoData['store_credit_amount'] = $this->getOfflineFlag() ? $this->getStoreCreditAmount() : 0;
        $this->creditMemoData['gift_card_amount'] = $this->getOfflineFlag() ? $this->getGiftCardAmount() : 0;
    }

    /**
     * @return array
     */
    private function createCreditMemo()
    {
        /**
            Expected params:

            $creditMemoData['do_offline'] = 1;
            $creditMemoData['shipping_amount'] = 0;
            $creditMemoData['adjustment_positive'] = 0;
            $creditMemoData['adjustment_negative'] = 0;
            $creditMemoData['comment_text'] = 'comment_text_for_creditmemo';
            $creditMemoData['send_email'] = 1;
            // $creditMemoData['refund_customerbalance_return_enable'] = 0; // only for Magento commerce
            $orderItemId = 10; // pass order item id
            $itemToCredit[$orderItemId] = ['qty'=>1];
            $creditMemoData['items'] = $itemToCredit;
         */

        $order = $this->getOrder();
        if (!$order) {
            return ['success' => false, 'status-title' => 'Refund Failed', 'status-message' => __('Order with ID %1 was not found.', $this->creditMemoData['increment_id'])];
        }

        if ($order->getPayment()->getMethodInstance()->isOffline() && !$this->creditMemoData['do_offline']) {
            $paymentInfo = $order->getPayment()->getAdditionalInformation();
            return ['success' => false, 'status-title' => 'Refund Failed', 'status-message' => __('Only offline type refunds are allowed for this order. Payment type used was %1.', $paymentInfo['method_title'])];
        }

        // clear unnecessary data before load credit memo
        $orderIncrementId = $this->creditMemoData['increment_id'];
        unset($this->creditMemoData['increment_id']);
        $giftCardAmount = $this->creditMemoData['gift_card_amount'];
        unset($this->creditMemoData['gift_card_amount']);
        $storeCreditAmount = $this->creditMemoData['store_credit_amount'];
        unset($this->creditMemoData['store_credit_amount']);

        try {
            if ($storeCreditAmount > 0) { // only available for Magento Enterprise
                $this->creditMemoData['refund_customerbalance_return'] = $storeCreditAmount;
                $this->creditMemoData['refund_customerbalance_return_enable'] = 1;
            }

            $this->creditMemoLoader->setOrderId($order->getId());
            $this->creditMemoLoader->setCreditmemo($this->creditMemoData);

            $creditMemo = $this->creditMemoLoader->load();
            if (!$creditMemo) {
                // @TODO implement response['reason'] read | display results accordingly on WeSupply
                return ['success' => false, 'reason' => 'order-locked', 'status-title' => 'Refund Failed', 'status-message' => __('Credit memo cannot be created. Check Magento order (Status %1).', strtoupper($order->getStatus()))];
            }

            if (!$creditMemo->isValidGrandTotal()) {
                return ['success' => false, 'status-title' => 'Refund Failed', 'status-message' => __('The credit memo\'s total must be positive.')];
            }

            if (!empty($this->creditMemoData['comment_text'])) {
                $creditMemo->addComment(
                    $this->creditMemoData['comment_text'],
                    isset($this->creditMemoData['comment_customer_notify']), // is not set anywhere
                    isset($this->creditMemoData['is_visible_on_front']) // is not set anywhere
                );

                $creditMemo->setCustomerNote($this->creditMemoData['comment_text']);
                $creditMemo->setCustomerNoteNotify(isset($this->creditMemoData['comment_customer_notify']));
            }

            $creditMemo->getOrder()->setCustomerNoteNotify(!empty($this->creditMemoData['send_email']));

            if (!$this->creditMemoData['do_offline']) {
                $invoiceIds = [];
                $invoices = $order->getInvoiceCollection();
                foreach ($invoices as $invoice) {
                    if ($invoice->canRefund()) {
                        $invoiceIds[] = $invoice->getIncrementId();
                    }
                }

                if (!$invoiceIds) {
                    return ['success' => false, 'status-title' => 'Refund Failed', 'status-message' => __('Invoice not found or cannot be refunded for order ID %1', $this->creditMemoData['increment_id'])];
                }
                $invoiceObj = $this->invoice->loadByIncrementId(reset($invoiceIds));
                $creditMemo->setInvoice($invoiceObj);
            }

            // process refund
            $this->creditMemoManagement->refund($creditMemo, (bool)$this->creditMemoData['do_offline']);

            if (!empty($creditMemoData['send_email'])) {
                $this->creditMemoSender->send($creditMemo);
            }

            $giftCardMessage = '';
            if ($giftCardAmount > 0) { // only available for Magento Enterprise
                $giftCardMessage = $this->generateGiftCard($creditMemo, $giftCardAmount);
            }

            return ['success' => true, 'status-title' => 'Successfully Refunded', 'status-message' => __('Credit Memo %1 created for order %2. %3', $creditMemo->getIncrementId(), $orderIncrementId, $giftCardMessage)];

        } catch (\Exception $e) {
            return ['success' => false, 'status-title' => 'Refund Failed', 'status-message' => __('Credit Memo not created! %1', $e->getMessage())];
        }
    }

    /**
     * @param $creditMemo
     * @param $giftCardAmount
     * @return array|Phrase
     */
    private function generateGiftCard($creditMemo, $giftCardAmount)
    {
        try {
            $order = $creditMemo->getOrder();
            $customerEmail = $order->getCustomerEmail();
            $customerName = $order->getCustomerFirstname() . ' ' . $order->getCustomerLastname();
            $websiteId = $this->storeManager->getStore($order->getStoreId())->getWebsiteId();

            // @TODO Giftcard.php needs refactoring
            $this->giftCardInterface->createAndDeliverGiftCard($giftCardAmount, $customerEmail, $customerName, $websiteId);

            $giftCardCode = $this->giftCardInterface->getGeneratedCode();
            $orderHistoryComment = __('Gift Card code %1', $giftCardCode);
            $order->addStatusHistoryComment($orderHistoryComment)->save();

            return $orderHistoryComment;
        } catch (\Exception $e) {
            $message = __('Error occurred while creating Gift Card for Credit Memo %1 with message %2', $creditMemo->getIncrementId(), $e->getMessage());
            return ['success' => false, 'status-title' => 'Refund Failed', 'status-message' => $message];
        }
    }

    /**
     * @return bool
     */
    private function getOfflineFlag()
    {
        switch (strtolower($this->getRefundType())) {
            case 'refund': // online
                return false;
            default: // for any others will be offline
                return true;
        }
    }

    /**
     * @return array
     */
    private function getReturnItems()
    {
        $returnItems = [];
        $items = isset($this->returnDetails['items']) ? $this->returnDetails['items'] : [];
        foreach ($items as $item) {
            $returnItems[$item['itemid']] = [
                'qty' => $item['quantity'],
                'back_to_stock' => (bool) $this->returnDetails['restock']
            ];

            $this->appendItemReturnReason($item);
        }

        return $returnItems;
    }

    /**
     * @param $item
     */
    private function appendItemReturnReason($item)
    {
        // $this->creditMemoData['comment_text'] .= 'SKU: ' . $item['sku'] . ' ---- ' . $item['return_reason'] . PHP_EOL;
        $this->creditMemoData['comment_text'] .= $item['reason_desc'] . PHP_EOL;
    }

    /**
     * @return string
     */
    private function getRefundType()
    {
        return $this->helper->recursivelyGetArrayData(['logistics','type'], $this->returnDetails, 'offline');
    }

    /**
     * @return int|string
     */
    private function getStoreCreditAmount()
    {
        if ($this->getRefundType() == 'credit') {
            return $this->helper->recursivelyGetArrayData(['logistics', 'refund_total'], $this->returnDetails, 0);
        }

        return 0;
    }

    /**
     * @return int|string
     */
    private function getGiftCardAmount()
    {
        if ($this->getRefundType() == 'gift_card') {
            return $this->helper->recursivelyGetArrayData(['logistics', 'refund_total'], $this->returnDetails, 0);
        }

        return 0;
    }

    /**
     * @return bool
     */
    private function isEnterprise()
    {
        if (strtolower($this->productMetadata->getEdition()) === 'enterprise') {
            return true;
        }

        return false;
    }

    /**
     * @return bool|mixed
     */
    private function getOrder()
    {
        $searchCriteria = $this->searchCriteriaBuilder
            ->addFilter('increment_id', $this->creditMemoData['increment_id'])->create();
        $orderList = $this->orderRepository->getList($searchCriteria)->getItems();

        $order = array_values(array_filter($orderList, function ($order) {
            return $order->getIncrementId() == $this->creditMemoData['increment_id'];
        }));

        return reset($order) ?? false;
    }

    /**
     * @return bool
     */
    private function refundShipping()
    {
        return (bool) $this->helper->recursivelyGetArrayData(['logistics','refund_shipping'], $this->returnDetails, 0);
    }

    /**
     * @return float
     */
    private function calculateShipping()
    {
        $total = (float) $this->helper->recursivelyGetArrayData(['logistics','refund_total'], $this->returnDetails, 0);
        $subtotal = (float) $this->helper->recursivelyGetArrayData(['logistics','refund_subtotal'], $this->returnDetails, 0);
        $cost = (float) $this->helper->recursivelyGetArrayData(['logistics','cost'], $this->returnDetails, 0);

        return $total - $subtotal + $cost;
    }

    /**
     * @TODO WeSupplyApiInterface needs refactoring
     * @param array $processedReturns
     */
    private function notifyProcessedReturns(array $processedReturns)
    {
        $this->weSupplyApiInterface->notifyProcessedReturns($processedReturns);
    }

    /**
     * @TODO WeSupplyApiInterface needs refactoring
     * @param array $processedReturns
     */
    private function saveProcessedReturns(array $processedReturns)
    {
        $processedToSave = array();
        foreach ($processedReturns as $returnId => $data){
            $processedToSave[] = ['return_id' => $returnId];
        }

        $table = $this->returnsList->getResource()->getMainTable();

        try {
            $tableName = $this->resource->getTableName($table);
            $this->connection->insertMultiple($tableName, $processedToSave);
        } catch (\Exception $e) {
            $this->logger->error('WeSupply saving returns to database error : '.$e->getMessage());
        }
    }
}

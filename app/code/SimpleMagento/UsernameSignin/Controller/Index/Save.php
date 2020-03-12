<?php


namespace SimpleMagento\UsernameSignin\Controller\Index;


use Magento\Framework\App\Action\Action;
use Magento\Framework\App\Action\Context;
use Magento\Framework\App\ResponseInterface;
use Magento\Customer\Model\Customer;

class Save extends Action
{

    /**
     * @var Customer
     */
    protected $customer;
    /**
     * @var \Magento\Framework\Controller\Result\JsonFactory
     */
    protected $resultJsonFactory;

    public function __construct(Context $context, Customer $customer,
                                \Magento\Framework\Controller\Result\JsonFactory $resultJsonFactory)
    {
        parent::__construct($context);
        $this->customer = $customer;
        $this->resultJsonFactory = $resultJsonFactory;
    }

    /**
     * Execute action based on request and return result
     *
     * Note: Request will be added as operation argument in future
     *
     * @return \Magento\Framework\Controller\ResultInterface|ResponseInterface
     * @throws \Magento\Framework\Exception\NotFoundException
     */
    public function execute()
    {
        $resultJson = $this->resultJsonFactory->create();
        $getUsername = $this->getRequest()->getParam('username');
            $customerData = $this->customer->getCollection()
                ->addFieldToFilter('username', $getUsername);
            if(!count($customerData)) {
                $data['value'] = 0;
                $resultJson->setData($data);
            } else {
                $data['value'] = 1;
                $resultJson->setData($data);
            }
        return $resultJson;
    }
}
<?php


namespace SimpleMagento\QuestionAnswer\Controller\Index;


use Magento\Framework\App\Action\Action;
use Magento\Framework\App\Action\Context;
use Magento\Framework\App\ResponseInterface;
use SimpleMagento\QuestionAnswer\Model\QuestionAnswerFactory;

class AskQuestion extends Action
{
    /**
     * @var QuestionAnswerFactory
     */
    protected $questionAnswerFactory;
    /**
     * @var \Magento\Framework\Controller\Result\JsonFactory
     */
    protected $jsonFactory;
    /**
     * @var \Magento\Customer\Model\Session
     */
    protected $session;
    /**
     * @var Psr\Log\LoggerInterface
     */
    protected $logger;

    public function __construct(Context $context,
                                QuestionAnswerFactory $questionAnswerFactory,
                                \Magento\Customer\Model\Session $session,
                                \Psr\Log\LoggerInterface $logger,
                                \Magento\Framework\Controller\Result\JsonFactory $jsonFactory)
    {
        parent::__construct($context);
        $this->questionAnswerFactory = $questionAnswerFactory;
        $this->jsonFactory = $jsonFactory;
        $this->session = $session;
        $this->logger = $logger;
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
        try {
            $getQuestion = $this->getRequest()->getParam('get_question');
            $getProductSku = $this->getRequest()->getParam('get_productSku');
            if($this->session->isLoggedIn()){
                $getCustomerId = $this->session->getCustomer()->getId();
            }else $getCustomerId = 0;

            $questionAnswerModel = $this->questionAnswerFactory->create();

            $questionAnswerModel->setData('question_title', $getQuestion);
            $questionAnswerModel->setData('question_productsku', $getProductSku);
            $questionAnswerModel->setData('question_customerid', $getCustomerId);

            $questionAnswerModel->save();

            $jsonData = ['result' => ['status' => 200, 'redirect' => 0, 'message' => 'saved successfully']];
            $result = $this->jsonFactory->create()->setData($jsonData);
            $this->messageManager->addSuccess('Question submitted succesfully.');
        } catch (\Exception $e) {
            $this->logger->critical($e->getMessage());
            $this->messageManager->addError($e->getMessage());
        }
        return $result;
    }
}
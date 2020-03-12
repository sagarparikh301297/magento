<?php


namespace SimpleMagento\QuestionAnswer\Controller\Adminhtml\Custom;

use Magento\Backend\App\Action;
use Magento\Framework\App\Request\Http;
use Magento\Framework\Registry;
use Magento\Framework\App\ResponseInterface;
use Magento\Framework\View\Result\PageFactory;
use SimpleMagento\QuestionAnswer\Model\QuestionAnswer;

class Edit extends Action
{
    /**
     * @var QuestionAnswer
     */
    protected $questionAnswer;
    /**
     * @var PageFactory
     */
    protected $pageFactory;
    /**
     * @var Registry
     */
    protected $registry;
    /**
     * @var Http
     */
    protected $http;

    public function __construct(Action\Context $context, PageFactory $pageFactory,
                                Http $http,
                                QuestionAnswer $questionAnswer,
                                Registry $registry)
    {
        parent::__construct($context);
        $this->questionAnswer = $questionAnswer;
        $this->pageFactory = $pageFactory;
        $this->registry = $registry;
        $this->http = $http;
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
        $id = $this->getRequest()->getParam("id");
        $model = $this->questionAnswer;
        $getData = $model->load($id);
        $this->registry->register("member",$getData);
        $resultPage = $this->pageFactory->create();

        return $resultPage;
    }
}
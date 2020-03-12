<?php
    namespace SimpleMagento\CustomAdmin\Controller\Adminhtml\Member;


    use Magento\Backend\App\Action;
    use Magento\Framework\App\ResponseInterface;
    use Magento\Backend\Model\View\Result\ForwardFactory;

    class NewAction extends Action
    {

        /**
        * @var ForwardFactory
        */
        protected $forwardFactory;

        public function __construct(
        ForwardFactory $forwardFactory,
        Action\Context $context)
        {
          parent::__construct($context);
          $this->forwardFactory = $forwardFactory;
        }
        protected function _isAllowed()
        {
            return $this->_authorization->isAllowed("SimpleMagento_CustomAdmin::parent");
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


          $resultForward = $this->forwardFactory->create();
          return $resultForward->forward('edit');
        }
    }
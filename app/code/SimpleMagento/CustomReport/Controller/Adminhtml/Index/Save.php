<?php
namespace SimpleMagento\CustomReport\Controller\Adminhtml\Index;

use Magento\Backend\App\Action;
use Magento\Backend\Model\View\Result\RedirectFactory;
use Magento\Framework\View\Result\PageFactory;

class Save extends Action
{
    /**
     * @var RedirectFactory
     */
    protected $resultRedirectFactory;
    /**
     * @var PageFactory
     */
    protected $pageFactory;

    public function __construct(
         PageFactory $pageFactory,
         RedirectFactory $redirectFactory,
         Action\Context $context)
    {
        parent::__construct($context);
        $this->resultRedirectFactory = $redirectFactory;
        $this->pageFactory = $pageFactory;
    }
    public function execute()
    {
        $resultRedirect = $this->resultRedirectFactory->create();
        $data = $this->getRequest()->getPostValue();
        $objectManager = \Magento\Framework\App\ObjectManager::getInstance();
        $customerSession = $objectManager->get('\Magento\Customer\Model\Session');
        $customerSession->setData($data);
        return $resultRedirect->setPath('*/*/listing');
    }
}


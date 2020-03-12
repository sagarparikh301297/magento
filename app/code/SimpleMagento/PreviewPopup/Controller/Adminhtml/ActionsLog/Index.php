<?php


namespace SimpleMagento\PreviewPopup\Controller\Adminhtml\ActionsLog;

use Magento\Backend\App\Action\Context;
use Magento\Framework\View\Result\PageFactory;
use Magento\Backend\App\Action;
use Magento\Backend\Model\UrlInterface;

class Index extends Action
{
    /**
     * @var string
     */
    const ADMIN_RESOURCE = 'SimpleMagento_PreviewPopup::actions_log';

    /**
     * @var PageFactory
     */
    public $resultPageFactory;

    /**
     * @var \Magento\Backend\Model\UrlInterface
     */
    protected $backendUrl;

    /**
     * Index constructor.
     * @param Context $context
     * @param PageFactory $resultPageFactory
     */
    public function __construct(
        Context $context,
        PageFactory $resultPageFactory,
        UrlInterface $backendUrl
    ) {
        $this->resultPageFactory = $resultPageFactory;
        $this->backendUrl = $backendUrl;
        parent::__construct($context);
    }

    /**
     * Index action
     * @return \Magento\Backend\Model\View\Result\Page
     */
    public function execute()
    {
        /** @var \Magento\Backend\Model\View\Result\Page $resultPage */
        $resultPage = $this->resultPageFactory->create();
        $resultPage->setActiveMenu('SimpleMagento_PreviewPopup::actions_log');
        $resultPage->addBreadcrumb(__('Test'), __('Actions Log'));
        $resultPage->getConfig()->getTitle()->prepend(__('Actions Log'));

        return $resultPage;
    }

    /**
     * access rights checking
     *
     * @return bool
     */
    protected function _isAllowed()
    {
        return $this->_authorization->isAllowed('SimpleMagento_PreviewPopup::actions_log');
    }
}
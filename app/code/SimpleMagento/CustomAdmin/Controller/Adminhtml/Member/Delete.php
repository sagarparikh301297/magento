<?php


namespace SimpleMagento\CustomAdmin\Controller\Adminhtml\Member;


use Magento\Backend\App\Action;
use Magento\Backend\Model\View\Result\RedirectFactory;
use Magento\Framework\View\Result\PageFactory;
use SimpleMagento\Database\Model\AffiliateMember;

class Delete extends Action
{
    protected $model;
    protected $pageFactory;
    protected $resultRedirectFactory;
    public function __construct(
        AffiliateMember $affiliateMember,
        PageFactory $pageFactory,
        RedirectFactory $redirectFactory,
        Action\Context $context)
    {
        parent::__construct($context);

        $this->model = $affiliateMember;
        $this->resultRedirectFactory = $redirectFactory;
        $this->pageFactory = $pageFactory;

    }
    protected function _isAllowed()
    {
        return $this->_authorization->isAllowed("SimpleMagento_CustomAdmin::parent");
    }

    public function execute()
    {
        $resultRedirect = $this->resultRedirectFactory->create();
        $id = $this->getRequest()->getParam('id');
        if($id) {
            $model = $this->model;
            $model->load($id);
            try {
                $model->delete();
                $this->messageManager->addSuccessMessage(__('Message Deleted'));
                return $resultRedirect->setPath('*/*/index');
            } catch (\Exception $e){
                $this->messageManager->addErrorMessage(__($e->getMessage()));
                return $resultRedirect->setPath('*/*/edit', ['id'=>$id]);
            }
        }

    }


}
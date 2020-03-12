<?php
namespace SimpleMagento\CustomAdmin\Controller\Adminhtml\Member;



use Magento\Backend\App\Action;
use Magento\Backend\Model\View\Result\RedirectFactory;
use Magento\Framework\App\ResponseInterface;
use Magento\Framework\View\Result\PageFactory;
use SimpleMagento\Database\Model\AffiliateMemberFactory;



class Save extends Action
{


    /**
     * @var AffiliateMember
     */
    protected $model;
    /**
     * @var RedirectFactory
     */
    protected $resultRedirectFactory;
    /**
     * @var PageFactory
     */
    private $pageFactory;

    protected $affiliateMember;


    public function __construct(
        AffiliateMemberFactory $affiliateMember,
        PageFactory $pageFactory,

        RedirectFactory $redirectFactory,
         Action\Context $context)
    {
        parent::__construct($context);

        $this->affiliateMember = $affiliateMember;
        $this->resultRedirectFactory = $redirectFactory;
        $this->pageFactory = $pageFactory;

    }
    protected function _isAllowed()
    {
        return $this->_authorization->isAllowed("SimpleMagento_CustomAdmin::parent");
    }

    public function execute()
    {
        $data = $this->getRequest()->getPostValue();
        $resultRedirect = $this->resultRedirectFactory->create();
        if($data['entity_end'] == null){
            unset($data['entity_end']);
            $newmember = $this->affiliateMember->create();

        }else {
            $newmember = $this->affiliateMember->create()->load($data['entity_end']);
        }
            $newmember->setData($data);
        try{
            $newmember->save();
                $this->messageManager->addSuccessMessage(__('Affiliate Member Saved Successfully'));
                $this->_getSession()->setFormData(false);
            return $resultRedirect->setPath('*/*/index');

        }catch (\Exception $e){
            $this->messageManager->addErrorMessage($e->getMessage());
        }

        return $resultRedirect->setPath('*/*/index');
    }
}


//        $writer = new \Zend\Log\Writer\Stream(BP . '/var/log/test.log');
//        $logger = new \Zend\Log\Logger();
//        $logger->addWriter($writer);
//        $logger->info('Array Log'.print_r($data, true));
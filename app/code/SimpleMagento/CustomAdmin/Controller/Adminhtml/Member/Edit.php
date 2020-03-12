<?php
namespace SimpleMagento\CustomAdmin\Controller\Adminhtml\Member;


use Magento\Backend\App\Action;
use Magento\Framework\App\Request\Http;
use Magento\Framework\Registry;
use Magento\Framework\App\ResponseInterface;
use Magento\Framework\View\Result\PageFactory;
use SimpleMagento\Database\Model\AffiliateMember;


class Edit extends Action
    {
        protected $pageFactory;



        protected $registry;
    /**
     * @var AffiliateMember
     */
    protected $affiliateMember;
    /**
     * @var Http
     */
    private $http;

    public function __construct(Action\Context $context,
         PageFactory $pageFactory,
         Http $http,
         AffiliateMember $affiliateMember,
         Registry $registry)
        {

            $this->pageFactory = $pageFactory;
            $this->registry = $registry;

            parent::__construct($context);
            $this->affiliateMember = $affiliateMember;
            $this->http = $http;
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
            //$this->http->getParams(); // all params
            //$id = $this->http->getParam('entity_end');
            $id = $this->getRequest()->getParam("id");
            //var_dump($id);exit();
            $model = $this->affiliateMember;
            if($id){
            $model->load($id);
            if(!$model->getId()){
                $this->messageManager->addErrorMessage(__('This member does not exists'));
                $result = $this->resultRedirectFactory->create();
                    return $result->setPath('affiliate/member/index');
                }
            }
        $data = $this->_getSession()->getFormData(true);
        if(!empty($data)){
             $model->setData($data);
        }

        $this->registry->register("member",$model);

        /**
        * @var \Magento\Framework\View\Result\Page $resultPage
        */
        $resultPage = $this->pageFactory->create();
        /* $resultPage->addBreadcrumb($id ? __("Edit Member"): __("Add A New Member"));*/

        if($id){
            $resultPage->getConfig()->getTitle()->prepend('Edit');
        }
        else{
           $resultPage->getConfig()->getTitle()->prepend('Add');
        }

        return $resultPage;
        }
    }
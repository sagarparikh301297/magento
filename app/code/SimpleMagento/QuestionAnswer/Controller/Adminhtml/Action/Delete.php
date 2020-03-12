<?php


namespace SimpleMagento\QuestionAnswer\Controller\Adminhtml\Action;

use Magento\Backend\App\Action;
use Magento\Backend\Model\View\Result\RedirectFactory;
use Magento\Framework\View\Result\PageFactory;
use SimpleMagento\QuestionAnswer\Model\QuestionAnswer;
use Magento\Catalog\Model\ProductRepository;

class Delete extends Action
{
    protected $model;
    /**
     * @var QuestionAnswer
     */
    protected $questionAnswer;
    /**
     * @var PageFactory
     */
    protected $pageFactory;
    /**
     * @var RedirectFactory
     */
    protected $redirectFactory;
    /**
     * @var ProductRepository
     */
    protected $productRepository;

    public function __construct(
        QuestionAnswer $questionAnswer,
        PageFactory $pageFactory,
        RedirectFactory $redirectFactory,
        ProductRepository $productRepository,
        Action\Context $context)
    {
        parent::__construct($context);
        $this->questionAnswer = $questionAnswer;
        $this->pageFactory = $pageFactory;
        $this->redirectFactory = $redirectFactory;
        $this->productRepository = $productRepository;
    }

    public function execute()
    {
        $resultRedirect = $this->resultRedirectFactory->create();
        $id = $this->getRequest()->getParam('id');
        if($id) {
            $model = $this->questionAnswer;
            $model->load($id);
            try {
                $productDetail = $this->productRepository->get($model['question_productsku']);
                $productId = (int)$productDetail->getId();
                $store = $this->getRequest()->getParam('store');
                $model->delete();
                $this->messageManager->addSuccessMessage(__('Message Deleted'));
                return $resultRedirect->setPath('catalog/product/edit',['id' => $productId, 'store' => $store,'_current' => true, 'set' => $productDetail->getAttributeSetId()]);
            } catch (\Exception $e){
                $this->messageManager->addErrorMessage(__($e->getMessage()));
                return $resultRedirect->setPath('catalog/product/edit',['id' => $productId, 'store' => $store,'_current' => true, 'set' => $productDetail->getAttributeSetId()]);
            }
        }

    }
}
<?php


namespace SimpleMagento\QuestionAnswer\Controller\Adminhtml\Action;

use Magento\Backend\App\Action;
use Magento\Framework\App\ResponseInterface;
use Magento\Backend\Model\View\Result\RedirectFactory;
use Magento\Framework\View\Result\PageFactory;
use SimpleMagento\QuestionAnswer\Model\QuestionAnswerFactory;
use Magento\Catalog\Model\ProductRepository;
use Magento\Framework\Controller\ResultFactory;

class Save extends Action
{
    /**
     * @var QuestionAnswerFactory
     */
    protected $questionAnswerFactory;
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

    public function __construct(Action\Context $context,
                                PageFactory $pageFactory,
                                QuestionAnswerFactory $questionAnswerFactory,
                                ResultFactory $resultFactory,
                                ProductRepository $productRepository,
                                RedirectFactory $redirectFactory)
    {
        parent::__construct($context);
        $this->questionAnswerFactory = $questionAnswerFactory;
        $this->pageFactory = $pageFactory;
        $this->redirectFactory = $redirectFactory;
        $this->productRepository = $productRepository;
        $this->resultFactory = $resultFactory;
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
        $data = $this->getRequest()->getPostValue();

        $resultRedirect = $this->resultFactory->create(ResultFactory::TYPE_REDIRECT);
        if($data['question_entity_id'] == null){
            unset($data['question_entity_id']);
            $newData = $this->questionAnswerFactory->create();
        }else {
            $newData = $this->questionAnswerFactory->create()->load($data['question_entity_id']);
        }
        $productDetail = $this->productRepository->get($data['question_productsku']);
        $productId = (int)$productDetail->getId();
        $store = $this->getRequest()->getParam('store');
        $newData->setData($data);
        try{
            $newData->save();
            $this->messageManager->addSuccessMessage(__('Changes Saved Successfully'));
            $this->_getSession()->setFormData(false);
            return $resultRedirect->setPath('catalog/product/edit',['id' => $productId, 'store' => $store,'_current' => true, 'set' => $productDetail->getAttributeSetId()]);
        }catch (\Exception $e){
            $this->messageManager->addErrorMessage($e->getMessage());
            return $resultRedirect->setPath('catalog/product/edit',['id' => $productId, 'store' => $store,'_current' => true, 'set' => $productDetail->getAttributeSetId()]);
        }
    }
}
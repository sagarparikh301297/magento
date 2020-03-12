<?php


namespace Ktpl\ProductCarousel\Controller\Index;


use Magento\Framework\App\Action\Action;
use Magento\Framework\App\Action\Context;
use Magento\Framework\App\ResponseInterface;
use Magento\Catalog\Model\CategoryFactory;
use Magento\Catalog\Model\Product;
use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Framework\Controller\Result\JsonFactory;
use Magento\Framework\View\Result\PageFactory;

class Slider extends Action
{
    /**
     * @var ScopeConfigInterface
     */
    protected $config;
    /**
     * @var CategoryFactory
     */
    protected $factory;
    /**
     * @var \Magento\Catalog\Model\ResourceModel\Product\CollectionFactory
     */
    protected $productCollectionFactory;
    /**
     * @var \Ktpl\ProductCarousel\Block\Configuration
     */
    protected $configuration;
    /**
     * @var \Magento\Framework\Registry
     */
    protected $registry;
    /**
     * @var JsonFactory
     */
    protected $jsonFactory;
    /**
     * @var ScopeConfigInterface
     */
    protected $scopeConfig;
    /**
     * @var CategoryFactory
     */
    protected $categoryFactory;
    /**
     * @var \Magento\Framework\View\Result\LayoutFactory
     */
    protected $resultLayoutFactory;
    /**
     * @var PageFactory
     */
    protected $resultPageFactory;
    /**
     * @var \Magento\Customer\Model\Session
     */
    protected $customerSession;

    public function __construct(Context $context,
                                ScopeConfigInterface $config,
                                CategoryFactory $factory,
                                \Magento\Catalog\Model\ResourceModel\Product\CollectionFactory  $productCollectionFactory,
                                \Ktpl\ProductCarousel\Block\Configuration $configuration,
                                \Magento\Framework\Registry $registry,
                                \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig,
                                \Magento\Catalog\Model\CategoryFactory $categoryFactory,
                                \Magento\Framework\View\Result\LayoutFactory $resultLayoutFactory,
                                \Magento\Customer\Model\Session $customerSession,
                                PageFactory $resultPageFactory,
                                JsonFactory $jsonFactory
                                )
    {
        parent::__construct($context);
        $this->config = $config;
        $this->factory = $factory;
        $this->productCollectionFactory = $productCollectionFactory;
        $this->configuration = $configuration;
        $this->registry = $registry;
        $this->jsonFactory = $jsonFactory;
        $this->scopeConfig = $scopeConfig;
        $this->categoryFactory = $categoryFactory;
        $this->resultLayoutFactory = $resultLayoutFactory;
        $this->resultPageFactory = $resultPageFactory;
        $this->customerSession = $customerSession;
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
        $limit = $this->getRequest()->getParam('limit');
        $curPage = $this->getRequest()->getParam('page');

        $this->customerSession->setMyLimit($limit);
        $this->customerSession->setMyPage($curPage);

        $result =  $this->resultLayoutFactory->create();
        $response =  $this->jsonFactory->create();
        $resultPage = $this->resultPageFactory->create();
        if ($this->getRequest()->isAjax())
        {
            $block = $resultPage->getLayout()
                ->createBlock('Ktpl\ProductCarousel\Block\Configuration')
                ->setTemplate('Ktpl_ProductCarousel::identifier3.phtml')
                ->toHtml();
            $response->setData($block);
            return $response;
        }
    }
}
<?php


namespace SimpleMagento\AddBulkProduct\Controller\Loadproduct;



use Magento\Framework\App\Action\Action;
use Magento\Framework\App\Action\Context;
use Magento\Framework\App\ResponseInterface;
use Magento\Framework\View\Result\PageFactory;
use Magento\Catalog\Model\ProductRepository;
use Magento\Catalog\Api\ProductRepositoryInterface;
use Magento\Framework\Controller\Result\JsonFactory;
use Magento\ConfigurableProduct\Model\Product\Type\Configurable;
use Magento\Swatches\Model\Swatch;

class Index extends Action
{
    /**
     * @var PageFactory
     */
    protected $pageFactory;
    /**
     * @var ProductRepository
     */
    protected $productRepository;
    /**
     * @var JsonFactory
     */
    protected $jsonFactory;

    /**
     * @var Configurable
     */
    protected $configurable;
    /**
     * @var Swatch
     */
    protected $swatchHelper;


    public function __construct(Context $context,
                                PageFactory $pageFactory,
                                ProductRepository $productRepository,
                                JsonFactory $jsonFactory,
                                Configurable $configurable,
                                Swatch $swatchHelper)
    {
        parent::__construct($context);
        $this->pageFactory = $pageFactory;
        $this->jsonFactory = $jsonFactory;
        $this->productRepository = $productRepository;
        $this->configurable = $configurable;
        $this->swatchHelper = $swatchHelper;
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
        if ($this->getRequest()->isAjax()) {
            $getProduct = $this->getRequest()->getParam('get_product');
               $productDetail = $this->productRepository->get($getProduct);
               $data['name'] = $productDetail->getName();
               $data['id'] = $productDetail->getId();
               $data['sku'] = $productDetail->getSku();
               $data['price'] = $productDetail->getFinalPrice();
               $data['stock'] = $productDetail->getQuantityAndStockStatus();
               $data['config'] = $this->configurable->getConfigurableAttributesAsArray($productDetail);


               $result = $this->jsonFactory->create();
               $result->setData($data);
               return $result;
        }
        return  $this->pageFactory->create();
    }
}


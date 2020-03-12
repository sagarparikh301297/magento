<?php


namespace SimpleMagento\AddBulkProduct\Controller\Loadproduct;


use Magento\Framework\App\Action\Action;
use Magento\Framework\App\Action\Context;
use Magento\Framework\App\ResponseInterface;
use Magento\CatalogInventory\Model\Stock\StockItemRepository;
use Magento\Catalog\Model\Product;
use Magento\Framework\Controller\Result\JsonFactory;

class Stockcheck extends Action
{
    /**
     * @var StockItemRepository
     */
    protected $itemRepository;
    /**
     * @var Product
     */
    protected $product;
    /**
     * @var JsonFactory
     */
    protected $jsonFactory;

    public function __construct(Context $context,
                                StockItemRepository $itemRepository,
                                Product $product,
                                JsonFactory $jsonFactory)
    {
        parent::__construct($context);
        $this->itemRepository = $itemRepository;
        $this->product = $product;
        $this->jsonFactory = $jsonFactory;
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
        $getProduct = $this->getRequest()->getParam('stock_check');
        $productId=$this->product->getIdBySku($getProduct);
        if($productId){
            $productStock = $this->itemRepository->get($productId);
            $productQty = $productStock->getQty();
            $result = $this->jsonFactory->create();
            $result->setData($productQty);
            return $result;
        }else{
            $error = "not working";
            $result = $this->jsonFactory->create();
            $result->setData($error);
            return $result;
        }
    }
}
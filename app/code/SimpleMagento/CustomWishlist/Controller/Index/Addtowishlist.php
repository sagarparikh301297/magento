<?php


namespace SimpleMagento\CustomWishlist\Controller\Index;

use Magento\Framework\App\Action\Action;
use Magento\Framework\App\Action\Context;
use Magento\Framework\App\ResponseInterface;
use Magento\Framework\Controller\ResultFactory;

class Addtowishlist extends Action
{
    /**
     * @var \Magento\Customer\Model\Session
     */
    protected $customerSession;
    /**
     * @var \Magento\Wishlist\Model\WishlistFactory
     */
    protected $wishlistRepository;
    /**
     * @var \Magento\Catalog\Api\ProductRepositoryInterface
     */
    protected $productRepository;
    /**
     * @var \Magento\Framework\Controller\Result\JsonFactory
     */
    protected $jsonFactory;

    public function __construct(
                                Context $context,
                                \Magento\Customer\Model\Session $customerSession,
                                \Magento\Wishlist\Model\WishlistFactory $wishlistRepository,
                                \Magento\Catalog\Api\ProductRepositoryInterface $productRepository,
                                ResultFactory $resultFactory,
                                \Magento\Framework\Controller\Result\JsonFactory $jsonFactory)
    {
        parent::__construct($context);
        $this->customerSession = $customerSession;
        $this->wishlistRepository = $wishlistRepository;
        $this->productRepository = $productRepository;
        $this->resultFactory = $resultFactory;
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
        $customerId = $this->customerSession->getCustomer()->getId();

//        var_dump($customerId);exit();

        if(!$customerId) {
            $jsonData = ['result' => ['status' => 200, 'redirect' => 1,'message' => 'Customer not logged in.']];
            $result = $this->jsonFactory->create()->setData($jsonData);
            return $result;
        }
        $productId = $this->getRequest()->getParam('productId');

        try {
            $product = $this->productRepository->getById($productId);
        } catch (NoSuchEntityException $e) {
            $product = null;
        }

        $wishlist = $this->wishlistRepository->create()->loadByCustomerId($customerId, true);

        $wishlist->addNewItem($product);
        $wishlist->save();

        $jsonData = ['result' => ['status' => 200, 'redirect' => 0, 'message' => 'Added to wishlist']];
        $result = $this->jsonFactory->create()->setData($jsonData);
//        var_dump($result);exit();
        return $result;
    }
}
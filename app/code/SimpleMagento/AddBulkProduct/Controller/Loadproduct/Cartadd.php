<?php


namespace SimpleMagento\AddBulkProduct\Controller\Loadproduct;


use Magento\Catalog\Model\Product;
use Magento\Framework\App\Action\Action;
use Magento\Framework\App\Action\Context;
use Magento\Framework\App\ResponseInterface;
use Magento\Framework\Data\Form\FormKey;
use Magento\Framework\View\Result\PageFactory;
use Magento\Framework\Controller\Result\JsonFactory;
use Magento\Checkout\Model\Cart;
use Magento\Catalog\Api\ProductRepositoryInterface;


class Cartadd extends Action
{
    /**
     * @var ProductRepositoryInterface
     */
    protected $productRepository;
    /**
     * @var \SimpleMagento\AddBulkProduct\Controller\Loadproduct\Product
     */
    protected $product;
    /**
     * @var FormKey
     */
    protected $formKey;
    /**
     * @var Cart
     */
    protected $cart;
    /**
     * @var JsonFactory
     */
    protected $jsonFactory;

    public function __construct(Context $context,
                                ProductRepositoryInterface $productRepository,
                                Product $product,
                                FormKey $formKey,
                                Cart $cart,
                                JsonFactory $jsonFactory)
    {
        parent::__construct($context);
        $this->productRepository = $productRepository;
        $this->product = $product;
        $this->formKey = $formKey;
        $this->cart = $cart;
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
        $response = [];
        $getProduct = $this->getRequest()->getParam('add_tocart');
        foreach($getProduct as $user) {
            try {
                $params['form_key'] = $this->formKey->getFormKey();
                $params['super_attribute'] = $user['super_attribute'];
                $params['qty'] = $user['qty'];
                $productId = $this->productRepository->getById($user['id']);
                $this->cart->addProduct($productId, $params);
                $this->cart->save();
            }
            catch (\Exception $e) {
                $response['message'] = $e->getMessage();
            }
        }
        $result = $this->jsonFactory->create();
        $result->setData($response);
        return $result;

    }

}
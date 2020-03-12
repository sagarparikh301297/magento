<?php


namespace SimpleMagento\HelloWorld\Block;


use Magento\Catalog\Api\ProductRepositoryInterface;
use Magento\Framework\View\Element\Template;



class HelloWorld extends \Magento\Framework\View\Element\Template
{
    protected $product;
    public function __construct(
        ProductRepositoryInterface $productRepository,
        Template\Context $context, array $data = [])
    {
        $this->product = $productRepository;
        parent::__construct($context, $data);
    }

    public function getHelloWorld(){
        return "This is from custom block";
    }

    public function helloArray(){
        $array = [
            "good",
            "very good",
            "excellent"
        ];
        return $array;
    }
    public function getProductName(){
        $product = $this->product->get('24-MB01');
        return $product->getName();

    }

}
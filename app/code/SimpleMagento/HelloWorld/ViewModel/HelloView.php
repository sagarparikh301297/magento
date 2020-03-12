<?php


namespace SimpleMagento\HelloWorld\ViewModel;


use Magento\Framework\View\Element\Block\ArgumentInterface;

class HelloView implements ArgumentInterface
{
    public function __construct()
    {
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

}
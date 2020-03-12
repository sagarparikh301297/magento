<?php


namespace SimpleMagento\FirstModule\Plugin;


class PluginSolution3
{
    public function beforeExecute(\SimpleMagento\FirstModule\Controller\Page\HelloWorld $subject){
        echo "before execute sort order 30"."</br>";
    }

    public function afterExecute(\SimpleMagento\FirstModule\Controller\Page\HelloWorld $subject){
        echo "after execute sort order 30"."</br>";
    }
//    public function aroundExecute(\SimpleMagento\FirstModule\Controller\Page\HelloWorld $subject, callable $proceed){
//        echo "before proceed sort order 10"."</br>";
//        $proceed();
//        echo "after proceed sort order 10"."</br>";
//    }
}
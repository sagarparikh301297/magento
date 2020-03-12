<?php


namespace SimpleMagento\FirstModule\Plugin;


class PluginSolution2
{
    public function beforeExecute(\SimpleMagento\FirstModule\Controller\Page\HelloWorld $subject){
        echo "before execute sort order 20"."</br>";
    }

    public function afterExecute(\SimpleMagento\FirstModule\Controller\Page\HelloWorld $subject){
        echo "after execute sort order 20"."</br>";
    }
    public function aroundExecute(\SimpleMagento\FirstModule\Controller\Page\HelloWorld $subject, callable $proceed){
        echo "before proceed sort order 20"."</br>";
        $proceed();
        echo "after proceed sort order 20"."</br>";
    }
}
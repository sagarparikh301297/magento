<?php


namespace SimpleMagento\FirstModule\Plugin;


class PluginSolution
{
    public function beforeExecute(\SimpleMagento\FirstModule\Controller\Page\HelloWorld $subject){
        echo "before execute sort order 10"."</br>";
    }

    public function afterExecute(\SimpleMagento\FirstModule\Controller\Page\HelloWorld $subject){
        echo "after execute sort order 10"."</br>";
    }
    public function aroundExecute(\SimpleMagento\FirstModule\Controller\Page\HelloWorld $subject, callable $proceed){
        echo "before proceed sort order 10"."</br>";
        $proceed();
        echo "after proceed sort order 10"."</br>";
    }


//      public function beforeSetName(\Magento\Catalog\Model\Product $subject, $name){
//        return "Before Plugin ".$name;
//    }

//      public function afterGetName(\Magento\Catalog\Model\Product $subject, $result){
//        return $result ." After Plugin";
//    }
//      public function aroundGetName(\Magento\Catalog\Model\Product $subject, callable $proceed){
//          echo "before proceed";
//          $name = $proceed;
//          echo $name;
//          echo "after proceed";
//          return $name;
//      }
//        public function aroundGetBySku(\Magento\Catalog\Model\Product $subject, callable $proceed, $sku){
//          echo "before proceed";
//          $id = $proceed($sku);
//          echo $id;
//          echo "after proceed";
//          return $id;
//        }

}

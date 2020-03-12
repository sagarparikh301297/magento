<?php
/**
 * Mirasvit
 *
 * This source file is subject to the Mirasvit Software License, which is available at https://mirasvit.com/license/.
 * Do not edit or add to this file if you wish to upgrade the to newer versions in the future.
 * If you wish to customize this module for your needs.
 * Please refer to http://www.magentocommerce.com for more information.
 *
 * @category  Mirasvit
 * @package   mirasvit/module-cataloglabel
 * @version   1.1.14
 * @copyright Copyright (C) 2019 Mirasvit (https://mirasvit.com/)
 */



namespace Mirasvit\CatalogLabel\Observer;

use Magento\Framework\Event\ObserverInterface;
use Mirasvit\CatalogLabel\Api\Repository\NewProductsRepositoryInterface;
use Mirasvit\CatalogLabel\Api\Data\NewProductsInterface;
use Mirasvit\CatalogLabel\Api\Config\ConfigInterface;

class NewProductsObserver implements ObserverInterface
{
    public function __construct(
        NewProductsRepositoryInterface $newProductsRepository,
        NewProductsInterface $newProducts,
        ConfigInterface $config
    ) {
        $this->newProductsRepository = $newProductsRepository;
        $this->newProducts = $newProducts;
        $this->config = $config;
    }



    /**
     * @param \Magento\Framework\Event\Observer $observer
     *
     * @return void
     *
     * @SuppressWarnings(PHPMD.UnusedFormalParameter)
     */
    public function execute(\Magento\Framework\Event\Observer $observer)
    {
        if ($this->config->isFlushCacheEnabled()
            && ( $controller = $observer->getController())
            && is_object($controller)) {
                $path = $controller->getRequest()->getOriginalPathInfo();
                if ($this->isNewProduct($path)) {
                    $product = $observer->getProduct();
                    $productId = $product->getId();
                    $this->newProductsRepository->save($this->newProducts->setProductId($productId));
                }
        }
    }

    private function isNewProduct($path)
    {
       if (strpos($path, '/id/') === false) {
           return true;
       }

        return false;
    }
}

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



namespace Mirasvit\CatalogLabel\Repository;

use Magento\Framework\EntityManager\EntityManager;
use Mirasvit\CatalogLabel\Api\Data\NewProductsInterface;
use Mirasvit\CatalogLabel\Api\Repository\NewProductsRepositoryInterface;
use Mirasvit\CatalogLabel\Api\Data\NewProductsInterfaceFactory;
use Mirasvit\CatalogLabel\Model\ResourceModel\NewProducts\CollectionFactory;

class NewProductsRepository implements NewProductsRepositoryInterface
{
    /**
     * @var KeywordInterfaceFactory
     */
    private $factory;

    /**
     * @var CollectionFactory
     */
    private $collectionFactory;

    /**
     * @var EntityManager
     */
    private $entityManager;

    public function __construct(
        NewProductsInterfaceFactory $factory,
        CollectionFactory $collectionFactory,
        EntityManager $entityManager
    ) {
        $this->factory = $factory;
        $this->collectionFactory = $collectionFactory;
        $this->entityManager = $entityManager;
    }

    public function create()
    {
        return $this->factory->create();
    }

    public function getCollection()
    {
        return $this->collectionFactory->create();
    }

    public function get($id)
    {
        $model = $this->create();

        $this->entityManager->load($model, $id);

        return $model->getId() ? $model : false;
    }

    public function save(NewProductsInterface $newProducts)
    {
        return $this->entityManager->save($newProducts);
    }

    public function delete(NewProductsInterface $newProducts)
    {
        return $this->entityManager->delete($newProducts);
    }
}
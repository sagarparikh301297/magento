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



namespace Mirasvit\CatalogLabel\Api\Repository;

use Magento\Framework\DataObject;
use Mirasvit\CatalogLabel\Api\Data\NewProductsInterface;

interface NewProductsRepositoryInterface
{
    /**
     * @return NewProductsInterface[]|\Mirasvit\CatalogLabel\Model\ResourceModel\NewProducts\Collection
     */
    public function getCollection();

    /**
     * @return NewProductsInterface
     */
    public function create();

    /**
     * @param int $id
     * @return NewProductsInterface|DataObject|false
     */
    public function get($id);

    /**
     * @param NewProductsInterface $model
     * @return NewProductsInterface
     */
    public function save(NewProductsInterface $model);

    /**
     * @param NewProductsInterface $model
     * @return bool
     */
    public function delete(NewProductsInterface $model);
}
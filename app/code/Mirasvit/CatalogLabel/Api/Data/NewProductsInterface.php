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



namespace Mirasvit\CatalogLabel\Api\Data;

interface NewProductsInterface
{
    const TABLE_NAME = 'mst_cataloglabel_new_product';

    const ID = 'id';
    const PRODUCT_ID = 'product_id';

    /**
     * @return int
     */
    public function getId();

    /**
     * @return int
     */
    public function getProductId();

    /**
     * @param string int
     * @return $this
     */
    public function setProductId($value);
}
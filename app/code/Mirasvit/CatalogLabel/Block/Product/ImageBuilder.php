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


namespace Mirasvit\CatalogLabel\Block\Product;
use Magento\Catalog\Model\Product;

class ImageBuilder extends \Magento\Catalog\Block\Product\ImageBuilder
{
    /**
     * Create image block
     *
     * @return \Magento\Catalog\Block\Product\Image
     */
    public function create(Product $product = null, string $imageId = null, array $attributes = null)
    {
        $image = parent::create($product, $imageId, $attributes);
        $image->setProduct($this->product);

        return $image;
    }
}

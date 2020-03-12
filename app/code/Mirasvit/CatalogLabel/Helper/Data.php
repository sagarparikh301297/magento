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



namespace Mirasvit\CatalogLabel\Helper;

class Data extends \Magento\Framework\App\Helper\AbstractHelper
{
    /**
     * @var \Magento\Framework\View\Element\BlockFactory
     */
    protected $blockFactory;

    /**
     * @var \Magento\Framework\App\Helper\Context
     */
    protected $context;

    /**
     * @param \Magento\Framework\View\Element\BlockFactory $blockFactory
     * @param \Magento\Framework\App\Helper\Context        $context
     * @param \Magento\Framework\App\Request\Http          $request
     */
    public function __construct(
        \Magento\Framework\View\Element\BlockFactory $blockFactory,
        \Magento\Framework\App\Helper\Context $context,
        \Magento\Framework\App\Request\Http $request
    ) {
        $this->blockFactory = $blockFactory;
        $this->context = $context;
        parent::__construct($context);
        $this->request = $request;
    }

    /**
     * @param string                         $placeholderCode
     * @param \Magento\Catalog\Model\Product $product
     * @param string                         $type
     * @param string                         $template
     * @param int                            $width
     * @param int                            $height
     * @return string
     */
    public function getProductHtml($placeholderCode, $product, $type, $template, $width = null, $height = null)
    {
        return $this->blockFactory
                ->createBlock('\Mirasvit\CatalogLabel\Block\Product\Label')
                ->setType($type)
                ->setTemplate('product/'.$template.'.phtml')
                ->setPlaceholderCode($placeholderCode)
                ->setProduct($product)
                ->setWidth($width)
                ->setHeight($height)
                ->toHtml();
    }

    /**
     * @param string                         $placeholderCode
     * @param \Magento\Catalog\Model\Product $product
     * @param int                            $width
     * @param int                            $height
     * @return string
     */
    public function getProductListHtml($placeholderCode, $product, $width = null, $height = null)
    {
        return $this->getProductHtml($placeholderCode, $product, 'list', 'list', $width, $height);
    }

    /**
     * @param string                         $placeholderCode
     * @param \Magento\Catalog\Model\Product $product
     * @param int                            $width
     * @param int                            $height
     * @return string
     */
    public function getProductViewHtml($placeholderCode, $product, $width = null, $height = null)
    {
        return $this->getProductHtml($placeholderCode, $product, 'view', 'view', $width, $height);
    }

    /**
     * @return string
     */
    public function getFullActionCode()
    {
        return strtolower(
            $this->request->getModuleName().
            '_'.$this->request->getControllerName().
            '_'.$this->request->getActionName()
            );
    }
}

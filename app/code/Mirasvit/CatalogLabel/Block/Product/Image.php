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

/**
 * @method string getImageUrl()
 * @method string getWidth()
 * @method string getHeight()
 * @method string getLabel()
 * @method mixed getResizedImageWidth()
 * @method mixed getResizedImageHeight()
 * @method float getRatio()
 * @method string getCustomAttributes()
 */
class Image extends \Magento\Catalog\Block\Product\Image
{
    protected $dataHelper;
    protected $placeholder;
    protected $productRepository;
    protected $request;

    public function __construct(
        \Magento\Catalog\Api\ProductRepositoryInterface $productRepository,
        \Magento\Framework\View\Element\Template\Context $context,
        \Mirasvit\CatalogLabel\Model\Placeholder $placeholder,
        \Mirasvit\CatalogLabel\Helper\Data $dataHelper,
        array $data = []
    ) {
        if (isset($data['template'])) {
            $this->setTemplate($data['template']);
            unset($data['template']);
        }
        parent::__construct($context, $data);

        $this->productRepository = $productRepository;
        $this->placeholder = $placeholder;
        $this->dataHelper = $dataHelper;
        $this->request = $context->getRequest();
    }

    /**
     * Processing block html after rendering
     *
     * @param   string $html
     * @return  string
     */
    protected function _afterToHtml($html)
    {
        //fix unexpected label
        if ($this->request->getFullActionName() == 'catalog_product_view') {
            return $html;
        }

        $listPlaceholders   = $this->placeholder->getCollection()
            ->addFieldTofilter('is_active', 1)
            ->addFieldToFilter('is_auto_for_list', 1)
            ->addFieldTofilter('image_type', ['like' => '%list%']);

        $product = $this->getProduct();
        if (!$product && $this->getData('product_id')) {
            $product = $this->productRepository->getById($this->getData('product_id'));
        }
        if ($product) {
            foreach ($listPlaceholders as $p) {
                $ourhtml = $this->dataHelper->getProductHtml($p->getCode(), $product, 'list', 'badge');
                if (!$ourhtml) {
                    continue;
                }
                $html .= $ourhtml;
            }
            $this->unsetData('product');
        }

        return $html;
    }
}

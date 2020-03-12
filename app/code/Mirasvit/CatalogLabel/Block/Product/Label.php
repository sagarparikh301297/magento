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
 * @SuppressWarnings(PHPMD.CouplingBetweenObjects)
 */
class Label extends \Magento\Framework\View\Element\Template
{
    private $stockItemFactory;

    private $placeholderFactory;

    private $labelCollectionFactory;

    private $config;

    private $mstcoreParsevariables;

    private $registry;

    private $pricingHelper;

    private $context;

    private $customerSession;

    private $helperData;

    /**
     * @var array Array of objects.
     */
    protected $labelObjectsArray;

    /**
     * @var array
     */
    protected $labelPosition
        = [
            'TL'    => 0,
            'TR'    => 0,
            'TC'    => 0,
            'ML'    => 0,
            'MR'    => 0,
            'MC'    => 0,
            'BL'    => 0,
            'BR'    => 0,
            'BC'    => 0,
            'EMPTY' => 0,
        ];

    /**
     * @var bool
     */
    protected $multipleImages = false;

    /**
     * @SuppressWarnings(PHPMD.ExcessiveParameterList)
     */
    public function __construct(
        \Magento\CatalogInventory\Model\Stock\ItemFactory $stockItemFactory,
        \Mirasvit\CatalogLabel\Model\PlaceholderFactory $placeholderFactory,
        \Mirasvit\CatalogLabel\Model\ResourceModel\Label\CollectionFactory $labelCollectionFactory,
        \Mirasvit\CatalogLabel\Model\Config $config,
        \Mirasvit\CatalogLabel\Helper\Data $helperData,
        \Mirasvit\Core\Helper\ParseVariables $mstcoreParsevariables,
        \Magento\Framework\Registry $registry,
        \Magento\Framework\Pricing\Helper\Data $pricingHelper,
        \Magento\Customer\Model\Session $customerSession,
        \Magento\Framework\View\Element\Template\Context $context,
        array $data = []
    ) {
        $this->stockItemFactory       = $stockItemFactory;
        $this->placeholderFactory     = $placeholderFactory;
        $this->labelCollectionFactory = $labelCollectionFactory;
        $this->config                 = $config;
        $this->mstcoreParsevariables  = $mstcoreParsevariables;
        $this->registry               = $registry;
        $this->pricingHelper          = $pricingHelper;
        $this->context                = $context;
        $this->customerSession        = $customerSession;
        $this->helperData             = $helperData;

        parent::__construct($context, $data);
    }

    /**
     * @param array $displays
     *
     * @return void
     */
    public function setLabelObjectsArray($displays)
    {
        $labelPosition = [
            'TL'    => 0,
            'TR'    => 0,
            'TC'    => 0,
            'ML'    => 0,
            'MR'    => 0,
            'MC'    => 0,
            'BL'    => 0,
            'BR'    => 0,
            'BC'    => 0,
            'EMPTY' => 0,
        ];

        $this->labelObjectsArray = [
            'TL'    => new \Magento\Framework\DataObject(),
            'TR'    => new \Magento\Framework\DataObject(),
            'TC'    => new \Magento\Framework\DataObject(),
            'ML'    => new \Magento\Framework\DataObject(),
            'MR'    => new \Magento\Framework\DataObject(),
            'MC'    => new \Magento\Framework\DataObject(),
            'BL'    => new \Magento\Framework\DataObject(),
            'BR'    => new \Magento\Framework\DataObject(),
            'BC'    => new \Magento\Framework\DataObject(),
            'EMPTY' => new \Magento\Framework\DataObject(),
        ];

        foreach ($displays as $display) {
            /** @var \Mirasvit\CatalogLabel\Model\Label\Display $display */
            if ($image = $display->getImage()) {
                $h = 50;
                $w = 50;
                if (file_exists($this->config->getBaseMediaPath() . $image)) {
                    list($w, $h) = getimagesize($this->config->getBaseMediaPath() . $image);

                }
                $this->labelObjectsArray[$display->getPosition()]
                    ->setData($labelPosition[$display->getPosition()],
                        new \Magento\Framework\DataObject([
                            'image'  => $display->getImage(),
                            'width'  => $w,
                            'height' => $h,
                        ])
                    );
                $labelPosition[$display->getPosition()] += 1;
            }
        }
    }

    /**
     * @param \Mirasvit\CatalogLabel\Model\Label\Display $display
     *
     * @return void
     */
    public function setLabelPositionCount($display)
    {
        $this->labelPosition[$display->getPosition()] += 1;
    }

    /**
     * @param \Mirasvit\CatalogLabel\Model\Label\Display $display
     *
     * @return string
     */
    public function getLabelPositionCount($display)
    {
        return $this->labelPosition[$display->getPosition()];
    }

    /**
     * @param \Magento\Catalog\Model\Product $product
     *
     * @return $this
     */
    public function setProduct($product)
    {
        parent::setProduct($product);

        if (count($product->getMediaGalleryImages()) > 1) {
            $this->multipleImages = true;
        }

        $stockItem = $this->stockItemFactory->create()->setProduct($product);

        if ($stockItem) {
            $this->setQty(intval($stockItem->getQty()));
            $this->setRf($product->getPrice() - $product->getFinalPrice());
            if ($product->getPrice() > 0) {
                $this->setRfc(ceil($product->getPrice() - $product->getFinalPrice()));
                $this->setRfp(intval(($product->getPrice() - $product->getFinalPrice()) / $product->getPrice() * 100));
            }
        }

        return $this;
    }

    /**
     * @return array
     */
    public function getDisplays()
    {
        $result = [];

        $placeholder = $this->placeholderFactory->create()->loadByCode($this->getPlaceholderCode());
        $labels      = $this->labelCollectionFactory->create()
            ->addActiveFilter()
            ->addCustomerGroupFilter($this->customerSession->getCustomerGroupId())
            ->addStoreFilter($this->context->getStoreManager()->getStore())
            ->addFieldToFilter('placeholder_id', $placeholder->getId());

        //it will be applied only if more than one labels are in the same position
        $labels->getSelect()->order('sort_order ASC');

        /** @var \Mirasvit\CatalogLabel\Model\Label $label */
        foreach ($labels as $label) {
            $result = array_merge($result, $label->getDisplays($this->getProduct()));
        }

        foreach ($result as $display) {
            $display->setType($this->getType());
            $this->_prepareDisplay($display);
        }

        return $result;
    }

    /**
     * @param string   $position
     * @param bool|int $positionCount
     * @param string   $type
     *
     * @return string
     */
    public function getImageSizeHtml($position, $positionCount = false, $type = null)
    {
        $currentPositionObject      = $this->labelObjectsArray[$position];
        $currentPositionObjectArray = $currentPositionObject->toArray();
        $currentLabelObject         = $currentPositionObject->getData($positionCount);

        $w = $currentLabelObject->getWidth();
        $h = $currentLabelObject->getHeight();

        $baseStyle = 'width: ' . $w . 'px; height: ' . $h . 'px;';
        $halfW     = 'margin-left: ' . -$w / 2 . 'px;';
        $halfH     = 'margin-top: ' . -$h / 2 . 'px;';

        if (in_array($position, ['BL', 'BR', 'BC']) && $this->multipleImages && $type == 'view') {
            $baseStyle .= ' margin-bottom: 115px; ';
        }

        $imageSpace = 10; //space between images if we use more then one image in the same position
        if ($positionCount) {
            $width = [];
            foreach ($currentPositionObjectArray as $positionKey => $positionData) {
                if ($positionKey == $positionCount) {
                    break;
                }
                $width[] = $positionData->getWidth();
            }

            $leftShiftW  = 'margin-left: ' . (($imageSpace * $positionCount) + array_sum($width)) . 'px;';
            $rightShiftW = 'margin-right: ' . (($imageSpace * $positionCount) + array_sum($width)) . 'px;';

            $leftShiftCenterW = '';
            if (in_array($position, ['MC', 'TC', 'BC'])) {
                $widthArray       = $this->_getWidthArray($currentPositionObjectArray);
                $leftShiftCenterW = 'margin-left: ' . (
                        (-(array_sum($widthArray) / 2) - ($imageSpace * (count($widthArray) - 1))) +
                        $widthArray[0] + (array_sum($width) - $widthArray[0]) + ($imageSpace * $positionCount)
                    ) . 'px;';
            }

            switch ($position) {
                case 'MC':
                    return $baseStyle . $halfH . $leftShiftCenterW;
                    break;

                case 'TC':
                    return $baseStyle . $leftShiftCenterW;
                    break;

                case 'BC':
                    return $baseStyle . $leftShiftCenterW;
                    break;

                case 'ML':
                    return $baseStyle . $halfH . $leftShiftW;
                    break;

                case 'MR':
                    return $baseStyle . $halfH . $rightShiftW;
                    break;

                case 'TR':
                    return $baseStyle . $rightShiftW;
                    break;

                case 'BR':
                    return $baseStyle . $rightShiftW;
                    break;

                case 'TL':
                    return $baseStyle . $rightShiftW;
                    break;

                case 'BL':
                    return $baseStyle . $leftShiftW;
                    break;

                default:
                    return $baseStyle;
                    break;
            }
        } else {
            $leftShiftFirstLabelCenterW = '';
            if (count($currentPositionObjectArray) > 1 && in_array($position, ['MC', 'TC', 'BC'])) {
                $widthArray                 = $this->_getWidthArray($currentPositionObjectArray);
                $leftShiftFirstLabelCenterW = 'margin-left: ' .
                    (-(array_sum($widthArray) / 2) - ($imageSpace * (count($widthArray) - 1))) . 'px;';
            }

            switch ($position) {
                case 'MC':
                    if (count($currentPositionObjectArray) > 1) {
                        return $baseStyle . $halfH . $leftShiftFirstLabelCenterW;
                    } else {
                        return $baseStyle . $halfH . $halfW;
                    }
                    break;

                case 'TC':
                case 'BC':
                    if (count($currentPositionObjectArray) > 1) {
                        return $baseStyle . $leftShiftFirstLabelCenterW;
                    } else {
                        return $baseStyle . $halfW;
                    }
                    break;

                case 'MR':
                case 'ML':
                    return $baseStyle . $halfH;
                    break;

                default:
                    return $baseStyle;
                    break;
            }
        }
    }

    /**
     * @param array $currentPositionObjectArray
     *
     * @return array
     */
    protected function _getWidthArray($currentPositionObjectArray)
    {
        $widthArray = [];
        foreach ($currentPositionObjectArray as $positionData) {
            $widthArray[] = $positionData->getWidth();
        }

        return $widthArray;
    }

    /**
     * @param \Mirasvit\CatalogLabel\Model\Label $display
     *
     * @return \Mirasvit\CatalogLabel\Model\Label
     */
    protected function _prepareDisplay($display)
    {
        $display->setData($this->getType() . '_title', $this->_formatTxt($display->getTitle()));

        return $display;
    }

    /**
     * @param string $txt
     *
     * @return string
     */
    protected function _formatTxt($txt)
    {
        $product = $this->getProduct();
        //need if [var_dsc] > 0 && < 1
        if ($this->getRfp() == 0
            && (strpos($txt, '[var_dsc]') !== false
                || strpos($txt, '[var_rfp]') !== false)) {
            $dsc = $this->_getSpecPriceMinusPricePercent($product);
            $txt = str_replace('[var_dsc]', $dsc, $txt);
            $txt = str_replace('[var_rfp]', $dsc, $txt);
        }

        $vars = new \Magento\Framework\DataObject();
        $vars->setData(
            [
                'qty'      => $this->getQty(),
                'nl'       => '<br>',
                'rf'       => $this->getRf(),
                'rfc'      => $this->getRfc(),
                'dsc'      => $this->getRfp(),
                'rfp'      => $this->getRfp(),
                'price'    => $this->_getActualPrice(),
                'category' => $this->_getParentCategory(),
            ]
        );

        return $this->mstcoreParsevariables->parse($txt, ['product' => $product, 'var' => $vars]);
    }

    /**
     * @param \Magento\Sales\Model\Product $product
     *
     * @return float|string
     * @SuppressWarnings(PHPMD.NPathComplexity)
     * @SuppressWarnings(PHPMD.CyclomaticComplexity)
     */
    protected function _getSpecPriceMinusPricePercent($product)
    {
        $price                      = $product->getPrice();
        $specPrice                  = $product->getSpecialPrice();
        $specPriceMinusPricePercent = 0;

        $specialPriceFromDate = $product->getSpecialFromDate();
        $specialPriceToDate   = $product->getSpecialToDate();
        $today                = time();
        $inDateInterval       = false;
        if ($specPrice) {
            if (($today >= strtotime($specialPriceFromDate) && $today <= strtotime($specialPriceToDate))
                || ($today >= strtotime($specialPriceFromDate) && $specialPriceToDate === null)) {
                $inDateInterval = true;
            }
        }

        if (!$inDateInterval) {
            return '';
        }

        if ($specPrice
            && $specPrice != 0
            && $specPrice < $price) {
            $specPriceMinusPricePercent = 100 - (($specPrice * 100) / $price);
        }

        if ($specPriceMinusPricePercent > 0
            && $specPriceMinusPricePercent < 1) {
            $specPriceMinusPricePercent = 1;
        }

        if ($specPriceMinusPricePercent == 0) {
            return '';
        }

        return $specPriceMinusPricePercent;
    }

    /**
     * @return float|string
     */
    protected function _getActualPrice()
    {
        $product               = $this->getProduct();
        $price                 = $product->getFinalPrice();
        $formattedSpecialPrice = $this->pricingHelper->currency($price, true, false);
        if ($price == 0) {
            return '';
        }

        return $formattedSpecialPrice;
    }

    /**
     * @return \Magento\Catalog\Model\Category
     */
    protected function _getParentCategory()
    {
        $category = $this->registry->registry('current_category');
        if ($category) {
            $thisCategory = $category->getName();

            return $thisCategory;
        }
    }

    /**
     * @return string
     */
    public function getFullActionCode()
    {
        return $this->helperData->getFullActionCode();
    }

    public function isProductList()
    {
        $fullActionCode     = $this->getFullActionCode();
        $productListActions = ['catalog_category_view',
                               'catalogsearch_result_index',
                               'catalogsearch_advanced_result',
                               'cms_index_index'];

        return in_array($fullActionCode, $productListActions) ? true : false;
    }
}

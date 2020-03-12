<?php

namespace Ktpl\ProductCarousel\Block;

use Magento\Catalog\Model\CategoryFactory;
use Magento\Catalog\Model\Product;
use Magento\Framework\App\ActionInterface;
use Magento\Framework\View\Element\Template;
use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Framework\Url\Helper\Data;
use Magento\Catalog\Block\Product\AbstractProduct;

class Configuration extends Template
{
    /**
     * @var ScopeConfigInterface
     */
    protected $config;
    /**
     * @var \Magento\Catalog\Block\Product\ImageBuilder
     */
    protected $imageBuilder;

    /**
     * @var \Magento\Catalog\Model\ResourceModel\Product\CollectionFactory
     */
    protected $productCollectionFactory;
    /**
     * @var CategoryFactory
     */
    protected $factory;
    /**
     * @var Data
     */
    protected $urlHelper;
    /**
     * @var AbstractProduct
     */
    protected $abstractProduct;
    /**
     * @var \Magento\Framework\Registry
     */
    protected $registry;

    protected $sliderPosition = [];
    /**
     * @var \Magento\Reports\Block\Product\Viewed
     */
    protected $recentlyViewed;
    /**
     * @var \Magento\Framework\View\LayoutInterface
     */
    protected $layout;
    /**
     * @var \Magento\Customer\Model\Session
     */
    protected $customerSession;

    public function __construct(\Magento\Catalog\Block\Product\Context $contextImage,
                                ScopeConfigInterface $config,
                                CategoryFactory $factory,
                                Data $urlHelper,
                                \Magento\Framework\Registry $registry,
                                \Magento\Reports\Block\Product\Viewed $recentlyViewed,
                                \Magento\Framework\View\LayoutInterface $layout,
                                AbstractProduct $abstractProduct,
                                \Magento\Customer\Model\Session $customerSession,
                                \Magento\Catalog\Model\ResourceModel\Product\CollectionFactory  $productCollectionFactory,
                                array $data = [])
    {
        parent::__construct($contextImage, $data);
        $this->imageBuilder = $contextImage->getImageBuilder();
        $this->config = $config;
        $this->productCollectionFactory = $productCollectionFactory;
        $this->factory = $factory;
        $this->urlHelper = $urlHelper;
        $this->abstractProduct = $abstractProduct;
        $this->registry = $registry;
        $this->recentlyViewed = $recentlyViewed;
        $this->layout = $layout;
        $this->customerSession = $customerSession;
    }

    /**
     * @param $id
     * @return \Magento\Catalog\Model\ResourceModel\Product\Collection|mixed
     */
    public function setSlider($id)
    {
        //get values of current page
        $page=($this->customerSession->getMyPage())? $this->customerSession->getMyPage() : 1;
        //get values of current limit
        $pageSize=($this->customerSession->getMyLimit())? $this->customerSession->getMyLimit() : 6;
//        var_dump($pageSize);exit();

        $isItEnable = $this->_scopeConfig->getValue('Carousel_Section/Carousel_'.$id.'/Carousel_field', \Magento\Store\Model\ScopeInterface::SCOPE_STORE);
        if($isItEnable == 0){
            if ($id === 'slider_two'){
                 $ProductCollection = $this->recentlyViewed->getItemsCollection()->load();
                return $ProductFilterCollection = $ProductCollection->setFlag('has_stock_status_filter', true);
            }else{
                $sort = $this->_scopeConfig->getValue('Carousel_Section/Carousel_'.$id.'/Carousel_Eight_field', \Magento\Store\Model\ScopeInterface::SCOPE_STORE);
                $collectionType = $this->_scopeConfig->getValue('Carousel_Section/Carousel_'.$id.'/Carousel_Fourth_field', \Magento\Store\Model\ScopeInterface::SCOPE_STORE);
                if($collectionType == 0){
                    $getAttribute = $this->_scopeConfig->getValue('Carousel_Section/Carousel_'.$id.'/Carousel_Fifth_field', \Magento\Store\Model\ScopeInterface::SCOPE_STORE);
                    $ProductCollection = $this->productCollectionFactory->create();
                    $ProductFilterCollection = $ProductCollection->setFlag('has_stock_status_filter', true)->addAttributeToFilter($getAttribute, true)->addAttributeToSelect('*')->setOrder($sort, 'asc')->setCurPage($page)->setPageSize($pageSize)->load();
                }else{
                    $getCategory = $this->_scopeConfig->getValue('Carousel_Section/Carousel_'.$id.'/Carousel_Six_field', \Magento\Store\Model\ScopeInterface::SCOPE_STORE);
                    $category = $this->factory->create()->load($getCategory);
                    $ProductFilterCollection = $category->getProductCollection()->addAttributeToSelect('price',true)->setFlag('has_stock_status_filter', true)->setCurPage($page)->setPageSize($pageSize)->addAttributeToSelect('*')->setOrder($sort, 'asc');
                }
//            if ($this->getRequest()->isAjax()) {
//                $jsonData = json_encode(array($ProductFilterCollection));
//                return $jsonData;
//            }else{
                return $ProductFilterCollection;
//            }
            }
        }
        else{
           return $isItEnable;
        }
    }

    public function getPosition(){
        $sliderPosition = array(
            'slider_1' => array(
                'enable' => $this->_scopeConfig->getValue('Carousel_Section/Carousel_slider_one/Carousel_field', \Magento\Store\Model\ScopeInterface::SCOPE_STORE),
                'position' => $this->_scopeConfig->getValue('Carousel_Section/Carousel_slider_one/Carousel_Nine_field', \Magento\Store\Model\ScopeInterface::SCOPE_STORE),
            ),
            'slider_2' => array(
                'enable' => $this->_scopeConfig->getValue('Carousel_Section/Carousel_slider_two/Carousel_field', \Magento\Store\Model\ScopeInterface::SCOPE_STORE),
                'position' => $this->_scopeConfig->getValue('Carousel_Section/Carousel_slider_two/Carousel_Nine_field', \Magento\Store\Model\ScopeInterface::SCOPE_STORE),
            ),
            'slider_3' => array(
                'enable' => $this->_scopeConfig->getValue('Carousel_Section/Carousel_slider_three/Carousel_field', \Magento\Store\Model\ScopeInterface::SCOPE_STORE),
                'position' => $this->_scopeConfig->getValue('Carousel_Section/Carousel_slider_three/Carousel_Nine_field', \Magento\Store\Model\ScopeInterface::SCOPE_STORE),
            ),
            'slider_4' => array(
                'enable' => $this->_scopeConfig->getValue('Carousel_Section/Carousel_slider_four/Carousel_field', \Magento\Store\Model\ScopeInterface::SCOPE_STORE),
                'position' => $this->_scopeConfig->getValue('Carousel_Section/Carousel_slider_four/Carousel_Nine_field', \Magento\Store\Model\ScopeInterface::SCOPE_STORE),
            ),
        );

        for ($i=1;$i<5;$i++){
            if($sliderPosition['slider_'.$i]['enable'] == 1){
                unset($sliderPosition['slider_'.$i]);
            }
          else{
                $sliderPosition['slider_'.$i]['slider_id'] = 'slider_'.$i;
            }
        }
//        var_dump($sliderPosition);
        usort($sliderPosition, function($a, $b) {
            return $a['position'] <=> $b['position'];
        });
//        usort($sliderPosition, 'sort');
//        var_dump($sliderPosition);exit();
        return $sliderPosition;
    }


    /**
     * @param $product
     * @param $imageId
     * @param array $attributes
     * @return \Magento\Catalog\Block\Product\Image
     */
    public function getImage($product, $imageId, $attributes = [])
    {
        return $this->imageBuilder->setProduct($product)
            ->setImageId($imageId)
            ->setAttributes($attributes)
            ->create();
    }

    /**
     * @param $id
     * @return mixed
     */
    public function getTitle($id)
    {
        return $this->_scopeConfig->getValue('Carousel_Section/Carousel_'.$id.'/Carousel_Second_field', \Magento\Store\Model\ScopeInterface::SCOPE_STORE);

    }

    /**
     * @param $id
     * @return mixed
     */
    public function SliderEnable($id)
    {
        return $this->_scopeConfig->getValue('Carousel_Section/Carousel_'.$id.'/Carousel_Seven_field', \Magento\Store\Model\ScopeInterface::SCOPE_STORE);
    }

    /**
     * Get post parameters
     *
     * @param Product $product
     * @return array
     */
    public function getAddToCartPostParams(Product $product)
    {
        $url = $this->abstractProduct->getAddToCartUrl($product);
        return [
            'action' => $url,
            'data' => [
                'product' => (int) $product->getEntityId(),
                ActionInterface::PARAM_NAME_URL_ENCODED => $this->urlHelper->getEncodedUrl($url),
            ]
        ];
    }

}
<?php
/**
 * Magezon
 *
 * This source file is subject to the Magezon Software License, which is available at https://www.magezon.com/license
 * Do not edit or add to this file if you wish to upgrade the to newer versions in the future.
 * If you wish to customize this module for your needs.
 * Please refer to https://www.magezon.com for more information.
 *
 * @category  Magezon
 * @package   Magezon_Core
 * @copyright Copyright (C) 2019 Magezon (https://www.magezon.com)
 */

namespace Magezon\Core\Helper;

class Data extends \Magento\Framework\App\Helper\AbstractHelper
{
    /**
     * Default charset
     */
    const ICONV_CHARSET = 'UTF-8';

    /**
     * @var \Magento\Store\Model\StoreManagerInterface
     */
    protected $_storeManager;

    /**
     * @var \Magento\Cms\Model\Template\FilterProvider
     */
    protected $_filterProvider;

    /**
     * @var \Magento\Framework\Registry
     */
    protected $registry;

    /**
     * @var \Magento\Cms\Model\Page
     */
    protected $_page;

    /**
     * @var \Magezon\Builder\Serialize\Serializer\Json
     */
    protected $serializer;

    /**
     * @param \Magento\Framework\App\Helper\Context             $context        
     * @param \Magento\Store\Model\StoreManagerInterface        $storeManager   
     * @param \Magento\Cms\Model\Template\FilterProvider        $filterProvider 
     * @param \Magento\Framework\Registry                       $registry       
     * @param \Magento\Cms\Model\Page                           $page           
     * @param \Magezon\Core\Framework\Serialize\Serializer\Json $serializer     
     */
    public function __construct(
        \Magento\Framework\App\Helper\Context $context,
        \Magento\Store\Model\StoreManagerInterface $storeManager,
        \Magento\Cms\Model\Template\FilterProvider $filterProvider,
        \Magento\Framework\Registry $registry,
        \Magento\Cms\Model\Page $page,
        \Magezon\Core\Framework\Serialize\Serializer\Json $serializer
    ) {
        parent::__construct($context);
        $this->_storeManager   = $storeManager;
        $this->_filterProvider = $filterProvider;
        $this->registry        = $registry;
        $this->_page           = $page;
        $this->serializer      = $serializer;
    }

    public function getDemo($demo) {
        return $demo;
    }
   
    public function filter($str)
    {
        if (!$str) return;
        $str       = $this->decodeDirectiveImages($str);
        $storeId   = $this->_storeManager->getStore()->getId();
        $filter    = $this->_filterProvider->getBlockFilter()->setStoreId($storeId);
        $variables = [];
        if ($this->_page->getId()) $variables['page'] = $this->_page;
        if ($category = $this->getCurrentCategory()) $variables['category'] = $category;
        if ($product = $this->getCurrentProduct()) $variables['product'] = $product;
        $filter->setVariables($variables);
        return $filter->filter($str);
    }

    /**
     * @return \Magento\Catalog\Model\Category
     */
    public function getCurrentCategory()
    {
        return $this->registry->registry('current_category');
    }

    /**
     * @return \Magento\Catalog\Model\Product
     */
    public function getCurrentProduct()
    {
        return $this->registry->registry('current_product');
    }

    /**
     * @param  string $content
     * @return string         
     */
    public function decodeDirectiveImages($content) {
        $matches = $search = $replace = [];
        preg_match_all( '/<img[\s\r\n]+.*?>/is', $content, $matches );
        foreach ($matches[0] as $imgHTML) {
            $key = 'directive/___directive/';
            if (strpos($imgHTML, $key) !== false) {
                $srcKey = 'src="';
                $start  = strpos($imgHTML, $srcKey) + strlen($srcKey);
                $end    = strpos($imgHTML, '"', $start);
                if ($end > $start) {
                    $imgSrc      = substr($imgHTML, $start, $end - $start);
                    $start       = strpos($imgSrc, $key) + strlen($key);
                    $imgBase64   = substr($imgSrc, $start);
                    $replaceHTML = str_replace($imgSrc, $this->urlDecoder->decode(urldecode($imgBase64)), $imgHTML);
                    $search[]    = $imgHTML;
                    $replace[]   = $replaceHTML;
                }
            }
        }
        return str_replace( $search, $replace, $content );
    }

    public function unserialize($string)
    {
        if ($string && $this->isJSON($string)) {
            return $this->serializer->unserialize($string);
        }
        return $string;
    }

    public function serialize($array = [])
    {
        return $this->serializer->serialize($array);
    }

    /**
     * @param  string  $string
     * @return boolean
     */
    public function isJSON($string)
    {
        return is_string($string) && is_array(json_decode($string, true)) ? true : false;
    }

    /**
     * @return string
     */
    public function getMediaUrl()
    {
        $mediaUrl = $this->_storeManager->getStore()->getBaseUrl(
            \Magento\Framework\UrlInterface::URL_TYPE_MEDIA
        );
        return $mediaUrl;
    }

    /**
     * Remove base url
     */
    public function convertImageUrl($string)
    {
        $mediaUrl = $this->getMediaUrl();
        return str_replace($mediaUrl, '', $string);
    }

    /**
     * @return boolean
     */
    public function startsWith($haystack, $needle)
    {
        $length = strlen($needle);
        return (substr($haystack, 0, $length) === $needle);
    }

    /**
     * @return boolean
     */
    public function endsWith($haystack, $needle)
    {
        $length = strlen($needle);

        return $length === 0 ||
        (substr($haystack, -$length) === $needle);
    }


    public function getImageUrl($string)
    {
        if (is_string($string) && $this->startsWith($string, 'wysiwyg') && (strpos($string, '<div') === false)) {
            $mediaUrl = $this->getMediaUrl();
            $string   = $mediaUrl . $string;
        }
        return $string;
    }

    /**
     * Convert string to numbder
     */
    public function dataPreprocessing($data)
    {
        if (is_array($data)) {
            foreach ($data as &$_row) {
                $_row = $this->unserialize($_row);
                if ($_row === '1' || $_row === '0') {
                    $_row = (int) $_row;
                }
                $_row = $this->getImageUrl($_row);
                if (is_array($_row)) {
                    $_row = $this->dataPreprocessing($_row);
                }
            }
        }
        return $data;
    }

    /**
     * Pass through to mb_substr()
     *
     * @param string $string
     * @param int $offset
     * @param int $length
     * @return string
     */
    public function substr($string, $length, $keepWords = true)
    {
        $string = $this->cleanString($string);
        if ($keepWords) {
            if (preg_match('/^.{1,' . $length . '}\b/s', $string, $match)) {
                $string = $match[0];
            }
        } else {
            $string = mb_substr($string, 0, $length, self::ICONV_CHARSET);
        }
        return $string;
    }

    /**
     * Clean non UTF-8 characters
     *
     * @param string $string
     * @return string
     */
    public function cleanString($string)
    {
        return mb_convert_encoding($string, self::ICONV_CHARSET);
    }
    /**
     * Retrieve string length using default charset
     *
     * @param string $string
     * @return int
     */
    public function strlen($string)
    {
        return mb_strlen($string, self::ICONV_CHARSET);
    }

    /**
     * Filter images with placeholders in the content
     * 
     * @param  string $content
     * @return string
     */
    public function filterCarouselLazyImage($content)
    {
        $matches = $search = $replace = [];
        preg_match_all( '/<img[\s\r\n]+.*?>/is', $content, $matches );
        $placeHolderUrl = 'data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAAEAAAABCAAAAAA6fptVAAAACklEQVR4nGNiAAAABgADNjd8qAAAAABJRU5ErkJggg==';
        $lazyClasses    = 'owl-lazy';

        foreach ($matches[0] as $imgHTML) {
            if ( ! preg_match( "/src=['\"]data:image/is", $imgHTML ) && strpos($imgHTML, 'data-src')===false) {

                // replace the src and add the data-src attribute
                $replaceHTML = preg_replace( '/<img(.*?)src=/is', '<img$1src="' . $placeHolderUrl . '" data-src=', $imgHTML );

                // add the lazy class to the img element
                if ( preg_match( '/class=["\']/i', $replaceHTML ) ) {
                    $replaceHTML = preg_replace( '/class=(["\'])(.*?)["\']/is', 'class=$1' . $lazyClasses . ' $2$1', $replaceHTML );
                } else {
                    $replaceHTML = preg_replace( '/<img/is', '<img class="' . $lazyClasses . '"', $replaceHTML );
                }

                $search[]  = $imgHTML;
                $replace[] = $replaceHTML;
            }
        }

        $content = str_replace( $search, $replace, $content );

        return $content;
    }

    /**
     * @param  int $number 
     * @return int         
     */
    public function getResponsiveClass($number)
    {
        if (in_array($number, [1, 2, 3, 4, 6, 12])) {
            return 12 / $number;
        }
        if ($number == 5) {
            return 15;
        }
        return $number;
    }
}

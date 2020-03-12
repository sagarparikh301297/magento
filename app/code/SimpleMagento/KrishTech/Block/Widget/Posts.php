<?php


namespace SimpleMagento\KrishTech\Block\Widget;


use Magento\Catalog\Block\Product\AbstractProduct;
use Magento\CatalogRule\Model\Rule;
use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Widget\Block\BlockInterface;
use Magento\Catalog\Block\Product\Context;


class Posts extends AbstractProduct implements BlockInterface {
    /**
     * @var ScopeConfigInterface
     */
    protected $_scopeConfig;
    /**
     * @var Rule
     */
    protected $rule;
    /**
     * @var \Magento\ConfigurableProduct\Model\ResourceModel\Product\Type\Configurable
     */
    protected $_catalogProductTypeConfigurable;
    /**
     * @var \SimpleMagento\KrishTech\Model\Config\Source\GetCatalog_Rule
     */
    protected $getRuleId;
    /**
     * @var \SimpleMagento\KrishTech\Model\Config\Source\GetCatalog_Rule
     */
    protected $getProductLimit;

    public function __construct(Context $context,
                                ScopeConfigInterface $_scopeConfig,
                                Rule $rule,
                                \Magento\ConfigurableProduct\Model\ResourceModel\Product\Type\Configurable $catalogProductTypeConfigurable,
                                array $data = [])
    {
        parent::__construct($context, $data);
        $this->_scopeConfig = $_scopeConfig;
        $this->rule = $rule;
        $this->_catalogProductTypeConfigurable = $catalogProductTypeConfigurable;
    }
    public function getMyValue(){
        $getRuleId = $this->_scopeConfig->getValue('CustomSection/Customgroup/Customfield', \Magento\Store\Model\ScopeInterface::SCOPE_STORE);
        $getProductLimit = $this->_scopeConfig->getValue('CustomSection/Customgroup/CustomSecondfield', \Magento\Store\Model\ScopeInterface::SCOPE_STORE);
        $results = $this->rule->load($getRuleId);
        $products = $results->getMatchingProductIds();
        $finalproducts = [];
        foreach ($products as $key=>$value){
            $parentByChild = $this->_catalogProductTypeConfigurable->getParentIdsByChild($key);
            if (isset($parentByChild[0]) && !in_array($parentByChild[0],$finalproducts)) {
                //set id as parent product id...
                $finalproducts[] = $parentByChild[0];
            }
        }
        $output = array_slice($finalproducts, 0, $getProductLimit);
        return $output;
    }
    protected $_template = "widget/posts.phtml";
}

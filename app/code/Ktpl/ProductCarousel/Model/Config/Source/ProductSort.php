<?php


namespace Ktpl\ProductCarousel\Model\Config\Source;



class ProductSort extends \Magento\Eav\Model\Entity\Attribute\Source\AbstractSource
{
    /**
     * @var \Magento\Catalog\Model\Config
     */
    protected $catalogConfig;

    public function __construct(\Magento\Catalog\Model\Config $catalogConfig)
    {
        $this->catalogConfig = $catalogConfig;
    }

    /**
     * Retrieve Catalog Config Singleton
     *
     * @return \Magento\Catalog\Model\Config
     */
    protected function _getCatalogConfig()
    {
        return $this->catalogConfig;
    }

    /**
     * Retrieve All options
     *
     * @return array
     */
    public function getAllOptions()
    {
        if ($this->_options === null) {
            $this->_options = [['label' => __('Position'), 'value' => 'position']];
            foreach ($this->_getCatalogConfig()->getAttributesUsedForSortBy() as $attribute) {
                $this->_options[] = [
                    'label' => __($attribute['frontend_label']),
                    'value' => $attribute['attribute_code']
                ];
            }
        }
        return $this->_options;
    }
}
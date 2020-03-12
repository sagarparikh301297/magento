<?php


namespace Ktpl\DeliveryDays\Block\Adminhtml\Form\Field;

use Magento\Framework\View\Element\Context;
use Magento\Framework\View\Element\Html\Select;

class Options extends Select
{
    /**
     * @var \Magento\Eav\Model\Config
     */
    protected $_eavConfig;
    /**
     * Customer groups cache
     *
     * @var array
     */
    protected $_attributeGroups;

    public function __construct(Context $context,
                                \Magento\Eav\Model\Config $eavConfig,
                                array $data = [])
    {
        parent::__construct($context, $data);
        $this->_eavConfig = $eavConfig;
    }

    /**
     * Set "name" for <select> element
     *
     * @param string $value
     * @return $this
     */
    public function setInputName($value)
    {
        return $this->setName($value);
    }

    public function _attributeOptions(){
        if ($this->_attributeGroups === null) {
            $this->_attributeGroups = [];

            $attributeCode = "delivery_days";
            $attribute = $this->_eavConfig->getAttribute('catalog_product', $attributeCode);
            $options = $attribute->getSource()->getAllOptions();
            foreach ($options as $option) {
                if ($option['value'] > 0) {
                    $this->_attributeGroups[$option['label']] = $option['label'];
                }
            }
        }
        return $this->_attributeGroups;
    }

    /**
     * Render block HTML
     *
     * @return string
     */
    public function _toHtml()
    {
        if (!$this->getOptions()) {
            foreach ($this->_attributeOptions() as $item) {
                $this->addOption($item, addslashes($item));
            }
        }
        return parent::_toHtml();
    }

}
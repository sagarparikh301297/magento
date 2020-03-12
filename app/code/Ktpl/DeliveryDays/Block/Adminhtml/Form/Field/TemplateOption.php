<?php


namespace Ktpl\DeliveryDays\Block\Adminhtml\Form\Field;


use Magento\Framework\View\Element\Html\Select;

class TemplateOption extends Select
{
    /**
     * Customer groups cache
     *
     * @var array
     */
    private $_templateGroups;

    public function _templateOptions(){
        if ($this->_templateGroups === null) {
            $this->_templateGroups = [];
            $this->_templateGroups = [
                ['label'=>'Template 1','value'=>'template_1'],
                ['label'=>'Template 2','value'=>'template_2'],
                ['label'=>'Template 3','value'=>'template_3']
            ];
            return $this->_templateGroups;
        }
    }

    /**
     * Render block HTML
     *
     * @return string
     */
    public function _toHtml()
    {
        if (!$this->getOptions()) {
            foreach ($this->_templateOptions() as $item => $value) {
                $this->addOption($value['value'], addslashes($value['label']));
            }
        }
        return parent::_toHtml();
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

}
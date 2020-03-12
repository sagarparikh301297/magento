<?php


namespace Ktpl\DeliveryDays\Block\Adminhtml\Form\Field;

use Magento\Config\Block\System\Config\Form\Field\FieldArray\AbstractFieldArray;
use Magento\Framework\DataObject;
use Magento\Framework\Exception\LocalizedException;
use Ktpl\DeliveryDays\Block\Adminhtml\Form\Field\Options;
use Ktpl\DeliveryDays\Block\Adminhtml\Form\Field\TemplateOption;

class Template extends AbstractFieldArray
{
    /**
     * @var Options
     */
    protected $_optionRenderer;

    /**
     * @var TemplateOption
     */
    protected $_templateRenderer;

    /**
     * Prepare rendering the new field by adding all the needed columns
     */
    protected function _prepareToRender()
    {
        $this->addColumn(
            'dayoption',
            [
                'label' => __('Delivery Days'),
                'renderer' => $this->_getRenderer(),
                'class' => 'required-entry'
            ]
        );
        $this->addColumn(
            'template',
            [
                'label' => __('Template'),
                'renderer' => $this->_getTemplateRenderer()
            ]
        );
        $this->_addAfter = false;
        $this->_addButtonLabel = __('Add More');
    }

    /**
     * Prepare existing row data object
     *
     * @param \Magento\Framework\DataObject $row
     * @return void
     */
    protected function _prepareArrayRow(\Magento\Framework\DataObject $row)
    {
        $options = [];

        $attribute = $row->getDayoption();
        $template = $row->getTemplate();

        if ($attribute !== null) {
            $options['option_' . $this->_getRenderer()->calcOptionHash($attribute)] = 'selected="selected"';
            $options['option_' . $this->_getTemplateRenderer()->calcOptionHash($template)] = 'selected="selected"';
        }

        $row->setData('option_extra_attrs', $options);
    }

    /**
     * @return Options
     * @throws LocalizedException
     */
    private function _getRenderer()
    {
        if (!$this->_optionRenderer) {
            $this->_optionRenderer = $this->getLayout()->createBlock(
                Options::class,
                '',
                ['data' => ['is_render_to_js_template' => true]]
            );
        }
        return $this->_optionRenderer;
    }

    /**
     * @return TemplateOption
     * @throws LocalizedException
     */
    protected function _getTemplateRenderer()
    {
        if (!$this->_templateRenderer) {
            $this->_templateRenderer = $this->getLayout()->createBlock(
                TemplateOption::class,
                '',
                ['data' => ['is_render_to_js_template' => true]]
            );
        }
        return $this->_templateRenderer;
    }

}
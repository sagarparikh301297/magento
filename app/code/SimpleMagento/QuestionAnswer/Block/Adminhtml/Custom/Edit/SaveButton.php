<?php


namespace SimpleMagento\QuestionAnswer\Block\Adminhtml\Custom\Edit;

use Magento\Framework\View\Element\UiComponent\Control\ButtonProviderInterface;

class SaveButton implements ButtonProviderInterface
{

    /**
     * Retrieve button-specified settings
     *
     * @return array
     */
    public function getButtonData()
    {
        return [
            'label' => __('Save'),
            'class' =>'save primary',
            'sort_order' => 50
        ];
    }
}
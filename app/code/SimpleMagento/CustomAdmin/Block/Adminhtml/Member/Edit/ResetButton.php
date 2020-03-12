<?php


namespace SimpleMagento\CustomAdmin\Block\Adminhtml\Member\Edit;


use Magento\Framework\View\Element\UiComponent\Control\ButtonProviderInterface;

class ResetButton implements ButtonProviderInterface
{

    /**
     * Retrieve button-specified settings
     *
     * @return array
     */
    public function getButtonData()
    {
        return [
          'label' => __('Reset'),
          'class' =>'reset',
          'on_click' => "alert();",
          'sort_order' => 30
        ];
    }
}
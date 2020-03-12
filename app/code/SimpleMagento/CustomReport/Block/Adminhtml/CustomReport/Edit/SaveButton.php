<?php
namespace SimpleMagento\CustomReport\Block\Adminhtml\CustomReport\Edit;

use Magento\Framework\View\Element\UiComponent\Control\ButtonProviderInterface;

class SaveButton extends GenericButton implements ButtonProviderInterface
{     
    public function getButtonData()
    {
        
        return [
            'label' => __('View Report'),
            'class' => 'save primary',
            'data_attribute' => [
                'mage-init' => ['button' => ['event' => 'save']],
                'form-role' => 'save',
            ],
            'sort_order' => 90,
        ];
    }
}

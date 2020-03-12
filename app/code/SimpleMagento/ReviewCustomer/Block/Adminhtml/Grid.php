<?php


namespace SimpleMagento\ReviewCustomer\Block\Adminhtml;


class Grid extends \Magento\Review\Block\Adminhtml\Grid
{
    /**
     * Prepare grid columns
     *
     * @return \Magento\Backend\Block\Widget\Grid
     * @SuppressWarnings(PHPMD.ExcessiveMethodLength)
     */
    protected function _prepareColumns()
    {
       $this->addColumn(
           'refer',
           [
               'header' => __('Refer'),
               'index' => 'refer',
               'type' => 'text',
               'truncate' => 50,
               'nl2br' => true,
               'escape' => true
           ]
       );
        $block = $this->getLayout()->getBlock('grid.bottom.links');
        if ($block) {
            $this->setChild('grid.bottom.links', $block);
        }
        return parent::_prepareColumns();
    }
}
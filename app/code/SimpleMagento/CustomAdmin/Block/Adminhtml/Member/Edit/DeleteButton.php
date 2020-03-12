<?php


namespace SimpleMagento\CustomAdmin\Block\Adminhtml\Member\Edit;


use Magento\Framework\View\Element\UiComponent\Control\ButtonProviderInterface;

class DeleteButton extends Generic implements ButtonProviderInterface
{

    /**
     * Retrieve button-specified settings
     *
     * @return array
     */
    public function getButtonData()
    {
        $data = [];
//        sprintf("location.href = '%s';", $this->getDeleteUrl()),

        if($this->getId()){
    //            echo "dfd";exit();
            $data = [
          'label' => __('Delete'),
           'on_click' => 'deleteConfirm(\'' . __(
                   'Are you sure you want to do this?'
               ) . '\', \'' . $this->getDeleteUrl() . '\', {"data": {}})',
          'sort_order' => 20,
          'class' =>'delete',
         ];
        }
        return $data;
    }
    public function getDeleteUrl(){
        return $this->getUrl('*/*/delete',['id'=>$this->getId()]);
    }
}
<?php


namespace SimpleMagento\ReviewCustomer\Plugin\Magento\Review\Block\Adminhtml\Edit;


class Form extends \Magento\Review\Block\Adminhtml\Edit\Form
{
    public function beforeSetForm(\Magento\Review\Block\Adminhtml\Edit\Form $object, $form) {

        $review = $object->_coreRegistry->registry('review_data');

        $fieldset = $form->addFieldset(
            'review_details_extra',
            ['legend' => __('Review Details Extra Data'), 'class' => 'fieldset-wide']
        );

        $fieldset->addField('refer', 'select', array(
            'label' => __('Would you recommend this product to a friend'),
            'required' => true,
            'name' => 'refer',
            'values' => ['Yes'=>'Yes','No'=>'No']
        ));

        $form->setValues($review->getData());

        return [$form];
    }
}
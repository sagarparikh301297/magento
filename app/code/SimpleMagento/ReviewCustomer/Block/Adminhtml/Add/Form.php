<?php


namespace SimpleMagento\ReviewCustomer\Block\Adminhtml\Add;


class Form extends \Magento\Review\Block\Adminhtml\Add\Form
{

    /**
     * Prepare add review form
     *
     * @return void
     * @SuppressWarnings(PHPMD.ExcessiveMethodLength)
     */
    protected function _prepareForm()
    {
        /** @var \Magento\Framework\Data\Form $form */
        $form = $this->_formFactory->create();

        $fieldset = $form->addFieldset('add_review_form', ['legend' => __('Review Details')]);

        $fieldset->addField('product_name', 'note', ['label' => __('Product'), 'text' => 'product_name']);

        $fieldset->addField(
            'detailed-rating',
            'note',
            [
                'label' => __('Product Rating'),
                'required' => true,
                'text' => '<div id="rating_detail">' . $this->getLayout()->createBlock(
                        \Magento\Review\Block\Adminhtml\Rating\Detailed::class
                    )->toHtml() . '</div>'
            ]
        );

        $fieldset->addField(
            'status_id',
            'select',
            [
                'label' => __('Status'),
                'required' => true,
                'name' => 'status_id',
                'values' => $this->_reviewData->getReviewStatusesOptionArray()
            ]
        );

        /**
         * Check is single store mode
         */
        if (!$this->_storeManager->isSingleStoreMode()) {
            $field = $fieldset->addField(
                'select_stores',
                'multiselect',
                [
                    'label' => __('Visibility'),
                    'required' => true,
                    'name' => 'select_stores[]',
                    'values' => $this->_systemStore->getStoreValuesForForm()
                ]
            );
            $renderer = $this->getLayout()->createBlock(
                \Magento\Backend\Block\Store\Switcher\Form\Renderer\Fieldset\Element::class
            );
            $field->setRenderer($renderer);
        }

        $fieldset->addField(
            'nickname',
            'text',
            [
                'name' => 'nickname',
                'title' => __('Nickname'),
                'label' => __('Nickname'),
                'maxlength' => '50',
                'required' => true
            ]
        );

        $fieldset->addField(
            'title',
            'text',
            [
                'name' => 'title',
                'title' => __('Summary of Review'),
                'label' => __('Summary of Review'),
                'maxlength' => '255',
                'required' => true
            ]
        );

        $fieldset->addField('refer', 'select', array(
            'label' => __('Would you recommend this product to a friend'),
            'required' => true,
            'name' => 'refer',
            'values' => ['Yes'=>'Yes','No'=>'No']
        ));

        $fieldset->addField(
            'detail',
            'textarea',
            [
                'name' => 'detail',
                'title' => __('Review'),
                'label' => __('Review'),
                'required' => true
            ]
        );


        $fieldset->addField('product_id', 'hidden', ['name' => 'product_id']);

        $form->setMethod('post');
        $form->setUseContainer(true);
        $form->setId('edit_form');
        $form->setAction($this->getUrl('review/product/post'));

        $this->setForm($form);
    }
}
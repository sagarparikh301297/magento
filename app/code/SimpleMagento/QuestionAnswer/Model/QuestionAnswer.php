<?php


namespace SimpleMagento\QuestionAnswer\Model;


use Magento\Framework\Model\AbstractModel;

class QuestionAnswer extends AbstractModel
{
    protected function _construct() {
        $this->_init('SimpleMagento\QuestionAnswer\Model\ResourceModel\QuestionAnswer');
    }
}
<?php


namespace SimpleMagento\QuestionAnswer\Model\ResourceModel\QuestionAnswer;


use Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection;

class Collection  extends AbstractCollection
{
    protected function _construct()
    {
        $this->_init('SimpleMagento\QuestionAnswer\Model\QuestionAnswer', 'SimpleMagento\QuestionAnswer\Model\ResourceModel\QuestionAnswer');
    }

}
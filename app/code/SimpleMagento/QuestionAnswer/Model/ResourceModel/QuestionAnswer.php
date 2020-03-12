<?php


namespace SimpleMagento\QuestionAnswer\Model\ResourceModel;


use Magento\Framework\Model\ResourceModel\Db\AbstractDb;

class QuestionAnswer extends AbstractDb
{

    /**
     * Resource initialization
     *
     * @return void
     */
    protected function _construct()
    {
        $this->_init('custom_question', 'question_entity_id');
    }
}
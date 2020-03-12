<?php


namespace SimpleMagento\QuestionAnswer\Block;

use Magento\Framework\View\Element\Template;
use SimpleMagento\QuestionAnswer\Model\QuestionAnswerFactory;

class Question extends Template
{
    /**
     * @var QuestionAnswerFactory
     */
    protected $questionAnswerFactory;

    public function __construct(Template\Context $context,
                                QuestionAnswerFactory $questionAnswerFactory,
                                array $data = [])
    {
        parent::__construct($context, $data);
        $this->questionAnswerFactory = $questionAnswerFactory;
    }
    public function getAnswer(){
         $getcollection = $this->questionAnswerFactory->create()->getCollection();
         return $getcollection;
    }
}
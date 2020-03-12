<?php


namespace SimpleMagento\QuestionAnswer\Ui\Component\Listing\Column;


use Magento\Framework\UrlInterface;
use Magento\Framework\View\Element\UiComponent\ContextInterface;
use Magento\Framework\View\Element\UiComponentFactory;
use Magento\Ui\Component\Listing\Columns\Column;

class QuestionAnswerAction extends Column
{

    protected $url;

    public function __construct(ContextInterface $context,
                                UiComponentFactory $uiComponentFactory,
                                UrlInterface $url,
                                array $components = [],

                                array $data = [])
    {
        parent::__construct($context, $uiComponentFactory, $components, $data);
        $this->url = $url;
    }

    public function prepareDataSource(array $dataSource)
    {
        if(isset($dataSource['data']['items']))

            foreach ($dataSource['data']['items'] as &$item){
                $item[$this->getData('name')] ["edit"] = ['href'=>
                $this->url->getUrl('question/action/edit',['id'=> $item['question_entity_id']]),
                'label'=>__('Edit'),
                'hidden'=> false
                ];

                $item[$this->getData('name')] ["delete"] = ['href'=>
                    $this->url->getUrl('question/action/delete',['id'=> $item['question_entity_id']]),
                    'label'=>__('Delete'),
                    'hidden'=> false
                ];
            }

        return parent::prepareDataSource($dataSource);
    }

}
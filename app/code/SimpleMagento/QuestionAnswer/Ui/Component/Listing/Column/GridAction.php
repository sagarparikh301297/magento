<?php


namespace SimpleMagento\QuestionAnswer\Ui\Component\Listing\Column;

use Magento\Framework\UrlInterface;
use Magento\Framework\View\Element\UiComponent\ContextInterface;
use Magento\Framework\View\Element\UiComponentFactory;
use Magento\Ui\Component\Listing\Columns\Column;

class GridAction extends Column
{
    /**
     * @var UrlInterface
     */
    protected $url;

    public function __construct(ContextInterface $context,
                                UrlInterface $url,
                                UiComponentFactory $uiComponentFactory,
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
                    $this->url->getUrl('question/custom/edit',['id'=> $item['question_entity_id'], 'page'=>0]),
                    'label'=>__('Edit'),
                    'hidden'=> false
                ];
            }
        return parent::prepareDataSource($dataSource);
    }
}

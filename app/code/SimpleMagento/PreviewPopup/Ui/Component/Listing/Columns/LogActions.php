<?php


namespace SimpleMagento\PreviewPopup\Ui\Component\Listing\Columns;

use Magento\Framework\View\Element\UiComponent\ContextInterface;
use Magento\Framework\View\Element\UiComponentFactory;
use Magento\Ui\Component\Listing\Columns\Column;
use Magento\Framework\UrlInterface;

class LogActions extends \Magento\Ui\Component\Listing\Columns\Column
{
    protected $urlBuilder;

    public function __construct(
        ContextInterface $context,
        UiComponentFactory $uiComponentFactory,
        UrlInterface $urlBuilder,
        \Magento\Framework\View\LayoutInterface $layout,
        array $components = [],
        array $data = []
    ) {
        $this->urlBuilder = $urlBuilder;
        $this->layout = $layout;
        parent::__construct($context, $uiComponentFactory, $components, $data);
    }

    /**
     * Get item url
     * @return string
     */
    public function getViewUrl()
    {
        return $this->urlBuilder->getUrl(
            $this->getData('config/viewUrlPath')
        );
    }

    public function prepareDataSource(array $dataSource)
    {
        if (isset($dataSource['data']['items'])) {
            foreach ($dataSource['data']['items'] as &$item) {
                if (isset($item['custom_entity_id'])) {
                    $name = $this->getData('name');

                    $item[$name] = html_entity_decode('<a href="#" class="action-activity-log-view" 
                        onclick="adminActivityLogView.open(\''
                        . $this->getViewUrl() . '\', \'' .
                        $item['custom_entity_id']. '\', \'' .
                        $item['restore'] . '\')'
                        .'")>'. __('Preview Changes').'</a>
                                <br/>
                                <a href="'.$this->urlBuilder->getUrl(
                            'previewpopup/actionslog/edit',
                            ['custom_entity_id' => $item['custom_entity_id']]
                        ).'">'. __('View Details').'</a>');
                    //View Details is not needed i've just added according to my requirement
                }
            }
        }

        return $dataSource;
    }
}
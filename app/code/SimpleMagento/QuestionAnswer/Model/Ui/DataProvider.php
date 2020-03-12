<?php

namespace SimpleMagento\QuestionAnswer\Model\Ui;

use SimpleMagento\QuestionAnswer\Model\ResourceModel\QuestionAnswer\CollectionFactory;
use Magento\Framework\App\Request\DataPersistorInterface;
use Magento\Ui\DataProvider\AbstractDataProvider;
use Magento\Framework\Registry;

class DataProvider extends AbstractDataProvider
{

    protected $collection;
    protected $loadedData;
    /**
     * @var Registry
     */
    protected $registry;

    private $dataPersistor;

    public function __construct(
        $name,
        $primaryFieldName,
        $requestFieldName,
        CollectionFactory $collectionFactory,
        DataPersistorInterface $dataPersistor,
        Registry $registry,
        array $meta = [],
        array $data = []
    ) {
        parent::__construct($name, $primaryFieldName, $requestFieldName, $meta, $data);
        $this->collection = $collectionFactory->create();
        $this->dataPersistor = $dataPersistor;
        $this->registry = $registry;
    }

    /**
     * @return array
     */
    public function getData()
    {
        if(isset($this->_loadedData)) {
            return $this->_loadedData;
        }
        $items = $this->collection->getItems();
        foreach($items as $contact)
        {
            $this->_loadedData[$contact->getId()] = $contact->getData();
        }
        return $this->_loadedData;
    }
}
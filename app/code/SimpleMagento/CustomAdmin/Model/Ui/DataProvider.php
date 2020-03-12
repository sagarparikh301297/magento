<?php

namespace SimpleMagento\CustomAdmin\Model\Ui;

use SimpleMagento\Database\Model\ResourceModel\AffiliateMember\CollectionFactory;
use Magento\Framework\App\Request\DataPersistorInterface;
use Magento\Ui\DataProvider\AbstractDataProvider;

class DataProvider extends AbstractDataProvider
{

    protected $collection;
    protected $loadedData;

    private $dataPersistor;

    public function __construct(
        $name,
        $primaryFieldName,
        $requestFieldName,
        CollectionFactory $collectionFactory,
        DataPersistorInterface $dataPersistor,
        array $meta = [],
        array $data = []
    ) {
        parent::__construct($name, $primaryFieldName, $requestFieldName, $meta, $data);
        $this->collection = $collectionFactory->create();
        $this->dataPersistor = $dataPersistor;
    }

    /**
     * @return array
     */
    public function getData()
    {
        if (isset($this->loadedData)) {
            return $this->loadedData;
        }


        $items = $this->collection->getItems();
        foreach ($items as $item) {
            $this->loadedData[$item->getId()] = $item->getData();
        }

        $data = $this->dataPersistor->get('member');
        if (!empty($data)) {
            $custom_admin = $this->collection->getNewEmptyItem();
            $custom_admin->setData($data);
            $this->loadedData[$custom_admin->getId()] = $custom_admin->getData();
            $this->dataPersistor->clear('member');
        }
//        var_dump($this->loadedData);
        return $this->loadedData;
    }
}
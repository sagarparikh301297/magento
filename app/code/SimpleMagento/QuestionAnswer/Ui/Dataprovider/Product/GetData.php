<?php


namespace SimpleMagento\QuestionAnswer\Ui\Dataprovider\Product;


use Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection;
use Magento\Ui\DataProvider\AbstractDataProvider;
use SimpleMagento\QuestionAnswer\Model\ResourceModel\QuestionAnswer\CollectionFactory;
use Magento\Catalog\Model\Locator\LocatorInterface;
use Magento\Framework\App\RequestInterface;


class GetData extends AbstractDataProvider
{
    /**
     * @var CollectionFactory
     */
    protected $factory;
    /**
     * @var AbstractCollection
     */
    protected $collection;
    /**
     * @var LocatorInterface
     */
    protected $locator;
    /**
     * @var RequestInterface
     */
    protected $request;

    /**
     * @param string $name
     * @param string $locator
     * @param string $primaryFieldName
     * @param string $requestFieldName
     * @param string $factory
     * @param RequestInterface $request
     * @param array $meta
     * @param array $data
     */
    public function __construct($name,
                                $primaryFieldName,
                                CollectionFactory $factory,
                                $requestFieldName,
                                LocatorInterface $locator,
                                RequestInterface $request,
                                array $meta = [],
                                array $data = [])
    {
        parent::__construct($name, $primaryFieldName, $requestFieldName, $meta, $data);
        $this->factory = $factory;
        $this->collection = $this->factory->create();
        $this->locator = $locator;
        $this->request = $request;
    }

    public function getData()
    {
        $this->getCollection();

        $arrItems = [
            'totalRecords' => $this->getCollection()->getSize(),
            'items' => [],
        ];

        foreach ($this->getCollection() as $item) {
            $arrItems['items'][] = $item->toArray([]);
        }

        return $arrItems;
    }

}
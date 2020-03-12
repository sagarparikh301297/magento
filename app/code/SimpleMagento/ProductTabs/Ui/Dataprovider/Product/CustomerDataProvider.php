<?php


namespace SimpleMagento\ProductTabs\Ui\Dataprovider\Product;


use Magento\Ui\DataProvider\AbstractDataProvider;
use Magento\Framework\App\RequestInterface;
use Magento\Catalog\Model\Locator\LocatorInterface;
use Magento\Customer\Model\Customer;

class CustomerDataProvider extends AbstractDataProvider
{
    /**
     * @var RequestInterface
     */
    protected $request;
    /**
     * @var LocatorInterface
     */
    protected $locator;
    /**
     * @var Customer
     */
    protected $customer;

    public function __construct($name,
                                $primaryFieldName,
                                RequestInterface $request,
                                LocatorInterface $locator,
                                Customer $customer,
                                $requestFieldName,
                                array $meta = [],
                                array $data = [])
    {
        parent::__construct($name, $primaryFieldName, $requestFieldName, $meta, $data);
        $this->request = $request;
        $this->locator = $locator;
        $this->customer = $customer;
    }

    /**
     * {@inheritdoc}
     */
    public function getData()
    {
       $collection = $this->customer->getCollection()
           ->load();

//        $collection->setCurPage(1);
//        $collection->setPageSize(10);
//        $arrItems = [
//            'totalRecords' => $collection->getSize(),
//            'items' => [],
//        ];
//
//        foreach ($arrItems as $item) {
//            $arrItems['items'][] = $item->toArray([]);
//        }

        return $collection;
    }
}
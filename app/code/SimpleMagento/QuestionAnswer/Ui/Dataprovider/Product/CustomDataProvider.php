<?php


namespace SimpleMagento\QuestionAnswer\Ui\Dataprovider\Product;

use Magento\Framework\App\RequestInterface;
use Magento\Ui\DataProvider\AbstractDataProvider;
use SimpleMagento\QuestionAnswer\Model\ResourceModel\QuestionAnswer\CollectionFactory;
use SimpleMagento\QuestionAnswer\Model\ResourceModel\QuestionAnswer\Collection;
use SimpleMagento\QuestionAnswer\Model\QuestionAnswer;
use Magento\Catalog\Model\Locator\LocatorInterface;

class CustomDataProvider extends AbstractDataProvider
{
    /**
     * @var CollectionFactory
     */
    protected $collectionFactory;

    /**
     * @var RequestInterface
     */
    protected $request;
    /**
     * @var LocatorInterface
     */
    protected $locator;

    /**
     * @param string $name
     * @param LocatorInterface $locator
     * @param string $primaryFieldName
     * @param string $requestFieldName
     * @param CollectionFactory $collectionFactory
     * @param RequestInterface $request
     * @param array $meta
     * @param array $data
     */
    public function __construct(
        $name,
        LocatorInterface $locator,
        $primaryFieldName,
        $requestFieldName,
        CollectionFactory $collectionFactory,
        RequestInterface $request,
        array $meta = [],
        array $data = []
    ) {
        parent::__construct($name, $primaryFieldName, $requestFieldName, $meta, $data);
        $this->collectionFactory = $collectionFactory;
        $this->collection = $this->collectionFactory->create();
        $this->request = $request;
        $this->locator = $locator;
    }

    /**
     * {@inheritdoc}
     */
    public function getData()
    {
        $objectManager = \Magento\Framework\App\ObjectManager::getInstance();
        $session = $objectManager->get('Magento\Customer\Model\Session');
        $productSku = $session->getMyvalue();
        $this->getCollection()->addFieldToFilter('question_productsku', $productSku);

        $arrItems = [
            'totalRecords' => $this->getCollection()->getSize(),
            'items' => [],
        ];

        foreach ($this->getCollection() as $item) {
            $arrItems['items'][] = $item->toArray([]);
        }

        return $arrItems;
    }

    /**
     * {@inheritdoc}
     */
    public function addFilter(\Magento\Framework\Api\Filter $filter)
    {
        $field = $filter->getField();

        if (in_array($field, ['question_title', 'question_productsku', 'question_customerid'])) {
            $filter->setField($field);
        }

        parent::addFilter($filter);
    }
}
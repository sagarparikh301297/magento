<?php


namespace SimpleMagento\QuestionAnswer\Ui\Component\Listing\Column;


use Magento\Framework\View\Element\UiComponent\ContextInterface;
use Magento\Framework\View\Element\UiComponentFactory;
use Magento\Ui\Component\Listing\Columns\Column;
use Magento\Customer\Api\CustomerRepositoryInterface;

class CustomerName extends Column
{

    /**
     * @var CustomerRepositoryInterface
     */
    protected $customerRepository;

    public function __construct(ContextInterface $context,
                                CustomerRepositoryInterface $customerRepository,
                                UiComponentFactory $uiComponentFactory,
                                array $components = [],
                                array $data = [])
    {
        parent::__construct($context, $uiComponentFactory, $components, $data);
        $this->customerRepository = $customerRepository;
    }


    public function prepareDataSource(array $dataSource)
    {

        if (isset($dataSource['data']['items'])) {
            foreach ($dataSource['data']['items'] as &$items) {
                if ($items['question_customerid'] == 0) {
                    $items['question_customerid'] = 'Guest';
                }else{
                    $Customer = $this->customerRepository->getById($items['question_customerid']);
                    $items['question_customerid'] = $Customer->getFirstname().' '.$Customer->getLastname();
                }
            }
        }
        return $dataSource;
    }
}
<?php


namespace SimpleMagento\QuestionAnswer\Ui\Component\Listing\Column;

use Magento\Framework\View\Element\UiComponent\ContextInterface;
use Magento\Framework\View\Element\UiComponentFactory;
use Magento\Ui\Component\Listing\Columns\Column;
use Magento\Catalog\Api\ProductRepositoryInterface;
use Magento\Framework\UrlInterface;

class ProductName extends Column
{
    /**
     * @var ProductRepositoryInterface
     */
    protected $productRepository;
    /**
     * @var UrlInterface
     */
    protected $urlBuilder;

    /** Url Path */
    const PRODUCT_URL_PATH_EDIT = 'catalog/product/edit';

    public function __construct(ContextInterface $context,
                                UiComponentFactory $uiComponentFactory,
                                ProductRepositoryInterface $productRepository,
                                UrlInterface $urlBuilder,
                                array $components = [],
                                array $data = [])
    {
        parent::__construct($context, $uiComponentFactory, $components, $data);
        $this->productRepository = $productRepository;
        $this->urlBuilder = $urlBuilder;
    }

    public function prepareDataSource(array $dataSource)
    {
        if (isset($dataSource['data']['items'])) {
            foreach ($dataSource['data']['items'] as &$items) {
                    $Product = $this->productRepository->get($items['question_productsku']);
                    $product_id = $Product->getId();
                    $name = $this->getData('name');
                    $product_name = $Product->getName();
                    $items[$name] = html_entity_decode('<a href="'.$this->urlBuilder->getUrl(self::PRODUCT_URL_PATH_EDIT, ['id' => $product_id]).'">'.$product_name.'</a>');
            }
        }
        return $dataSource;
    }
}
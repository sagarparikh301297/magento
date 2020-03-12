<?php


namespace SimpleMagento\QuestionAnswer\Ui\Dataprovider\Product\Form\Modifier;

use Magento\Catalog\Model\Locator\LocatorInterface;
use Magento\Catalog\Ui\DataProvider\Product\Form\Modifier\AbstractModifier;
use Magento\Framework\Stdlib\ArrayManager;
use Magento\Framework\UrlInterface;
use Magento\Ui\Component\Container;
use Magento\Ui\Component\Form\Fieldset;

class CustomTab extends AbstractModifier
{
    const SAMPLE_FIELDSET_NAME = 'custom_fieldset';
    const SAMPLE_FIELD_NAME = 'is_custom';


    protected $_backendUrl;
    protected $_productloader;
    protected $_modelCustomtabFactory;

    /**
     * @var \Magento\Catalog\Model\Locator\LocatorInterface
     */
    protected $locator;

    /**
     * @var ArrayManager
     */
    protected $arrayManager;

    /**
     * @var UrlInterface
     */
    protected $urlBuilder;

    /**
     * @var array
     */
    protected $meta = [];

    /**
     * @param LocatorInterface $locator
     * @param ArrayManager $arrayManager
     * @param UrlInterface $urlBuilder
     */
    public function __construct(
        LocatorInterface $locator,
        ArrayManager $arrayManager,
        UrlInterface $urlBuilder,
        \Magento\Catalog\Model\ProductFactory $_productloader,
        \Magento\Backend\Model\UrlInterface $backendUrl
    )
    {
        $this->locator = $locator;
        $this->arrayManager = $arrayManager;
        $this->urlBuilder = $urlBuilder;
        $this->_productloader = $_productloader;
        $this->_backendUrl = $backendUrl;
    }

    public function modifyData(array $data)
    {
        $objectManager = \Magento\Framework\App\ObjectManager::getInstance();
        $session = $objectManager->get('Magento\Customer\Model\Session');
        foreach ($data as $key){
            foreach ($key as $value){
                $session->setMyvalue($value['sku']);
            }
        }

        return $data;
    }

    public function modifyMeta(array $meta)
    {
        $this->meta = $meta;
        $this->addCustomTab();
        return $this->meta;
    }

    protected function addCustomTab()
    {
        $this->meta = array_merge_recursive(
            $this->meta,
            [
                static::SAMPLE_FIELDSET_NAME => $this->getTabConfig(),
            ]
        );
    }

    protected function getTabConfig()
    {
        return [
            'arguments' => [
                'data' => [
                    'config' => [
                        'label' => __('Product Questions & Answers'),
                        'componentType' => Fieldset::NAME,
                        'dataScope' => '',
                        'provider' => static::FORM_NAME . '.product_form_data_source',
                        'ns' => static::FORM_NAME,
                        'collapsible' => true,
                    ],
                ],
            ],
            'children' => [
                static::SAMPLE_FIELD_NAME => [
                    'arguments' => [
                        'data' => [
                            'config' => [
                                'autoRender' => true,
                                'componentType' => 'insertListing',
                                'dataScope' => 'custom_grid_listing',
                                'externalProvider' => 'custom_listing.custom_listing_data_source',
                                'selectionsProvider' => 'custom_listing.custom_listing.product_columns.ids',
                                'ns' => 'custom_grid_listing',
                                'render_url' => $this->urlBuilder->getUrl('mui/index/render'),
                                'realTimeLink' => false,
                                'behaviourType' => 'simple',
                                'externalFilterMode' => true,
                                'imports' => [
                                    'productId' => '${ $.provider }:data.product.current_product_id'
                                ],
                                'exports' => [
                                    'productId' => '${ $.externalProvider }:params.current_product_id'
                                ],
                            ],
                        ],
                    ],
                ],
            ],
        ];
    }
}
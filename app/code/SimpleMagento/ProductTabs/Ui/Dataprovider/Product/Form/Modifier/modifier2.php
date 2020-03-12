<?php


namespace SimpleMagento\ProductTabs\Ui\Dataprovider\Product\Form\Modifier;

use Magento\Catalog\Api\Data\ProductInterface;
use Magento\Customer\Api\Data\CustomerInterface;
use Magento\Catalog\Api\Data\ProductLinkInterface;
use Magento\Customer\Api\CustomerRepositoryInterface;
use Magento\Catalog\Model\Locator\LocatorInterface;
use Magento\Eav\Api\AttributeSetRepositoryInterface;
use Magento\Framework\App\ObjectManager;
use Magento\Framework\Phrase;
use Magento\Framework\UrlInterface;
use Magento\Ui\Component\DynamicRows;
use Magento\Ui\Component\Form\Element\DataType\Number;
use Magento\Ui\Component\Form\Element\DataType\Text;
use Magento\Ui\Component\Form\Element\Input;
use Magento\Ui\Component\Form\Field;
use Magento\Ui\Component\Form\Fieldset;
use Magento\Ui\Component\Modal;
use Magento\Catalog\Helper\Image as ImageHelper;
use Magento\Catalog\Model\Product\Attribute\Source\Status;
use Magento\Catalog\Ui\DataProvider\Product\Form\Modifier\AbstractModifier;
//use Magento\Customer\Model\ResourceModel\Customer\CollectionFactory;
use SimpleMagento\ProductTabs\Model\ResourceModel\ProductTabs\CollectionFactory;

class modifier2
{
        const CUSTOM_MODAL_LINK = 'custom_modal_link';
    const CUSTOM_MODAL_INDEX = 'custom_modal';
    const CUSTOM_MODAL_CONTENT = 'content';
    const CUSTOM_MODAL_FIELDSET = 'fieldset';
    const CONTAINER_HEADER_NAME = 'header';
    const CUSTOM_GROUP_NAME = 'my_custom_group';
    const CUSTOM_GROUP_BTN_CONTAINER_NAME = 'my_buttons';
    const SAMPLE_FIELD_NAME = 'is_custom';
    /**
     * @var LocatorInterface
     */
    protected $locator;
    /**
     * @var UrlInterface
     */
    protected $urlBuilder;
    /**
     * @var string
     */
    protected $scopeName;
    /**
     * @var string
     */
    protected $scopePrefix;
    /**
     * @var string
     */
    private static $previousGroup = 'search-engine-optimization';

    /**
     * @var int
     */
    private static $sortOrder = 90;

    /**
     * @var array
     */
    protected $meta = [];
    /**
     * @var CustomerRepositoryInterface
     */
    protected $customerRepository;

    /**
     * Modifier constructor.
     * @param LocatorInterface $locator
     * @param UrlInterface $urlBuilder
     */
    public function __construct(
        LocatorInterface $locator,
        UrlInterface $urlBuilder,
    CustomerRepositoryInterface $customerRepository
    ) {
        $this->locator = $locator;
        $this->urlBuilder = $urlBuilder;
        $this->customerRepository = $customerRepository;
    }

    /**
     * @param array $data
     * @return array
     * @since 100.1.0
     */
    public function modifyData(array $data)
    {
        return $data;
    }

    /**
     * @param array $meta
     * @return array
     * @since 100.1.0
     */
    public function modifyMeta(array $meta)
    {
        $this->meta = $meta;
        $this->addCustomModal();
        $this->addCustomModalLink(1000);

        return $this->meta;
    }

    /**
     * @return void
     */
    protected function addCustomModal()
    {
        $this->meta = array_merge_recursive(
            $this->meta,
            [
                static::CUSTOM_MODAL_INDEX => $this->getModalConfig(),
            ]
        );
    }

    /**
     * @return array
     */
    protected function getModalConfig()
    {
        return [
            'arguments' => [
                'data' => [
                    'config' => [
                        'componentType' => Modal::NAME,
                        'dataScope' => '',
                        'provider' => static::FORM_NAME . '.product_form_data_source',
                        'ns' => static::FORM_NAME,
                        'options' => [
                            'title' => __('Modal Title'),
                            'buttons' => [
                                [
                                    'text' => __('Save'),
                                    'class' => 'action-primary', // additional class
                                    'actions' => [
                                        [
                                            'targetName' => 'index = product_form', // Element selector
                                            'actionName' => 'save', // Save parent form (product)
                                        ],
                                        'closeModal', // method name
                                    ],
                                ],
                            ],
                        ],
                    ],
                ],
            ],
            'children' => [
                static::CUSTOM_MODAL_CONTENT => [
                    'arguments' => [
                        'data' => [
                            'config' => [
                                'autoRender' => false,
                                'componentType' => 'container',
                                'dataScope' => 'data.product', // save data in the product data
                                'externalProvider' => 'data.product_data_source',
                                'ns' => static::FORM_NAME,
                                'render_url' => $this->urlBuilder->getUrl('mui/index/render'),
                                'realTimeLink' => true,
                                'behaviourType' => 'edit',
                                'externalFilterMode' => true,
                                'currentProductId' => $this->locator->getProduct()->getId(),
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
                                        'dataScope' => 'customer_grid_listing',
                                        'externalProvider' => 'custom_listing.customer_grid_listing_data_source',
                                        'selectionsProvider' => 'custom_listing.custom_listing.product_columns.ids',
                                        'ns' => 'customer_grid_listing',
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
                ],
            ],
        ];
    }

    /**
     * @param $sortOrder
     * @return array
     */
    protected function getHeaderContainerConfig($sortOrder)
    {
        return [
            'arguments' => [
                'data' => [
                    'config' => [
                        'label' => null,
                        'formElement' => Container::NAME,
                        'componentType' => Container::NAME,
                        'template' => 'ui/form/components/complex',
                        'sortOrder' => $sortOrder,
                        'content' => __('You can write any text here'),
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
                                'dataScope' => 'customer_grid_listing',
                                'externalProvider' => 'custom_listing.customer_grid_listing_data_source',
                                'selectionsProvider' => 'custom_listing.custom_listing.product_columns.ids',
                                'ns' => 'customer_grid_listing',
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

    /**
     * @param $sortOrder
     * @return void
     */
    protected function addCustomModalLink($sortOrder)
    {
        $this->meta = array_replace_recursive(
            $this->meta,
            [
                static::CUSTOM_GROUP_NAME => [
                    'arguments' => [
                        'data' => [
                            'config' => [
                                'label' => __('My Group Fieldset'),
                                'componentType' => Fieldset::NAME,
                                'collapsible' => true,
                                'sortOrder' => $sortOrder,
                                'opened' => true,
                            ],
                        ],
                    ],
                    'children' => [
                        self::CUSTOM_GROUP_BTN_CONTAINER_NAME => [
                            'arguments' => [
                                'data' => [
                                    'config' => [
                                        'label' => null,
                                        'formElement' => Container::NAME,
                                        'componentType' => Container::NAME,
                                        'template' => 'ui/form/components/complex',
                                        'sortOrder' => 10,
                                        'content' => __('There are your buttons inside children section.'),
                                    ],
                                ],
                            ],
                            'children' => [
                                static::CUSTOM_MODAL_LINK => [
                                    'arguments' => [
                                        'data' => [
                                            'config' => [
                                                'title' => __('Open Custom Modal'),
                                                'formElement' => Container::NAME,
                                                'componentType' => Container::NAME,
                                                'component' => 'Magento_Ui/js/form/components/button',
                                                'actions' => [
                                                    [
                                                        'targetName' => 'ns=' . static::FORM_NAME . ', index='
                                                            . static::CUSTOM_MODAL_INDEX, // selector
                                                        'actionName' => 'openModal', // method name
                                                    ],
                                                ],
                                                'displayAsLink' => false,
                                                'sortOrder' => 10,
                                            ],
                                        ],
                                    ],
                                ],
                            ],
                        ],
                    ],
                ],
            ]
        );
    }
}
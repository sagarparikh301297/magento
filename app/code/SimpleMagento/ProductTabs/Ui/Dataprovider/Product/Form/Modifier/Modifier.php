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
use Magento\Customer\Model\ResourceModel\Customer\CollectionFactory as CustomFactory;
use SimpleMagento\ProductTabs\Model\ResourceModel\ProductTabs\CollectionFactory;
use Magento\Ui\Component\Container;

class
    Modifier extends AbstractModifier
{
    const DATA_SCOPE = '';
    const DATA_SCOPE_CUSTOMER = 'customertab';
    const GROUP_CUSTOMERTAB = 'customertab';

    /**
     * @var string
     */
    private static $previousGroup = 'search-engine-optimization';

    /**
     * @var int
     */
    private static $sortOrder = 90;

    /**
     * @var LocatorInterface
     */
    protected $locator;

    /**
     * @var UrlInterface
     */
    protected $urlBuilder;


    protected $CustomerInterface;

    /**
     * @var ImageHelper
     */
    protected $imageHelper;

    /**
     * @var Status
     */
    protected $status;

    /**
     * @var AttributeSetRepositoryInterface
     */
    protected $attributeSetRepository;

    /**
     * @var string
     */
    protected $scopeName;

    /**
     * @var string
     */
    protected $scopePrefix;
    /**
     * @var CustomFactory
     */
    protected $customfactory;

    /**
     * @var \Magento\Catalog\Ui\Component\Listing\Columns\Price
     */
    private $priceModifier;

    private $collectionFactory;

    /**
     * @param LocatorInterface $locator
     * @param UrlInterface $urlBuilder
     * @param ImageHelper $imageHelper
     * @param Status $status
     * @param AttributeSetRepositoryInterface $attributeSetRepository
     * @param string $scopeName
     * @param string $scopePrefix
     */
    public function __construct(
        LocatorInterface $locator,
        UrlInterface $urlBuilder,
        CustomFactory $customfactory,
        ImageHelper $imageHelper,
        Status $status,
        AttributeSetRepositoryInterface $attributeSetRepository,
        CollectionFactory $collectionFactory,
        $scopeName = '',
        $scopePrefix = ''
    ) {
        $this->locator = $locator;
        $this->urlBuilder = $urlBuilder;
        $this->imageHelper = $imageHelper;
        $this->status = $status;
        $this->attributeSetRepository = $attributeSetRepository;
        $this->scopeName = $scopeName;
        $this->scopePrefix = $scopePrefix;
        $this->collectionFactory = $collectionFactory;
        $this->customfactory = $customfactory;
    }

    /**
     * {@inheritdoc}
     */
    public function modifyMeta(array $meta)
    {
        $meta = array_replace_recursive(
            $meta,
            [
                static::GROUP_CUSTOMERTAB => [
                    'children' => [
                        $this->scopePrefix . static::DATA_SCOPE_CUSTOMER => $this->getCustomerFieldset(),
                    ],
                    'arguments' => [
                        'data' => [
                            'config' => [
                                'label' => __('Customer'),
                                'collapsible' => true,
                                'componentType' => Fieldset::NAME,
                                'dataScope' => static::DATA_SCOPE,
                                'sortOrder' =>
                                    $this->getNextGroupSortOrder(
                                        $meta,
                                        self::$previousGroup,
                                        self::$sortOrder
                                    ),
                            ],
                        ],

                    ],
                ],
            ]
        );

        return $meta;
    }

    /**
     * {@inheritdoc}
     */
    public function modifyData(array $data)
    {
        /** @var \Magento\Catalog\Model\Product $product */
        $product = $this->locator->getProduct();
        $productId = $product->getId();

        if (!$productId) {
            return $data;
        }

        $customerCollection = $this->customfactory->create();
        $collection = $this->collectionFactory->create();
        foreach ($this->getDataScopes() as $dataScope) {

            $data[$productId]['links'][$dataScope] = [];
            foreach ($collection as $linkItem) {
                $data[$productId]['links'][$dataScope][] = $this->fillData($linkItem);
            }

        }

        $data[$productId][self::DATA_SOURCE_DEFAULT]['current_product_id'] = $productId;
        $data[$productId][self::DATA_SOURCE_DEFAULT]['current_store_id'] = $this->locator->getStore()->getId();

        return $data;
    }


    /**
     * Prepare data column
     *
     * @param ProductInterface $linkedProduct
     * @param ProductLinkInterface $linkItem
     * @return array
     */
    protected function fillData($linkItem)
    {
        return [
            'id' => $linkItem['custom_entity_id'],
            'name' => $linkItem['custom_customer_id'],
            'email' => $linkItem['custom_product_sku'],
        ];
    }

    /**
     * Retrieve all data scopes
     *
     * @return array
     */
    protected function getDataScopes()
    {
        return [
            static::DATA_SCOPE_CUSTOMER,
        ];
    }

    /**
     * Prepares config for the Related products fieldset
     *
     * @return array
     */
    protected function getCustomerFieldset()
    {
        $content = __(
            'This products are shown to customers in addition to the item the customer is looking at.'
        );

        return [
            'children' => [
                'button_set' => $this->getButtonSet(
                    $content,
                    __('Add Specific Customer'),
                    $this->scopePrefix . static::DATA_SCOPE_CUSTOMER
                ),
                'modal' => $this->getGenericModal(
                    __('Add Specific Customer'),
                    $this->scopePrefix . static::DATA_SCOPE_CUSTOMER
                ),
                static::DATA_SCOPE_CUSTOMER => $this->getGrid($this->scopePrefix . static::DATA_SCOPE_CUSTOMER),
            ],
            'arguments' => [
                'data' => [
                    'config' => [
                        'additionalClasses' => 'admin__fieldset-section',
                        'label' => __('Customer'),
                        'collapsible' => false,
                        'componentType' => Fieldset::NAME,
                        'dataScope' => '',
                        'sortOrder' => 10,
                    ],
                ],
            ]
        ];
    }


    /**
     * Retrieve button set
     *
     * @param Phrase $content
     * @param Phrase $buttonTitle
     * @param string $scope
     * @return array
     */
    protected function getButtonSet(Phrase $content, Phrase $buttonTitle, $scope)
    {
        $modalTarget = $this->scopeName . '.' . static::GROUP_CUSTOMERTAB . '.' . $scope . '.modal';

        return [
            'arguments' => [
                'data' => [
                    'config' => [
                        'formElement' => 'container',
                        'componentType' => 'container',
                        'label' => false,
                        'content' => $content,
                        'template' => 'ui/form/components/complex',
                    ],
                ],
            ],
            'children' => [
                'button_' . $scope => [
                    'arguments' => [
                        'data' => [
                            'config' => [
                                'formElement' => 'container',
                                'componentType' => 'container',
                                'component' => 'Magento_Ui/js/form/components/button',
                                'actions' => [
                                    [
                                        'targetName' => $modalTarget,
                                        'actionName' => 'toggleModal',
                                    ],
                                    [
                                        'targetName' => $modalTarget . '.' . $scope . '_customer_listing',
                                        'actionName' => 'render',
                                    ]
                                ],
                                'title' => $buttonTitle,
                                'provider' => null,
                            ],
                        ],
                    ],
                ],
            ],
        ];
    }

    /**
     * Prepares config for modal slide-out panel
     *
     * @param Phrase $title
     * @param string $scope
     * @return array
     */
    protected function getGenericModal(Phrase $title, $scope)
    {
        $listingTarget = $scope . '_customer_listing';
        $modal = [
            'arguments' => [
                'data' => [
                    'config' => [
                        'componentType' => Modal::NAME,
                        'dataScope' => '',
                        'options' => [
                            'title' => $title,
                            'buttons' => [
                                [
                                    'text' => __('Cancel'),
                                    'actions' => [
                                        'closeModal'
                                    ]
                                ],
                                [
                                    'text' => __('Add Selected Customers'),
                                    'class' => 'action-primary',
                                    'actions' => [
                                        [
                                            'targetName' => 'index = producttab_index_edit',
                                            'actionName' => 'save'
                                        ],
                                        'closeModal'
                                    ]
                                ],
                            ],
                        ],
                    ],
                ],
            ],
            'children' => [
                $listingTarget => [
                    'arguments' => [
                        'data' => [
                                'config' => [
                                    'autoRender' => false,
                                    'componentType' => 'insertListing',
    //                                'component' => 'SimpleMagento_ProductTabs/js/assign',
                                    'renderDefaultRecord' => false,
                                    'dataScope' => 'customer_grid_listing',
                                    'selectionsProvider' => 'customer_grid_listing.customer_grid_listing.customer_columns.ids',
                                    'ns' => 'customer_grid_listing',
                                    'render_url' => $this->urlBuilder->getUrl('mui/index/render'),
                                    'realTimeLink' => true,
                                    'dataLinks' => [
                                        'imports' => false,
                                        'exports' => true
                                    ],
                                    'behaviourType' => 'simple',
                                    'externalFilterMode' => true,
                                    'imports' => [
                                        'productId' => '${ $.provider }:data.product.current_product_id',
                                        'storeId' => '${ $.provider }:data.product.current_store_id',
                                    ],
                                    'exports' => [
                                        'productId' => '${ $.externalProvider }:params.current_product_id',
                                        'storeId' => '${ $.externalProvider }:params.current_store_id',
                                    ]
        //                                'additionalClasses' => 'admin__field-wide',
        //                                'componentType' => DynamicRows::NAME,
        //                                'label' => null,
        //                                'columnsHeader' => false,
        //                                'columnsHeaderAfterRender' => true,
        //                                'renderDefaultRecord' => false,
        //                                'template' => 'ui/dynamic-rows/templates/grid',
        //                                'component' => 'Magento_Ui/js/dynamic-rows/dynamic-rows-grid',
        //                                'addButton' => false,
        //                                'recordTemplate' => 'record',
        //                                'dataScope' => 'data.links',
        //                                'deleteButtonLabel' => __('Remove'),
        //                                    'dataProvider' => 'customertab_customer_listing',
        //                                'map' => [
        //                                    'id' => 'id',
        //                                    'name' => 'name',
        //                                    'email' => 'email',
        //                                ],
        //                                'links' => [
        //                                    'insertData' => '${ $.provider }:${ $.dataProvider }'
        //                                ],
        //                                'sortOrder' => 2,
        //                            ],
        //                        ],
        //                    ],
        //                    'children' => [
        //                        'record' => [
        //                            'arguments' => [
        //                                'data' => [
        //                                    'config' => [
        //                                        'componentType' => 'container',
        //                                        'isTemplate' => true,
        //                                        'is_collection' => true,
        //                                        'component' => 'Magento_Ui/js/dynamic-rows/record',
        //                                        'dataScope' => '',
        //                                    ],
        //                                ],
        //                            ],
        //                            'children' => $this->fillMeta(),
        //                        ],
        //                    ],
                                ],
                        ],
                    ],
                ],
            ],
        ];

        return $modal;
    }

    /**
     * Retrieve grid
     *
     * @param string $scope
     * @return array
     * @SuppressWarnings(PHPMD.ExcessiveMethodLength)
     */
    protected function getGrid($scope)
    {
        $dataProvider = $scope . '_customer_listing';
        return [
            'arguments' => [
                'data' => [
                    'config' => [
                        'additionalClasses' => 'admin__field-wide',
                        'componentType' => DynamicRows::NAME,
                        'label' => null,
                        'columnsHeader' => false,
                        'columnsHeaderAfterRender' => true,
                        'renderDefaultRecord' => false,
                        'template' => 'ui/dynamic-rows/templates/grid',
                        'component' => 'Magento_Ui/js/dynamic-rows/dynamic-rows-grid',
                        'addButton' => false,
                        'recordTemplate' => 'record',
                        'dataScope' => 'data.links',
                        'deleteButtonLabel' => __('Remove'),
                        'dataProvider' => $dataProvider,
                        'map' => [
                            'id' => 'id',
                            'name' => 'name',
                            'email' => 'email',
                        ],
                        'links' => [
                            'insertData' => '${ $.provider }:${ $.dataProvider }'
                        ],
                        'sortOrder' => 2,
                    ],
                ],
            ],
            'children' => [
                'record' => [
                    'arguments' => [
                        'data' => [
                            'config' => [
                                'componentType' => 'container',
                                'isTemplate' => true,
                                'is_collection' => true,
                                'component' => 'Magento_Ui/js/dynamic-rows/record',
                                'dataScope' => '',
                            ],
                        ],
                    ],
                    'children' => $this->fillMeta(),
                ],
            ],
        ];
    }

    /**
     * Retrieve meta column
     *
     * @return array
     */
    protected function fillMeta()
    {
        return [
            'id' => $this->getTextColumn('id', false, __('ID'), 0),
            'name' => $this->getTextColumn('name', false, __('Name'), 20),
            'email' => $this->getTextColumn('email', true, __('Email'), 30),
            'actionDelete' => [
                'arguments' => [
                    'data' => [
                        'config' => [
                            'additionalClasses' => 'data-grid-actions-cell',
                            'componentType' => 'actionDelete',
                            'dataType' => Text::NAME,
                            'label' => __('Actions'),
                            'sortOrder' => 70,
                            'fit' => true,
                        ],
                    ],
                ],
            ],
        ];
    }

    /**
     * Retrieve meta column
     *
     * @return array
     */
    protected function fillMetas()
    {
        return [
            'id' => $this->getTextColumn('id', false, __('ID'), 0),
            'name' => $this->getTextColumn('name', false, __('Name'), 20),
            'email' => $this->getTextColumn('email', true, __('Email'), 30),
        ];
    }

    /**
     * Retrieve text column structure
     *
     * @param string $dataScope
     * @param bool $fit
     * @param Phrase $label
     * @param int $sortOrder
     * @return array
     */
    protected function getTextColumn($dataScope, $fit, Phrase $label, $sortOrder)
    {
        $column = [
            'arguments' => [
                'data' => [
                    'config' => [
                        'componentType' => Field::NAME,
                        'formElement' => Input::NAME,
                        'elementTmpl' => 'ui/dynamic-rows/cells/text',
                        'component' => 'Magento_Ui/js/form/element/text',
                        'dataType' => Text::NAME,
                        'dataScope' => $dataScope,
                        'fit' => $fit,
                        'label' => $label,
                        'sortOrder' => $sortOrder,
                    ],
                ],
            ],
        ];

        return $column;
    }
}
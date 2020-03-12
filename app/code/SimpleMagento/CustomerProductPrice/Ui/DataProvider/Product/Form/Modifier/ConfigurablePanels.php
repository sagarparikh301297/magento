<?php


namespace SimpleMagento\CustomerProductPrice\Ui\DataProvider\Product\Form\Modifier;

use Magento\Catalog\Api\Data\ProductInterface;
use Magento\Catalog\Api\Data\ProductLinkInterface;
use Magento\Catalog\Model\Locator\LocatorInterface;
use Magento\Catalog\Model\Product\Attribute\Backend\Sku;
use Magento\Catalog\Ui\DataProvider\Product\Form\Modifier\AbstractModifier;
use Magento\ConfigurableProduct\Ui\DataProvider\Product\Form\Modifier\ConfigurablePanel;
use Magento\Framework\Phrase;
use Magento\Framework\UrlInterface;
use Magento\Ui\Component\Container;
use Magento\Ui\Component\DynamicRows;
use Magento\Ui\Component\Form;
use Magento\Ui\Component\Form\Element\DataType\Text;
use Magento\Ui\Component\Form\Element\Input;
use Magento\Ui\Component\Form\Field;
use Magento\Ui\Component\Modal;
use SimpleMagento\ProductTabs\Model\ResourceModel\ProductTabs\CollectionFactory;

class ConfigurablePanels extends AbstractModifier
{
    const GROUP_CONFIGURABLE = 'configurables';
    const ASSOCIATED_PRODUCT_MODAL = 'configurable_associated_product_modals';
    const ASSOCIATED_PRODUCT_LISTING = 'configurable_associated_product_listings';
    const CONFIGURABLE_MATRIX = 'configurable-matrixs';
    const DATA_SCOPE_CUSTOMER = 'custom';

    /**
     * @var string
     */
    private static $groupContent = 'content';

    /**
     * @var int
     */
    private static $sortOrder = 30;
    /**
     * @var CollectionFactory
     */
    protected $collectionFactory;

    /**
     * @var UrlInterface
     */
    private $urlBuilder;

    /**
     * @var string
     */
    private $formName;

    /**
     * @var string
     */
    private $dataScopeName;

    /**
     * @var string
     */
    private $dataSourceName;

    /**
     * @var string
     */
    private $associatedListingPrefix;

    /**
     * @var LocatorInterface
     */
    private $locator;

    /**
     * @param LocatorInterface $locator
     * @param UrlInterface $urlBuilder
     * @param string $formName
     * @param string $dataScopeName
     * @param string $dataSourceName
     * @param string $associatedListingPrefix
     */
    public function __construct(
        LocatorInterface $locator,
        UrlInterface $urlBuilder,
        CollectionFactory $collectionFactory,
        $formName,
        $dataScopeName,
        $dataSourceName,
        $associatedListingPrefix = ''
    ) {
        $this->locator = $locator;
        $this->urlBuilder = $urlBuilder;
        $this->formName = $formName;
        $this->dataScopeName = $dataScopeName;
        $this->dataSourceName = $dataSourceName;
        $this->associatedListingPrefix = $associatedListingPrefix;
        $this->collectionFactory = $collectionFactory;
    }


    /**
     * @param array $data
     * @return array
     * @since 100.1.0
     */
    public function modifyData(array $data)
    {
        $product = $this->locator->getProduct();
        $productId = $product->getId();

        if (!$productId) {
            return $data;
        }

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
     * @param array $meta
     * @return array
     * @since 100.1.0
     */
    public function modifyMeta(array $meta)
    {
        $meta = array_merge_recursive(
            $meta,
            [
                static::GROUP_CONFIGURABLE => [
                    'arguments' => [
                        'data' => [
                            'config' => [
                                'label' => __('Custom Configurations 2'),
                                'collapsible' => true,
                                'opened' => true,
                                'componentType' => Form\Fieldset::NAME,
                                'sortOrder' => $this->getNextGroupSortOrder(
                                    $meta,
                                    self::$groupContent,
                                    self::$sortOrder
                                ),
                            ],
                        ],
                    ],
                    'children' => $this->getPanelChildren(),
                ],
            ]
        );

        return $meta;
    }

    /**
     * Prepares panel children configuration
     *
     * @return array
     */
    protected function getPanelChildren()
    {
        return [
            'configurable_products_button_set' => $this->getButtonSet(),
            static::DATA_SCOPE_CUSTOMER => $this->getGrid($this->dataScopeName . static::DATA_SCOPE_CUSTOMER),
        ];
    }

    /**
     * Returns Buttons Set configuration
     *
     * @return array
     */
    protected function getButtonSet()
    {
        return [
            'arguments' => [
                'data' => [
                    'config' => [
                        'component' => 'Magento_ConfigurableProduct/js/components/container-configurable-handler',
                        'formElement' => 'container',
                        'componentType' => 'container',
                        'label' => false,
                        'content1' => __(
                            'Configurable products allow customers to choose options '
                            . '(Ex: shirt color). You need to create a simple product for each '
                            . 'configuration (Ex: a product for each color).'
                        ),
                        'content2' => __(
                            'Configurations cannot be created for a standard product with downloadable files. '
                            . 'To create configurations, first remove all downloadable files.'
                        ),
                        'template' => 'ui/form/components/complex',
                        'createConfigurableButton' => 'ns = ${ $.ns }, index = create_configurable_products_button',
                    ],
                ],
            ],
            'children' => [
                'create_configurable_products_button' => [
                    'arguments' => [
                        'data' => [
                            'config' => [
                                'formElement' => 'container',
                                'componentType' => 'container',
                                'component' => 'Magento_Ui/js/form/components/button',
                                'actions' => [
                                    [
                                        'targetName' => $this->dataScopeName . '.configurableModal', //details of modal window
                                        'actionName' => 'trigger',
                                        'params' => ['active', true],
                                    ],
                                    [
                                        'targetName' => $this->dataScopeName . '.configurableModal',
                                        'actionName' => 'openModal',
                                    ],
                                ],
                                'title' => __('Create Configurations'),
                                'sortOrder' => 20,
                            ],
                        ],
                    ],
                ],
            ],
        ];
    }

    /**
     * Returns dynamic rows configuration
     *
     * @return array
     */
    protected function getGrid($scope)
    {
        $dataProvider = 'configurable_associated_product_listings';
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
                        'dataProvider' => 'configurable_associated_product_listings',
                        'map' => [
                            'id' => 'entity_id',
                            'name' => 'custom_customer_id',
                            'email' => 'custom_product_sku',
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
//        return [
//            'arguments' => [
//                'data' => [
//                    'config' => [
//                        'additionalClasses' => 'admin__field-wide',
//                        'componentType' => DynamicRows::NAME,
//                        'dndConfig' => [
//                            'enabled' => false,
//                        ],
//                        'label' => __('Current Variations'),
//                        'renderDefaultRecord' => false,
//                        'template' => 'ui/dynamic-rows/templates/grid',
//                        'component' => 'Magento_Ui/js/dynamic-rows/dynamic-rows-grid',
//                        'addButton' => false,
//                        'isEmpty' => true,
//                        'itemTemplate' => 'record',
//                        'dataScope' => 'data',
//                        'dataProviderFromGrid' => $this->associatedListingPrefix . static::ASSOCIATED_PRODUCT_LISTING,
//                        'dataProviderChangeFromGrid' => 'change_product',
//                        'dataProviderFromWizard' => 'variations',
//                        'map' => [
//                            'id' => 'entity_id',
//                            'name' => 'custom_customer_id',
//                            'email' => 'custom_product_sku',
//                        ],
//                        'links' => [
//                            'insertDataFromGrid' => '${$.provider}:${$.dataProviderFromGrid}',
//                            'insertDataFromWizard' => '${$.provider}:${$.dataProviderFromWizard}',
//                            'changeDataFromGrid' => '${$.provider}:${$.dataProviderChangeFromGrid}',
//                        ],
//                        'sortOrder' => 20,
//                        'columnsHeader' => false,
//                        'columnsHeaderAfterRender' => true,
//                        'modalWithGrid' => 'ns=' . $this->formName . ', index='
//                            . static::ASSOCIATED_PRODUCT_MODAL,
//                        'gridWithProducts' => 'ns=' . $this->associatedListingPrefix
//                            . static::ASSOCIATED_PRODUCT_LISTING
//                            . ', index=' . static::ASSOCIATED_PRODUCT_LISTING,
//                    ],
//                ],
//            ],
//            'children' => $this->getRows(),
//        ];
    }

    /**
     * Returns Dynamic rows records configuration
     *
     * @return array
     * @SuppressWarnings(PHPMD.ExcessiveMethodLength)
     */
    protected function getRows()
    {
        return [
            'record' => [
                'arguments' => [
                    'data' => [
                        'config' => [
                            'componentType' => Container::NAME,
                            'isTemplate' => true,
                            'is_collection' => true,
                            'component' => 'Magento_Ui/js/dynamic-rows/record',
                            'dataScope' => '',
                        ],
                    ],
                ],
                'children' => $this->fillMeta(),
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

    /**
     * Get configuration of column
     *
     * @param string $name
     * @param \Magento\Framework\Phrase $label
     * @param array $editConfig
     * @param array $textConfig
     * @return array
     */
    protected function getColumn(
        $name,
        \Magento\Framework\Phrase $label,
        $editConfig = [],
        $textConfig = []
    ) {
        $fieldEdit['arguments']['data']['config'] = [
            'dataType' => Form\Element\DataType\Number::NAME,
            'formElement' => Form\Element\Input::NAME,
            'componentType' => Form\Field::NAME,
            'dataScope' => $name,
            'fit' => true,
            'visibleIfCanEdit' => true,
            'imports' => [
                'visible' => '${$.provider}:${$.parentScope}.canEdit'
            ],
        ];
        $fieldText['arguments']['data']['config'] = [
            'componentType' => Form\Field::NAME,
            'formElement' => Form\Element\Input::NAME,
            'elementTmpl' => 'Magento_ConfigurableProduct/components/cell-html',
            'dataType' => Form\Element\DataType\Text::NAME,
            'dataScope' => $name,
            'visibleIfCanEdit' => false,
            'labelVisible' => false,
            'imports' => [
                'visible' => '!${$.provider}:${$.parentScope}.canEdit'
            ],
        ];
        $fieldEdit['arguments']['data']['config'] = array_replace_recursive(
            $fieldEdit['arguments']['data']['config'],
            $editConfig
        );
        $fieldText['arguments']['data']['config'] = array_replace_recursive(
            $fieldText['arguments']['data']['config'],
            $textConfig
        );
        $container['arguments']['data']['config'] = [
            'componentType' => Container::NAME,
            'formElement' => Container::NAME,
            'component' => 'Magento_Ui/js/form/components/group',
            'label' => $label,
            'dataScope' => '',
            'showLabel' => false
        ];
        $container['children'] = [
            $name . '_edit' => $fieldEdit,
            $name . '_text' => $fieldText,
        ];

        return $container;
    }
}
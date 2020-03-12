<?php
/**
 * Mirasvit
 *
 * This source file is subject to the Mirasvit Software License, which is available at https://mirasvit.com/license/.
 * Do not edit or add to this file if you wish to upgrade the to newer versions in the future.
 * If you wish to customize this module for your needs.
 * Please refer to http://www.magentocommerce.com for more information.
 *
 * @category  Mirasvit
 * @package   mirasvit/module-cataloglabel
 * @version   1.1.14
 * @copyright Copyright (C) 2019 Mirasvit (https://mirasvit.com/)
 */



namespace Mirasvit\CatalogLabel\Block\Adminhtml\Label\Edit\Tab\Attribute;

use Mirasvit\CatalogLabel\Model as CatalogLabelModel;

/**
 * @SuppressWarnings(PHPMD.CouplingBetweenObjects)
 */
class Gallery extends \Magento\Backend\Block\Widget
{
    /**
     * @var CatalogLabelModel\Label\AttributeFactory
     */
    protected $labelAttributeFactory;

    /**
     * @var \Magento\Eav\Model\Entity\AttributeFactory
     */
    protected $entityAttributeFactory;

    /**
     * @var \Magento\Backend\Model\UrlFactory
     */
    protected $urlFactory;

    /**
     * @var CatalogLabelModel\ResourceModel\Label\Attribute\CollectionFactory
     */
    protected $labelAttributeCollectionFactory;

    /**
     * @var CatalogLabelModel\System\Config\Source\ImageType
     */
    protected $systemConfigSourceImageType;

    /**
     * @var \Magento\Eav\Model\Config
     */
    protected $config;

    /**
     * @var \Magento\Framework\Registry
     */
    protected $registry;

    /**
     * @var \Magento\Framework\Json\Helper\Data
     */
    protected $jsonEncoder;

    /**
     * @var \Magento\Backend\Block\Widget\Context
     */
    protected $context;

    /**
     * @var \Magento\Framework\DataObject
     */
    protected $uploadConfig;

    /**
     * @param CatalogLabelModel\Label\AttributeFactory                          $labelAttributeFactory
     * @param \Magento\Eav\Model\Entity\AttributeFactory                        $entityAttributeFactory
     * @param \Magento\Backend\Model\UrlFactory                                 $urlFactory
     * @param CatalogLabelModel\ResourceModel\Label\Attribute\CollectionFactory $labelAttributeCollectionFactory
     * @param CatalogLabelModel\System\Config\Source\ImageType                  $systemConfigSourceImageType
     * @param \Magento\Eav\Model\Config                                         $config
     * @param \Magento\Framework\Registry                                       $registry
     * @param \Magento\Framework\Json\Helper\Data                               $jsonEncoder
     * @param \Magento\Framework\Data\FormFactory                               $formFactory
     * @param \Magento\Backend\Block\Widget\Context                             $context
     * @param array                                                             $data
     *
     * @SuppressWarnings(PHPMD.ExcessiveParameterList)
     * @SuppressWarnings(PHPMD.CouplingBetweenObjects)
     */
    public function __construct(
        CatalogLabelModel\Label\AttributeFactory $labelAttributeFactory,
        \Magento\Eav\Model\Entity\AttributeFactory $entityAttributeFactory,
        \Magento\Backend\Model\UrlFactory $urlFactory,
        CatalogLabelModel\ResourceModel\Label\Attribute\CollectionFactory $labelAttributeCollectionFactory,
        CatalogLabelModel\System\Config\Source\ImageType $systemConfigSourceImageType,
        \Magento\Eav\Model\Config $config,
        \Magento\Framework\Registry $registry,
        \Magento\Framework\Json\Helper\Data $jsonEncoder,
        \Magento\Framework\Data\FormFactory $formFactory,
        \Magento\Backend\Block\Widget\Context $context,
        array $data = []
    ) {
        $this->labelAttributeFactory = $labelAttributeFactory;
        $this->entityAttributeFactory = $entityAttributeFactory;
        $this->urlFactory = $urlFactory;
        $this->labelAttributeCollectionFactory = $labelAttributeCollectionFactory;
        $this->systemConfigSourceImageType = $systemConfigSourceImageType;
        $this->config = $config;
        $this->registry = $registry;
        $this->jsonEncoder = $jsonEncoder;
        $this->formFactory = $formFactory;
        $this->context = $context;
        parent::__construct($context, $data);
    }

    /**
     * @return void
     */
    protected function _construct()
    {
        parent::_construct();
        $this->setTemplate('label/edit/tab/attribute/gallery.phtml');
    }

    /**
     * @return string
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function getAddButtonHtml()
    {
        $this->_getAttribute()->getData('frontend_input');

        $addButton = $this->getLayout()->createBlock('\Magento\Backend\Block\Widget\Button')
            ->setData([
                'label' => __('Add New Row'),
                'id' => 'add_link_item',
                'class' => 'add',
            ]);

        if (!$this->_getAttribute()->usesSource()) {
            return $addButton->toHtml();
        }
    }

    /**
     * @return array
     * @throws \Magento\Framework\Exception\LocalizedException
     *
     * @SuppressWarnings(PHPMD.UnusedLocalVariable)
     */
    public function getAttibuteOptions()
    {
        $options = [];
        $attribute = $this->_getAttribute();

        if ($attribute->usesSource()) {
            $options = $attribute->getSource()->getAllOptions(false);
            foreach ($options as $key => $option) {
                $attr = $this->labelAttributeFactory->create()
                    ->loadByKey($this->_getModel()->getId(), $option['value']);
                foreach ($this->getImageType() as $type => $label) {
                    $options[$key][$type.'_title'] = $attr->getDisplay()->getTitle($type);
                    $options[$key][$type.'_description'] = $attr->getDisplay()->getDescription($type);
                    $options[$key][$type.'_url'] = $attr->getDisplay()->getUrl($type);
                    $options[$key][$type.'_file_save'] = [
                        'url' => $attr->getDisplay()->getImageUrl($type),
                        'file' => $attr->getDisplay()->getImage($type),
                    ];
                }
            }
        } else {
            $attributes = $this->labelAttributeCollectionFactory->create()
                ->addFieldToFilter('label_id', $this->_getModel()->getId());
            foreach ($attributes as $key => $attr) {
                $options[$key] = $attr->getData();
                $options[$key]['file_save'] = [
                    'url' => $attr->getImageUrl(),
                    'file' => $attr->getImage(),
                ];
            }
        }

        return $options;
    }

    /**
     * @return array
     */
    public function getImageType()
    {
        $images = [];

        $placeholder = $this->_getModel()->getPlaceholder();
        foreach ($placeholder->getImageType() as $type) {
            $images[$type] = $this->systemConfigSourceImageType->getLabel($type);
        }

        return $images;
    }

    /**
     * @return \Magento\Framework\Model\AbstractModel
     */
    protected function _getModel()
    {
        return $this->registry->registry('current_model');
    }

    /**
     * @return \Magento\Eav\Model\Entity\Attribute\AbstractAttribute
     */
    protected function _getAttribute()
    {
        $attributeId = $this->_getModel()->getAttributeId();
        $attributeCode = $this->entityAttributeFactory->create()->load($attributeId)->getAttributeCode();
        $attribute = $this->config->getAttribute('catalog_product', $attributeCode);

        return $attribute;
    }

    /**
     * @return string
     */
    public function getConfigJson()
    {
        $this->getConfig()->setUrl($this->urlFactory->create()
            ->addSessionParam()->getUrl('*/adminhtml_label/upload', ['_secure' => true]));
        $this->getConfig()->setParams(['form_key' => $this->getFormKey()]);
        $this->getConfig()->setFileField('file');
        $this->getConfig()->setFilters([
            'all' => [
                'label' => __('All Files'),
                'files' => ['*.*'],
            ],
        ]);
        $this->getConfig()->setReplaceBrowseWithRemove(true);
        $this->getConfig()->setWidth('32');
        $this->getConfig()->setHideUploadButton(true);

        return $this->jsonEncoder->jsonEncode($this->getConfig()->getData());
    }

    /**
     * @return \Magento\Framework\DataObject
     */
    public function getConfig()
    {
        if ($this->uploadConfig === null) {
            $this->uploadConfig = new \Magento\Framework\DataObject();
        }

        return $this->uploadConfig;
    }

    /**
     * @param string $fieldId
     * @param string $fieldName
     * @return string
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function getImageField($fieldId = 'img_field', $fieldName = 'img_field')
    {
        $form = $this->formFactory->create();
        $form->setFieldNameSuffix('label');

        $general = $form->addFieldset('fieldset_'.$fieldId, [
            'legend' => __('Image'),
            'html_id' => 'fieldsethtml_'.$fieldId,
        ]);
        $general->addType('image1', '\Mirasvit\CatalogLabel\Block\Adminhtml\Helper\Image');
        $general->addField($fieldId, 'image1', [
            'label' => __('Title'),
            'required' => true,
            'name' => $fieldName,
            'value' => '',
            'html_id' => $fieldId,
        ]);

        return $general->getChildrenHtml();
    }
}

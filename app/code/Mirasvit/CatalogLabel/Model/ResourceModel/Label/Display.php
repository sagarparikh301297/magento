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



namespace Mirasvit\CatalogLabel\Model\ResourceModel\Label;

class Display extends \Magento\Framework\Model\ResourceModel\Db\AbstractDb
{
    /**
     * @var \Mirasvit\CatalogLabel\Model\System\Config\Source\ImageType
     */
    protected $systemConfigSourceImageType;

    /**
     * @var \Mirasvit\CatalogLabel\Model\Config
     */
    protected $config;

    /**
     * @var \Magento\Framework\Model\ResourceModel\Db\Context
     */
    protected $context;

    /**
     * @var string
     */
    protected $resourcePrefix;

    /**
     * @param \Mirasvit\CatalogLabel\Model\System\Config\Source\ImageType $systemConfigSourceImageType
     * @param \Mirasvit\CatalogLabel\Model\Config $config
     * @param \Magento\Framework\Model\ResourceModel\Db\Context $context
     * @param \Magento\Framework\ObjectManagerInterface $objectManager
     * @param string|null $resourcePrefix
     */
    public function __construct(
        \Mirasvit\CatalogLabel\Model\System\Config\Source\ImageType $systemConfigSourceImageType,
        \Mirasvit\CatalogLabel\Model\Config $config,
        \Magento\Framework\Model\ResourceModel\Db\Context $context,
        \Magento\Framework\ObjectManagerInterface $objectManager,
        $resourcePrefix = null
    ) {
        $this->systemConfigSourceImageType = $systemConfigSourceImageType;
        $this->config = $config;
        $this->context = $context;
        $this->resourcePrefix = $resourcePrefix;
        $this->objectManager = $objectManager;
        parent::__construct($context, $resourcePrefix);
    }

    /**
     * @return void
     */
    protected function _construct()
    {
        $this->_init('mst_cataloglabel_label_display', 'display_id');
    }

    /**
     * @param \Magento\Framework\Model\AbstractModel $object
     *
     * @return void
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function _beforeSave(\Magento\Framework\Model\AbstractModel $object)
    {
        $this->_saveImages($object);

        parent::_beforeSave($object);
    }

    /**
     * @param \Magento\Framework\Model\AbstractModel $object
     *
     * @return void
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    protected function _saveImages(\Magento\Framework\Model\AbstractModel $object)
    {
        $images = $this->systemConfigSourceImageType->toOptionArray();
        foreach ($images as $image) {
            $code = 'display_'.$image['value'].'_image';
            if (isset($_FILES[$code]['name']) && $_FILES[$code]['name']) {
                try {
                    $uploader = $this->objectManager->create(
                        'Magento\MediaStorage\Model\File\Uploader',
                        ['fileId' => $code]
                    );
                    /** @var \Magento\Framework\Image\Adapter\AdapterInterface $imageAdapter */
                    $imageAdapter = $this->objectManager->get('Magento\Framework\Image\AdapterFactory')->create();
                    $uploader->addValidateCallback('label_product_image', $imageAdapter, 'validateUploadFile');
                    $uploader->setAllowCreateFolders(true);
                    $uploader->setAllowRenameFiles(true);
                    $uploader->setFilesDispersion(true);

                    $result = $uploader->save($this->config->getBaseMediaPath());

                    $object->setData($image['value'].'_image', $result['file']);
                } catch (\Exception $e) {
                    throw new \Magento\Framework\Exception\LocalizedException($e);
                }
            }

            if (isset($_POST[$code]) && isset($_POST[$code]['delete']) && $_POST[$code]['delete'] == 1) {
                $object->setData($image['value'].'_image', '');
            }
        }
    }
}

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

class Attribute extends \Magento\Framework\Model\ResourceModel\Db\AbstractDb
{
    /**
     * @var \Mirasvit\CatalogLabel\Model\System\Config\Source\ImageType
     */
    protected $systemConfigSourceImageType;

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
     * @param \Magento\Framework\Model\ResourceModel\Db\Context           $context
     * @param \Mirasvit\CatalogLabel\Model\Config                         $config
     * @param \Magento\Framework\ObjectManagerInterface                   $objectManager
     * @param string|null                                                 $resourcePrefix
     */
    public function __construct(
        \Mirasvit\CatalogLabel\Model\System\Config\Source\ImageType $systemConfigSourceImageType,
        \Magento\Framework\Model\ResourceModel\Db\Context $context,
        \Mirasvit\CatalogLabel\Model\Config $config,
        \Magento\Framework\ObjectManagerInterface $objectManager,
        $resourcePrefix = null
    ) {
        $this->systemConfigSourceImageType = $systemConfigSourceImageType;
        $this->context = $context;
        $this->resourcePrefix = $resourcePrefix;
        $this->config = $config;
        $this->objectManager = $objectManager;
        parent::__construct($context, $resourcePrefix);
    }

    /**
     * @return void
     */
    protected function _construct()
    {
        $this->_init('mst_cataloglabel_label_attribute', 'attribute_id');
    }

    /**
     * @param \Magento\Framework\Model\AbstractModel $object
     * @return $this
     */
    protected function _beforeSave(\Magento\Framework\Model\AbstractModel $object)
    {
        if ($object->isObjectNew() && !$object->hasCreatedAt()) {
            $object->setCreatedAt((new \DateTime())->format(\Magento\Framework\Stdlib\DateTime::DATETIME_PHP_FORMAT));
        }

        $object->setUpdatedAt((new \DateTime())->format(\Magento\Framework\Stdlib\DateTime::DATETIME_PHP_FORMAT));

        $this->_saveDisplay($object);

        return parent::_beforeSave($object);
    }

    /**
     * @param \Magento\Framework\Model\AbstractModel $object
     * @return $this
     */
    protected function _saveDisplay(\Magento\Framework\Model\AbstractModel $object)
    {
        $displayData = $object->getData('display');

        $images = $this->systemConfigSourceImageType->toOptionArray();
        foreach ($images as $image) {
            $code = $image['value'];
            if (isset($displayData[$code.'_file'])) {
                $image = json_decode($displayData[$code.'_file']);
                if (isset($image[0])) {
                    if (!empty($image[0]->name)) {
                        $this->saveImg($image[0]->file);
                    }

                    $displayData[$code.'_image'] = $image[0]->file;
                } else {
                    $displayData[$code.'_image'] = false;
                }
            }
        }

        $display = $object->getDisplay();
        $display->addData($displayData);
        $display->save();

        $object->setDisplayId($display->getId());

        return $this;
    }

    /**
     * @param string $imgPath
     *
     * @return void
     */
    protected function saveImg($imgPath)
    {
        /** @var \Magento\Framework\Filesystem\Directory\Read $mediaDirectory */
        $config = $this->objectManager->get('Magento\Catalog\Model\Product\Media\Config');

        $mediaDirectory = $this->objectManager->get('Magento\Framework\Filesystem')
            ->getDirectoryRead(\Magento\Framework\App\Filesystem\DirectoryList::MEDIA);

        $path = $mediaDirectory->getAbsolutePath($config->getBaseTmpMediaPath()).$imgPath;

        $newPath = $this->config->getBaseMediaPath().$imgPath;

        $destinationFolder = implode('/', explode('/', $newPath, -1));
        @mkdir(
            $destinationFolder,
            \Magento\Framework\Filesystem\DriverInterface::WRITEABLE_DIRECTORY_MODE,
            true
        );

        @rename($path, $newPath);
    }
}

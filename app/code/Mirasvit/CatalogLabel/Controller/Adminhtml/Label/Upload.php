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



namespace Mirasvit\CatalogLabel\Controller\Adminhtml\Label;

use Magento\Framework\Controller\ResultFactory;

class Upload extends \Mirasvit\CatalogLabel\Controller\Adminhtml\Label
{
    /**
     * @return void
     */
    public function execute()
    {
        $this->resultFactory->create(ResultFactory::TYPE_PAGE);

        try {
            $uploader = $this->_objectManager->create(
                'Magento\MediaStorage\Model\File\Uploader',
                ['fileId' => 'image']
            );
            $uploader->setAllowedExtensions(['jpg', 'jpeg', 'gif', 'png']);
            /** @var \Magento\Framework\Image\Adapter\AdapterInterface $imageAdapter */
            $imageAdapter = $this->_objectManager->get('Magento\Framework\Image\AdapterFactory')->create();
            $uploader->addValidateCallback('label_product_image', $imageAdapter, 'validateUploadFile');
            $uploader->setAllowRenameFiles(true);
            $uploader->setFilesDispersion(true);

            /** @var \Magento\Framework\Filesystem\Directory\Read $mediaDirectory */
            $mediaDirectory = $this->_objectManager->get('Magento\Framework\Filesystem')
                ->getDirectoryRead(\Magento\Framework\App\Filesystem\DirectoryList::MEDIA);
            $config = $this->_objectManager->get('Magento\Catalog\Model\Product\Media\Config');
            $result = $uploader->save($mediaDirectory->getAbsolutePath($config->getBaseTmpMediaPath()));

            unset($result['tmp_name']);
            unset($result['path']);

            $result['url'] = $this->_objectManager->get('Magento\Catalog\Model\Product\Media\Config')
                ->getTmpMediaUrl($result['file']);

        } catch (\Exception $e) {
            $result = ['error' => $e->getMessage(), 'errorcode' => $e->getCode()];
        }

        $this->getResponse()->setBody($this->jsonEncoder->jsonEncode($result));
    }
}

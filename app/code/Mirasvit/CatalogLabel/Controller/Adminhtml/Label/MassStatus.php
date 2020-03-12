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

class MassStatus extends \Mirasvit\CatalogLabel\Controller\Adminhtml\Label
{
    /**
     * @return void
     */
    public function execute()
    {
        $this->resultFactory->create(ResultFactory::TYPE_PAGE);

        $ids = $this->getRequest()->getParam('label');
        if (!is_array($ids)) {
            $this->messageManager->addError(__('Please select label(s)'));
        } else {
            try {
                foreach ($ids as $itemId) {
                    $this->label
                        ->setIsMassStatus(true)
                        ->load($itemId)
                        ->setIsActive($this->getRequest()->getParam('is_active'))
                        ->save();
                }
                $this->messageManager->addSuccess(
                    __('Total of %1 record(s) were successfully updated', count($ids))
                );
            } catch (\Exception $e) {
                $this->messageManager->addError($e->getMessage());
            }
        }
        $this->_redirect('*/*/index');
    }
}

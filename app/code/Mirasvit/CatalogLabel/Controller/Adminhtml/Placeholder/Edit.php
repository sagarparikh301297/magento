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



namespace Mirasvit\CatalogLabel\Controller\Adminhtml\Placeholder;

use Magento\Framework\Controller\ResultFactory;

class Edit extends \Mirasvit\CatalogLabel\Controller\Adminhtml\Placeholder
{
    /**
     * @return \Magento\Backend\Model\View\Result\Page
     */
    public function execute()
    {
        /** @var \Magento\Backend\Model\View\Result\Page $resultPage */
        $resultPage = $this->resultFactory->create(ResultFactory::TYPE_PAGE);

        $model = $this->getModel();

        if ($model->getId()) {
            $resultPage->getConfig()->getTitle()->prepend($model->getName());

            $this->_initAction();
            $this->_addBreadcrumb(__('Manage Placeholders'), __('Manage Placeholders'), $this->getUrl('*/*/'));
            $this->_addBreadcrumb(__('Edit Placeholder'), __('Edit Placeholder'));

            $this->_addContent($resultPage->getLayout()
                ->createBlock('\Mirasvit\CatalogLabel\Block\Adminhtml\Placeholder\Edit'))
                ->_addLeft($resultPage->getLayout()
                ->createBlock('\Mirasvit\CatalogLabel\Block\Adminhtml\Placeholder\Edit\Tabs'));

            return $resultPage;
        } else {
            $this->messageManager->addError(__('The placeholder does not exist.'));
            $this->_redirect('*/*/');
        }
    }
}

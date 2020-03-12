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

class Edit extends \Mirasvit\CatalogLabel\Controller\Adminhtml\Label
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
            $this->_addBreadcrumb(__('Manage Labels'), __('Manage Labels'), $this->getUrl('*/*/'));
            $this->_addBreadcrumb(__('Edit Label'), __('Edit Label'));

            $this->_addContent(
                $resultPage->getLayout()->createBlock('\Mirasvit\CatalogLabel\Block\Adminhtml\Label\Edit')
            )->_addLeft(
                $resultPage->getLayout()->createBlock('\Mirasvit\CatalogLabel\Block\Adminhtml\Label\Edit\Tabs')
            );


            $resultPage->getLayout()->getBlock('head');

            return $resultPage;
        } else {
            $this->messageManager->addError(__('The label does not exist.'));
            $this->_redirect('*/*/');
        }
    }
}

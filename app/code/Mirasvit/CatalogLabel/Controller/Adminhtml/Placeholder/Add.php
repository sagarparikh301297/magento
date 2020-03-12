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

class Add extends \Mirasvit\CatalogLabel\Controller\Adminhtml\Placeholder
{
    /**
     * @return \Magento\Backend\Model\View\Result\Page
     */
    public function execute()
    {
        /** @var \Magento\Backend\Model\View\Result\Page $resultPage */
        $resultPage = $this->resultFactory->create(ResultFactory::TYPE_PAGE);

        $this->getModel();

        $resultPage->getConfig()->getTitle()->prepend(__('New Placeholder'));

        $this->_initAction();

        $this->_addBreadcrumb(__('Manage Placeholders'), __('Manage Placeholders'), $this->getUrl('*/*/'));
        $this->_addBreadcrumb(__('New Placeholder'), __('New Placeholder'));

        $this->_addContent(
                $resultPage->getLayout()->createBlock('\Mirasvit\CatalogLabel\Block\Adminhtml\Placeholder\Edit')
            )
            ->_addLeft(
                $resultPage->getLayout()->createBlock('\Mirasvit\CatalogLabel\Block\Adminhtml\Placeholder\Edit\Tabs')
            );

        return $resultPage;
    }
}

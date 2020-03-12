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

class Index extends \Mirasvit\CatalogLabel\Controller\Adminhtml\Label
{
    /**
     * @return \Magento\Backend\Model\View\Result\Page
     */
    public function execute()
    {
        /** @var \Magento\Backend\Model\View\Result\Page $resultPage */
        $resultPage = $this->resultFactory->create(ResultFactory::TYPE_PAGE);

        $cronStatus = $this->cronHelper->checkCronStatus(
            false,
            false,
            '<span style="color:red;"><b>Cron job is required for automatical label update.</b></span>'
        );
        if ($cronStatus[0] !== true) {
            $this->messageManager->addSuccess($cronStatus[1]);
        }

        $resultPage->getConfig()->getTitle()->prepend(__('Manage Labels'));
        $this->_initAction();
        $this->_addContent($resultPage->getLayout()->createBlock('\Mirasvit\CatalogLabel\Block\Adminhtml\Label'));

        return $resultPage;
    }
}

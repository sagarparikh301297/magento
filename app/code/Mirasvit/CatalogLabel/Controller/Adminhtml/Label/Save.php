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

class Save extends \Mirasvit\CatalogLabel\Controller\Adminhtml\Label
{
    /**
     * @return void
     */
    public function execute()
    {
        $this->resultFactory->create(ResultFactory::TYPE_PAGE);

        if ($data = $this->getRequest()->getParams()) {
            if (isset($data['rule'])) {
                $data['label']['rule'] = $data['rule'];
            }
            if (isset($data['attribute'])) {
                $data['label']['attribute'] = $data['attribute'];
            }

            $data = $data['label'];

            if (!empty($data['active_from'])) {
                $fromDateFrom = str_replace('/', '-', $data['active_from']);
                $formattedDateFrom = $this->date->gmtDate(null, strtotime($fromDateFrom));
                $data['active_from'] = $formattedDateFrom;
            }

            if (!empty($data['active_to'])) {
                $fromDateTo = str_replace('/', '-', $data['active_to']);
                $formattedDateTo = $this->date->gmtDate(null, strtotime($fromDateTo));
                $data['active_to'] = $formattedDateTo;
            }

            $model = $this->getModel();
            $model->addData($data);
            try {
                $model->save();

                $this->messageManager->addSuccess(__('Label was successfully saved'));
                $this->backendSession->setFormData(false);

                if ($this->getRequest()->getParam('back')) {
                    $this->_redirect('*/*/edit', ['id' => $model->getId()]);

                    return;
                }
                $this->_redirect('*/*/');

                return;
            } catch (\Exception $e) {
                $this->messageManager->addError($e->getMessage());
                $this->backendSession->setFormData($data);
                $this->_redirect('*/*/edit', ['id' => $this->getRequest()->getParam('id')]);

                return;
            }
        }
        $this->messageManager->addError(__('Unable to find label to save'));
        $this->_redirect('*/*/');
    }
}

<?php


namespace SimpleMagento\ProductTabs\Controller\Adminhtml\Index;


use Magento\Backend\App\Action;
use Magento\Framework\App\ResponseInterface;

class Edit extends Action
{

    /**
     * Execute action based on request and return result
     *
     * Note: Request will be added as operation argument in future
     *
     * @return \Magento\Framework\Controller\ResultInterface|ResponseInterface
     * @throws \Magento\Framework\Exception\NotFoundException
     */
    public function execute()
    {
        $check = "working";
        return $check;
    }
}
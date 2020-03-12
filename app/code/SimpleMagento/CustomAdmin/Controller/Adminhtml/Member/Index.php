<?php


namespace SimpleMagento\CustomAdmin\Controller\Adminhtml\Member;


use Magento\Backend\App\Action;
use Magento\Framework\View\Result\PageFactory;


class Index extends Action
{


    private $pageFactory;

    public function __construct(
        PageFactory $pageFactory,
        Action\Context $context
    )
    {
        parent::__construct($context);
        $this->pageFactory = $pageFactory;

    }

    public function execute()
    {
        return $this->pageFactory->create();

    }
}
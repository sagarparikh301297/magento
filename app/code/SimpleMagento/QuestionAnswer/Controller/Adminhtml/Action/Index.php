<?php


namespace SimpleMagento\QuestionAnswer\Controller\Adminhtml\Action;


use Magento\Backend\App\Action;
use Magento\Framework\App\ResponseInterface;
use Magento\Framework\View\Result\PageFactory;

class Index extends Action
{
    /**
     * @var PageFactory
     */
    protected $factory;

    public function __construct(Action\Context $context,
                                PageFactory $factory)
    {
        parent::__construct($context);
        $this->factory = $factory;
    }

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
        return $this->factory->create();
    }
}
<?php
namespace SimpleMagento\FirstModule\Controller\Index;

use Magento\Framework\App\Action\Context;
use Magento\Framework\View\Result\PageFactory;
use \Magento\Framework\App\Action\Action;

use SimpleMagento\FirstModule\NotMagento\PencilInterface;
use SimpleMagento\FirstModule\NotMagento\YellowPencil;

class Index extends Action
{
    protected $resultPageFactory;
    protected $pencilInterface;

    public function __construct(
       \Magento\Framework\App\Action\Context $context,
       PageFactory $resultPageFactory,
       PencilInterface $vivek
   )
   {
       $this->resultPageFactory = $resultPageFactory;
       $this->pencilInterface = $vivek;
       parent::__construct($context);
   }
   public function execute()
   {
        echo $this->pencilInterface->getPencilType();exit;
        $resultPage = $this->resultPageFactory->create();
        $resultPage->getConfig()->getTitle()->set(__('Home Page'));
        return $resultPage;
   }
}
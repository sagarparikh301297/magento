<?php


namespace SimpleMagento\Database\Controller\Page;

use Magento\Framework\App\Action\Action;
use Magento\Framework\App\Action\Context;
use Magento\Framework\App\ResponseInterface;
use SimpleMagento\Database\Model\AffiliateMemberFactory;
use SimpleMagento\Database\Model\ResourceModel\AffiliateMember;

class Index extends Action
{
    protected $affiliateMemberFactory;
    public function __construct(Context $context, AffiliateMemberFactory $affiliateMemberFactory)
    {
        $this->affiliateMemberFactory = $affiliateMemberFactory;
        parent::__construct($context);
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
        $affiliateMember = $this->affiliateMemberFactory->create();
//        $member = $affiliateMember->load(1);
//        $member = $affiliateMember->load(4);
//        $member->delete();
        $collection = $affiliateMember->getCollection()
        ->addFieldToSelect('name')->addFieldToFilter('name',array('eq'=>'Bob'));
        foreach ($collection as $item) {
            print_r($item->getData());
            echo '</br>';
        }


//        $member->setAddress('new address');
//        $member->save();
        //var_dump($member->getData());
//        $affiliateMember->addData(['name'=>'Pizza','address'=>'a new address', 'status'=>true,'phone_number'=>'7359944562']);
//        $affiliateMember->save();

    }
}
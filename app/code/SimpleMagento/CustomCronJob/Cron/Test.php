<?php


namespace SimpleMagento\CustomCronJob\Cron;



use \SimpleMagento\Database\Model\AffiliateMemberFactory;

class Test
{
    /**
     * @var AffiliateMember
     */
    private $affiliateMember;

    public function __construct(AffiliateMemberFactory $affiliateMember)
    {
        $this->affiliateMember = $affiliateMember;
    }

    public function execute()
    {
        $affiliateSave = $this->affiliateMember->create();
        $affiliateSave->addData(['name'=>'cron']);
        $affiliateSave->save();
//        $writer = new \Zend\Log\Writer\Stream(BP . '/var/log/cron.log');
//        $logger = new \Zend\Log\Logger();
//        $logger->addWriter($writer);
//        $logger->info(__METHOD__);
//
//        return $this;




    }
}
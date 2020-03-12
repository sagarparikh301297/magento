<?php


namespace SimpleMagento\CustomAdmin\Block\Adminhtml\Member\Edit;



use Magento\Framework\Registry;
use SimpleMagento\Database\Model\AffiliateMember;

class Generic
{

    /**
     * @var Registry
     */
    private $registry;

    protected $urlBuilder;


    public function __construct(Registry $registry, \Magento\Backend\Block\Widget\Context $context)
    {
        $this->registry = $registry;
        $this->urlBuilder = $context->getUrlBuilder();

    }

    public function getId(){
        $member = $this->registry->registry('member');
        return $member ? $member->getId() : null;

    }
    public function getUrl($route='', $param=[]){
        return $this->urlBuilder->getUrl($route,$param);
    }

}
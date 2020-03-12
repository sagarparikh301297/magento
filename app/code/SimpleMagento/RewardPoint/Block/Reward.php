<?php


namespace SimpleMagento\RewardPoint\Block;

use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Framework\View\Element\Template;
use Magento\Checkout\Model\Cart as CustomerCart;

class Reward extends Template
{
    /**
     * @var ScopeConfigInterface
     */
    protected $config;
    /**
     * @var CustomerCart
     */
    protected $cart;
    /**
     * @var \Magento\Customer\Model\Session
     */
    protected $customerSession;

    public function __construct(Template\Context $context,
                                ScopeConfigInterface $config,
                                CustomerCart $cart,
                                \Magento\Customer\Model\Session $customerSession,
                                array $data = [])
    {
        parent::__construct($context, $data);
        $this->config = $config;
        $this->cart = $cart;
        $this->customerSession = $customerSession;
    }

    public function getRewardConfiguration(){
        $getPointType = $this->_scopeConfig->getValue('Reward_Section/Reward_group/Reward_field', \Magento\Store\Model\ScopeInterface::SCOPE_STORE);
        $getPoint = $this->_scopeConfig->getValue('Reward_Section/Reward_group/Reward_Second_field', \Magento\Store\Model\ScopeInterface::SCOPE_STORE);
        $getCustomerGroup = $this->_scopeConfig->getValue('Reward_Section/Reward_group/Reward_Third_field', \Magento\Store\Model\ScopeInterface::SCOPE_STORE);
        $getMinAmount = $this->_scopeConfig->getValue('Reward_Section/Reward_group/Reward_Fourth_field', \Magento\Store\Model\ScopeInterface::SCOPE_STORE);

        $subTotal = $this->cart->getQuote()->getSubtotal();
//        $shippingAmount = $this->cart->getQuote()->getShippingAddress()->getShippingAmount();
        $selectedCustomerGroups = (explode(",",$getCustomerGroup));
        if($this->customerSession->isLoggedIn()) {
            $getCurrentCustomerGroup = $this->customerSession->getCustomer()->getGroupId();
            if(in_array($getCurrentCustomerGroup, $selectedCustomerGroups)){
                if($subTotal>=$getMinAmount){
                    if($getPointType == 1){
                        $getRewardPoint = ($subTotal * $getPoint)/100;
                        $print = "You Will Earn:".$getRewardPoint;
                        return $print ;
                    }else{
                        $getRewardPoint = $getPoint;
                        $print = "You Will Earn:".$getRewardPoint;
                        return $print ;
                    }
                }else{
                    $needMoney = $getMinAmount - $subTotal;
                    $getRewardPoint = "Add $".$needMoney."to get eligible for Reward Point";
                    return $getRewardPoint;
                }
            }
        }elseif(in_array('0', $selectedCustomerGroups)){
            if($subTotal>=$getMinAmount){
                if($getPointType == 1){
                    $getRewardPoint = ($subTotal * $getPoint)/100;
                    $print = "You Will Earn:".$getRewardPoint;
                    return $print ;
                }else{
                    $getRewardPoint = $getPoint;
                    $print = "You Will Earn:".$getRewardPoint;
                    return $print ;
                }
            }else{
                $needMoney = $getMinAmount - $subTotal;
                $getRewardPoint = "Add $".$needMoney."'s worth shopping & get eligible for Reward Point";
                return $getRewardPoint;
            }
        }
    }
}
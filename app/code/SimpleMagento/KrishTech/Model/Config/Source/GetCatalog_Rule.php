<?php


namespace SimpleMagento\KrishTech\Model\Config\Source;


use Magento\Framework\Option\ArrayInterface;
use Magento\CatalogRule\Model\CatalogRuleRepository;

class GetCatalog_Rule implements ArrayInterface
{
    /**
     * @var CatalogRuleRepository
     */
    protected $catalogRuleRepository;

    public function __construct(CatalogRuleRepository $catalogRuleRepository)
    {
        $this->catalogRuleRepository = $catalogRuleRepository;
    }

    /**
     * Return array of options as value-label pairs
     *
     * @return array Format: array(array('value' => '<value>', 'label' => '<label>'), ...)
     */
    public function toOptionArray()
    {
        $catalogRule = \Magento\Framework\App\ObjectManager::getInstance()->create(
            '\Magento\CatalogRule\Model\RuleFactory'
        );
        
        $catalogRuleCollection = $catalogRule->create()->getCollection();
        $catalogRuleCollection->addIsActiveFilter(1);//filter for active rules only
        foreach ($catalogRuleCollection as $catalogRule) {
             $catalogRuleName[] = ['value'=> $catalogRule->getId(),'label' =>$catalogRule->getName()];
        }
            return $catalogRuleName;
    }
}


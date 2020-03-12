<?php


namespace SimpleMagento\RewardPoint\Model\Config\Source;

use Magento\CatalogRule\Model\CatalogRuleRepository;
use Magento\Framework\Option\ArrayInterface;

class PointType implements ArrayInterface
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
        return [['value' => 1, 'label' => __('Percentage')], ['value' => 0, 'label' => __('Fixed')]];
    }
}
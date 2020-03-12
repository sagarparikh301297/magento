<?php


namespace Ktpl\DeliveryDays\Setup;


use Magento\Catalog\Model\Product;
use Magento\Eav\Model\Entity\Attribute\ScopedAttributeInterface;
use Magento\Eav\Setup\EavSetupFactory;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Setup\InstallDataInterface;
use Magento\Framework\Setup\ModuleContextInterface;
use Magento\Framework\Setup\ModuleDataSetupInterface;
use Ktpl\DeliveryDays\Model\Config\DayOptions;
use Zend_Validate_Exception;


class InstallData implements InstallDataInterface
{

    private $eavSetupFactory;
    public function __construct(EavSetupFactory $eavSetupFactory)
    {
        $this->eavSetupFactory = $eavSetupFactory;
    }

    /**
     * Installs data for a module
     *
     * @param ModuleDataSetupInterface $setup
     * @param ModuleContextInterface $context
     * @return void
     * @throws LocalizedException
     * @throws Zend_Validate_Exception
     */
    public function install(ModuleDataSetupInterface $setup, ModuleContextInterface $context)
    {
        $setup->startSetup();

        $eavSetup = $this->eavSetupFactory->create(['setup'=>$setup]);
        $eavSetup->addAttribute(
            Product::ENTITY,
            'delivery_days',
            [
                'type' => 'text',
                'global' => ScopedAttributeInterface::SCOPE_GLOBAL,
                'visible' => true,
                'required' => false,
                'searchable' => false,
                'user_defined' => true,
                'used_in_product_listing' => true,
                'label' => 'Delivery Available (in days)',
                'input' => 'select',
                //'source' => DayOptions::class,
                'visible_on_front' => true
            ]
        );

        $setup->endSetup();

    }
}
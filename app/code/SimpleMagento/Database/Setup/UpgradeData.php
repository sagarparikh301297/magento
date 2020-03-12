<?php


namespace SimpleMagento\Database\Setup;


use Magento\Framework\Setup\ModuleContextInterface;
use Magento\Framework\Setup\ModuleDataSetupInterface;
use Magento\Framework\Setup\UpgradeDataInterface;

class UpgradeData implements UpgradeDataInterface
{

    /**
     * Upgrades data for a module
     *
     * @param ModuleDataSetupInterface $setup
     * @param ModuleContextInterface $context
     * @return void
     */
    public function upgrade(ModuleDataSetupInterface $setup, ModuleContextInterface $context)
    {
        $setup->startSetup();
        if(version_compare($context->getVersion(),'0.0.4','<')) {
            $setup->getConnection()->insert(
                $setup->getTable('affiliate_member'),
                ['name'=>'Yash', 'address'=>'No 15, Ahmedavad', 'status'=> true, 'phone_number'=>'7359944562']
            );
        }
        $setup->endSetup();
    }
}
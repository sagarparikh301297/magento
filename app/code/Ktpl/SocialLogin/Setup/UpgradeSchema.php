<?php
/**
 * @category  Ktpl
 * @package   Ktpl_SocialLogin
 */
namespace Ktpl\SocialLogin\Setup;

use Magento\Framework\DB\Ddl\Table;
use Magento\Framework\Setup\ModuleContextInterface;
use Magento\Framework\Setup\SchemaSetupInterface;
use Magento\Framework\Setup\UpgradeSchemaInterface;

/**
 * Class UpgradeSchema
 *
 * @package Ktpl\SocialLogin\Setup
 */
class UpgradeSchema implements UpgradeSchemaInterface
{
    /**
     * @param SchemaSetupInterface $setup
     * @param ModuleContextInterface $context
     */
    public function upgrade(SchemaSetupInterface $setup, ModuleContextInterface $context)
    {
        $installer = $setup;
        $installer->startSetup();
        $connection = $installer->getConnection();
        $tableName  = $setup->getTable('ktpl_social_customer');
        if (version_compare($context->getVersion(), '1.2.0', '<')) {
            if ($connection->tableColumnExists($tableName, 'social_created_at') === false) {
                $connection->addColumn(
                    $tableName,
                    'social_created_at',
                    [
                        'type'    => Table::TYPE_TIMESTAMP,
                        'comment' => 'Social Created At',
                    ]
                );
            }
            if ($connection->tableColumnExists($tableName, 'user_id') === false) {
                    $connection->addColumn(
                        $tableName,
                        'user_id',
                        [
                            'type'     => Table::TYPE_INTEGER,
                            'nullable' => true,
                            'unsigned' => true,
                            'comment'  => 'User Id',
                        ]
                    );
                    $connection->addForeignKey(
                        $installer->getFkName('ktpl_social_customer', 'user_id', 'admin_user', 'user_id'),
                        $tableName,
                        'user_id',
                        $installer->getTable('admin_user'),
                        'user_id',
                        Table::ACTION_CASCADE
                    );
                }

                if ($connection->tableColumnExists($tableName, 'status') === false) {
                    $connection->addColumn(
                        $tableName,
                        'status',
                        [
                            'type'     => Table::TYPE_TEXT,
                            'nullable' => true,
                            'length'   => 255,
                            'comment'  => 'Status',
                        ]
                    );
                }

               
        }

        $installer->endSetup();
    }
}

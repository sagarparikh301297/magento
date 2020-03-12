<?php
/**
 * Mirasvit
 *
 * This source file is subject to the Mirasvit Software License, which is available at https://mirasvit.com/license/.
 * Do not edit or add to this file if you wish to upgrade the to newer versions in the future.
 * If you wish to customize this module for your needs.
 * Please refer to http://www.magentocommerce.com for more information.
 *
 * @category  Mirasvit
 * @package   mirasvit/module-cataloglabel
 * @version   1.1.14
 * @copyright Copyright (C) 2019 Mirasvit (https://mirasvit.com/)
 */



namespace Mirasvit\CatalogLabel\Setup;

use Magento\Framework\Setup\InstallSchemaInterface;
use Magento\Framework\Setup\ModuleContextInterface;
use Magento\Framework\Setup\SchemaSetupInterface;

/**
 * @codeCoverageIgnore
 */
class InstallSchema implements InstallSchemaInterface
{
    /**
     * {@inheritdoc}
     *
     * @SuppressWarnings(PHPMD.ExcessiveMethodLength)
     */
    public function install(SchemaSetupInterface $setup, ModuleContextInterface $context)
    {
        $installer = $setup;

        $installer->startSetup();
        $table = $installer->getConnection()->newTable(
            $installer->getTable('mst_cataloglabel_label')
        )
        ->addColumn(
            'label_id',
            \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
            null,
            ['unsigned' => false, 'nullable' => false, 'identity' => true, 'primary' => true],
            'Label Id')
        ->addColumn(
            'type',
            \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
            255,
            ['unsigned' => false, 'nullable' => false],
            'Type')
        ->addColumn(
            'attribute_id',
            \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
            null,
            ['unsigned' => false, 'nullable' => true],
            'Attribute Id')
        ->addColumn(
            'placeholder_id',
            \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
            null,
            ['unsigned' => false, 'nullable' => false],
            'Placeholder Id')
        ->addColumn(
            'name',
            \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
            255,
            ['unsigned' => false, 'nullable' => true],
            'Name')
        ->addColumn(
            'active_from',
            \Magento\Framework\DB\Ddl\Table::TYPE_DATETIME,
            null,
            ['unsigned' => false, 'nullable' => true],
            'Active From')
        ->addColumn(
            'active_to',
            \Magento\Framework\DB\Ddl\Table::TYPE_DATETIME,
            null,
            ['unsigned' => false, 'nullable' => true],
            'Active To')
        ->addColumn(
            'sort_order',
            \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
            null,
            ['unsigned' => false, 'nullable' => false, 'default' => 0],
            'Sort Order')
        ->addColumn(
            'is_active',
            \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
            null,
            ['unsigned' => false, 'nullable' => true, 'default' => 1],
            'Is Active')
        ->addColumn(
            'created_at',
            \Magento\Framework\DB\Ddl\Table::TYPE_DATETIME,
            null,
            ['unsigned' => false, 'nullable' => false],
            'Created At')
        ->addColumn(
            'updated_at',
            \Magento\Framework\DB\Ddl\Table::TYPE_DATETIME,
            null,
            ['unsigned' => false, 'nullable' => false],
            'Updated At')
            ->addIndex(
                $installer->getIdxName('mst_cataloglabel_label', ['placeholder_id']),
                ['placeholder_id']
            )
            ->addForeignKey(
            $installer->getFkName(
            'mst_cataloglabel_label',
            'placeholder_id',
            'mst_cataloglabel_placeholder',
            'placeholder_id'
            ),
            'placeholder_id',
            $installer->getTable('mst_cataloglabel_placeholder'),
            'placeholder_id',
            \Magento\Framework\DB\Ddl\Table::ACTION_CASCADE
            );
        $installer->getConnection()->createTable($table);

        $table = $installer->getConnection()->newTable(
            $installer->getTable('mst_cataloglabel_label_attribute')
        )
        ->addColumn(
            'attribute_id',
            \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
            null,
            ['unsigned' => false, 'nullable' => false, 'identity' => true, 'primary' => true],
            'Attribute Id')
        ->addColumn(
            'label_id',
            \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
            null,
            ['unsigned' => false, 'nullable' => false],
            'Label Id')
        ->addColumn(
            'display_id',
            \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
            null,
            ['unsigned' => false, 'nullable' => false],
            'Display Id')
        ->addColumn(
            'option_id',
            \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
            null,
            ['unsigned' => false, 'nullable' => true],
            'Option Id')
        ->addColumn(
            'option_text',
            \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
            '64K',
            ['unsigned' => false, 'nullable' => true],
            'Option Text')
            ->addIndex(
                $installer->getIdxName('mst_cataloglabel_label_attribute', ['label_id']),
                ['label_id']
            )
            ->addForeignKey(
            $installer->getFkName(
            'mst_cataloglabel_label_attribute',
            'label_id',
            'mst_cataloglabel_label',
            'label_id'
            ),
            'label_id',
            $installer->getTable('mst_cataloglabel_label'),
            'label_id',
            \Magento\Framework\DB\Ddl\Table::ACTION_CASCADE
            );
        $installer->getConnection()->createTable($table);

        $table = $installer->getConnection()->newTable(
            $installer->getTable('mst_cataloglabel_label_customer_group')
        )
        ->addColumn(
            'id',
            \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
            null,
            ['unsigned' => false, 'nullable' => false, 'identity' => true, 'primary' => true],
            'Id')
        ->addColumn(
            'label_id',
            \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
            null,
            ['unsigned' => false, 'nullable' => false],
            'Label Id')
        ->addColumn(
            'customer_group_id',
            \Magento\Framework\DB\Ddl\Table::TYPE_SMALLINT,
            5,
            ['unsigned' => true, 'nullable' => false],
            'Customer Group Id')
            ->addIndex(
                $installer->getIdxName('mst_cataloglabel_label_customer_group', ['customer_group_id']),
                ['customer_group_id']
            )
            ->addIndex(
                $installer->getIdxName('mst_cataloglabel_label_customer_group', ['label_id']),
                ['label_id']
            )
            ->addForeignKey(
            $installer->getFkName(
            'mst_cataloglabel_label_customer_group',
            'label_id',
            'mst_cataloglabel_label',
            'label_id'
            ),
            'label_id',
            $installer->getTable('mst_cataloglabel_label'),
            'label_id',
            \Magento\Framework\DB\Ddl\Table::ACTION_CASCADE
            );
        $installer->getConnection()->createTable($table);

        $table = $installer->getConnection()->newTable(
            $installer->getTable('mst_cataloglabel_label_display')
        )
        ->addColumn(
            'display_id',
            \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
            null,
            ['unsigned' => true, 'nullable' => false, 'identity' => true, 'primary' => true],
            'Display Id')
        ->addColumn(
            'list_image',
            \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
            255,
            ['unsigned' => false, 'nullable' => true],
            'List Image')
        ->addColumn(
            'list_title',
            \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
            255,
            ['unsigned' => false, 'nullable' => true],
            'List Title')
        ->addColumn(
            'list_description',
            \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
            '64K',
            ['unsigned' => false, 'nullable' => true],
            'List Description')
        ->addColumn(
            'list_position',
            \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
            255,
            ['unsigned' => false, 'nullable' => true],
            'List Position')
        ->addColumn(
            'view_image',
            \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
            255,
            ['unsigned' => false, 'nullable' => true],
            'View Image')
        ->addColumn(
            'view_title',
            \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
            255,
            ['unsigned' => false, 'nullable' => true],
            'View Title')
        ->addColumn(
            'view_description',
            \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
            '64K',
            ['unsigned' => false, 'nullable' => true],
            'View Description')
        ->addColumn(
            'view_position',
            \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
            255,
            ['unsigned' => false, 'nullable' => true],
            'View Position')
        ->addColumn(
            'list_url',
            \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
            '64K',
            ['unsigned' => false, 'nullable' => true],
            'List Url')
        ->addColumn(
            'view_url',
            \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
            '64K',
            ['unsigned' => false, 'nullable' => true],
            'View Url');
        $installer->getConnection()->createTable($table);

        $table = $installer->getConnection()->newTable(
            $installer->getTable('mst_cataloglabel_label_rule')
        )
        ->addColumn(
            'rule_id',
            \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
            null,
            ['unsigned' => true, 'nullable' => false, 'identity' => true, 'primary' => true],
            'Rule Id')
        ->addColumn(
            'label_id',
            \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
            null,
            ['unsigned' => false, 'nullable' => false],
            'Label Id')
        ->addColumn(
            'display_id',
            \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
            null,
            ['unsigned' => false, 'nullable' => false],
            'Display Id')
        ->addColumn(
            'conditions_serialized',
            \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
            '64K',
            ['unsigned' => false, 'nullable' => false],
            'Conditions Serialized')
        ->addColumn(
            'actions_serialized',
            \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
            '64K',
            ['unsigned' => false, 'nullable' => false],
            'Actions Serialized')
        ->addColumn(
            'stop_rules_processing',
            \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
            null,
            ['unsigned' => false, 'nullable' => false, 'default' => 1],
            'Stop Rules Processing')
            ->addIndex(
                $installer->getIdxName('mst_cataloglabel_label_rule', ['label_id']),
                ['label_id']
            )
            ->addForeignKey(
            $installer->getFkName(
            'mst_cataloglabel_label_rule',
            'label_id',
            'mst_cataloglabel_label',
            'label_id'
            ),
            'label_id',
            $installer->getTable('mst_cataloglabel_label'),
            'label_id',
            \Magento\Framework\DB\Ddl\Table::ACTION_CASCADE
            );
        $installer->getConnection()->createTable($table);

        $table = $installer->getConnection()->newTable(
            $installer->getTable('mst_cataloglabel_label_rule_product')
        )
        ->addColumn(
            'id',
            \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
            null,
            ['unsigned' => true, 'nullable' => false, 'identity' => true, 'primary' => true],
            'Id')
        ->addColumn(
            'rule_id',
            \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
            null,
            ['unsigned' => true, 'nullable' => false, 'default' => 0],
            'Rule Id')
        ->addColumn(
            'product_id',
            \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
            null,
            ['unsigned' => true, 'nullable' => false, 'default' => 0],
            'Product Id');
        $installer->getConnection()->createTable($table);

        $table = $installer->getConnection()->newTable(
            $installer->getTable('mst_cataloglabel_label_store')
        )
        ->addColumn(
            'id',
            \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
            null,
            ['unsigned' => false, 'nullable' => false, 'identity' => true, 'primary' => true],
            'Id')
        ->addColumn(
            'label_id',
            \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
            null,
            ['unsigned' => false, 'nullable' => false],
            'Label Id')
        ->addColumn(
            'store_id',
            \Magento\Framework\DB\Ddl\Table::TYPE_SMALLINT,
            5,
            ['unsigned' => true, 'nullable' => false],
            'Store Id')
            ->addIndex(
                $installer->getIdxName('mst_cataloglabel_label_store', ['store_id']),
                ['store_id']
            )
            ->addIndex(
                $installer->getIdxName('mst_cataloglabel_label_store', ['label_id']),
                ['label_id']
            )
            ->addForeignKey(
            $installer->getFkName(
            'mst_cataloglabel_label_store',
            'store_id',
            'store',
            'store_id'
            ),
            'store_id',
            $installer->getTable('store'),
            'store_id',
            \Magento\Framework\DB\Ddl\Table::ACTION_CASCADE
            )
            ->addForeignKey(
            $installer->getFkName(
            'mst_cataloglabel_label_store',
            'label_id',
            'mst_cataloglabel_label',
            'label_id'
            ),
            'label_id',
            $installer->getTable('mst_cataloglabel_label'),
            'label_id',
            \Magento\Framework\DB\Ddl\Table::ACTION_CASCADE
            );
        $installer->getConnection()->createTable($table);

        $table = $installer->getConnection()->newTable(
            $installer->getTable('mst_cataloglabel_placeholder')
        )
        ->addColumn(
            'placeholder_id',
            \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
            null,
            ['unsigned' => false, 'nullable' => false, 'identity' => true, 'primary' => true],
            'Placeholder Id')
        ->addColumn(
            'name',
            \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
            255,
            ['unsigned' => false, 'nullable' => true],
            'Name')
        ->addColumn(
            'code',
            \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
            255,
            ['unsigned' => false, 'nullable' => true],
            'Code')
        ->addColumn(
            'is_active',
            \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
            null,
            ['unsigned' => false, 'nullable' => true, 'default' => 1],
            'Is Active')
        ->addColumn(
            'is_positioned',
            \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
            null,
            ['unsigned' => false, 'nullable' => true, 'default' => 0],
            'Is Positioned')
        ->addColumn(
            'output_type',
            \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
            255,
            ['unsigned' => false, 'nullable' => true],
            'Output Type')
        ->addColumn(
            'image_type',
            \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
            255,
            ['unsigned' => false, 'nullable' => true],
            'Image Type')
        ->addColumn(
            'is_auto_for_list',
            \Magento\Framework\DB\Ddl\Table::TYPE_SMALLINT,
            null,
            ['unsigned' => false, 'nullable' => false, 'default' => 1],
            'Is Auto For List')
        ->addColumn(
            'is_auto_for_view',
            \Magento\Framework\DB\Ddl\Table::TYPE_SMALLINT,
            null,
            ['unsigned' => false, 'nullable' => false, 'default' => 1],
            'Is Auto For View')
        ->addColumn(
            'created_at',
            \Magento\Framework\DB\Ddl\Table::TYPE_DATETIME,
            null,
            ['unsigned' => false, 'nullable' => false],
            'Created At')
        ->addColumn(
            'updated_at',
            \Magento\Framework\DB\Ddl\Table::TYPE_DATETIME,
            null,
            ['unsigned' => false, 'nullable' => false],
            'Updated At');
        $installer->getConnection()->createTable($table);
    }
}

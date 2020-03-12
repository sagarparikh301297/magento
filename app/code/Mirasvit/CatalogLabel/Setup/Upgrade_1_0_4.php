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

use Magento\Framework\Setup\ModuleContextInterface;
use Magento\Framework\Setup\SchemaSetupInterface;
use Mirasvit\CatalogLabel\Api\Data\NewProductsInterface;
use Magento\Framework\DB\Ddl\Table;

class Upgrade_1_0_4
{
    /**
     * {@inheritdoc}
     *
     * @SuppressWarnings(PHPMD.UnusedFormalParameter)
     * @SuppressWarnings(PHPMD.ExcessiveMethodLength)
     */
    public static function upgrade(SchemaSetupInterface $installer, ModuleContextInterface $context)
    {
        $installer->getConnection()->dropTable($installer->getTable('mst_cataloglabel_label_rule_product'));

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
                'Product Id')
            ->addIndex(
                $installer->getIdxName('mst_cataloglabel_label_rule_product', ['rule_id']),
                ['rule_id'])
            ->addIndex(
                $installer->getIdxName('mst_cataloglabel_label_rule_product', ['product_id']),
                ['product_id']
            );

        $installer->getConnection()->createTable($table);
    }
}
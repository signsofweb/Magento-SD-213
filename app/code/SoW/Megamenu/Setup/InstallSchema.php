<?php

namespace SoW\Megamenu\Setup;

use Magento\Framework\Setup\InstallSchemaInterface;
use Magento\Framework\Setup\ModuleContextInterface;
use Magento\Framework\Setup\SchemaSetupInterface;
use Magento\Framework\DB\Ddl\Table;

class InstallSchema implements InstallSchemaInterface
{
    public function install(SchemaSetupInterface $setup, ModuleContextInterface $context)
    {
        $installer = $setup;

        $installer->startSetup();

        /*
        * Drop tables if exists
        */
        $installer->getConnection()->dropTable($installer->getTable('sow_megamenu'));

        if (!$installer->tableExists('sow_megamenu')) {
            $table = $installer->getConnection()
                ->newTable($installer->getTable('sow_megamenu'))
                ->addColumn('item_id', Table::TYPE_SMALLINT, null, ['identity' => true, 'nullable' => false, 'primary' => true], 'Item ID')
                ->addColumn('item_name', Table::TYPE_TEXT, 255, ['nullable' => true], 'Item name')
                ->addColumn('parent_id', Table::TYPE_SMALLINT, null, ['nullable' => false,'default' => 0], 'Parent id')
                ->addColumn('store_ids', Table::TYPE_TEXT, 255, ['unsigned' => true, 'nullable' => false, 'default' => null], 'Store IDs')
                ->addColumn('link_type', Table::TYPE_TEXT, 255, ['nullable' => false, 'default' => null], 'Link Type')
                ->addColumn('category_link', Table::TYPE_TEXT, 255, ['nullable' => false, 'default' => null], 'Category Link')
                ->addColumn('custom_link', Table::TYPE_TEXT, 255, ['nullable' => false, 'default' => null], 'Custom Link')
                ->addColumn('link_target', Table::TYPE_TEXT, 255, ['nullable' => false, 'default' => null], 'Link Target')
                ->addColumn('display', Table::TYPE_TEXT, 255, ['nullable' => false, 'default' => null], 'Display')
                ->addColumn('icon_classes', Table::TYPE_TEXT, 255, ['nullable' => false, 'default' => null], 'Icon Classes')
                ->addColumn('item_label', Table::TYPE_TEXT, 255, ['nullable' => false, 'default' => null], 'Item Label')
                ->addColumn('item_order', Table::TYPE_TEXT, 255, ['nullable' => false, 'default' => null], 'Item Order')
                ->addColumn('dropdown_enable', Table::TYPE_SMALLINT, 1, ['nullable' => false,'default' => 0], 'Dropdown Enable')
                ->addColumn('dropdown_width', Table::TYPE_TEXT, 255, ['nullable' => false, 'default' => null], 'Dropdown Width')
                ->addColumn('dropdown_alignment', Table::TYPE_TEXT, 255, ['nullable' => false, 'default' => null], 'Dropdown Alignment')
                ->addColumn('main_enable', Table::TYPE_SMALLINT, 1, ['nullable' => false, 'default' => 0], 'Main Enable')
                ->addColumn('main_width', Table::TYPE_TEXT, 255, ['nullable' => false, 'default' => null], 'Main Width')
                ->addColumn('main_column', Table::TYPE_TEXT, 255, ['nullable' => false, 'default' => null], 'Main Column')
                ->addColumn('custom_html_enable', Table::TYPE_SMALLINT, 1, ['nullable' => false, 'default' => 0], 'Custom HTML Enable')
                ->addColumn('custom_html', \Magento\Framework\DB\Ddl\Table::TYPE_TEXT, '2M', [], 'Item Html')
                ->addColumn('html_position', Table::TYPE_INTEGER, 10, ['nullable' => true], 'html_position')
                ->addColumn('html_width', Table::TYPE_TEXT, 255, ['nullable' => false, 'default' => null], 'Html Width')
                ->addColumn('is_active', Table::TYPE_SMALLINT, 1, ['nullable' => false,'default' => 1], 'Is Active')
                ->setComment('Megamenu Table');
            $installer->getConnection()->createTable($table);
        }
        $installer->endSetup();

    }
}

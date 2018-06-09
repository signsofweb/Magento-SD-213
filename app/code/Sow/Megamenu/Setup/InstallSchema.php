<?php

namespace Sow\Megamenu\Setup;

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
                ->addColumn('item_name', Table::TYPE_TEXT, 255, ['nullable' => false], 'Item name')
                ->setComment('Megamenu Table');
            $installer->getConnection()->createTable($table);
        }
        $installer->endSetup();

    }
}

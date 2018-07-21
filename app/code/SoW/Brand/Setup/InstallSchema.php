<?php

namespace SoW\Brand\Setup;

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
        $installer->getConnection()->dropTable($installer->getTable('sow_manufacturer'));

        if (!$installer->tableExists('sow_manufacturer')) {
            $table = $installer->getConnection()
                ->newTable($installer->getTable('sow_manufacturer'))
                ->addColumn('manufacturer_id', Table::TYPE_SMALLINT, null, ['identity' => true, 'nullable' => false, 'primary' => true], 'Manufacturer ID')
                ->addColumn('manufacturer_name', Table::TYPE_TEXT, 255, ['nullable' => false], 'Manufacturer name')
                ->addColumn('manufacturer_link', Table::TYPE_TEXT, 255, ['nullable' => false], 'Manufacturer link')
                ->addColumn('manufacturer_image', Table::TYPE_TEXT, 255, ['nullable' => true], 'Manufacturer image')
                ->addColumn('manufacturer_status', Table::TYPE_INTEGER, 1, ['nullable' => true,'default' => '1'], 'Manufacturer status')
                ->addColumn('store_ids', Table::TYPE_TEXT, '64k', ['unsigned' => true, 'nullable' => false, 'default' => null], 'Store IDs')
                ->setComment('Manufacturer Table');
            $installer->getConnection()->createTable($table);
        }
        $installer->endSetup();
    }
}

<?php

namespace SoW\Instagram\Setup;

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
        $installer->getConnection()->dropTable($installer->getTable('sow_instagram'));

        if (!$installer->tableExists('sow_instagram')) {
            $table = $installer->getConnection()
                ->newTable($installer->getTable('sow_instagram'))
                ->addColumn('instagram_id', Table::TYPE_SMALLINT, null, ['identity' => true, 'nullable' => false, 'primary' => true], 'Instagram ID')
                ->addColumn('instagram_name', Table::TYPE_TEXT, 255, ['nullable' => false], 'Instagram name')
                ->addColumn('instagram_link', Table::TYPE_TEXT, 255, ['nullable' => true], 'Instagram Link')
                ->addColumn('instagram_image', Table::TYPE_TEXT, 255, ['nullable' => true], 'Instagram image')
                ->addColumn('instagram_status', Table::TYPE_INTEGER, 1, ['nullable' => true,'default' => '1'], 'Instagram status')
                ->addColumn('store_ids', Table::TYPE_TEXT, '64k', ['unsigned' => true, 'nullable' => false, 'default' => null], 'Store IDs')
                ->setComment('Instagram Table');
            $installer->getConnection()->createTable($table);
        }
        $installer->endSetup();

    }
}

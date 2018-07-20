<?php

namespace SoW\Testimonial\Setup;

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
        $installer->getConnection()->dropTable($installer->getTable('sow_testimonial'));

        if (!$installer->tableExists('sow_testimonial')) {
            $table = $installer->getConnection()
                ->newTable($installer->getTable('sow_testimonial'))
                ->addColumn('testimonial_id', Table::TYPE_SMALLINT, null, ['identity' => true, 'nullable' => false, 'primary' => true], 'Testimonial ID')
                ->addColumn('testimonial_name', Table::TYPE_TEXT, 255, ['nullable' => false], 'Testimonial name')
                ->addColumn('testimonial_information', Table::TYPE_TEXT, '64k', ['nullable' => true], 'Testimonial title')
                ->addColumn('testimonial_content', Table::TYPE_TEXT, '64k', ['nullable' => true], 'Testimonial content')
                ->addColumn('testimonial_image', Table::TYPE_TEXT, 255, ['nullable' => true], 'Testimonial image')
                ->addColumn('testimonial_order', Table::TYPE_INTEGER, 10, ['nullable' => true], 'Testimonial order')
                ->addColumn('testimonial_status', Table::TYPE_INTEGER, 1, ['nullable' => true,'default' => '1'], 'Testimonial status')
                ->addColumn('store_ids', Table::TYPE_TEXT, '64k', ['unsigned' => true, 'nullable' => false, 'default' => null], 'Store IDs')
                ->setComment('Testimonial Table');
            $installer->getConnection()->createTable($table);
        }
        $installer->endSetup();

    }
}

<?php

namespace Sow\Slideshow\Setup;

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
        $installer->getConnection()->dropTable($installer->getTable('sow_slider'));

        if (!$installer->tableExists('sow_slider')) {
            $table = $installer->getConnection()
                ->newTable($installer->getTable('sow_slider'))
                ->addColumn('slider_id', Table::TYPE_SMALLINT, null, ['identity' => true, 'nullable' => false, 'primary' => true], 'Slider ID')
                ->addColumn('slider_name', Table::TYPE_TEXT, 255, ['nullable' => false], 'Slider name')
                ->addColumn('slider_title', Table::TYPE_TEXT, '64k', ['nullable' => true], 'Slider title')
                ->addColumn('slider_text', Table::TYPE_TEXT, '64k', ['nullable' => true], 'Slider content')
                ->addColumn('slider_image', Table::TYPE_TEXT, 255, ['nullable' => true], 'Slider image')
                ->addColumn('slider_btn_text', Table::TYPE_TEXT, 255, ['nullable' => true], 'Slider button text')
                ->addColumn('slider_btn_url', Table::TYPE_TEXT, 255, ['nullable' => true], 'Slider button URL')
                ->addColumn('slider_style', Table::TYPE_INTEGER, 10, ['nullable' => true], 'Slider style')
                ->addColumn('slider_order', Table::TYPE_INTEGER, 10, ['nullable' => true], 'Slider order')
                ->addColumn('slider_status', Table::TYPE_INTEGER, 1, ['nullable' => true,'default' => '1'], 'Slider status')
                ->addColumn('slider_cms_id', Table::TYPE_INTEGER, null, ['nullable' => true], 'Cms block id')
                ->setComment('Slider Table');
            $installer->getConnection()->createTable($table);
        }
        /**
         * Create table 'sow_slideshow_store'
         */
        $installer->getConnection()->dropTable($installer->getTable('sow_slider_store'));
        if (!$installer->tableExists('sow_slider_store')) {
            $table = $installer->getConnection()->newTable($installer->getTable('sow_slider_store'))
                ->addColumn('slider_id', \Magento\Framework\DB\Ddl\Table::TYPE_SMALLINT, null, ['nullable' => false, 'primary' => true], 'Slider ID')
                ->addColumn('store_id', \Magento\Framework\DB\Ddl\Table::TYPE_SMALLINT, null, ['unsigned' => true, 'nullable' => false, 'primary' => true], 'Store ID')
                ->addIndex($installer->getIdxName('sow_slider_store', ['store_id']), ['store_id'])
                ->addForeignKey($installer->getFkName('sow_slider_store', 'slider_id', 'sow_slider', 'slider_id'), 'slider_id', $installer->getTable('sow_slider'), 'slider_id', \Magento\Framework\DB\Ddl\Table::ACTION_CASCADE)
                ->addForeignKey($installer->getFkName('sow_slider_store', 'store_id', 'store', 'store_id'), 'store_id', $installer->getTable('store'), 'store_id', \Magento\Framework\DB\Ddl\Table::ACTION_CASCADE)
            ->setComment(
                'Sow Slider To Store Linkage Table'
            );
            $installer->getConnection()->createTable($table);
        }
        $installer->endSetup();

    }
}

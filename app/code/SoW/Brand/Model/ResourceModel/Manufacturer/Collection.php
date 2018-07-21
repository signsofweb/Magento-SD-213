<?php

namespace SoW\Brand\Model\ResourceModel\Manufacturer;

use \Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection;

class Collection extends AbstractCollection
{
    protected $_idFieldName = 'manufacturer_id';


    /**
     * Define model & resource model
     */
    protected function _construct()
    {
        $this->_init(
            'SoW\Brand\Model\Manufacturer',
            'SoW\Brand\Model\ResourceModel\Manufacturer'
        );
    }


}
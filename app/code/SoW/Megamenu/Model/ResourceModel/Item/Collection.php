<?php

namespace SoW\Megamenu\Model\ResourceModel\Item;

use \Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection;

class Collection extends AbstractCollection
{
    protected $_idFieldName = 'item_id';


    /**
     * Define model & resource model
     */
    protected function _construct()
    {
        $this->_init(
            'SoW\Megamenu\Model\Item',
            'SoW\Megamenu\Model\ResourceModel\Item'
        );
    }


}
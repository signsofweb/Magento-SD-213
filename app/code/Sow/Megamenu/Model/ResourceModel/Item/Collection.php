<?php

namespace Sow\Megamenu\Model\ResourceModel\Item;

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
            'Sow\Megamenu\Model\Item',
            'Sow\Megamenu\Model\ResourceModel\Item'
        );
    }


}
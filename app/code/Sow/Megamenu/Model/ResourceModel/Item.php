<?php

namespace Sow\Megamenu\Model\ResourceModel;

use Magento\Framework\Model\ResourceModel\Db\AbstractDb;


class Item extends AbstractDb
{
    /**
     * Define main table
     */
    protected function _construct()
    {
        $this->_init('sow_megamenu', 'item_id');
    }

}
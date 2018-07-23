<?php

namespace SoW\Instagram\Model\ResourceModel\Instagram;

use \Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection;

class Collection extends AbstractCollection
{
    protected $_idFieldName = 'instagram_id';


    /**
     * Define model & resource model
     */
    protected function _construct()
    {
        $this->_init(
            'SoW\Instagram\Model\Instagram',
            'SoW\Instagram\Model\ResourceModel\Instagram'
        );
    }


}
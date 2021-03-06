<?php

namespace SoW\Slideshow\Model\ResourceModel\Slider;

use \Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection;

class Collection extends AbstractCollection
{
    protected $_idFieldName = 'slider_id';


    /**
     * Define model & resource model
     */
    protected function _construct()
    {
        $this->_init(
            'SoW\Slideshow\Model\Slider',
            'SoW\Slideshow\Model\ResourceModel\Slider'
        );
    }


}
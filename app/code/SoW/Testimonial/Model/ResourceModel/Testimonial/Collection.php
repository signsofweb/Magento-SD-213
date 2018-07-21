<?php

namespace SoW\Testimonial\Model\ResourceModel\Testimonial;

use \Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection;

class Collection extends AbstractCollection
{
    protected $_idFieldName = 'testimonial_id';


    /**
     * Define model & resource model
     */
    protected function _construct()
    {
        $this->_init(
            'SoW\Testimonial\Model\Testimonial',
            'SoW\Testimonial\Model\ResourceModel\Testimonial'
        );
    }


}
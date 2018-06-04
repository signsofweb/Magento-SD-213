<?php

namespace Sow\Slideshow\Model;

use Magento\Framework\Model\AbstractModel;

class Slider extends AbstractModel
{
    const STATUS_ENABLED = 1;
    const STATUS_DISABLED = 0;
    protected function _construct()
    {
        $this->_init('Sow\Slideshow\Model\ResourceModel\Slider');
    }


    public function getAvailableStatus(){
        return [
            self::STATUS_ENABLED => 'Enabled',
            self::STATUS_DISABLED  => 'Disabled'
        ];
    }
}
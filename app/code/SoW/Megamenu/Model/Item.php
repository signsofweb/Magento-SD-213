<?php

namespace SoW\Megamenu\Model;

use Magento\Framework\Model\AbstractModel;

class Item extends AbstractModel
{
    const STATUS_ENABLED = 1;
    const STATUS_DISABLED = 0;
    protected function _construct()
    {
        $this->_init('SoW\Megamenu\Model\ResourceModel\Item');
    }


    public function getAvailableStatus(){
        return [
            self::STATUS_ENABLED => 'Enabled',
            self::STATUS_DISABLED  => 'Disabled'
        ];
    }
}
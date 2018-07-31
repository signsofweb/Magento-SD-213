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

    public function getName(){
        return $this->getData('item_name');
    }

    public function getCustomHtml(){
        return $this->getData('custom_html');
    }

    public function getAlignment(){
        return $this->getData('dropdown_alignment');
    }

    public function getDropdownEnable(){
        return $this->getData('dropdown_enable');
    }
    public function getMainEnable(){
        return $this->getData('main_enable');
    }
    public function getHtmlPosition(){
        return $this->getData('html_position');
    }
}
<?php

namespace Sow\Megamenu\Block\Adminhtml\Item;

class Tree extends \Magento\Backend\Block\Template{
    protected $_template = 'item/tree.phtml';

    public function getStore(){
        $id = $this->getRequest()->getParam('store');
        return $id;
    }
}
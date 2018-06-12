<?php

namespace Sow\Megamenu\Block\Adminhtml\Item;
use Sow\Megamenu\Model\ResourceModel\Item\Collection;
class Tree extends \Magento\Backend\Block\Template{
    protected $_template = 'item/tree.phtml';

    protected $_collection;

    public function __construct(
        \Magento\Backend\Block\Template\Context $context,
        Collection $collection,
        array $data = [])
    {
        $this->_collection = $collection;
        parent::__construct($context, $data);
    }

    public function getMenuItem(){
        return $this->_collection->addFieldToFilter(
            'parent_id' ,['eq'=>0]
        );
    }
    public function getSubItems($id){
        return $this->_collection->addFieldToFilter(
            'parent_id' ,['eq'=>$id]
        );
    }
}
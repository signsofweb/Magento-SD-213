<?php

namespace SoW\Megamenu\Block\Adminhtml\Item;
use SoW\Megamenu\Model\ResourceModel\Item\Collection;
class Tree extends \Magento\Backend\Block\Template{
    protected $_template = 'item/tree.phtml';

    protected $_collection;
    protected $_urlBuilder;

    public function __construct(
        \Magento\Backend\Block\Template\Context $context,
        Collection $collection,
        array $data = [])
    {
        $this->_collection = $collection;
        $this->_urlBuilder = $context->getUrlBuilder();
        parent::__construct($context, $data);
    }

    public function getItems(){

       return $this->_collection->getData();

    }

    public function drawMenu($items, $parent_id = 0, $lv = 1)
    {
        $cate_child = array();
        foreach ($items as $key => $item)
        {
            if ($item['parent_id'] == $parent_id)
            {
                $cate_child[] = $item;
                unset($items[$key]);
            }
        }
        $html = '';
        if ($cate_child)
        {
            $html.= '<ul>';
            foreach ($cate_child as $key => $item)
            {
                if($this->isActive($item)){
                    $html.= '<li class="active '.$lv.'">';
                }else{
                    $html.= '<li class="'.$lv.'">';
                }
                $html .= '<a href="'.$this->_urlBuilder->getUrl('megamenu/item/edit',['item_id'=> $item['item_id']]).'"/>' .$item['item_name'] . '</a>';
                if($this->isParent($item)){
                    $html.= '<div class="open"></div>';
                }
                $html.= $this->drawMenu($items, $item['item_id'],$lv +1);
                $html.= '</li>';
            }
            $html.= '</ul>';
        }
        return $html;
    }

    public function isActive($item){
        return $item['item_id'] == $this->getRequest()->getParam('item_id') ? true : false;
    }
    public function isParent($item){
        return count($item) > 0 ? true : false;
    }

}
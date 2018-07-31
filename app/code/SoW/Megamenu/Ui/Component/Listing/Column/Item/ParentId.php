<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace SoW\Megamenu\Ui\Component\Listing\Column\Item;

use Magento\Framework\Data\OptionSourceInterface;


class ParentId implements OptionSourceInterface
{
    static $arr = array();
    static $tmp = array();

    protected $_collectionFactory;
    protected $_coreRegistry;

    public function __construct(
        \Sow\Megamenu\Model\ResourceModel\Item\CollectionFactory $collectionFactory,
        \Magento\Framework\Registry $coreRegistry
    )
    {
        $this->_collectionFactory = $collectionFactory;
        $this->_coreRegistry = $coreRegistry;
    }

    public function toOptionArray()
    {

        return $this->getTreeCategories(0);
    }

    public function getTreeCategories($parentId,$level = 0, $caret = ' _ '){
        $model = $this->_coreRegistry->registry('megamenu_item');
        $id = $model->getId();

        $allCats = $this->_collectionFactory->create()->addFieldToFilter('item_id',['neq' => $id]);
        if ($parentId) {
            $allCats->addFieldToFilter('parent_id',array('eq' => $parentId));
        }
        $prefix = "";
        if($level) {
            $prefix = "|_";
            for($i=0;$i < $level; $i++) {
                $prefix .= $caret;
            }
        }
        foreach($allCats as $category)
        {
            if(!isset(self::$tmp[$category->getId()])) {
                self::$tmp[$category->getId()] = $category->getId();
                $tmp["value"] = $category->getId();
                $tmp["label"] = $prefix."(ID:".$category->getId().") ".addslashes($category->getData('item_name'));
                $arr[] = $tmp;
                $subcats = $this->getChildren($category->getId());
                if($subcats != ''){
                    $arr = array_merge($arr, $this->getTreeCategories($category->getId(),(int)$level + 1, $caret.' _ '));
                }

            }

        }
        return isset($arr)?$arr:array();
    }
    public function getChildren($id){
        return $this->_collectionFactory->create()->addFieldToFilter('parent_id',['eq' => $id]);
    }
}

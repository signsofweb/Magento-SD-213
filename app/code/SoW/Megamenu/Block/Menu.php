<?php

namespace SoW\Megamenu\Block;

use SoW\Megamenu\Model\ItemFactory;

class Menu extends \Magento\Backend\Block\Template
{


    protected $_itemFactory;
    protected $_urlBuilder;
    protected $_storeManager;
    /**
     * @var \Magento\Cms\Model\Template\FilterProvider
     */
    protected $_filterProvider;


    public function __construct(
        \Magento\Backend\Block\Template\Context $context,
        ItemFactory $itemFactory,
        \Magento\Store\Model\StoreManagerInterface $storeManager,
        \Magento\Framework\Json\Helper\Data $jsonHelper,
        \Magento\Cms\Model\Template\FilterProvider $filterProvider,
        array $data = [])
    {
        $this->_itemFactory = $itemFactory;
        $this->_urlBuilder = $context->getUrlBuilder();
        $this->_storeManager = $storeManager;
        $this->_filterProvider = $filterProvider;
        parent::__construct($context, $data);
    }

    public function getParentsItemId()
    {
        $collection = $this->_itemFactory->create()->getCollection();
        $storeid = $this->_storeManager->getStore()->getId();
        $collection->addFieldToFilter('parent_id', ['eq' => 0])
            ->addFieldToFilter('is_active', ['eq' => 1])
            ->addFieldToFilter('store_ids', [['eq' => 0], ['finset' => $storeid]]);
        $parentsId = [];
        foreach ($collection as $item) {
            $parentsId[]['eq'] = $item->getData('item_id');
        }
        return $parentsId;
    }

    public function getMenuItems()
    {
        $parentsId = $this->getParentsItemId();
        $collection = $this->_itemFactory->create()->getCollection();
        $collection->addFieldToFilter('is_active', ['eq' => 1])->addOrder('item_order', 'ASC')
            ->addFieldToFilter('parent_id', $parentsId);
        return $collection;
    }

    public function getMenu()
    {
        $html = '';
        $collection = $this->getMenuItems();
        if ($collection->count() > 0) {
            foreach ($collection as $item) {
                $html .= '<li class="menu-item level-1' . ' ' . $this->getItemClass($item) . '">';
                $html .= '<a>';
                $html .= $item->getName();
                $html .= '</a>';
                $subChildHtml = $this->getSubmenu($item);
                if ($subChildHtml != '') {
                    $html .= $subChildHtml;
                }

                $html .= '</li>';
            }
        }
        return $html;
    }

    public function getSubmenu($item, $lv = 2)
    {
        $html = '';
        $id = $item->getId();
        $collection = $this->_itemFactory->create()->getCollection();
        if ($id) {
            $collection->addFieldToFilter('is_active', ['eq' => 1])
                ->addFieldToFilter('parent_id', $id);
        }
        $count = $collection->count();
        $customHtml = $this->getCustomHtml($item);
        $htmlPosition = $item->getHtmlPosition();
            if ($item->getDropdownEnable() == 1) {
                $html .= '<div class="submenu">';
            }
            if ($htmlPosition == 1 && $customHtml != '') {
                $html .= '<div class="submenu-header">';
                $html .= $customHtml;
                $html .= '</div>'; /* End Sub Header*/
            };
            if ($count > 0 || ($htmlPosition == 2 && $customHtml != '') || ($htmlPosition == 4 && $customHtml != '')) {
                $html .= '<div class="submenu-middle">';
                if ($htmlPosition == 4 && $customHtml != '') {
                    $html .= '<div clsas="main-left">';
                    $html .= $customHtml;
                    $html .= '</div>';
                }
                if ($count > 0) {
                    $html .= '<div class="main-content">';
                    $html .= '<ul>';
                    foreach ($collection as $itemChild) {
                        $html .= '<li class="menu-item level-'.$lv . ' ' . $this->getItemClass($itemChild) . '">';
                        $html .= '<a>';
                        $html .= $itemChild->getName();
                        $html .= '</a>';
                        $subChildHtml = $this->getSubmenu($itemChild,(int)$lv+1);
                        if ($subChildHtml != '') {
                            $html .= $subChildHtml;
                        }
                        $html .= '</li>';
                    }
                    $html .= '<ul>';

                    $html .= '</div>'; /* End Main content */
                }
                if ($htmlPosition == 2 && $customHtml != '') {
                    $html .= '<div clsas="main-right">';
                    $html .= $customHtml;
                    $html .= '</div>';
                }
                $html .= '</div>';/*End submenu-middle*/
            }

            if ($htmlPosition == 3 && $customHtml != '') {
                $html .= '<div class="submenu-bottom">';
                $html .= $customHtml;
                $html .= '</div>'; /* End Sub Header*/
            };

            if ($item->getDropdownEnable() == 1) {
                $html .= '</div>';
            }
        return $html;
    }

    public function getCustomHtml($item)
    {
        $content = $item->getCustomHtml();
        $html = $this->_filterProvider->getPageFilter()->filter($content);
        return $html;
    }

    public function getItemClass($item)
    {
        $class = '';
        $alignment = $item->getAlignment();
        switch ($alignment) {
            case 1:
                $class .= 'menu-item-relative from-left';
                break;
            case  2:
                $class .= 'menu-item-relative from-right';
                break;
            case  3:
                $class .= 'relative-left';
                break;
            case  4:
                $class .= 'relative-right';
                break;
        }
        return $class;
    }


}
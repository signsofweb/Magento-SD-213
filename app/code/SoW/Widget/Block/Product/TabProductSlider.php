<?php
namespace SoW\Widget\Block\Product;
use Magento\Framework\View\Element\Template;
use Magento\Widget\Block\BlockInterface;

class TabProductSlider extends Template implements BlockInterface{
    protected $_template = 'widget/tabproductslider.phtml';
    public function getProductSlider($data){
        $html = $this->getLayout()->createBlock('SoW\Widget\Block\Product\ProductSlider')->setData($data)->toHtml();
        return $html;
    }
}

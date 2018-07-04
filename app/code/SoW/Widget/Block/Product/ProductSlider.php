<?php
namespace SoW\Widget\Block\Product;
use SoW\Widget\Block\AbstractProduct;
use SoW\Widget\Model\Product;

class ProductSlider extends AbstractProduct{

    protected $_productModel;

    public function __construct(
        \Magento\Catalog\Block\Product\Context $context,
        Product $productModel,
        array $data = []
    )
    {
        $this->_productModel = $productModel;
        parent::__construct($context, $data);
    }

    public function getProductCollection(){
        return $this->_productModel->getSpecialProducts();
    }
    public function getDataSlider(){
        $options = array(
            'item_md' => ($this->getConfig('max_item') != '')? $this->getConfig('max_item') :4,
            'item_sm' => ($this->getConfig('medium_item') != '')? $this->getConfig('medium_item') :2,
            'item_xs' => ($this->getConfig('min_item') != '')? $this->getConfig('min_item') :1,
            'dots' => ($this->getConfig('show_dots') == 0)? false :true,
            'nav'=> ($this->getConfig('show_nav') == 0)? false :true,
            'loop'=> ($this->getConfig('loop') == 0)? false :true,
            'autoplay' => ($this->getConfig('autoplay') == 0)? false :true,
            'autoplaySpeed'=> ($this->getConfig('autoplay_speed') != '')? $this->getConfig('autoplay_speed') :3000,
        );
        return json_encode($options);
    }

    public function getTitle(){
        return ($this->getConfig('widget_title'))? $this->getConfig('widget_title') : false;
    }

    public function getDescription(){
        return ($this->getConfig('widget_description'))? $this->getConfig('widget_description') : false;
    }

    public function getRow(){
        return ($this->getConfig('row'))? $this->getConfig('row') : 1;
    }
    /**
     * {@inheritdoc}
     */
    protected function _beforeToHtml()
    {
        if($this->getConfig('is_vertical') != 1){
            $this->setTemplate('widget/productslider.phtml');
        }else{
            $this->setTemplate('widget/vertical_productslider.phtml');
        }
        return parent::_beforeToHtml();
    }
}

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

        $options = $this->getAllOptions();
        if($this->getOptions('by_source') == 1){
            return $this->_productModel->getProductCollectidon($this->getOptions('source'),$options);

        }else{
            return $this->_productModel->getProductCollectidon('categories',$options);
        }

    }
    public function getDataSlider(){
        $options = array(
            'item_md' => ($this->getOptions('max_item') != '')? $this->getOptions('max_item') :4,
            'item_sm' => ($this->getOptions('medium_item') != '')? $this->getOptions('medium_item') :2,
            'item_xs' => ($this->getOptions('min_item') != '')? $this->getOptions('min_item') :1,
            'dots' => ($this->getOptions('show_dots') == 0)? false :true,
            'nav'=> ($this->getOptions('show_nav') == 0)? false :true,
            'loop'=> ($this->getOptions('loop') == 0)? false :true,
            'autoplay' => ($this->getOptions('autoplay') == 0)? false :true,
            'autoplaySpeed'=> ($this->getOptions('autoplay_speed') != '')? $this->getOptions('autoplay_speed') :3000,
        );
        return json_encode($options);
    }

    public function getTitle(){
        return ($this->getOptions('widget_title'))? $this->getOptions('widget_title') : false;
    }

    public function getDescription(){
        return ($this->getOptions('widget_description'))? $this->getOptions('widget_description') : false;
    }

    public function getRow(){
        return ($this->getOptions('row'))? $this->getOptions('row') : 1;
    }
    /**
     * {@inheritdoc}
     */
    protected function _beforeToHtml()
    {
        if($this->getOptions('is_vertical') != 1){
            $this->setTemplate('widget/productslider.phtml');
        }else{
            $this->setTemplate('widget/vertical_productslider.phtml');
        }
        return parent::_beforeToHtml();
    }
}

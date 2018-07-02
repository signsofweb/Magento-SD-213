<?php
namespace SoW\Widget\Block\Product;
use SoW\Widget\Block\AbstractProduct;
use SoW\Widget\Model\Product;

class ProductSlider extends AbstractProduct{
    protected $_template = 'widget/productslider.phtml';
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
            'item_md' => 5,
            'item_sm' => 5,
            'item_xs' => 2,
            'dots' => 1,
            'nav'=> 1,
            'loop'=> 0,
            'autoplayHoverPause'=> 0,
            'autoplaySpeed'=> 1000,
            'autoplay' => 0
        );
        return json_encode($options);
    }
}

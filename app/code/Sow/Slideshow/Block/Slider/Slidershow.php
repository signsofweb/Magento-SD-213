<?php
namespace Sow\Slideshow\Block\Slider;
use Magento\Framework\View\Element\Template;
use Sow\Slideshow\Model\ResourceModel\Slider\Collection;

class Slidershow extends \Magento\Framework\View\Element\Template{
    protected  $_sliderCollection;

    protected $_storeManager;
    public function __construct(
        Collection $sliderCollection,
        Template\Context $context,
        array $data = []
    )
    {
        $this->sliderCollection = $sliderCollection;
        $this->_storeManager = $context->getStoreManager();
        parent::__construct($context, $data);
    }
    public function getSliderCollection(){
        $storeId = $this->_storeManager->getstore()->getId();

        $collection = $this->sliderCollection
            ->addFieldToFilter('slider_status',['neq' => '0'])
        ->addFieldToFilter('store_ids',['finset' => $storeId]);
        return $collection;

    }
    public function getDataSlider(){
        $options = array(
            'item_md' => 1,
            'item_sm' => 1,
            'item_xs' => 1,
            'dots' => 1,
            'nav'=> 1,
            'loop'=> 1,
            'autoplayHoverPause'=> 1,
            'autoplaySpeed'=> 3000,
            'autoplay' => 1,
        );
        return json_encode($options);
    }
}
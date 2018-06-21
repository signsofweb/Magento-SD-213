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
        \Magento\Store\Model\StoreManagerInterface $storeManager,
        array $data = []
    )
    {
        $this->sliderCollection = $sliderCollection;
        $this->_storeManager = $storeManager;
        parent::__construct($context, $data);
    }
    public function getSliderCollection(){
        $storeId = $this->_storeManager->getstore()->getId();

        $collection = $this->sliderCollection
            ->addFieldToFilter('slider_status',['neq' => '0'])
        ->addFieldToFilter('store_ids',['finset' => $storeId]);
        return $collection;

    }
}
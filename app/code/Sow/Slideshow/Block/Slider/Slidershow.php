<?php
namespace Sow\Slideshow\Block\Slider;
use Magento\Framework\View\Element\Template;
use Sow\Slideshow\Model\ResourceModel\Slider\Collection;

class Slidershow extends \Magento\Framework\View\Element\Template{
    protected  $_sliderCollection;
    public function __construct(
        Collection $sliderCollection,
        Template\Context $context,
        array $data = []
    )
    {
        $this->sliderCollection = $sliderCollection;
        parent::__construct($context, $data);
    }
    public function getSliderCollection(){
        return $this->sliderCollection;
    }
}
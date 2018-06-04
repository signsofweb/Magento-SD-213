<?php

namespace Sow\Slideshow\Block\Adminhtml\Slider\Edit;

use Magento\Backend\Block\Widget\Context;

class GenericButton
{

    protected $context;
    protected $sliderFactory;

    public function __construct(
        Context $context,
        \Sow\Slideshow\Model\SliderFactory $sliderFactory
    )
    {
        $this->context = $context;
        $this->sliderFactory = $sliderFactory;
    }

    /**
     * Return Slider ID
     */
    public function getSliderId()
    {
        $id = $this->context->getRequest()->getParam('slider_id');
        $slider = $this->sliderFactory->create()->load($id);
        if ($slider->getId())
            return $id;
        return null;
    }

    /**
     * Generate url by route and parameters
     */
    public function getUrl($route = '', $params = [])
    {
        return $this->context->getUrlBuilder()->getUrl($route, $params);
    }
}

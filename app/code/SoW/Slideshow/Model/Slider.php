<?php

namespace SoW\Slideshow\Model;

use Magento\Framework\Model\AbstractModel;
use Magento\Framework\UrlInterface;
//\Magento\Catalog\Model\ResourceModel\Product $resource,
//        \Magento\Catalog\Model\ResourceModel\Product\Collection $resourceCollection,
class Slider extends AbstractModel
{
    const STATUS_ENABLED = 1;
    const STATUS_DISABLED = 0;
    const SLIDER_URL_MEDIA = 'sow/slideshow/sliders/';
    protected  $_urlBuilder;
    protected function _construct()
    {
        $this->_init('SoW\Slideshow\Model\ResourceModel\Slider');
    }
    public function __construct(
        \Magento\Framework\Model\Context $context,
        \Magento\Framework\Registry $registry,
        \SoW\Slideshow\Model\ResourceModel\Slider $resource = null,
        \SoW\Slideshow\Model\ResourceModel\Slider\Collection $resourceCollection = null,
        UrlInterface $urlBuilder,
        array $data = []
    )
    {
        $this->_urlBuilder = $urlBuilder;
        parent::__construct($context, $registry, $resource, $resourceCollection, $data);
    }


    public function getAvailableStatus(){
        return [
            self::STATUS_ENABLED => 'Enabled',
            self::STATUS_DISABLED  => 'Disabled'
        ];
    }
    public function getStoreIds(){
        return $this->getData('store_ids');
    }
    public function getSliderUrl(){
            $image = $this->getData('slider_image');
            $path = self::SLIDER_URL_MEDIA . $image;
            return $this->_urlBuilder->getBaseUrl(['_type' => \Magento\Framework\UrlInterface::URL_TYPE_MEDIA]) . $path;
    }
    public function getSliderName(){
        return $this->getData('slider_name');
    }
}
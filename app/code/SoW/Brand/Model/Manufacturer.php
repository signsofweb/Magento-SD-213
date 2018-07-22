<?php

namespace SoW\Brand\Model;

use Magento\Framework\Model\AbstractModel;
use Magento\Framework\UrlInterface;

class Manufacturer extends AbstractModel
{
    const STATUS_ENABLED = 1;
    const STATUS_DISABLED = 0;
    const SLIDER_URL_MEDIA = 'sow/brand/manufacturers/';
    protected  $_urlBuilder;
    protected function _construct()
    {
        $this->_init('SoW\Brand\Model\ResourceModel\Manufacturer');
    }
    public function __construct(
        \Magento\Framework\Model\Context $context,
        \Magento\Framework\Registry $registry,
        \SoW\Brand\Model\ResourceModel\Manufacturer $resource = null,
        \SoW\Brand\Model\ResourceModel\Manufacturer\Collection $resourceCollection = null,
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

    public function getImage($size){

        $image = $this->getData('manufacturer_image');
        $path = self::SLIDER_URL_MEDIA . $image;
        $url =  $this->_urlBuilder->getBaseUrl(['_type' => \Magento\Framework\UrlInterface::URL_TYPE_MEDIA]) . $path;
        $html = '<img src="'.$url.'" width="'.$size['width'].'" height="'.$size['height'].'" alt="'. $this->getManufacturerName() .'"/>';
        return $html;

    }
    public function getManufacturerName(){
        return $this->getData('manufacturer_name');
    }

    public function getLink(){
        return $this->getData('manufacturer_link');
    }
}
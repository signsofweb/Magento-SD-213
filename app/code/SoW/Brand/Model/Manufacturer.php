<?php

namespace SoW\Brand\Model;

use Magento\Framework\Model\AbstractModel;
use Magento\Framework\UrlInterface;
//\Magento\Catalog\Model\ResourceModel\Product $resource,
//        \Magento\Catalog\Model\ResourceModel\Product\Collection $resourceCollection,
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
    public function getManufacturerUrl(){
            $image = $this->getData('manufacturer_image');
            $path = self::SLIDER_URL_MEDIA . $image;
            return $this->_urlBuilder->getBaseUrl(['_type' => \Magento\Framework\UrlInterface::URL_TYPE_MEDIA]) . $path;
    }
    public function getManufacturerName(){
        return $this->getData('manufacturer_name');
    }
}
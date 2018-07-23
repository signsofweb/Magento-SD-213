<?php

namespace SoW\Instagram\Model;

use Magento\Framework\Model\AbstractModel;
use Magento\Framework\UrlInterface;

class Instagram extends AbstractModel
{
    const STATUS_ENABLED = 1;
    const STATUS_DISABLED = 0;
    const SLIDER_URL_MEDIA = 'sow/instagram/instagrams/';
    protected  $_urlBuilder;


    protected function _construct()
    {
        $this->_init('SoW\Instagram\Model\ResourceModel\Instagram');
    }
    public function __construct(
        \Magento\Framework\Model\Context $context,
        \Magento\Framework\Registry $registry,
        \SoW\Instagram\Model\ResourceModel\Instagram $resource = null,
        \SoW\Instagram\Model\ResourceModel\Instagram\Collection $resourceCollection = null,
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

            $image = $this->getData('instagram_image');
            $path = self::SLIDER_URL_MEDIA . $image;
            $url =  $this->_urlBuilder->getBaseUrl(['_type' => \Magento\Framework\UrlInterface::URL_TYPE_MEDIA]) . $path;
            $html = '<img src="'.$url.'" width="'.$size['width'].'" height="'.$size['height'].'" alt="'. $this->getInstagramName() .'"/>';
            return $html;

    }
    public function getInstagramName(){
        return $this->getData('instagram_name');
    }
    public function getInstagramContent(){
        return $this->getData('instagram_content');
    }
}
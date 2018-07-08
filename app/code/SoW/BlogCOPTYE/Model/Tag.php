<?php
/**
 * Fieldthemes
 * 
 * NOTICE OF LICENSE
 * 
 * This source file is subject to the Fieldthemes.com license that is
 * available through the world-wide-web at this URL:
 * http://www.fieldthemes.com/license-agreement.html
 * 
 * DISCLAIMER
 * 
 * Do not edit or add to this file if you wish to upgrade this extension to newer
 * version in the future.
 * 
 * @category   Fieldthemes
 * @package    Field_Blog
 * @copyright  Copyright (c) 2014 Fieldthemes (http://www.fieldthemes.com/)
 * @license    http://www.fieldthemes.com/LICENSE-1.0.html
 */
namespace Field\Blog\Model;

use Magento\Cms\Api\Data\PageInterface;
use Magento\Framework\DataObject\IdentityInterface;

class Tag extends \Magento\Framework\Model\AbstractModel
{
    /**
     * Blog's Statuses
     */
    const STATUS_ENABLED = 1;
    const STATUS_DISABLED = 0;

    /**
     * Product collection factory
     *
     * @var \Magento\Catalog\Model\ResourceModel\Product\CollectionFactory
     */
    protected $_productCollectionFactory;

    /** @var \Magento\Store\Model\StoreManagerInterface */
    protected $_storeManager;

    /**
     * URL Model instance
     *
     * @var \Magento\Framework\UrlInterface
     */
    protected $_url;

    /**
     * @var \Magento\Catalog\Helper\Category
     */
    protected $_blogHelper;

    /**
     * @param \Magento\Framework\Model\Context
     * @param \Magento\Framework\Registry
     * @param \Magento\Store\Model\StoreManagerInterface
     * @param \Field\Blog\Model\ResourceModel\Tag|null
     * @param \Field\Blog\Model\ResourceModel\Tag\Collection|null
     * @param \Magento\Store\Model\StoreManagerInterface
     * @param \Magento\Framework\UrlInterface
     * @param array
     */
    public function __construct(
        \Magento\Framework\Model\Context $context,
        \Magento\Framework\Registry $registry,
        \Field\Blog\Model\ResourceModel\Tag $resource = null,
        \Field\Blog\Model\ResourceModel\Tag\Collection $resourceCollection = null,
        \Magento\Store\Model\StoreManagerInterface $storeManager,
        \Magento\Framework\UrlInterface $url,
        \Field\Blog\Helper\Data $blogHelper,
        array $data = []
        ) {
        $this->_storeManager = $storeManager;
        $this->_url = $url;
        $this->_blogHelper = $blogHelper;
        parent::__construct($context, $registry, $resource, $resourceCollection, $data);
    }

    /**
     * Initialize resource model
     *
     * @return void
     */
    protected function _construct()
    {
        $this->_init('Field\Blog\Model\ResourceModel\Tag');
    }

    /**
     * Prepare page's statuses.
     * Available event cms_page_get_available_statuses to customize statuses.
     *
     * @return array
     */
    public function getAvailableStatuses()
    {
        return [self::STATUS_ENABLED => __('Enabled'), self::STATUS_DISABLED => __('Disabled')];
    }
    
    public function getUrl(){
        $url = $this->_storeManager->getStore()->getBaseUrl();
        $url_prefix = $this->getConfig('general_settings/url_prefix');
        $url_suffix = $this->getConfig('general_settings/url_suffix');
        $urlPrefix = '';
        if($url_prefix){
            $urlPrefix = $url_prefix.'/';
        }
        return $url . $urlPrefix . 'tag/' . $this->getAlias();
    }
}
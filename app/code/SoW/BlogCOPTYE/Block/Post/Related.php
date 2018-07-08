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
namespace Field\Blog\Block\Post;

class Related extends \Magento\Framework\View\Element\Template
{

	/**
     * Core registry
     *
     * @var \Magento\Framework\Registry
     */
	protected $_coreRegistry = null;

    /**
     * @var \Magento\Catalog\Helper\Category
     */
    protected $_blogHelper;

    /**
     * @var \Field\Blog\Model\Post
     */
    protected $_postFactory;
    protected $_postsBlock;
    protected $_collection;

    /**
     * @param \Magento\Framework\View\Element\Template\Context
     * @param \Magento\Framework\Registry
     * @param \Field\Blog\Model\Post
     * @param \Field\Blog\Helper\Data
     * @param array
     */
    public function __construct(
    	\Magento\Framework\View\Element\Template\Context $context,
    	\Magento\Framework\Registry $registry,
    	\Field\Blog\Model\Post $postFactory,
    	\Field\Blog\Helper\Data $blogHelper,
    	array $data = []
    	) {
    	$this->_blogHelper = $blogHelper;
    	$this->_coreRegistry = $registry;
    	$this->_postFactory = $postFactory;
    	parent::__construct($context, $data);

    }

    public function getConfig($key, $default = '')
    {   
        $c = explode("/", $key);
        if(count($c)==2){
            if($this->hasData($c[1])){
                return $this->getData($c[1]);
            }
        }
        if($this->hasData($key)){
            return $this->getData($key);
        }
        return $default;
    }

    public function _construct()
    {
    	parent::_construct();
    }

    public function _toHtml(){
        $post = $this->getPost();
        if(!$this->_blogHelper->getConfig('general_settings/enable') || !$post->getIsActive()) return;
        return parent::_toHtml();
    }

    public function getPost(){
        $post = $this->_coreRegistry->registry('current_post');
        return $post;
    }


    /**
     * Set brand collection
     * @param \Field\Blog\Model\Post
     */
    public function setCollection($collection)
    {
    	$this->_collection = $collection;
    	return $this->_collection;
    }

    public function getCollection(){
    	return $this->_collection;
    }

    /**
     * Need use as _prepareLayout - but problem in declaring collection from
     * another block (was problem with search result)
     * @return $this
     */
    protected function _beforeToHtml()
    {
        $store = $this->_storeManager->getStore();
        $post = $this->getPost();
        $postRelated = $post->getPostsRelated();
        $postCollection = $this->_postFactory->getCollection()
        ->addFieldToFilter('is_active',1)
        ->addStoreFilter($store)
        ->setCurPage(1);
        $postCollection->getSelect()
        ->joinLeft(
            [
                'relatedtbl' => 'field_blog_post_related'],
                'relatedtbl.post_related_id = main_table.post_id',
            [
                'position' => 'position'
            ]
            )
        ->where('relatedtbl.post_id = (?)', (int)$post->getPostId())
        ->group('main_table.post_id')
        ->order('position ASC');
        $this->setCollection($postCollection);
        return parent::_beforeToHtml();
    }
}
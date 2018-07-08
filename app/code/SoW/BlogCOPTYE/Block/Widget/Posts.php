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
namespace Field\Blog\Block\Widget;

class Posts extends \Magento\Framework\View\Element\Template implements \Magento\Widget\Block\BlockInterface
{
	/**
	 * @var \Field\Blog\Helper\Data
	 */
	protected $_blogHelper;

	/**
	 * @var \Field\Blog\Model\Post
	 */
	protected $_post;

	/**
	 * @param \Magento\Framework\View\Element\Template\Context
	 * @param \Field\Blog\Helper\Data
	 * @param \Field\Blog\Model\Post
	 * @param array
	 */
	public function __construct(
		\Magento\Framework\View\Element\Template\Context $context,
		\Field\Blog\Helper\Data $blogHelper,
		\Field\Blog\Model\Post $post,
		\Magento\Cms\Model\Block $blockModel,
		array $data = []
		) {
		$this->_blogHelper = $blogHelper;
		$this->_post = $post;
		$this->_blockModel = $blockModel;
		parent::__construct($context, $data);
	}

	 public function getCmsBlockModel(){
        return $this->_blockModel;
    }

	public function _toHtml(){
		$this->setTemplate("Field_Blog::widget/posts.phtml");
		$itemPerPage = $this->getConfig('number_post', 6);
		$categories = $this->getConfig('categories');
		$categories = explode(",", $categories);

		$store = $this->_storeManager->getStore();
		$collection = $this->_post
		->getCollection()
		->addFieldToFilter("is_active", 1)
		->setPagesize($itemPerPage)
		->addStoreFilter($store)
		->setCurpage(1);

		$collection->getSelect()
        ->joinLeft(
            [
            'cat' => 'field_blog_post_category'],
            'cat.post_id = main_table.post_id',
            [
            'post_id' => 'post_id',
            'position' => 'position'
            ]
            )
        ->where('cat.category_id in (?)', $categories)
        ->limit($itemPerPage)
        ->group('main_table.post_id');

		$orderBy = $this->getConfig("orderby");
		if($orderBy == 1){
			$collection->getSelect()->order("main_table.post_id DESC");
		}else if($orderBy == 2){
			$collection->getSelect()->order("main_table.post_id ASC");
		}else if($orderBy == 3){
			$collection->getSelect()->order("main_table.hits DESC");
		}else if($orderBy == 4){
			$collection->getSelect()->order("main_table.hits ASC");
		}
		$this->setCollection($collection);
		return parent::_toHtml();
	}

	/**
     * @param AbstractCollection $collection
     * @return $this
     */
	public function setCollection($collection)
	{
		$this->_postCollection = $collection;
		return $this;
	}

	public function getCollection()
	{
		return $this->_postCollection;
	}

	public function getConfig($key, $default = '')
	{
		if($this->hasData($key)){
			return $this->getData($key);
		}
		return $default;
	}
}
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
namespace Field\Blog\Block\Category;

class CategoryList extends \Magento\Framework\View\Element\Template
{
	/**
	 * @var \Field\Blog\Helper\Data
	 */
	protected $_blogHelper;

	/**
	 * @var \Field\Blog\Model\Category
	 */
	protected $_tag;

	/**
	 * @var Field\Blog\Model\ResourceModel\Category\Collection
	 */
	protected $_colleciton;

	/**
	 * @param \Magento\Framework\View\Element\Template\Context
	 * @param \Field\Blog\Helper\Data
	 * @param \Field\Blog\Model\Category
	 * @param array
	 */
	public function __construct(
		\Magento\Framework\View\Element\Template\Context $context,
		\Field\Blog\Helper\Data $blogHelper,
		\Field\Blog\Model\Category $category,
		array $data = []
		) {
		parent::__construct($context, $data);
		$this->_blogHelper = $blogHelper;
		$this->_category = $category;
	}

	public function _toHtml(){
		if(!$this->_blogHelper->getConfig('general_settings/enable')) return;
		$store = $this->_storeManager->getStore();
		$collection = $this->_category->getCollection()
		->addFieldToFilter('is_active', 1)
		->addStoreFilter($store)
		->setOrder("cat_position", "ASC");
		$this->setCollection($collection);
		return parent::_toHtml();
	}

	public function setCollection($collection){
		$this->_collection = $collection;
		return $this;
	}

	public function getCollection(){
		return $this->_collection;
	}
}
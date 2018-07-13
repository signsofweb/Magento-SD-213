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
namespace Field\Blog\Block\Tag;

class TagList extends \Magento\Framework\View\Element\Template
{
	/**
	 * @var \Field\Blog\Helper\Data
	 */
	protected $_blogHelper;

	/**
	 * @var \Field\Blog\Model\Tag
	 */
	protected $_tag;

	/**
	 * @var Field\Blog\Model\ResourceModel\Tag\Collection
	 */
	protected $_colleciton;

	/**
	 * @param \Magento\Framework\View\Element\Template\Context
	 * @param \Field\Blog\Helper\Data
	 * @param \Field\Blog\Model\Tag
	 * @param array
	 */
    public function __construct(
        \Magento\Framework\View\Element\Template\Context $context,
        \Field\Blog\Helper\Data $blogHelper,
        \Field\Blog\Model\Tag $tag,
        array $data = []
        ) {
        parent::__construct($context, $data);
        $this->_blogHelper = $blogHelper;
        $this->_tag = $tag;
    }

    public function _toHtml(){
        if(!$this->_blogHelper->getConfig('general_settings/enable')){
            return;
        }
        $collection = $this->_tag->getCollection();
        $tags = [];
        $stop = 1;
		foreach ($collection as $k => $v) {
                    if ($stop < 9){
			$count = 1;
			if(isset($tags[$v['alias']])){
				$count = $tags[$v['alias']]['count']+1;
                                $stop--;
			}
			$tags[$v['alias']] = [
				'name' => $v['name'],
				'count' => $count
			];
                    }
                    $stop++;
		}
		$this->setData("tags", $tags);
        return parent::_toHtml();
    }
}
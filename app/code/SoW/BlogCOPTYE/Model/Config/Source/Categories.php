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
namespace Field\Blog\Model\Config\Source;

class Categories implements \Magento\Framework\Option\ArrayInterface
{
    /**
     * @var \Magento\User\Model\UserFactory
     */
	protected $_categoryFactory;

    /**
     * @param \Field\Blog\Model\Category
     */
	public function __construct(
		\Field\Blog\Model\Category $categoryFactory
		){
		$this->_categoryFactory = $categoryFactory;
	}

    public function toOptionArray()
    {
    	$options = [];
        $collection = $this->_categoryFactory->getCollection();
        foreach ($collection as $_cat) {
        	$options[] = [
                'label' => $_cat->getName(),
                'value' => $_cat->getCategoryId()
            ];
        }
        return $options;
    }
}
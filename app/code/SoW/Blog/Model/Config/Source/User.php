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

use Magento\Framework\Data\OptionSourceInterface;

class User implements OptionSourceInterface
{
    /**
     * @var \Magento\User\Model\UserFactory
     */
	protected $_userFactory;

	public function __construct(
		\Magento\User\Model\UserFactory $userFactory
		){
		$this->_userFactory = $userFactory;
	}

    public function toOptionArray()
    {
    	$options = [];
        $collection = $this->_userFactory->create()->getCollection();
        foreach ($collection as $_user) {
        	$options[$_user->getUserId()] = $_user->getFirstname() . ' ' . $_user->getLastname();
        }
        return $options;
    }
}
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
namespace Field\Blog\Controller\Adminhtml\Category;
use Magento\Backend\App\Action\Context;

class Index extends \Magento\Backend\App\Action
{
	/**
     * Core registry
     *
     * @var \Magento\Framework\Registry
     */
    protected $_coreRegistry = null;

    /**
     * @var \Magento\Framework\View\Result\PageFactory
     */
    protected $resultPageFactory;

    /**
     * @param Context
     * @param \Magento\Framework\View\Result\PageFactory
     * @param \Magento\Framework\Registry
     */
    public function __construct(
        Context $context,
        \Magento\Framework\View\Result\PageFactory $resultPageFactory,
        \Magento\Framework\Registry $registry
    ) {
        $this->resultPageFactory = $resultPageFactory;
        $this->_coreRegistry = $registry;
        parent::__construct($context);
    }

	/**
     * Check the permission to run it
     *
     * @return bool
     */
    protected function _isAllowed()
    {
        return $this->_authorization->isAllowed('Field_Blog::category');
    }

	public function execute()
	{

		$resultPage = $this->resultPageFactory->create();

		/**
		 * Set active menu item
		 */
		$resultPage->setActiveMenu("Field_Blog::category");
		$resultPage->getConfig()->getTitle()->prepend(__('Categories'));

		/**
		 * Add breadcrumb item
		 */
		$resultPage->addBreadcrumb(__('Field_Blog'),__('Categories'));
		$resultPage->addBreadcrumb(__('Manage Categories'),__('Manage Categories'));

		return $resultPage;
	}
	
}
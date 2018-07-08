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
namespace Field\Blog\Controller\Latest;

use Magento\Customer\Controller\AccountInterface;
use Magento\Framework\App\Action\Action;
use Magento\Framework\App\Action\Context;
use Magento\Framework\View\Result\PageFactory;

class View extends \Magento\Framework\App\Action\Action
{
	/**
     * @var \Magento\Framework\App\RequestInterface
     */
    protected $_request;

    /**
     * @var \Magento\Framework\App\ResponseInterface
     */
    protected $_response;

    /**
     * @var \Magento\Framework\Controller\Result\RedirectFactory
     */
    protected $resultRedirectFactory;

    /**
     * @var \Magento\Framework\Controller\ResultFactory
     */
    protected $resultFactory;

    /**
     * @var \Field\Blog\Helper\Data
     */
    protected $_blogHelper;

    /**
     * @var \Magento\Framework\Controller\Result\ForwardFactory
     */
    protected $resultForwardFactory;

    /**
     * @param Context
     * @param \Magento\Store\Model\StoreManager
     * @param \Magento\Framework\View\Result\PageFactory
     * @param \Field\Blog\Helper\Data
     * @param \Magento\Framework\Controller\Result\ForwardFactory
     */
    public function __construct(
        Context $context,
        \Magento\Store\Model\StoreManager $storeManager,
        \Magento\Framework\View\Result\PageFactory $resultPageFactory,
        \Field\Blog\Helper\Data $blogHelper,
        \Magento\Framework\Controller\Result\ForwardFactory $resultForwardFactory
        ) {
        $this->resultPageFactory = $resultPageFactory;
        $this->_blogHelper = $blogHelper;
        $this->resultForwardFactory = $resultForwardFactory;
        parent::__construct($context);
    }

    /**
     * Default customer account page
     *
     * @return \Magento\Framework\View\Result\Page
     */
    public function execute()
    {
        $page = $this->resultPageFactory->create();
        $blogHelper = $this->_blogHelper;
        if(!$blogHelper->getConfig('general_settings/enable')){
            $resultForward = $this->resultForwardFactory->create();
            return $resultForward->forward('defaultnoroute');
        }
        $page_layout = $blogHelper->getConfig('blog_page/page_layout');
        if($page_layout){
            $page->getConfig()->setPageLayout($page_layout);
        }
        return $page;
    }
}
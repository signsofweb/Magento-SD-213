<?php

namespace SoW\Blog\Controller\Adminhtml;

use Magento\Backend\App\Action;
use Magento\Backend\App\Action\Context;
use Magento\Framework\Registry;
use Magento\Framework\View\Result\PageFactory;
use Magento\Framework\App\Response\Http\FileFactory;
use SoW\Blog\Helper\Data;
use Magento\Framework\View\LayoutFactory;
use \Magento\Framework\View\Result\LayoutFactory as ResultLayoutFactory;

abstract class Blog extends Action
{
    protected $_coreRegistry = null;
    protected $layoutFactory;
    protected $_fileFactory;
    protected $_viewHelper;
    protected $resultLayoutFactory;
    protected $resultPageFactory;

    public function __construct(
        Context $context,
        Registry $coreRegistry,
        FileFactory $fileFactory,
        Data $viewHelper,
        LayoutFactory $layoutFactory,
        ResultLayoutFactory $resultLayoutFactory,
        PageFactory $resultPageFactory
    )
    {
        $this->_coreRegistry = $coreRegistry;
        $this->_fileFactory = $fileFactory;
        $this->_viewHelper = $viewHelper;
        $this->layoutFactory = $layoutFactory;
        $this->resultLayoutFactory = $resultLayoutFactory;
        $this->resultPageFactory = $resultPageFactory;
        parent::__construct($context);
    }

    protected function _initAction()
    {
        $this->_view->loadLayout();
        $this->_setActiveMenu('SoW_Blog::blog')->_addBreadcrumb(__('Blog'), __('Blog'));
        return $this;
    }

    protected function _isAllowed()
    {
        return $this->_authorization->isAllowed('SoW_Blog::blog');
    }
}

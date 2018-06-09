<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Sow\Megamenu\Controller\Adminhtml\Item;

class Edit extends \Magento\Backend\App\Action
{
    /**
     * @var \Magento\Framework\View\Result\PageFactory
     */
    protected $resultPageFactory;

    protected $_coreRegistry;

    /**
     * @param \Magento\Backend\App\Action\Context $context
     * @param \Magento\Framework\Registry $coreRegistry
     * @param \Magento\Framework\View\Result\PageFactory $resultPageFactory
     */
    public function __construct(
        \Magento\Backend\App\Action\Context $context,
        \Magento\Framework\Registry $coreRegistry,
        \Magento\Framework\View\Result\PageFactory $resultPageFactory
    ) {
        $this->_coreRegistry = $coreRegistry;
        $this->resultPageFactory = $resultPageFactory;
        parent::__construct($context, $coreRegistry);
    }

    public function initPage(){
        $resultPage = $this->resultPageFactory->create();
        $resultPage->setActiveMenu('Sow_Base::base');
        return $resultPage;
    }
    /**
     * Edit CMS block
     *
     * @return \Magento\Framework\Controller\ResultInterface
     * @SuppressWarnings(PHPMD.NPathComplexity)
     */
    public function execute()
    {

        // 1. Get ID and create model
        $id = $this->getRequest()->getParam('item_id');
        $model = $this->_objectManager->create(\Sow\Megamenu\Model\Item::class);

        // 2. Initial checking
        if ($id) {
            $model->load($id);

            if (!$model->getId()) {
                $this->messageManager->addError(__('This item no longer exists.'));
                /** @var \Magento\Backend\Model\View\Result\Redirect $resultRedirect */
                $resultRedirect = $this->resultRedirectFactory->create();
                return $resultRedirect->setPath('*/*/');
            }
        }


        $this->_coreRegistry->register('megamenu_item', $model);

        // 5. Build edit form
        /** @var \Magento\Backend\Model\View\Result\Page $resultPage */
        $resultPage = $this->resultPageFactory->create();

        $resultPage->getConfig()->getTitle()->prepend(__('Items'));
        $resultPage->getConfig()->getTitle()->prepend($model->getId() ? $model->getData('item_name') : __('New Item'));

        return $resultPage;
    }
}

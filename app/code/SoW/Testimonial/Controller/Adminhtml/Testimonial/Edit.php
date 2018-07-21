<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace SoW\Testimonial\Controller\Adminhtml\Testimonial;

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
        parent::__construct($context);
    }

    public function initPage(){
        $resultPage = $this->resultPageFactory->create();
        $resultPage->setActiveMenu('SoW_Base::base');
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
        $id = $this->getRequest()->getParam('testimonial_id');
        $model = $this->_objectManager->create(\SoW\Testimonial\Model\Testimonial::class);

        // 2. Initial checking
        if ($id) {
            $model->load($id);

            if (!$model->getId()) {
                $this->messageManager->addError(__('This testimonial no longer exists.'));
                /** @var \Magento\Backend\Model\View\Result\Redirect $resultRedirect */
                $resultRedirect = $this->resultRedirectFactory->create();
                return $resultRedirect->setPath('*/*/');
            }
        }


        $this->_coreRegistry->register('testimonial_testimonial', $model);

        // 5. Build edit form
        /** @var \Magento\Backend\Model\View\Result\Page $resultPage */
        $resultPage = $this->resultPageFactory->create();

        $resultPage->getConfig()->getTitle()->prepend(__('Testimonials'));
        $resultPage->getConfig()->getTitle()->prepend($model->getId() ? $model->getData('testimonial_name') : __('New Testimonial'));

        return $resultPage;
    }
}

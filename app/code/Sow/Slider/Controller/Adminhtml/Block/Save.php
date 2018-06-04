<?php
/**
 *
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Sow\Slider\Controller\Adminhtml\Block;

use Magento\Backend\App\Action\Context;
use Sow\Slider\Model\Block;
use Magento\Framework\App\Request\DataPersistorInterface;
use Magento\Framework\Exception\LocalizedException;
use Magento\TestFramework\Inspection\Exception;

class Save extends \Sow\Slider\Controller\Adminhtml\Block
{
    /**
     * @var DataPersistorInterface
     */
    protected $dataPersistor;

    /**
     * @param Context $context
     * @param \Magento\Framework\Registry $coreRegistry
     * @param DataPersistorInterface $dataPersistor
     */
    public function __construct(
        Context $context,
        \Magento\Framework\Registry $coreRegistry,
        DataPersistorInterface $dataPersistor
    ) {
        $this->dataPersistor = $dataPersistor;
        parent::__construct($context, $coreRegistry);
    }

    /**
     * Save action
     *
     * @SuppressWarnings(PHPMD.CyclomaticComplexity)
     * @return \Magento\Framework\Controller\ResultInterface
     */
    public function execute()
    {
        /** @var \Magento\Backend\Model\View\Result\Redirect $resultRedirect */
        $resultRedirect = $this->resultRedirectFactory->create();
        $data = $this->getRequest()->getPostValue();
        if ($data) {
            $id = $this->getRequest()->getParam('block_id');

            if (isset($data['is_active']) && $data['is_active'] === 'true') {
                $data['is_active'] = Block::STATUS_ENABLED;
            }
            if (empty($data['block_id'])) {
                $data['block_id'] = null;
            }

            /** @var \Sow\Slider\Model\Block $model */
            $model = $this->_objectManager->create('Sow\Slider\Model\Block')->load($id);
            if (!$model->getId() && $id) {
                $this->messageManager->addError(__('This block no longer exists.'));
                return $resultRedirect->setPath('*/*/');
            }

            $model->setData($data);

            try {
                $model->save();
                $this->messageManager->addSuccess(__('You saved the block.'));
                $this->dataPersistor->clear('sow_slider_banner');

                if ($this->getRequest()->getParam('back')) {
                    return $resultRedirect->setPath('*/*/edit', ['block_id' => $model->getId()]);
                }
                return $resultRedirect->setPath('*/*/');
            } catch (LocalizedException $e) {
                $this->messageManager->addError($e->getMessage());
            } catch (\Exception $e) {
                $this->messageManager->addException($e, __('Something went wrong while saving the block.'));
            }

            $this->dataPersistor->set('sow_slider_banner', $data);
            return $resultRedirect->setPath('*/*/edit', ['block_id' => $this->getRequest()->getParam('block_id')]);
        }
        return $resultRedirect->setPath('*/*/');
    }
}

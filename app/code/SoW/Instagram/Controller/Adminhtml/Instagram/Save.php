<?php
/**
 *
 * Copyright © 2013-2017 Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace SoW\Instagram\Controller\Adminhtml\Instagram;

use Magento\Backend\App\Action;
use SoW\Instagram\Model\Instagram;
use Magento\Framework\App\Request\DataPersistorInterface;
use Magento\Framework\Exception\LocalizedException;

class Save extends \Magento\Backend\App\Action
{
    /**
     * Authorization level of a basic admin session
     *
     * @see _isAllowed()
     */
    const ADMIN_RESOURCE = 'SoW_Instagram::Instagram_manage';

    /**
     * @var PostDataProcessor
     */
    protected $dataProcessor;

    /**
     * @var DataPersistorInterface
     */
    protected $dataPersistor;

    /**
     * @var \Magento\Cms\Model\InstagramFactory
     */
    private $instagramFactory;

    /**
     * @param Action\Context $context
     * @param PostDataProcessor $dataProcessor
     * @param DataPersistorInterface $dataPersistor
     * @param \Magento\Cms\Model\InstagramFactory $instagramFactory
     */
    public function __construct(
        Action\Context $context,
        PostDataProcessor $dataProcessor,
        DataPersistorInterface $dataPersistor,
        \SoW\Instagram\Model\InstagramFactory $instagramFactory = null
    ) {
        $this->dataProcessor = $dataProcessor;
        $this->dataPersistor = $dataPersistor;
        $this->instagramFactory = $instagramFactory
            ?: \Magento\Framework\App\ObjectManager::getInstance()->get(\SoW\Instagram\Model\InstagramFactory::class);

        parent::__construct($context);
    }

    /**
     * Save action
     *
     * @SuppressWarnings(PHPMD.CyclomaticComplexity)
     * @return \Magento\Framework\Controller\ResultInterface
     */
    public function execute()
    {
        $data = $this->getRequest()->getPostValue();

        /** @var \Magento\Backend\Model\View\Result\Redirect $resultRedirect */
        $resultRedirect = $this->resultRedirectFactory->create();
        if ($data) {

            if (isset($data['instagram_status']) && $data['instagram_status'] === 'true') {
                $data['instagram_status'] = Instagram::STATUS_ENABLED;
            }

            if (empty($data['instagram_id'])) {
                $data['instagram_id'] = null;
            }
            if (empty($data['instagram_images'])) {
                $data['instagram_image'] = null;
            } else {
                if ($data['instagram_images'][0] && $data['instagram_images'][0]['name']){
                    $data['instagram_image'] = $data['instagram_images'][0]['name'];
                }
                else{
                    $data['instagram_image'] = null;
                }

            }
            if (empty($data['store_ids'])) {
                $data['store_ids'] = 0;
            }elseif(in_array(0,$data['store_ids'])){
                $data['store_ids'] = 0;
            }else{
                $data['store_ids'] = implode(',',$data['store_ids']);
            }

            /** @var \SoW\Instagram\Model\Instagram $model */
            $model = $this->instagramFactory->create();

            $id = $this->getRequest()->getParam('instagram_id');
            if ($id) {
                $model->load($id);
                if (!$model->getId()) {
                    $this->messageManager->addErrorMessage(__('This instagram no longer exists.'));
                    /** \Magento\Backend\Model\View\Result\Redirect $resultRedirect */
                    $resultRedirect = $this->resultRedirectFactory->create();

                    return $resultRedirect->setPath('*/*/');
                }
            }


            $model->setData($data);

            if (!$this->dataProcessor->validateRequireEntry($data)) {
                return $resultRedirect->setPath('*/*/edit', ['instagram_id' => $model->getInstagramId(), '_current' => true]);
            }

            try {
                $model->save();
                $this->messageManager->addSuccess(__('You saved the instagram.'));
                $this->dataPersistor->clear('instagram_instagram');
                if ($this->getRequest()->getParam('back')) {
                    return $resultRedirect->setPath('*/*/edit', ['instagram_id' => $model->getInstagramId(), '_current' => true]);
                }
                return $resultRedirect->setPath('*/*/');
            } catch (LocalizedException $e) {
                $this->messageManager->addError($e->getMessage());
            } catch (\Exception $e) {
                $this->messageManager->addException($e, __('Something went wrong while saving the instagram.'));
            }

            $this->dataPersistor->set('instagram_instagram', $data);
            return $resultRedirect->setPath('*/*/edit', ['instagram_id' => $this->getRequest()->getParam('instagram_id')]);
        }
        return $resultRedirect->setPath('*/*/');
    }
}

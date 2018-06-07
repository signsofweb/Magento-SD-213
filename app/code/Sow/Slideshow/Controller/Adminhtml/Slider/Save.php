<?php
/**
 *
 * Copyright © 2013-2017 Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Sow\Slideshow\Controller\Adminhtml\Slider;

use Magento\Backend\App\Action;
use Sow\Slideshow\Model\Slider;
use Magento\Framework\App\Request\DataPersistorInterface;
use Magento\Framework\Exception\LocalizedException;

class Save extends \Magento\Backend\App\Action
{
    /**
     * Authorization level of a basic admin session
     *
     * @see _isAllowed()
     */
    const ADMIN_RESOURCE = 'Sow_Slideshow::Slider_manage';

    /**
     * @var PostDataProcessor
     */
    protected $dataProcessor;

    /**
     * @var DataPersistorInterface
     */
    protected $dataPersistor;

    /**
     * @var \Magento\Cms\Model\SliderFactory
     */
    private $sliderFactory;

    /**
     * @param Action\Context $context
     * @param PostDataProcessor $dataProcessor
     * @param DataPersistorInterface $dataPersistor
     * @param \Magento\Cms\Model\SliderFactory $sliderFactory
     */
    public function __construct(
        Action\Context $context,
        PostDataProcessor $dataProcessor,
        DataPersistorInterface $dataPersistor,
        \Sow\Slideshow\Model\SliderFactory $sliderFactory = null
    ) {
        $this->dataProcessor = $dataProcessor;
        $this->dataPersistor = $dataPersistor;
        $this->sliderFactory = $sliderFactory
            ?: \Magento\Framework\App\ObjectManager::getInstance()->get(\Sow\Slideshow\Model\SliderFactory::class);

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

            if (isset($data['slider_status']) && $data['slider_status'] === 'true') {
                $data['slider_status'] = Slider::STATUS_ENABLED;
            }

            if (empty($data['slider_id'])) {
                $data['slider_id'] = null;
            }
            if (empty($data['slider_images'])) {
                $data['slider_image'] = null;
            } else {
                if ($data['slider_images'][0] && $data['slider_images'][0]['name']){
                    $data['slider_image'] = $data['slider_images'][0]['name'];
                }
                else{
                    $data['slider_image'] = null;
                }

            }
            if (empty($data['store_ids'])) {
                $data['store_ids'] = 0;
            }elseif(in_array(0,$data['store_ids'])){
                $data['store_ids'] = 0;
            }else{
                $data['store_ids'] = implode(',',$data['store_ids']);
            }

            /** @var \Sow\Slideshow\Model\Slider $model */
            $model = $this->sliderFactory->create();

            $id = $this->getRequest()->getParam('slider_id');
            if ($id) {
                $model->load($id);
                if (!$model->getId()) {
                    $this->messageManager->addErrorMessage(__('This slider no longer exists.'));
                    /** \Magento\Backend\Model\View\Result\Redirect $resultRedirect */
                    $resultRedirect = $this->resultRedirectFactory->create();

                    return $resultRedirect->setPath('*/*/');
                }
            }


            $model->setData($data);

            if (!$this->dataProcessor->validateRequireEntry($data)) {
                return $resultRedirect->setPath('*/*/edit', ['slider_id' => $model->getSliderId(), '_current' => true]);
            }

            try {
                $model->save();
                $this->messageManager->addSuccess(__('You saved the slider.'));
                $this->dataPersistor->clear('slideshow_slider');
                if ($this->getRequest()->getParam('back')) {
                    return $resultRedirect->setPath('*/*/edit', ['slider_id' => $model->getSliderId(), '_current' => true]);
                }
                return $resultRedirect->setPath('*/*/');
            } catch (LocalizedException $e) {
                $this->messageManager->addError($e->getMessage());
            } catch (\Exception $e) {
                $this->messageManager->addException($e, __('Something went wrong while saving the slider.'));
            }

            $this->dataPersistor->set('slideshow_slider', $data);
            return $resultRedirect->setPath('*/*/edit', ['slider_id' => $this->getRequest()->getParam('slider_id')]);
        }
        return $resultRedirect->setPath('*/*/');
    }
}

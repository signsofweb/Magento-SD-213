<?php
/**
 *
 * Copyright © 2013-2017 Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace SoW\Testimonial\Controller\Adminhtml\Testimonial;

use Magento\Backend\App\Action;
use SoW\Testimonial\Model\Testimonial;
use Magento\Framework\App\Request\DataPersistorInterface;
use Magento\Framework\Exception\LocalizedException;

class Save extends \Magento\Backend\App\Action
{
    /**
     * Authorization level of a basic admin session
     *
     * @see _isAllowed()
     */
    const ADMIN_RESOURCE = 'SoW_Testimonial::Testimonial_manage';

    /**
     * @var PostDataProcessor
     */
    protected $dataProcessor;

    /**
     * @var DataPersistorInterface
     */
    protected $dataPersistor;

    /**
     * @var \Magento\Cms\Model\TestimonialFactory
     */
    private $testimonialFactory;

    /**
     * @param Action\Context $context
     * @param PostDataProcessor $dataProcessor
     * @param DataPersistorInterface $dataPersistor
     * @param \Magento\Cms\Model\TestimonialFactory $testimonialFactory
     */
    public function __construct(
        Action\Context $context,
        PostDataProcessor $dataProcessor,
        DataPersistorInterface $dataPersistor,
        \SoW\Testimonial\Model\TestimonialFactory $testimonialFactory = null
    ) {
        $this->dataProcessor = $dataProcessor;
        $this->dataPersistor = $dataPersistor;
        $this->testimonialFactory = $testimonialFactory
            ?: \Magento\Framework\App\ObjectManager::getInstance()->get(\SoW\Testimonial\Model\TestimonialFactory::class);

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

            if (isset($data['testimonial_status']) && $data['testimonial_status'] === 'true') {
                $data['testimonial_status'] = Testimonial::STATUS_ENABLED;
            }

            if (empty($data['testimonial_id'])) {
                $data['testimonial_id'] = null;
            }
            if (empty($data['testimonial_images'])) {
                $data['testimonial_image'] = null;
            } else {
                if ($data['testimonial_images'][0] && $data['testimonial_images'][0]['name']){
                    $data['testimonial_image'] = $data['testimonial_images'][0]['name'];
                }
                else{
                    $data['testimonial_image'] = null;
                }

            }
            if (empty($data['store_ids'])) {
                $data['store_ids'] = 0;
            }elseif(in_array(0,$data['store_ids'])){
                $data['store_ids'] = 0;
            }else{
                $data['store_ids'] = implode(',',$data['store_ids']);
            }

            /** @var \SoW\Testimonial\Model\Testimonial $model */
            $model = $this->testimonialFactory->create();

            $id = $this->getRequest()->getParam('testimonial_id');
            if ($id) {
                $model->load($id);
                if (!$model->getId()) {
                    $this->messageManager->addErrorMessage(__('This testimonial no longer exists.'));
                    /** \Magento\Backend\Model\View\Result\Redirect $resultRedirect */
                    $resultRedirect = $this->resultRedirectFactory->create();

                    return $resultRedirect->setPath('*/*/');
                }
            }


            $model->setData($data);

            if (!$this->dataProcessor->validateRequireEntry($data)) {
                return $resultRedirect->setPath('*/*/edit', ['testimonial_id' => $model->getTestimonialId(), '_current' => true]);
            }

            try {
                $model->save();
                $this->messageManager->addSuccess(__('You saved the testimonial.'));
                $this->dataPersistor->clear('testimonial_testimonial');
                if ($this->getRequest()->getParam('back')) {
                    return $resultRedirect->setPath('*/*/edit', ['testimonial_id' => $model->getTestimonialId(), '_current' => true]);
                }
                return $resultRedirect->setPath('*/*/');
            } catch (LocalizedException $e) {
                $this->messageManager->addError($e->getMessage());
            } catch (\Exception $e) {
                $this->messageManager->addException($e, __('Something went wrong while saving the testimonial.'));
            }

            $this->dataPersistor->set('testimonial_testimonial', $data);
            return $resultRedirect->setPath('*/*/edit', ['testimonial_id' => $this->getRequest()->getParam('testimonial_id')]);
        }
        return $resultRedirect->setPath('*/*/');
    }
}

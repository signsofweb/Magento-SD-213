<?php
/**
 *
 * Copyright © 2013-2017 Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace SoW\Brand\Controller\Adminhtml\Manufacturer;

use Magento\Backend\App\Action;
use SoW\Brand\Model\Manufacturer;
use Magento\Framework\App\Request\DataPersistorInterface;
use Magento\Framework\Exception\LocalizedException;

class Save extends \Magento\Backend\App\Action
{
    /**
     * Authorization level of a basic admin session
     *
     * @see _isAllowed()
     */
    const ADMIN_RESOURCE = 'SoW_Brand::Manufacturer_manage';

    /**
     * @var PostDataProcessor
     */
    protected $dataProcessor;

    /**
     * @var DataPersistorInterface
     */
    protected $dataPersistor;

    /**
     * @var \Magento\Cms\Model\ManufacturerFactory
     */
    private $manufacturerFactory;

    /**
     * @param Action\Context $context
     * @param PostDataProcessor $dataProcessor
     * @param DataPersistorInterface $dataPersistor
     * @param \Magento\Cms\Model\ManufacturerFactory $manufacturerFactory
     */
    public function __construct(
        Action\Context $context,
        PostDataProcessor $dataProcessor,
        DataPersistorInterface $dataPersistor,
        \SoW\Brand\Model\ManufacturerFactory $manufacturerFactory = null
    ) {
        $this->dataProcessor = $dataProcessor;
        $this->dataPersistor = $dataPersistor;
        $this->manufacturerFactory = $manufacturerFactory
            ?: \Magento\Framework\App\ObjectManager::getInstance()->get(\SoW\Brand\Model\ManufacturerFactory::class);

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

            if (isset($data['manufacturer_status']) && $data['manufacturer_status'] === 'true') {
                $data['manufacturer_status'] = Manufacturer::STATUS_ENABLED;
            }

            if (empty($data['manufacturer_id'])) {
                $data['manufacturer_id'] = null;
            }
            if (empty($data['manufacturer_images'])) {
                $data['manufacturer_image'] = null;
            } else {
                if ($data['manufacturer_images'][0] && $data['manufacturer_images'][0]['name']){
                    $data['manufacturer_image'] = $data['manufacturer_images'][0]['name'];
                }
                else{
                    $data['manufacturer_image'] = null;
                }

            }
            if (empty($data['store_ids'])) {
                $data['store_ids'] = 0;
            }elseif(in_array(0,$data['store_ids'])){
                $data['store_ids'] = 0;
            }else{
                $data['store_ids'] = implode(',',$data['store_ids']);
            }

            /** @var \SoW\Brand\Model\Manufacturer $model */
            $model = $this->manufacturerFactory->create();

            $id = $this->getRequest()->getParam('manufacturer_id');
            if ($id) {
                $model->load($id);
                if (!$model->getId()) {
                    $this->messageManager->addErrorMessage(__('This manufacturer no longer exists.'));
                    /** \Magento\Backend\Model\View\Result\Redirect $resultRedirect */
                    $resultRedirect = $this->resultRedirectFactory->create();

                    return $resultRedirect->setPath('*/*/');
                }
            }


            $model->setData($data);

            if (!$this->dataProcessor->validateRequireEntry($data)) {
                return $resultRedirect->setPath('*/*/edit', ['manufacturer_id' => $model->getManufacturerId(), '_current' => true]);
            }

            try {
                $model->save();
                $this->messageManager->addSuccess(__('You saved the manufacturer.'));
                $this->dataPersistor->clear('brand_manufacturer');
                if ($this->getRequest()->getParam('back')) {
                    return $resultRedirect->setPath('*/*/edit', ['manufacturer_id' => $model->getManufacturerId(), '_current' => true]);
                }
                return $resultRedirect->setPath('*/*/');
            } catch (LocalizedException $e) {
                $this->messageManager->addError($e->getMessage());
            } catch (\Exception $e) {
                $this->messageManager->addException($e, __('Something went wrong while saving the manufacturer.'));
            }

            $this->dataPersistor->set('brand_manufacturer', $data);
            return $resultRedirect->setPath('*/*/edit', ['manufacturer_id' => $this->getRequest()->getParam('manufacturer_id')]);
        }
        return $resultRedirect->setPath('*/*/');
    }
}

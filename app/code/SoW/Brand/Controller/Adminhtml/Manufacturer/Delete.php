<?php

namespace SoW\Brand\Controller\Adminhtml\Manufacturer;

class Delete extends \Magento\Backend\App\Action
{
    /**
     * Delete action
     *
     * @return \Magento\Framework\Controller\ResultInterface
     */
    public function execute()
    {
        /** @var \Magento\Backend\Model\View\Result\Redirect $resultRedirect */
        $resultRedirect = $this->resultRedirectFactory->create();
        // check if we know what should be deleted
        $id = $this->getRequest()->getParam('manufacturer_id');
        if ($id) {
            try {
                // init model and delete
                $model = $this->_objectManager->create(\SoW\Brand\Model\Manufacturer::class);
                $model->load($id);
                $model->delete();
                // display success message
                $this->messageManager->addSuccess(__('You deleted the manufacturer.'));
                // go to grid
                return $resultRedirect->setPath('*/*/');
            } catch (\Exception $e) {
                // display error message
                $this->messageManager->addError($e->getMessage());
                // go back to edit form
                return $resultRedirect->setPath('*/*/edit', ['manufacturer_id' => $id]);
            }
        }
        // display error message
        $this->messageManager->addError(__('We can\'t find a manufacturer to delete.'));
        // go to grid
        return $resultRedirect->setPath('*/*/');
    }
}

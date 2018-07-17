<?php

namespace SoW\Blog\Controller\Adminhtml\Comment;

use Magento\Backend\App\Action;

class Delete extends Action
{
    public function execute()
    {
        $id = $this->getRequest()->getParam('comment_id');
        $resultRedirect = $this->resultRedirectFactory->create();
        if ($id) {
            try {
                $model = $this->_objectManager->create('SoW\Blog\Model\Comment');
                $model->load($id);
                $model->delete();
                $this->messageManager->addSuccess(__('The comment has been deleted.'));
                return $resultRedirect->setPath('blog/comment/index');
            } catch (\Exception $e) {
                $this->messageManager->addError($e->getMessage());
                return $resultRedirect->setPath('blog/comment/edit', ['comment_id' => $id]);
            }
        }
        $this->messageManager->addError(__('We can\'t find a comment to delete.'));
        return $resultRedirect->setPath('blog/comment/index');
    }

    protected function _isAllowed()
    {
        return $this->_authorization->isAllowed('SoW_Blog::delete_comment');
    }
}

<?php

namespace SoW\Blog\Controller\Adminhtml\Category;

class NewAction extends \SoW\Blog\Controller\Adminhtml\Blog
{
    public function execute()
    {
        $this->_forward('edit');
    }

    protected function _isAllowed()
    {
        return $this->_authorization->isAllowed('SoW_Blog::edit_category');
    }
}

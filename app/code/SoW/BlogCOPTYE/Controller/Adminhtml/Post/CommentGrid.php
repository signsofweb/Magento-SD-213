<?php
/**
 * Fieldthemes
 * 
 * NOTICE OF LICENSE
 * 
 * This source file is subject to the Fieldthemes.com license that is
 * available through the world-wide-web at this URL:
 * http://www.fieldthemes.com/license-agreement.html
 * 
 * DISCLAIMER
 * 
 * Do not edit or add to this file if you wish to upgrade this extension to newer
 * version in the future.
 * 
 * @category   Fieldthemes
 * @package    Field_Blog
 * @copyright  Copyright (c) 2014 Fieldthemes (http://www.fieldthemes.com/)
 * @license    http://www.fieldthemes.com/LICENSE-1.0.html
 */
namespace Field\Blog\Controller\Adminhtml\Post;

class CommentGrid extends \Magento\Catalog\Controller\Adminhtml\Product
{
    /**
     * @var \Magento\Framework\View\Result\LayoutFactory
     */
    protected $resultLayoutFactory;

    /**
     * @param \Magento\Backend\App\Action\Context
     * @param \Magento\Catalog\Controller\Adminhtml\Product\Builder
     * @param \Magento\Framework\View\Result\LayoutFactory
     */
    public function __construct(
        \Magento\Backend\App\Action\Context $context,
        \Magento\Catalog\Controller\Adminhtml\Product\Builder $productBuilder,
        \Magento\Framework\View\Result\LayoutFactory $resultLayoutFactory
    ) {
        parent::__construct($context, $productBuilder);
        $this->resultLayoutFactory = $resultLayoutFactory;
    }

    /**
     * Get upsell products grid
     *
     * @return \Magento\Framework\View\Result\Layout
     */
    public function execute()
    {   
        $id = $this->getRequest()->getparam('post_id');
        $post = $this->_objectManager->create('Field\Blog\Model\Post');
        $post->load($id);
        $registry = $this->_objectManager->get('Magento\Framework\Registry');
        $registry->register("current_post", $post);

        $this->productBuilder->build($this->getRequest());
        $resultLayout = $this->resultLayoutFactory->create();
        $resultLayout->getLayout()->getBlock('field.blog.edit.tab.comments')
            ->setProductsUpsell($this->getRequest()->getPost('comments', null));
        return $resultLayout;
    }
     /**
     * Check for is allowed
     *
     * @return boolean
     */
    protected function _isAllowed()
    {
        return $this->_authorization->isAllowed('Field_Blog::posts');
    }
}

<?php
namespace Sow\Base\Controller\Index;

use Magento\Framework\Controller\ResultFactory;

class Quickview extends \Magento\Catalog\Controller\Product\View
{
    public function execute()
    {
        if($this->getRequest()->getParam('is_redirect') && ($productId = (int) $this->getRequest()->getParam('id'))){
            $product = $this->_initProduct();
            $resultRedirect = $this->resultFactory->create(ResultFactory::TYPE_REDIRECT);
            $resultRedirect->setUrl($product->getProductUrl());
            return $resultRedirect;
        }else{
            return parent::execute();
        }
    }
}
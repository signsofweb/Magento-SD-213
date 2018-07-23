<?php

namespace SoW\Instagram\Block\Adminhtml\Instagram\Edit;

use Magento\Backend\Block\Widget\Context;

class GenericButton
{

    protected $context;
    protected $instagramFactory;

    public function __construct(
        Context $context,
        \SoW\Instagram\Model\InstagramFactory $instagramFactory
    )
    {
        $this->context = $context;
        $this->instagramFactory = $instagramFactory;
    }

    /**
     * Return Instagram ID
     */
    public function getInstagramId()
    {
        $id = $this->context->getRequest()->getParam('instagram_id');
        $instagram = $this->instagramFactory->create()->load($id);
        if ($instagram->getId())
            return $id;
        return null;
    }

    /**
     * Generate url by route and parameters
     */
    public function getUrl($route = '', $params = [])
    {
        return $this->context->getUrlBuilder()->getUrl($route, $params);
    }
}

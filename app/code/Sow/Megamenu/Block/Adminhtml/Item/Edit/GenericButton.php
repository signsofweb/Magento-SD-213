<?php

namespace Sow\Megamenu\Block\Adminhtml\Item\Edit;

use Magento\Backend\Block\Widget\Context;

class GenericButton
{

    protected $context;
    protected $itemFactory;

    public function __construct(
        Context $context,
        \Sow\Megamenu\Model\ItemFactory $itemFactory
    )
    {
        $this->context = $context;
        $this->itemFactory = $itemFactory;
    }

    /**
     * Return Item ID
     */
    public function getItemId()
    {
        $id = $this->context->getRequest()->getParam('item_id');
        $item = $this->itemFactory->create()->load($id);
        if ($item->getId())
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

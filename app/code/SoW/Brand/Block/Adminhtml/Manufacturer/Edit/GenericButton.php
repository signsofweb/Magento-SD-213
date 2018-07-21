<?php

namespace SoW\Brand\Block\Adminhtml\Manufacturer\Edit;

use Magento\Backend\Block\Widget\Context;

class GenericButton
{

    protected $context;
    protected $manufacturerFactory;

    public function __construct(
        Context $context,
        \SoW\Brand\Model\ManufacturerFactory $manufacturerFactory
    )
    {
        $this->context = $context;
        $this->manufacturerFactory = $manufacturerFactory;
    }

    /**
     * Return Manufacturer ID
     */
    public function getManufacturerId()
    {
        $id = $this->context->getRequest()->getParam('manufacturer_id');
        $manufacturer = $this->manufacturerFactory->create()->load($id);
        if ($manufacturer->getId())
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

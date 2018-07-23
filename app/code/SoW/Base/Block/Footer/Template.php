<?php

namespace SoW\Base\Block\Footer;

class Template extends \Magento\Framework\View\Element\Template {
    public $_coreRegistry;

    public function __construct(
        \Magento\Backend\Block\Template\Context $context,
        \Magento\Framework\Registry $coreRegistry,
        array $data = []
    ) {
        $this->_coreRegistry = $coreRegistry;
        parent::__construct($context, $data);
    }

}
?>
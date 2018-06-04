<?php
namespace Sow\Exilee\Controller\Table;

use Magento\Framework\App\Action\Context;


class Ajax extends \Magento\Framework\App\Action\Action{
    protected $_catalogSession;

    public function __construct(
        Context $context,
        \Magento\Catalog\Model\Session $catalogSession
    )
    {
        $this->_catalogSession = $catalogSession;
        parent::__construct($context);
    }

    public function execute()
    {

    }
}
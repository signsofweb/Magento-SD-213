<?php
namespace Sow\PopupNewsletter\Block;
use Sow\PopupNewsletter\Model\Config;
use Magento\Framework\UrlInterface;

class PopupNewsletter extends \Magento\Newsletter\Block\Subscribe{

    protected $config;

    protected $_UrlInterface;

    public function __construct(
        \Magento\Framework\View\Element\Template\Context $context,
        Config $config,
        UrlInterface $urlInterface
    )
    {
        $this->config = $config;
        $this->_UrlInterface = $urlInterface;
        parent::__construct($context);
    }
    public function getPopupConfig(){
        return $this->config;
    }
    public function getBgImage(){
        return "url('".$this->_UrlInterface->getUrl('', ['_secure' => true]).'pub/media/sow/popupnewsletter/'.$this->config->getBgImage()."')";

    }
}
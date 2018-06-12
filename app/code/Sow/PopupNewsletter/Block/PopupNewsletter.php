<?php
namespace Sow\PopupNewsletter\Block;
use Sow\PopupNewsletter\Model\Config;


class PopupNewsletter extends \Magento\Newsletter\Block\Subscribe{

    protected $config;
    protected $urlBuilder;

    public function __construct(
        \Magento\Framework\View\Element\Template\Context $context,
        Config $config
    )
    {
        $this->urlBuilder = $context->getUrlBuilder();
        $this->config = $config;
        parent::__construct($context);
    }
    public function getPopupConfig(){
        return $this->config;
    }
    public function getBgImage(){
        return "url('".$this->urlBuilder->getUrl('', ['_secure' => true]).'pub/media/sow/popupnewsletter/'.$this->config->getBgImage()."')";

    }
}
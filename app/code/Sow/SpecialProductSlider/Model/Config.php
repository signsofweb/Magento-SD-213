<?php
namespace Sow\SpecialProductSlider\Model;
use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Store\Model\ScopeInterface;

class Config {

    /**
     * Configuration path to Popup Newsletter is enabled flag
     */
    const XML_PATH_ENABLED ='specialproductslider/general/is_enabled';

    private $scopeConfigInterface;

    /**
     * @param ScopeConfigInterface ScopeConfigInterface
     */
    public function __construct(
        ScopeConfigInterface $scopeConfigInterface
    ) {
        $this->scopeConfigInterface = $scopeConfigInterface;
    }

    /**
     * Check if popup is enabled
     *
     * @return bool
     */
    public function getStatus(){
        if($this->scopeConfigInterface->getValue(self::XML_PATH_ENABLED,ScopeInterface::SCOPE_WEBSITE) ==1){
            return true;
        }
        return false;
    }


}
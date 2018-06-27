<?php
namespace Sow\SpecialProductSlider\Model;
use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Store\Model\ScopeInterface;

class Config {

    /**
     * Configuration path to Special product slider is enabled flag
     */
    const XML_PATH_CONFIG_ENABLED ='specialproductslider/general/is_enabled';

    /**
     * Configuration path to Special product slider title
     */
    const XML_PATH_CONFIG_TITLE ='specialproductslider/general/title';
    /**
     * Configuration path to Special product slider title
     */
    const XML_PATH_CONFIG_DESCRIPTION ='specialproductslider/general/description';

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
        if($this->scopeConfigInterface->getValue(self::XML_PATH_CONFIG_ENABLED,ScopeInterface::SCOPE_WEBSITE) ==1){
            return true;
        }
        return false;
    }
    public function getTitle(){
        return $this->scopeConfigInterface->getValue(self::XML_PATH_CONFIG_TITLE,ScopeInterface::SCOPE_WEBSITE);
    }
    public function getDescription(){
        return $this->scopeConfigInterface->getValue(self::XML_PATH_CONFIG_DESCRIPTION,ScopeInterface::SCOPE_WEBSITE);
    }


}
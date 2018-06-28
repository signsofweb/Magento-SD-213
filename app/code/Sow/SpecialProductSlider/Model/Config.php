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
     * Configuration path to Special product slider description
     */
    const XML_PATH_CONFIG_DESCRIPTION ='specialproductslider/general/description';
    /**
     * Configuration path to Special product slider vertical
     */
    const XML_PATH_CONFIG_IS_VERTICAL ='specialproductslider/slidesettings/is_vertical';
    /**
     * Configuration path to Special product slider product_count
     */
    const XML_PATH_CONFIG_PRODUCT_COUNT ='specialproductslider/slidesettings/product_count';
    /**
     * Configuration path to Special product slider max_item
     */
    const XML_PATH_CONFIG_MAX_ITEM ='specialproductslider/slidesettings/max_item';
    /**
     * Configuration path to Special product slider medium_item
     */
    const XML_PATH_CONFIG_MEDIUM_ITEM ='specialproductslider/slidesettings/medium_item';
    /**
     * Configuration path to Special product slider min_item
     */
    const XML_PATH_CONFIG_MIN_ITEM ='specialproductslider/slidesettings/min_item';
    /**
     * Configuration path to Special product slider row_item
     */
    const XML_PATH_CONFIG_ROW_ITEM ='specialproductslider/slidesettings/row_item';
    /**
     * Configuration path to Special product slider dots
     */
    const XML_PATH_CONFIG_DOTS ='specialproductslider/slidesettings/dots';
    /**
     * Configuration path to Special product slider nav
     */
    const XML_PATH_CONFIG_NAV ='specialproductslider/slidesettings/nav';
    /**
     * Configuration path to Special product slider loop
     */
    const XML_PATH_CONFIG_LOOP ='specialproductslider/slidesettings/loop';
    /**
     * Configuration path to Special product slider autoplayhoverpause
     */
    const XML_PATH_CONFIG_AUTO_PLAY_HOVER_PAUSE ='specialproductslider/slidesettings/autoplayhoverpause';
    /**
     * Configuration path to Special product slider min_item
     */
    const XML_PATH_CONFIG_AUTO_PLAY ='specialproductslider/slidesettings/autoplay';
    /**
     * Configuration path to Special product slider autoplayspeed
     */
    const XML_PATH_CONFIG_AUTO_PLAY_SPEED ='specialproductslider/slidesettings/autoplayspeed';

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
    public function getIsVertical(){
        if($this->scopeConfigInterface->getValue(self::XML_PATH_CONFIG_IS_VERTICAL,ScopeInterface::SCOPE_WEBSITE) ==1){
            return true;
        }
        return false;
    }
    public function getProductCount(){
        return $this->scopeConfigInterface->getValue(self::XML_PATH_CONFIG_PRODUCT_COUNT,ScopeInterface::SCOPE_WEBSITE);
    }
    public function getMaxItem(){
        return $this->scopeConfigInterface->getValue(self::XML_PATH_CONFIG_MAX_ITEM,ScopeInterface::SCOPE_WEBSITE);
    }
    public function getMediumItem(){
        return $this->scopeConfigInterface->getValue(self::XML_PATH_CONFIG_MEDIUM_ITEM,ScopeInterface::SCOPE_WEBSITE);
    }
    public function getMinItem(){
        return $this->scopeConfigInterface->getValue(self::XML_PATH_CONFIG_MIN_ITEM,ScopeInterface::SCOPE_WEBSITE);
    }
    public function getRowItem(){
        return $this->scopeConfigInterface->getValue(self::XML_PATH_CONFIG_ROW_ITEM,ScopeInterface::SCOPE_WEBSITE);
    }
    public function getDots(){
        if($this->scopeConfigInterface->getValue(self::XML_PATH_CONFIG_DOTS,ScopeInterface::SCOPE_WEBSITE) ==1){
            return true;
        }
        return false;
    }
    public function getNav(){
        if($this->scopeConfigInterface->getValue(self::XML_PATH_CONFIG_NAV,ScopeInterface::SCOPE_WEBSITE) ==1){
            return true;
        }
        return false;
    }
    public function getLoop(){
        if($this->scopeConfigInterface->getValue(self::XML_PATH_CONFIG_LOOP,ScopeInterface::SCOPE_WEBSITE) ==1){
            return true;
        }
        return false;
    }
    public function getAutoPlayHoverPause(){
        if($this->scopeConfigInterface->getValue(self::XML_PATH_CONFIG_AUTO_PLAY_HOVER_PAUSE,ScopeInterface::SCOPE_WEBSITE) ==1){
            return true;
        }
        return false;
    }
    public function getAutoPlay(){
        if($this->scopeConfigInterface->getValue(self::XML_PATH_CONFIG_AUTO_PLAY,ScopeInterface::SCOPE_WEBSITE) ==1){
            return true;
        }
        return false;
    }
    public function getAutoPlaySpeed(){
        return $this->scopeConfigInterface->getValue(self::XML_PATH_CONFIG_AUTO_PLAY_SPEED,ScopeInterface::SCOPE_WEBSITE);
    }

}
<?php
namespace SoW\PopupNewsletter\Model;
use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Store\Model\ScopeInterface;

class Config {

    /**
     * Configuration path to Popup Newsletter is enabled flag
     */
    const XML_PATH_ENABLED ='popupnewsletter/popup_newsletter/status';
    /**
     * Configuration path to Popup Newsletter title
     */
    const XML_PATH_POPUP_TITLE ='popupnewsletter/popup_newsletter/popup_title';
    /**
     * Configuration path to Popup Newsletter description
     */
    const XML_PATH_POPUP_DESCRIPTION ='popupnewsletter/popup_newsletter/popup_description';
    /**
     * Configuration path to Popup Newsletter width
     */
    const XML_PATH_POPUP_WIDTH ='popupnewsletter/popup_newsletter/popup_width';
    /**
     * Configuration path to Popup Newsletter height
     */
    const XML_PATH_POPUP_HEIGHT ='popupnewsletter/popup_newsletter/popup_height';
    /**
     * Configuration path to Popup Newsletter background image
     */
    const XML_PATH_POPUP_BG_IMAGE ='popupnewsletter/popup_newsletter/bg_image';
    /**
     * Configuration path to Popup Newsletter static block
     */
    const XML_PATH_POPUP_STATIC_BLOCK ='popupnewsletter/popup_newsletter/static_block';
    /**
     * Configuration path to Popup Newsletter cookie time
     */
    const XML_PATH_POPUP_COOKIE_TIME ='popupnewsletter/popup_newsletter/cookie_time';
    /**
     * @var ScopeConfigInterface
     */
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

    /**
     * Get popup title
     *
     * @return string
     */
    public function getTitle(){
        return $this->scopeConfigInterface->getValue(self::XML_PATH_POPUP_TITLE,ScopeInterface::SCOPE_WEBSITE);
    }

    /**
     * Get popup description
     *
     * @return string
     */
    public function getDescription(){
        return $this->scopeConfigInterface->getValue(self::XML_PATH_POPUP_DESCRIPTION,ScopeInterface::SCOPE_WEBSITE);
    }

    /**
     * Get popup width
     *
     * @return string
     */
    public function getWidth(){
        return $this->scopeConfigInterface->getValue(self::XML_PATH_POPUP_WIDTH,ScopeInterface::SCOPE_WEBSITE);
    }

    /**
     * Get popup width
     *
     * @return string
     */
    public function getHeight(){
        return $this->scopeConfigInterface->getValue(self::XML_PATH_POPUP_HEIGHT,ScopeInterface::SCOPE_WEBSITE);
    }

    /**
     * Get popup background url
     *
     * @return string
     */
    public function getBgImage(){
        return $this->scopeConfigInterface->getValue(self::XML_PATH_POPUP_BG_IMAGE,ScopeInterface::SCOPE_WEBSITE);
    }

    /**
     * Get popup static block id
     *
     * @return string
     */
    public function getStaticBlockId(){
        return $this->scopeConfigInterface->getValue(self::XML_PATH_POPUP_STATIC_BLOCK,ScopeInterface::SCOPE_WEBSITE);
    }

    /**
     * Get popup cookie time
     *
     * @return string
     */
    public function getCookieTime(){
        return $this->scopeConfigInterface->getValue(self::XML_PATH_POPUP_COOKIE_TIME,ScopeInterface::SCOPE_WEBSITE);
    }


}
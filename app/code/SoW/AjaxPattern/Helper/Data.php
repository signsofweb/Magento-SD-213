<?php
namespace SoW\AjaxPattern\Helper;

use Magento\Framework\App\Helper\Context;

class Data extends \Magento\Framework\App\Helper\AbstractHelper
{

    protected $_jsonEncoder;

    protected $postHelper;

    protected $_urlBuilder;

    protected $urlEncoder;

    protected $_itemCollection;

    protected $_itemCollectionFactory;

    private $_storeManager;

    protected $_customerSession;

    protected $_customerId = null;

    protected $_customerVisitor;

    protected $_catalogProductVisibility;

    protected $_catalogSession;

    public function __construct(
        Context $context,
        \Magento\Framework\Json\EncoderInterface $jsonEncoder,
        \Magento\Framework\Data\Helper\PostHelper $postHelper,
        \Magento\Catalog\Model\ResourceModel\Product\Compare\Item\CollectionFactory $itemCollectionFactory,
        \Magento\Store\Model\StoreManagerInterface $storeManager,
        \Magento\Customer\Model\Session $customerSession,
        \Magento\Customer\Model\Visitor $customerVisitor,
        \Magento\Catalog\Model\Product\Visibility $catalogProductVisibility,
        \Magento\Catalog\Model\Session $catalogSession
    )
    {
        $this->_jsonEncoder = $jsonEncoder;
        $this->postHelper = $postHelper;
        $this->_urlBuilder = $context->getUrlBuilder();
        $this->urlEncoder = $context->getUrlEncoder();
        $this->_itemCollectionFactory = $itemCollectionFactory;
        $this->_storeManager = $storeManager;
        $this->_customerSession = $customerSession;
        $this->_customerVisitor = $customerVisitor;
        $this->_catalogProductVisibility = $catalogProductVisibility;
        $this->_catalogSession = $catalogSession;
        parent::__construct($context);
    }

    public function getDataAction($id){
        $data = 'add';
        $ids = $this->getItemCollection()->getAllIds();
        foreach ($ids as $item_id){
            if ($id == $item_id){
                $data = 'remove';
            }
        }
        return $data;
    }
    public function getCompareData(\Magento\Catalog\Api\Data\ProductInterface $product)
    {
        $config = [
            'id' => $product->getId(),
            'name' => $product->getName(),
            'product_url' => $product->getUrlModel()->getUrl($product),
            'remove_data' => $this->getAjaxPostDataRemove($product),
            'add_data'    => $this->getAjaxPostDataParams($product)
        ];
        return $this->_jsonEncoder->encode($config);
    }

    public function getAjaxPostDataParams($product)
    {
        return $this->postHelper->getPostData($this->getAjaxAddUrl(), ['product' => $product->getId()]);
    }

    public function getAjaxAddUrl()
    {
        return $this->_getUrl('ajaxpattern/product_compare/Add');
    }

    public function getAjaxPostDataRemove($product)
    {
        $listCleanUrl = $this->getEncodedUrl($this->_getUrl('catalog/product_compare'));
        $data = [
            \Magento\Framework\App\ActionInterface::PARAM_NAME_URL_ENCODED => $listCleanUrl,
            'product' => $product->getId()
        ];
        return $this->postHelper->getPostData($this->getAjaxRemoveUrl(), $data);
    }

    public function getAjaxRemoveUrl()
    {
        return $this->_getUrl('catalog/product_compare/Remove');
    }

    public function getEncodedUrl($url = null)
    {
        if (!$url) {
            $url = $this->_urlBuilder->getCurrentUrl();
        }
        return $this->urlEncoder->encode($url);
    }
    public function getItemCollection()
    {
        if (!$this->_itemCollection) {
            // cannot be placed in constructor because of the cyclic dependency which cannot be fixed with proxy class
            // collection uses this helper in constructor when calling isEnabledFlat() method
            $this->_itemCollection = $this->_itemCollectionFactory->create();
            $this->_itemCollection->useProductItem(true)->setStoreId($this->_storeManager->getStore()->getId());

            if ($this->_customerSession->isLoggedIn()) {
                $this->_itemCollection->setCustomerId($this->_customerSession->getCustomerId());
            } elseif ($this->_customerId) {
                $this->_itemCollection->setCustomerId($this->_customerId);
            } else {
                $this->_itemCollection->setVisitorId($this->_customerVisitor->getId());
            }

            $this->_itemCollection->setVisibility($this->_catalogProductVisibility->getVisibleInSiteIds());

            /* Price data is added to consider item stock status using price index */
            $this->_itemCollection->addPriceData();

            $this->_itemCollection->addAttributeToSelect('name')->addUrlRewrite()->load();

            /* update compare items count */
            $this->_catalogSession->setCatalogCompareItemsCount(count($this->_itemCollection));
        }

        return $this->_itemCollection;
    }
    public function setCustomerId($id)
    {
        $this->_customerId = $id;
        return $this;
    }

}
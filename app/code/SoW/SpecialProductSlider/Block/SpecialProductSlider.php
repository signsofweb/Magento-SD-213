<?php
namespace SoW\SpecialProductSlider\Block;
use SoW\SpecialProductSlider\Block\Product\AbstractProduct;
use SoW\SpecialProductSlider\Model\Config;

class SpecialProductSlider extends AbstractProduct{
    protected $_template = 'specialproductslider.phtml';
    protected $_config;
    protected $_urlHelper;
    public function __construct(
        \Magento\Catalog\Block\Product\Context $context,
        \Magento\Catalog\Model\ResourceModel\Product\CollectionFactory $productCollectionFactory,
        \Magento\Catalog\Model\Product\Visibility $catalogProductVisibility,
        \Magento\Framework\App\Http\Context $httpContext,
        \Magento\Rule\Model\Condition\Sql\Builder $sqlBuilder,
        \Magento\CatalogWidget\Model\Rule $rule, \Magento\Widget\Helper\Conditions $conditionsHelper,
        \Magento\Framework\Url\Helper\Data $urlHelper,
        Config $config,
        array $data = []
    )
    {
        $this->_config = $config;
        $this->_urlHelper = $urlHelper;
        parent::__construct($context, $productCollectionFactory, $catalogProductVisibility, $httpContext, $sqlBuilder, $rule, $conditionsHelper, $data);
    }
    /*
     * Get Status
     * return bolone
     * */

    public function getStatus(){
        return $this->_config->getStatus();
    }

    /**
     * Get product collection
     *
     * @return $this
     */
    public function getProductCollection()
    {
        $objectManager   = \Magento\Framework\App\ObjectManager::getInstance();
        $visibleProducts = $objectManager->create(
            '\Magento\Catalog\Model\Product\Visibility'
        )->getVisibleInCatalogIds();
        $collection      = $objectManager->create(
            '\Magento\Catalog\Model\ResourceModel\Product\Collection'
        )->setVisibility($visibleProducts);
        $collection      = $this->_addProductAttributesAndPrices($collection)
            ->addAttributeToFilter(
                'special_from_date',
                ['date' => true, 'to' => $this->getEndOfDayDate()], 'left'
            )->addAttributeToFilter(
            'special_to_date', ['or' => [       0 => ['date' => true, 'from' => $this->getStartOfDayDate()],
                                                        1 => ['is' => new \Zend_Db_Expr('null')],]], 'left'
            )
            ->addAttributeToSort(
                'news_from_date', 'ASC'
            )->addStoreFilter($this->getStoreId())->setPageSize(
                $this->getProductCount()
            );
        return $collection;
    }


    public function getProductCacheKey()
    {
        return 'sow_special_product_slider';
    }

    public function getTitle(){
        return $this->_config->getTitle();
    }
    public function getDescription(){
        return ($this->_config->getDescription())? $this->_config->getDescription() : 10;
    }

    public function getProductCount(){
        return $this->_config->getProductCount();
    }
    public function getDataSlider(){
        $options = array(
            'item_md' => $this->_config->getMaxItem(),
            'item_sm' => $this->_config->getMediumItem(),
            'item_xs' => $this->_config->getMinItem(),
            'dots' => $this->_config->getDots(),
            'nav'=> $this->_config->getNav(),
            'loop'=> $this->_config->getLoop(),
            'autoplayHoverPause'=> $this->_config->getAutoPlayHoverPause(),
            'autoplaySpeed'=> $this->_config->getAutoPlaySpeed(),
            'autoplay' => $this->_config->getAutoPlay()
        );
        return json_encode($options);
    }

    public function getAddToCartPostParams(\Magento\Catalog\Model\Product $product)
    {
        $url = $this->getAddToCartUrl($product);
        return [
            'action' => $url,
            'data' => [
                'product' => $product->getEntityId(),
                \Magento\Framework\App\ActionInterface::PARAM_NAME_URL_ENCODED =>
                    $this->_urlHelper->getEncodedUrl($url),
            ]
        ];
    }
}
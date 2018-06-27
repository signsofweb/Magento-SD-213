<?php
namespace Sow\SpecialProductSlider\Block;
use Sow\Base\Block\Products\AbstractProduct;
use Sow\SpecialProductSlider\Model\Config;

class SpecialProductSlider extends AbstractProduct{
    protected $_template = 'specialproductslider.phtml';
    protected $_config;
    public function __construct(
        \Magento\Catalog\Block\Product\Context $context,
        \Magento\Catalog\Model\ResourceModel\Product\CollectionFactory $productCollectionFactory,
        \Magento\Catalog\Model\Product\Visibility $catalogProductVisibility,
        \Magento\Framework\App\Http\Context $httpContext,
        \Magento\Rule\Model\Condition\Sql\Builder $sqlBuilder,
        \Magento\CatalogWidget\Model\Rule $rule, \Magento\Widget\Helper\Conditions $conditionsHelper,
        Config $config,
        array $data = []
    )
    {
        $this->_config = $config;
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
                'news_from_date', 'desc'
            )->addStoreFilter($this->getStoreId())->setPageSize(
                $this->getProductsCount()
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
        return $this->_config->getDescription();
    }
    public function getProductsCount(){
        return 10;
    }
    public function getDataSlider(){
        $options = array(
            'item_md' => 5,
            'item_sm' => 5,
            'item_xs' => 1,
            'dots' => 1,
            'nav'=> 1,
            'loop'=> 0,
            'autoplayHoverPause'=> 0,
            'autoplaySpeed'=> 3000,
            'autoplay' => 0,
        );
        return json_encode($options);
    }


}
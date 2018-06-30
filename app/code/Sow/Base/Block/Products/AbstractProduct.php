<?php

namespace Sow\Base\Block\Products;

/**
 * Catalog Products List widget block
 * Class ProductsList
 */
class AbstractProduct extends \Magento\Catalog\Block\Product\AbstractProduct implements \Magento\Widget\Block\BlockInterface
{
	/**
	 * Default value for products count that will be shown
	 */
	const DEFAULT_PRODUCTS_COUNT = 5;

	/**
	 * Name of request parameter for page number value
	 */
	const PAGE_VAR_NAME = 'np';

	/**
	 * Default value for products per page
	 */
	const DEFAULT_PRODUCTS_PER_PAGE = 5;

	/**
	 * Default value whether show pager or not
	 */
	const DEFAULT_SHOW_PAGER = false;

	/**
	 * Instance of pager block
	 *
	 * @var \Magento\Catalog\Block\Product\Widget\Html\Pager
	 */
	protected $pager;

	/**
	 * @var \Magento\Framework\App\Http\Context
	 */
	protected $httpContext;

	/**
	 * Catalog product visibility
	 *
	 * @var \Magento\Catalog\Model\Product\Visibility
	 */
	protected $catalogProductVisibility;

	/**
	 * Product collection factory
	 *
	 * @var \Magento\Catalog\Model\ResourceModel\Product\CollectionFactory
	 */
	protected $productCollectionFactory;

	/**
	 * @var \Magento\Rule\Model\Condition\Sql\Builder
	 */
	protected $sqlBuilder;

	/**
	 * @var \Magento\CatalogWidget\Model\Rule
	 */
	protected $rule;

	/**
	 * @var \Magento\Widget\Helper\Conditions
	 */
	protected $conditionsHelper;

	protected $_allOptions;

	public $productCacheKey = 'sow_product_slider_cache';

	/**
	 * @param \Magento\Catalog\Block\Product\Context $context
	 * @param \Magento\Catalog\Model\ResourceModel\Product\CollectionFactory $productCollectionFactory
	 * @param \Magento\Catalog\Model\Product\Visibility $catalogProductVisibility
	 * @param \Magento\Framework\App\Http\Context $httpContext
	 * @param \Magento\Rule\Model\Condition\Sql\Builder $sqlBuilder
	 * @param \Magento\CatalogWidget\Model\Rule $rule
	 * @param \Magento\Widget\Helper\Conditions $conditionsHelper
	 * @param array $data
	 */
	public function __construct(
		\Magento\Catalog\Block\Product\Context $context,
		\Magento\Catalog\Model\ResourceModel\Product\CollectionFactory $productCollectionFactory,
		\Magento\Catalog\Model\Product\Visibility $catalogProductVisibility,
		\Magento\Framework\App\Http\Context $httpContext,
		\Magento\Rule\Model\Condition\Sql\Builder $sqlBuilder,
		\Magento\CatalogWidget\Model\Rule $rule,
		\Magento\Widget\Helper\Conditions $conditionsHelper,
		array $data = []
	)
	{
		$this->productCollectionFactory = $productCollectionFactory;
		$this->catalogProductVisibility = $catalogProductVisibility;
		$this->httpContext              = $httpContext;
		$this->sqlBuilder               = $sqlBuilder;
		$this->rule                     = $rule;
		$this->conditionsHelper         = $conditionsHelper;
		parent::__construct(
			$context,
			$data
		);
	}

	/**
	 * {@inheritdoc}
	 */
	protected function _construct()
	{
		parent::_construct();

		$this->setData('cache_lifetime',86400);
		$this->addColumnCountLayoutDepend('empty', 6)
			->addColumnCountLayoutDepend('1column', 5)
			->addColumnCountLayoutDepend('2columns-left', 4)
			->addColumnCountLayoutDepend('2columns-right', 4)
			->addColumnCountLayoutDepend('3columns', 3);
		$this->addData([
			'cache_lifetime' => $this->getCacheLifetime(),
			'cache_tags'     => [\Magento\Catalog\Model\Product::CACHE_TAG,],
			'cache_key'      => $this->getProductCacheKey(),
		]);
	}

	public function getProductCacheKey()
	{
		return $this->productCacheKey;
	}
	/**
	 * Get key pieces for caching block content
	 *
	 * @return array
	 */
	public function getCacheKeyInfo()
	{
		$conditions = $this->getData('conditions')
			? $this->getData('conditions')
			: $this->getData('conditions_encoded');

		return [
			'CATALOG_PRODUCTS_LIST_WIDGET',
			$this->_storeManager->getStore()->getId(),
			$this->_design->getDesignTheme()->getId(),
			$this->httpContext->getValue(\Magento\Customer\Model\Context::CONTEXT_GROUP),
			intval($this->getRequest()->getParam(self::PAGE_VAR_NAME, 1)),
			$this->getProductsPerPage(),
			$conditions
		];
	}

	/**
	 * {@inheritdoc}
	 * @SuppressWarnings(PHPMD.NPathComplexity)
	 */
	public function getProductPriceHtml(
		\Magento\Catalog\Model\Product $product,
		$priceType = null,
		$renderZone = \Magento\Framework\Pricing\Render::ZONE_ITEM_LIST,
		array $arguments = []
	)
	{
		if (!isset($arguments['zone'])) {
			$arguments['zone'] = $renderZone;
		}
		$arguments['price_id'] = isset($arguments['price_id']) ? $arguments['price_id'] : 'old-price-' . $product->getId() . '-' . $priceType;
		$arguments['include_container'] = isset($arguments['include_container']) ? $arguments['include_container'] : true;
		$arguments['display_minimal_price'] = isset($arguments['display_minimal_price']) ? $arguments['display_minimal_price'] : true;

		/** @var \Magento\Framework\Pricing\Render $priceRender */
		$priceRender = $this->getLayout()->getBlock('product.price.render.default');

		$price = '';
		if ($priceRender) {
			$price = $priceRender->render(
				\Magento\Catalog\Pricing\Price\FinalPrice::PRICE_CODE,
				$product,
				$arguments
			);
		}

		return $price;
	}

	/**
	 * {@inheritdoc}
	 */
	protected function _beforeToHtml()
	{
		$this->setProductCollection($this->createCollection());
		$this->getData();
		return parent::_beforeToHtml();
	}

	/**
	 * Prepare and return product collection
	 *
	 * @return \Magento\Catalog\Model\ResourceModel\Product\Collection
	 */
	public function createCollection()
	{
		/** @var $collection \Magento\Catalog\Model\ResourceModel\Product\Collection */
		$collection = $this->productCollectionFactory->create();
		$collection->setVisibility($this->catalogProductVisibility->getVisibleInCatalogIds());

		$collection = $this->_addProductAttributesAndPrices($collection)
			->addStoreFilter()
			->setPageSize($this->getPageSize())
			->setCurPage($this->getRequest()->getParam(self::PAGE_VAR_NAME, 1));

		$conditions = $this->getConditions();
		$conditions->collectValidatedAttributes($collection);
		$this->sqlBuilder->attachConditionToCollection($collection, $conditions);

		return $collection;
	}

	/**
	 * @return \Magento\Rule\Model\Condition\Combine
	 */
	protected function getConditions()
	{
		$conditions = $this->getData('conditions_encoded')
			? $this->getData('conditions_encoded')
			: $this->getData('conditions');

		if ($conditions) {
			$conditions = $this->conditionsHelper->decode($conditions);
		}

		$this->rule->loadPost(['conditions' => $conditions]);

		return $this->rule->getConditions();
	}

	/**
	 * Retrieve how many products should be displayed
	 *
	 * @return int
	 */
	public function getProductsCount()
	{
		if ($this->hasData('products_count')) {
			return $this->getData('products_count');
		}

		if (null === $this->getData('products_count')) {
			$this->setData('products_count', self::DEFAULT_PRODUCTS_COUNT);
		}

		return $this->getData('products_count');
	}


	/**
	 * Return flag whether pager need to be shown or not
	 *
	 * @return bool
	 */
	public function showPager()
	{
		if (!$this->hasData('show_pager')) {
			$this->setData('show_pager', self::DEFAULT_SHOW_PAGER);
		}

		return (bool)$this->getData('show_pager');
	}

	/**
	 * Retrieve how many products should be displayed on page
	 *
	 * @return int
	 */
	protected function getPageSize()
	{
		return $this->showPager() ? $this->getProductsPerPage() : $this->getProductsCount();
	}

	/**
	 * Render pagination HTML
	 *
	 * @return string
	 */
	public function getPagerHtml()
	{
		if ($this->showPager() && $this->getProductCollection()->getSize() > $this->getProductsPerPage()) {
			if (!$this->pager) {
				$this->pager = $this->getLayout()->createBlock(
					'Magento\Catalog\Block\Product\Widget\Html\Pager',
					'widget.products.list.pager'
				);

				$this->pager->setUseContainer(true)
					->setShowAmounts(true)
					->setShowPerPage(false)
					->setPageVarName(self::PAGE_VAR_NAME)
					->setLimit($this->getProductsPerPage())
					->setTotalLimit($this->getProductsCount())
					->setCollection($this->getProductCollection());
			}
			if ($this->pager instanceof \Magento\Framework\View\Element\AbstractBlock) {
				return $this->pager->toHtml();
			}
		}

		return '';
	}
    public function getReviewsSummaryHtml(
        \Magento\Catalog\Model\Product $product,
        $templateType = true,
        $displayIfNoReviews = true
    ) {
        return $this->reviewRenderer->getReviewsSummaryHtml($product, $templateType, $displayIfNoReviews);
    }

	/**
	 * Return identifiers for produced content
	 *
	 * @return array
	 */
	public function getIdentities()
	{
		return [\Magento\Catalog\Model\Product::CACHE_TAG];
	}

	/**
	 * Get config value
	 *
	 * @return mixed|string
	 */

	public function getConfigValue($config)
	{
		return $this->getData($config) ? $this->getData($config) : '';
	}

	/**
	 * Get start of day
	 * @return mixed
	 */
	public function getStartOfDayDate()
	{
		$objectManager = \Magento\Framework\App\ObjectManager::getInstance();

		return $objectManager->create('\Magento\Framework\Stdlib\DateTime\DateTime')->date(null, '0:0:0');
	}

	/**
	 * Get end of day
	 * @return mixed
	 */
	public function getEndOfDayDate()
	{
		$objectManager = \Magento\Framework\App\ObjectManager::getInstance();

		return $objectManager->create('\Magento\Framework\Stdlib\DateTime\DateTime')->date(null, '23:59:59');
	}

	/**
	 * Get store id
	 * @return mixed
	 */
	public function getStoreId()
	{
		$objectManager = \Magento\Framework\App\ObjectManager::getInstance();
		return $objectManager->create('\Magento\Catalog\Block\Product\Context')->getStoreManager()->getStore()->getId();
	}
    public function checkProductIsNew($_product = null) {
        $from_date = $_product->getNewsFromDate();
        $to_date = $_product->getNewsToDate();
        $is_new = false;
        $today = strtotime("now");
        if ($from_date && $to_date) {
            $from_date = strtotime($from_date);
            $to_date = strtotime($to_date);
            if ($from_date <= $today && $to_date >= $today) {
                $is_new = true;
            }
        }
        elseif ($from_date && !$to_date) {
            $from_date = strtotime($from_date);
            if ($from_date <= $today) {
                $is_new = true;
            }
        }elseif (!$from_date && $to_date) {
            $to_date = strtotime($to_date);
            if ($to_date >= $today) {
                $is_new = true;
            }
        }
        return $is_new;
    }
    public function checkProductIsSale($_product = null){
        $specialprice = $_product->getSpecialPrice();
        $oldPrice = $_product->getPrice();
        $specialPriceFromDate = $_product->getSpecialFromDate();
        $specialPriceToDate = $_product->getSpecialToDate();
        $today =  time();
        if ($specialprice < $oldPrice) {
            if($today >= strtotime( $specialPriceFromDate) && $today <= strtotime($specialPriceToDate) || $today >= strtotime( $specialPriceFromDate) && is_null($specialPriceToDate)) {
               return true;
            }
        }
        return false;
    }
    public function checkCountDownTimer($_product = null){
        $specialPriceToDate = $_product->getSpecialToDate();
        $today =  time();
        $res = array();
        $res['isCountDown'] = false;
        if ($specialPriceToDate && $today <= strtotime($specialPriceToDate)){
            $res['time'] = $specialPriceToDate;
            $res['isCountDown'] = true;
        }
        return $res;
    }

}

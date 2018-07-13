<?php
namespace SoW\Widget\Model;

class Product extends \Magento\Framework\DataObject{

    protected $_productCollectionFactory;

    protected $_reportCollection;

    protected $productState;

    protected $_resource;

    protected $_catalogProductVisibility;


    public function __construct(
        \Magento\Catalog\Model\ResourceModel\Product\CollectionFactory $productCollectionFactory,
        \Magento\Reports\Model\ResourceModel\Product\CollectionFactory $reportCollection,
        \Magento\Catalog\Model\Indexer\Product\Flat\State $productState,
        \Magento\Framework\App\ResourceConnection $resource,
        \Magento\Catalog\Model\Product\Visibility $catalogProductVisibility,
        array $data = []
    )
    {
        $this->_productCollectionFactory = $productCollectionFactory;
        $this->_reportCollection = $reportCollection;
        $this->productState              = $productState;
        $this->_resource                 = $resource;
        $this->_catalogProductVisibility = $catalogProductVisibility;
        parent::__construct($data);
    }

    protected function createCollection($options){
        $collection = $this->_productCollectionFactory->create();
        if(isset($options['category'])){
            $collection->joinField(
                'category_id', $this->_resource->getTableName('catalog_category_product'), 'category_id',
                'product_id = entity_id', null, 'left'
            )
                ->addAttributeToFilter('category_id', array(
                    array('finset' => $options['category']),
                ));
        }
        $collection->setVisibility($this->_catalogProductVisibility->getVisibleInCatalogIds());

        $collection      = $this->_addProductAttributesAndPrices($collection)
            ->addStoreFilter($this->getStoreId());
        return $collection;
    }

    public function getLatestProducts($options = [])
    {
        $collection = $this->createCollection($options);
        $collection ->setPageSize(isset($options['products_count'])?$options['products_count']:10)
            ->setCurPage(isset($options['curpage'])?$options['curpage']:1)
            ->getSelect()->group("e.entity_id");
        return $collection;
    }

    public function getNewarrivalProducts($options = [])
    {
        $todayStartOfDayDate = $this->getStartOfDayDate();
        $todayEndOfDayDate = $this->getEndOfDayDate();
        $collection = $this->createCollection($options);
        $collection->addAttributeToFilter(
            'news_from_date',
            [
                'or' => [
                    0 => ['date' => true, 'to' => $todayEndOfDayDate],
                    1 => ['is' => new \Zend_Db_Expr('null')],
                ]
            ],
            'left'
        )->addAttributeToFilter(
            'news_to_date',
            [
                'or' => [
                    0 => ['date' => true, 'from' => $todayStartOfDayDate],
                    1 => ['is' => new \Zend_Db_Expr('null')],
                ]
            ],
            'left'
        )->addAttributeToFilter(
            [
                ['attribute' => 'news_from_date', 'is' => new \Zend_Db_Expr('not null')],
                ['attribute' => 'news_to_date', 'is' => new \Zend_Db_Expr('not null')],
            ]
        )->addAttributeToSort(
            'news_from_date',
            'desc'
        )
            ->setPageSize(isset($options['products_count'])?$options['products_count']:10)
            ->setCurPage(isset($options['curpage'])?$options['curpage']:1);

        return $collection;
    }

    public function getSpecialProducts($options = [])
    {
        $collection = $this->createCollection($options);
        $collection->addAttributeToFilter(
            'special_from_date',
            ['date' => true, 'to' => $this->getEndOfDayDate()], 'left'
        )->addAttributeToFilter(
            'special_to_date', ['or' => [0 => ['date' => true,
            'from' => $this->getStartOfDayDate(
            )],
            1 => ['is' => new \Zend_Db_Expr(
                'null'
            )],]], 'left'
        )->addAttributeToSort(
            'news_from_date', 'desc'
        );
        $collection
            ->setPageSize(isset($options['products_count'])?$options['products_count']:10)
            ->setCurPage(isset($options['curpage'])?$options['curpage']:1)
                    ->getSelect()->group("e.entity_id");
        $collection->getSelect()->where('price_index.final_price < price_index.price');
        return $collection;
    }

    public function getMostViewedProducts($options = [])
    {
        /** @var $collection \Magento\Reports\Model\ResourceModel\Product\CollectionFactory */
        $collection = $this->_reportCollection->create()->addAttributeToSelect('*')->addViewsCount();
        if(isset($options['categories'])){
            $collection->joinField(
                'category_id', $this->_resource->getTableName('catalog_category_product'), 'category_id',
                'product_id = entity_id', null, 'left'
            )
                ->addAttributeToFilter('category_id', array(
                    array('finset' => $options['categories']),
                ));
        }
        $collection->setVisibility($this->_catalogProductVisibility->getVisibleInCatalogIds())
            ->addAttributeToSelect('*')
            ->addStoreFilter()
            ->setPageSize(isset($options['pagesize'])?$options['pagesize']:5)
            ->setCurPage(isset($options['curpage'])?$options['curpage']:1)
            ->getSelect()->group("e.entity_id");
        return $collection;
    }
    public function getBestsellerProducts($options = [])
    {
        $storeId = $this->getStoreId();
        $collection = $this->createCollection($options);
        $collection ->joinField(
                'qty_ordered',
                $this->_resource->getTableName('sales_bestsellers_aggregated_monthly'),
                'qty_ordered',
                'product_id=entity_id',
                'at_qty_ordered.store_id=' . (int)$storeId,
                'at_qty_ordered.qty_ordered > 0',
                'left'
            )
            ->setPageSize(isset($options['products_count'])?$options['products_count']:10)
            ->setCurPage(isset($options['curpage'])?$options['curpage']:1)
            ->getSelect()->group("e.entity_id");
        return $collection;
    }
    public function getRandomProducts($options = [])
    {
        $collection = $this->createCollection($options);
        $collection ->setPageSize(isset($options['products_count'])?$options['products_count']:5)
            ->setCurPage(isset($options['curpage'])?$options['curpage']:1)
            ->getSelect()->group("e.entity_id");
        $collection->getSelect()->order('rand()');
        return $collection;
    }
    public function getFeaturedProducts($options = [])
    {
        $collection = $this->createCollection($options);
        $collection->addAttributeToFilter(array(array( 'attribute'=>'featured', 'eq' => '1')))
            ->setPageSize(isset($options['products_count'])?$options['products_count']:5)
            ->setCurPage(isset($options['curpage'])?$options['curpage']:1)
            ->getSelect()->group("e.entity_id");
        return $collection;
    }

    protected function _addProductAttributesAndPrices(
        \Magento\Catalog\Model\ResourceModel\Product\Collection $collection
    ) {
        return $collection
            ->addMinimalPrice()
            ->addFinalPrice()
            ->addTaxPercents()
            ->addAttributeToSelect('*')
            ->addUrlRewrite();
    }
    public function getStoreId()
    {
        $objectManager = \Magento\Framework\App\ObjectManager::getInstance();

        return $objectManager->create('\Magento\Catalog\Block\Product\Context')->getStoreManager()->getStore()->getId();
    }

    public function getStartOfDayDate()
    {
        $objectManager = \Magento\Framework\App\ObjectManager::getInstance();

        return $objectManager->create('\Magento\Framework\Stdlib\DateTime\DateTime')->date(null, '0:0:0');
    }

    public function getEndOfDayDate()
    {
        $objectManager = \Magento\Framework\App\ObjectManager::getInstance();

        return $objectManager->create('\Magento\Framework\Stdlib\DateTime\DateTime')->date(null, '23:59:59');
    }
    public function getProductCollection($options = [])
    {
        $collection = '';
        switch ($options['source']) {
            case 'latest':
                $collection = $this->getLatestProducts($options);
                break;
            case 'new_arrival':
                $collection = $this->getNewarrivalProducts($options);
                break;
            case 'special':
                $collection = $this->getSpecialProducts($options);
                break;
            case 'most_view':
                $collection = $this->getMostViewedProducts($options);
                break;
            case 'best_seller':
                $collection = $this->getBestsellerProducts($options);
                break;
            case 'random':
                $collection = $this->getRandomProducts($options);
                break;
        }
        return $collection;
    }
}
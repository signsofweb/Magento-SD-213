<?php
namespace SoW\Widget\Model;

class Product extends \Magento\Framework\DataObject{

    protected $_productCollectionFactory;
    protected $productState;
    protected $_resource;
    protected $_catalogProductVisibility;
    public function __construct(
        \Magento\Catalog\Model\ResourceModel\Product\CollectionFactory $productCollectionFactory,
        \Magento\Catalog\Model\Indexer\Product\Flat\State $productState,
        \Magento\Framework\App\ResourceConnection $resource,
        \Magento\Catalog\Model\Product\Visibility $catalogProductVisibility,
        array $data = []
    )
    {
        $this->_productCollectionFactory = $productCollectionFactory;
        $this->productState              = $productState;
        $this->_resource                 = $resource;
        $this->_catalogProductVisibility = $catalogProductVisibility;
        parent::__construct($data);
    }
    protected function createCollection(){
        $collection = $this->_productCollectionFactory->create();
        $collection->setVisibility($this->_catalogProductVisibility->getVisibleInCatalogIds());
        return $collection      = $this->_addProductAttributesAndPrices($collection)
            ->addStoreFilter($this->getStoreId());
    }
    public function getSpecialProducts($options = [])
    {
        $collection = $this->createCollection();
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
        $collection ->setPageSize(isset($options['products_count'])?$options['products_count']:5)
                    ->setCurPage(isset($options['curpage'])?$options['curpage']:1)
                    ->getSelect()->group("e.entity_id");
        $collection->getSelect()->where('price_index.final_price < price_index.price');
        return $collection;
    }

    public function getProductCollection($source_key, $config = [])
    {
        $collection = '';
        switch ($source_key) {
            case 'latest':
                $collection = $this->getLatestProducts($config);
                break;
            case 'new_arrival':
                $collection = $this->getNewarrivalProducts($config);
                break;
            case 'special':
                $collection = $this->getSpecialProducts($config);
                break;
            case 'most_popular':
                $collection = $this->getMostViewedProducts($config);
                break;
            case 'best_seller':
                $collection = $this->getBestsellerProducts($config);
                break;
            case 'top_rated':
                $collection = $this->getTopratedProducts($config);
                break;
            case 'random':
                $collection = $this->getRandomProducts($config);
                break;
            case 'featured':
                $collection = $this->getFeaturedProducts($config);
                break;
            case 'deals':
                $collection = $this->getDealsProducts($config);
                break;
        }
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
}
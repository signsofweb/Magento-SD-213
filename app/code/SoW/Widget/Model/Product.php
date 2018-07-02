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

    public function getSpecialProducts($config = [])
    {
        /** @var $collection \Magento\Catalog\Model\ResourceModel\Product\Collection */
        $collection = $this->_productCollectionFactory->create();
        if (isset($config['categories'])) {
            if ($this->productState->isFlatEnabled()) {
                $collection->joinField(
                    'category_id',
                    $this->_resource->getTableName('catalog_category_product'),
                    'category_id',
                    'product_id = entity_id',
                    'category_id in (' . implode($config['categories'], ",") . ')' ,
                    'at_category_id.category_id == NULL',
                    'left'
                );
            } else {
                $collection->joinField(
                    'category_id', $this->_resource->getTableName('catalog_category_product'), 'category_id',
                    'product_id = entity_id', null, 'left'
                )
                    ->addAttributeToFilter('category_id', array(
                        array('finset' => $config['categories']),
                    ));
            }
        }

        $collection->setVisibility($this->_catalogProductVisibility->getVisibleInCatalogIds())
            ->addAttributeToSelect('*')
            ->addStoreFilter()
            ->addMinimalPrice()
            ->addUrlRewrite()
            ->addTaxPercents()
            ->addFinalPrice();
        $collection->setPageSize(isset($config['pagesize'])?$config['pagesize']:5)
            ->setCurPage(isset($config['curpage'])?$config['curpage']:1)
            ->getSelect()->group("e.entity_id");
        $collection->getSelect()->order("e.entity_id DESC")->where('price_index.final_price < price_index.price');
        return $collection;
    }
}
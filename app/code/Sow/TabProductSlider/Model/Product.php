<?php

namespace Sow\TabProductSlider\Model;

class Product extends \Magento\Framework\DataObject
{
    /**
     * @var \Magento\Catalog\Model\ResourceModel\Product\CollectionFactory
     */
    protected $_productCollectionFactory;

    /**
     * @var \Magento\Reports\Model\ResourceModel\Product\CollectionFactory
     */
    protected $_reportCollection;

    /**
     * @var \Magento\Catalog\Model\Product\Visibility
     */
    protected $_catalogProductVisibility;

    /**
     * @var \Magento\Framework\Stdlib\DateTime\TimezoneInterface
     */
    protected $_localeDate;

    /**
     * Store manager
     *
     * @var \Magento\Store\Model\StoreManagerInterface
     */
    protected $_storeManager;

    /**
     * \Magento\Framework\App\ResourceConnection
     * @var [type]
     */
    protected $_resource;

    /**
     * @param \Magento\Catalog\Model\ResourceModel\Product\CollectionFactory $productCollectionFactory
     * @param \Magento\Reports\Model\ResourceModel\Product\CollectionFactory $reportCollection
     * @param \Magento\Catalog\Model\Product\Visibility                 $catalogProductVisibility
     * @param \Magento\Framework\Stdlib\DateTime\TimezoneInterface      $localeDate
     * @param \Magento\Store\Model\StoreManagerInterface                $storeManager
     * @param array                                                     $data
     */
    public function __construct(
        \Magento\Catalog\Model\ResourceModel\Product\CollectionFactory $productCollectionFactory,
        \Magento\Reports\Model\ResourceModel\Product\CollectionFactory $reportCollection,
        \Magento\Catalog\Model\Product\Visibility $catalogProductVisibility,
        \Magento\Framework\Stdlib\DateTime\TimezoneInterface $localeDate,
        \Magento\Store\Model\StoreManagerInterface $storeManager,
        \Magento\Framework\Stdlib\DateTime\DateTime $date,
        \Magento\Framework\App\ResourceConnection $resource,
        array $data = []
    ) {
        $this->_localeDate = $localeDate;
        $this->_productCollectionFactory = $productCollectionFactory;
        $this->_reportCollection = $reportCollection;
        $this->_catalogProductVisibility = $catalogProductVisibility;
        $this->_storeManager = $storeManager;
        $this->date = $date;
        $this->_resource = $resource;
        parent::__construct($data);
    }


}
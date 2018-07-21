<?php

namespace SoW\Brand\Model\Manufacturer;

use SoW\Brand\Model\ResourceModel\Manufacturer\CollectionFactory;
use Magento\Framework\App\Request\DataPersistorInterface;

class DataProvider extends \Magento\Ui\DataProvider\AbstractDataProvider
{

    protected $collection;
    protected $dataPersistor;
    protected $loadedData;
    protected $storeManager;

    public function __construct(
        $name,
        $primaryFieldName,
        $requestFieldName,
        CollectionFactory $manufacturerCollectionFactory,
        DataPersistorInterface $dataPersistor,
        \Magento\Store\Model\StoreManagerInterface $storeManager,
        array $meta = [],
        array $data = []
    )
    {
        $this->collection = $manufacturerCollectionFactory->create();
        $this->dataPersistor = $dataPersistor;
        $this->storeManager = $storeManager;
        parent::__construct($name, $primaryFieldName, $requestFieldName, $meta, $data);
        $this->meta = $this->prepareMeta($this->meta);
    }

    /**
     * Prepares Meta
     */
    public function prepareMeta(array $meta)
    {
        return $meta;
    }

    /**
     * Get data
     */
    public function getData()
    {
        // Get items
        if (isset($this->loadedData)) {
            return $this->loadedData;
        }
        $items = $this->collection->getItems();


        foreach ($items as $manufacturer) {
            $data = $manufacturer->getData();
            $image = $data['manufacturer_image'];
            if ($image && is_string($image)) {
                $data['manufacturer_images'][0]['name'] = $image;
                $data['manufacturer_images'][0]['url'] = $this->storeManager->getStore()
                        ->getBaseUrl(\Magento\Framework\UrlInterface::URL_TYPE_MEDIA)
                    . 'sow/brand/manufacturers/' . $image;
            }

            $this->loadedData[$manufacturer->getId()] = $data;
        }

        $data = $this->dataPersistor->get('manufacturer');
        if (!empty($data)) {
            $manufacturer = $this->collection->getNewEmptyItem();
            $manufacturer->setData($data);
            $this->loadedData[$manufacturer->getId()] = $manufacturer->getData();
            $this->dataPersistor->clear('manufacturer');
        }

        return $this->loadedData;
    }
}

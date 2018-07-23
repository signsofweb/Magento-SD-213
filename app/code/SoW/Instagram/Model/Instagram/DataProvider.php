<?php

namespace SoW\Instagram\Model\Instagram;

use SoW\Instagram\Model\ResourceModel\Instagram\CollectionFactory;
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
        CollectionFactory $instagramCollectionFactory,
        DataPersistorInterface $dataPersistor,
        \Magento\Store\Model\StoreManagerInterface $storeManager,
        array $meta = [],
        array $data = []
    )
    {
        $this->collection = $instagramCollectionFactory->create();
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


        foreach ($items as $instagram) {
            $data = $instagram->getData();
            $image = $data['instagram_image'];
            if ($image && is_string($image)) {
                $data['instagram_images'][0]['name'] = $image;
                $data['instagram_images'][0]['url'] = $this->storeManager->getStore()
                        ->getBaseUrl(\Magento\Framework\UrlInterface::URL_TYPE_MEDIA)
                    . 'sow/instagram/instagrams/' . $image;
            }

            $this->loadedData[$instagram->getId()] = $data;
        }

        $data = $this->dataPersistor->get('instagram');
        if (!empty($data)) {
            $instagram = $this->collection->getNewEmptyItem();
            $instagram->setData($data);
            $this->loadedData[$instagram->getId()] = $instagram->getData();
            $this->dataPersistor->clear('instagram');
        }

        return $this->loadedData;
    }
}

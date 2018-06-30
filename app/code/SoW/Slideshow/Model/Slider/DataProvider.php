<?php

namespace SoW\Slideshow\Model\Slider;

use SoW\Slideshow\Model\ResourceModel\Slider\CollectionFactory;
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
        CollectionFactory $sliderCollectionFactory,
        DataPersistorInterface $dataPersistor,
        \Magento\Store\Model\StoreManagerInterface $storeManager,
        array $meta = [],
        array $data = []
    )
    {
        $this->collection = $sliderCollectionFactory->create();
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


        foreach ($items as $slider) {
            $data = $slider->getData();
            $image = $data['slider_image'];
            if ($image && is_string($image)) {
                $data['slider_images'][0]['name'] = $image;
                $data['slider_images'][0]['url'] = $this->storeManager->getStore()
                        ->getBaseUrl(\Magento\Framework\UrlInterface::URL_TYPE_MEDIA)
                    . 'sow/slideshow/sliders/' . $image;
            }

            $this->loadedData[$slider->getId()] = $data;
        }

        $data = $this->dataPersistor->get('slider');
        if (!empty($data)) {
            $slider = $this->collection->getNewEmptyItem();
            $slider->setData($data);
            $this->loadedData[$slider->getId()] = $slider->getData();
            $this->dataPersistor->clear('slider');
        }

        return $this->loadedData;
    }
}

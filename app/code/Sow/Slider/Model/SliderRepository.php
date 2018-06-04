<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Sow\Slider\Model;

use Sow\Slider\Api\Data;
use Sow\Slider\Api\SliderRepositoryInterface;
use Magento\Framework\Api\DataObjectHelper;
use Magento\Framework\Api\SortOrder;
use Magento\Framework\Exception\CouldNotDeleteException;
use Magento\Framework\Exception\CouldNotSaveException;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Framework\Reflection\DataObjectProcessor;
use Sow\Slider\Model\ResourceModel\Slider as ResourceSlider;
use Sow\Slider\Model\ResourceModel\Slider\CollectionFactory as SliderCollectionFactory;
use Magento\Store\Model\StoreManagerInterface;

/**
 * Class SliderRepository
 * @SuppressWarnings(PHPMD.CouplingBetweenObjects)
 */
class SliderRepository implements SliderRepositoryInterface
{
    /**
     * @var ResourceSlider
     */
    protected $resource;

    /**
     * @var SliderFactory
     */
    protected $blockFactory;

    /**
     * @var SliderCollectionFactory
     */
    protected $blockCollectionFactory;

    /**
     * @var Data\SliderSearchResultsInterfaceFactory
     */
    protected $searchResultsFactory;

    /**
     * @var DataObjectHelper
     */
    protected $dataObjectHelper;

    /**
     * @var DataObjectProcessor
     */
    protected $dataObjectProcessor;

    /**
     * @var \Sow\Slider\Api\Data\SliderInterfaceFactory
     */
    protected $dataSliderFactory;

    /**
     * @var \Magento\Store\Model\StoreManagerInterface
     */
    private $storeManager;

    /**
     * @param ResourceSlider $resource
     * @param SliderFactory $blockFactory
     * @param Data\SliderInterfaceFactory $dataSliderFactory
     * @param SliderCollectionFactory $blockCollectionFactory
     * @param Data\SliderSearchResultsInterfaceFactory $searchResultsFactory
     * @param DataObjectHelper $dataObjectHelper
     * @param DataObjectProcessor $dataObjectProcessor
     * @param StoreManagerInterface $storeManager
     */
    public function __construct(
        ResourceSlider $resource,
        SliderFactory $blockFactory,
        \Sow\Slider\Api\Data\SliderInterfaceFactory $dataSliderFactory,
        SliderCollectionFactory $blockCollectionFactory,
        Data\SliderSearchResultsInterfaceFactory $searchResultsFactory,
        DataObjectHelper $dataObjectHelper,
        DataObjectProcessor $dataObjectProcessor,
        StoreManagerInterface $storeManager
    ) {
        $this->resource = $resource;
        $this->blockFactory = $blockFactory;
        $this->blockCollectionFactory = $blockCollectionFactory;
        $this->searchResultsFactory = $searchResultsFactory;
        $this->dataObjectHelper = $dataObjectHelper;
        $this->dataSliderFactory = $dataSliderFactory;
        $this->dataObjectProcessor = $dataObjectProcessor;
        $this->storeManager = $storeManager;
    }

    /**
     * Save Slider data
     *
     * @param \Sow\Slider\Api\Data\SliderInterface $block
     * @return Slider
     * @throws CouldNotSaveException
     */
    public function save(Data\SliderInterface $block)
    {
        $storeId = $this->storeManager->getStore()->getId();
        $block->setStoreId($storeId);
        try {
            $this->resource->save($block);
        } catch (\Exception $exception) {
            throw new CouldNotSaveException(__($exception->getMessage()));
        }
        return $block;
    }

    /**
     * Load Slider data by given Slider Identity
     *
     * @param string $blockId
     * @return Slider
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    public function getById($blockId)
    {
        $block = $this->blockFactory->create();
        $this->resource->load($block, $blockId);
        if (!$block->getId()) {
            throw new NoSuchEntityException(__('CMS Slider with id "%1" does not exist.', $blockId));
        }
        return $block;
    }

    /**
     * Load Slider data collection by given search criteria
     *
     * @SuppressWarnings(PHPMD.CyclomaticComplexity)
     * @SuppressWarnings(PHPMD.NPathComplexity)
     * @param \Magento\Framework\Api\SearchCriteriaInterface $criteria
     * @return \Sow\Slider\Model\ResourceModel\Slider\Collection
     */
    public function getList(\Magento\Framework\Api\SearchCriteriaInterface $criteria)
    {
        $searchResults = $this->searchResultsFactory->create();
        $searchResults->setSearchCriteria($criteria);

        $collection = $this->blockCollectionFactory->create();
        foreach ($criteria->getFilterGroups() as $filterGroup) {
            foreach ($filterGroup->getFilters() as $filter) {
                if ($filter->getField() === 'store_id') {
                    $collection->addStoreFilter($filter->getValue(), false);
                    continue;
                }
                $condition = $filter->getConditionType() ?: 'eq';
                $collection->addFieldToFilter($filter->getField(), [$condition => $filter->getValue()]);
            }
        }
        $searchResults->setTotalCount($collection->getSize());
        $sortOrders = $criteria->getSortOrders();
        if ($sortOrders) {
            foreach ($sortOrders as $sortOrder) {
                $collection->addOrder(
                    $sortOrder->getField(),
                    ($sortOrder->getDirection() == SortOrder::SORT_ASC) ? 'ASC' : 'DESC'
                );
            }
        }
        $collection->setCurPage($criteria->getCurrentPage());
        $collection->setPageSize($criteria->getPageSize());
        $blocks = [];
        /** @var Slider $blockModel */
        foreach ($collection as $blockModel) {
            $blockData = $this->dataSliderFactory->create();
            $this->dataObjectHelper->populateWithArray(
                $blockData,
                $blockModel->getData(),
                'Sow\Slider\Api\Data\SliderInterface'
            );
            $blocks[] = $this->dataObjectProcessor->buildOutputDataArray(
                $blockData,
                'Sow\Slider\Api\Data\SliderInterface'
            );
        }
        $searchResults->setItems($blocks);
        return $searchResults;
    }

    /**
     * Delete Slider
     *
     * @param \Sow\Slider\Api\Data\SliderInterface $block
     * @return bool
     * @throws CouldNotDeleteException
     */
    public function delete(Data\SliderInterface $block)
    {
        try {
            $this->resource->delete($block);
        } catch (\Exception $exception) {
            throw new CouldNotDeleteException(__($exception->getMessage()));
        }
        return true;
    }

    /**
     * Delete Slider by given Slider Identity
     *
     * @param string $sliderId
     * @return bool
     * @throws CouldNotDeleteException
     * @throws NoSuchEntityException
     */
    public function deleteById($sliderId)
    {
        return $this->delete($this->getById($sliderId));
    }
}

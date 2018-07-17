<?php
/**
* Copyright 2016 aheadWorks. All rights reserved.
* See LICENSE.txt for license details.
*/

namespace Convert\Blog\Block\Sidebar;

use Magento\Framework\Api\SearchCriteriaBuilder;
use Aheadworks\Blog\Api\Data\CategoryInterface;
use Aheadworks\Blog\Api\CategoryRepositoryInterface;
use Aheadworks\Blog\Model\Source\Category\Status as CategoryStatus;
use Aheadworks\Blog\Model\Config;
use Aheadworks\Blog\Model\Url;
use Magento\Framework\View\Element\Template\Context;

/**
 * Categories sidebar
 * @package Convert\Blog\Block\Sidebar
 */
class Category extends \Magento\Framework\View\Element\Template
{
    /**
     * @var CategoryRepositoryInterface
     */
    private $categoryRepository;
    
    /**
     * @var SearchCriteriaBuilder
     */
    private $searchCriteriaBuilder;
    
    /**
     * @var LinkFactory
     */
    private $linkFactory;
    
    /**
     * @var Config
     */
    protected $config;

    /**
     * @var Url
     */
    private $url;

    /**
     * @param Context $context
     * @param PostRepositoryInterface $postRepository
     * @param ListingFactory $postListingFactory
     * @param Config $config
     * @param Url $url
     * @param array $data
     */
    public function __construct(
        Context $context,
        CategoryRepositoryInterface $categoryRepository,
        SearchCriteriaBuilder $searchCriteriaBuilder,
        \Aheadworks\Blog\Block\LinkFactory $linkFactory,
        Config $config,
        Url $url,
        array $data = []
    ) {
        $this->categoryRepository = $categoryRepository;
        $this->searchCriteriaBuilder = $searchCriteriaBuilder;
        $this->linkFactory = $linkFactory;
        $this->config = $config;
        $this->url = $url;
        parent::__construct($context, $data);
    }

    /**
     * Get post categories
     *
     * @return CategoryInterface[]
     */
    private function getCategories()
    {
        $this->searchCriteriaBuilder
            ->addFilter(CategoryInterface::STATUS, CategoryStatus::ENABLED)
            ->addFilter(CategoryInterface::STORE_IDS, $this->_storeManager->getStore()->getId());
        return $this->categoryRepository
            ->getList($this->searchCriteriaBuilder->create())
            ->getItems();
    }

    /**
     * Retrieves array of category links html
     *
     * @return string[]
     */
    public function getCategoryLinks() {
        $categoryLinks = [];
        foreach ($this->getCategories() as $category) {
            /** @var Link $link */
            $link = $this->linkFactory->create();
            $categoryLinks[] = $link
                ->setHref($this->url->getCategoryUrl($category))
                ->setTitle($category->getName())
                ->setLabel($category->getName())
                ->toHtml();
        }
        return $categoryLinks;
    }
}

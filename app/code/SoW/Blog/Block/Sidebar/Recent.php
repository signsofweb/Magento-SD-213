<?php
/**
* Copyright 2016 aheadWorks. All rights reserved.
* See LICENSE.txt for license details.
*/

namespace Convert\Blog\Block\Sidebar;

use Aheadworks\Blog\Api\Data\PostInterface;
use Aheadworks\Blog\Api\PostRepositoryInterface;
use Aheadworks\Blog\Block\Post\ListingFactory ;
use Aheadworks\Blog\Model\Config;
use Aheadworks\Blog\Model\Url;
use Magento\Framework\DataObject\IdentityInterface;
use Magento\Framework\View\Element\Template\Context;
use Aheadworks\Blog\Model\Template\FilterProvider;
 
class Recent extends \Aheadworks\Blog\Block\Sidebar\Recent
{
    private $postRepository;

    /**
     * @var \Aheadworks\Blog\Block\Post\Listing
     */
    private $postListing;

    /**
     * @var Url
     */
    private $url;
    public function __construct(
        Context $context,
        PostRepositoryInterface $postRepository,
        ListingFactory $postListingFactory,
        Config $config,
        Url $url,
        FilterProvider $templateFilterProvider,
        array $data = []
    ) {
        $this->templateFilterProvider = $templateFilterProvider;
        parent::__construct($context,$postRepository,$postListingFactory, $config,$url, $data);
    }


    public function getShortContent(PostInterface $post)
    {
        
        $content = $post->getShortContent(); 
        return $this->templateFilterProvider->getFilter()
            ->setStoreId($this->_storeManager->getStore()->getId())
            ->filter($content);
    }
     
}

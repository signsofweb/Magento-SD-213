<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Sow\Slider\Api\Data;

use Magento\Framework\Api\SearchResultsInterface;

/**
 * Interface for cms block search results.
 * @api
 */
interface BlockSearchResultsInterface extends SearchResultsInterface
{
    /**
     * Get blocks list.
     *
     * @return \Sow\Slider\Api\Data\BlockInterface[]
     */
    public function getItems();

    /**
     * Set blocks list.
     *
     * @param \Sow\Slider\Api\Data\BlockInterface[] $items
     * @return $this
     */
    public function setItems(array $items);
}

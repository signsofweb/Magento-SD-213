<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Sow\Slider\Model\Block\Source;

use Magento\Framework\Data\OptionSourceInterface;

/**
 * Class IsActive
 */
class IsActive implements OptionSourceInterface
{
    /**
     * @var \Sow\Slider\Model\Block
     */
    protected $cmsBlock;

    /**
     * Constructor
     *
     * @param \Sow\Slider\Model\Block $cmsBlock
     */
    public function __construct(\Sow\Slider\Model\Block $cmsBlock)
    {
        $this->cmsBlock = $cmsBlock;
    }

    /**
     * Get options
     *
     * @return array
     */
    public function toOptionArray()
    {
        $availableOptions = $this->cmsBlock->getAvailableStatuses();
        $options = [];
        foreach ($availableOptions as $key => $value) {
            $options[] = [
                'label' => $value,
                'value' => $key,
            ];
        }
        return $options;
    }
}

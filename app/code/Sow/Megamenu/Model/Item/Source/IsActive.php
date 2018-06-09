<?php
/**
 * Copyright © 2013-2017 Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Sow\Megamenu\Model\Item\Source;

use Magento\Framework\Data\OptionSourceInterface;


class IsActive implements OptionSourceInterface
{

    protected $_item;


    public function __construct(\Sow\Megamenu\Model\Item $item)
    {
        $this->_item = $item;
    }

    /**
     * Get options
     *
     * @return array
     */
    public function toOptionArray()
    {
        $availableOptions = $this->_item->getAvailableStatus();
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

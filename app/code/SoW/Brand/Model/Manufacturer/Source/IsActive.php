<?php
/**
 * Copyright © 2013-2017 Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace SoW\Brand\Model\Manufacturer\Source;

use Magento\Framework\Data\OptionSourceInterface;


class IsActive implements OptionSourceInterface
{

    protected $_manufacturer;


    public function __construct(\SoW\Brand\Model\Manufacturer $Manufacturer)
    {
        $this->_manufacturer = $Manufacturer;
    }

    /**
     * Get options
     *
     * @return array
     */
    public function toOptionArray()
    {
        $availableOptions = $this->_manufacturer->getAvailableStatus();
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

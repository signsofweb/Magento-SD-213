<?php
/**
 * Copyright © 2013-2017 Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace SoW\Slideshow\Model\Slider\Source;

use Magento\Framework\Data\OptionSourceInterface;


class IsActive implements OptionSourceInterface
{

    protected $_slider;


    public function __construct(\SoW\Slideshow\Model\Slider $Slider)
    {
        $this->_slider = $Slider;
    }

    /**
     * Get options
     *
     * @return array
     */
    public function toOptionArray()
    {
        $availableOptions = $this->_slider->getAvailableStatus();
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

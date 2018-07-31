<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace SoW\Megamenu\Ui\Component\Listing\Column\Item;

use Magento\Framework\Data\OptionSourceInterface;


class Display implements OptionSourceInterface
{

    public function toOptionArray()
    {
        $arr = [];
        $arr[]=[
            'value' => 1,
            'label' => 'Icon and name'
        ];
        $arr[]=[
            'value' => 2,
            'label' => 'Name only'
        ];
        $arr[]=[
            'value' => 3,
            'label' => 'Icon only'
        ];
        return $arr;
    }

}

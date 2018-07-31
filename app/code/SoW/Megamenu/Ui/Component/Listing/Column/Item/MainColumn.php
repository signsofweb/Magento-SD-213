<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace SoW\Megamenu\Ui\Component\Listing\Column\Item;

use Magento\Framework\Data\OptionSourceInterface;


class MainColumn implements OptionSourceInterface
{

    public function toOptionArray()
    {
        $arr = [];
        $arr[]=[
            'value' => 1,
            'label' => '1 Column'
        ];
        $arr[]=[
            'value' => 2,
            'label' => '2 Column'
        ];
        $arr[]=[
            'value' => 3,
            'label' => '3 Column'
        ];
        $arr[]=[
            'value' => 4,
            'label' => '4 Column'
        ];
        $arr[]=[
            'value' => 5,
            'label' => '5 Column'
        ];
        return $arr;
    }

}

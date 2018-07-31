<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace SoW\Megamenu\Ui\Component\Listing\Column\Item;

use Magento\Framework\Data\OptionSourceInterface;


class DropdownAlignment implements OptionSourceInterface
{

    public function toOptionArray()
    {
        $arr = [];
        $arr[]=[
            'value' => 1,
            'label' => 'From right item'
        ];
        $arr[]=[
            'value' => 2,
            'label' => 'From left item'
        ];
        $arr[]=[
            'value' => 3,
            'label' => 'From right menu'
        ];
        $arr[]=[
            'value' => 4,
            'label' => 'From left menu'
        ];
        return $arr;
    }

}

<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace SoW\Megamenu\Ui\Component\Listing\Column\Item;

use Magento\Framework\Data\OptionSourceInterface;


class HtmlPosition implements OptionSourceInterface
{

    public function toOptionArray()
    {
        $arr = [];
        $arr[]=[
            'value' => 1,
            'label' => 'Top'
        ];
        $arr[]=[
            'value' => 2,
            'label' => 'Right'
        ];
        $arr[]=[
            'value' => 3,
            'label' => 'Bottom'
        ];
        $arr[]=[
            'value' => 4,
            'label' => 'Left'
        ];
        return $arr;
    }

}

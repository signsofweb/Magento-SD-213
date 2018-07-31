<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace SoW\Megamenu\Ui\Component\Listing\Column\Item;

use Magento\Framework\Data\OptionSourceInterface;


class LinkType implements OptionSourceInterface
{

    public function toOptionArray()
    {
        $arr = [];
        $arr[]=[
            'value' => 1,
            'label' => 'Custom Link'
        ];
        $arr[]=[
            'value' => 2,
            'label' => 'Category Link'
        ];
        array_unshift($arr, array(
            'value' => '',
            'label' => 'Select an option.',
        ));
        return $arr;
    }

}

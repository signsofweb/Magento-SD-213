<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace SoW\Megamenu\Ui\Component\Listing\Column\Item;

use Magento\Framework\Data\OptionSourceInterface;


class LinkTarget implements OptionSourceInterface
{

    public function toOptionArray()
    {
        $arr = [];
        $arr[]=[
            'value' => '_top',
            'label' => 'Load in the full body of the window'
        ];
        $arr[]=[
            'value' => '_parent',
            'label' => 'Load in the parent frameset'
        ];
        $arr[]=[
            'value' => '_blank',
            'label' => 'Load in a new window'
        ];
        array_unshift($arr, array(
            'value' => '_self',
            'label' => 'Load in the same frame as it was clicked'
        ));
        return $arr;
    }

}

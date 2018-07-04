<?php
namespace SoW\Base\Model\System\Config\Source;

class Source implements \Magento\Framework\Option\ArrayInterface{

    public function toOptionArray()
    {
        $sources[] = [
            'value' => 'latest',
            'label' => 'Latest'];
        $sources[] = [
            'value' => 'new_arrival',
            'label' => 'New Arrival'];
        $sources[] = [
            'value' => 'special',
            'label' => 'Special'];
        $sources[] = [
            'value' => 'most_view',
            'label' => 'Most View'];
        $sources[] = [
            'value' => 'best_seller',
            'label' => 'Best Seller'];
        $sources[] = [
            'value' => 'random',
            'label' => 'Random'];
        $sources[] = [
            'value' => 'featured',
            'label' => 'Featured'];
        $sources[] = [
            'value' => 'deals',
            'label' => 'Deals'];
        return $sources;
    }
}
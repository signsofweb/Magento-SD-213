<?php
namespace Sow\Base\Model\System\Config\Source;

class StaticBlocks implements \Magento\Framework\Option\ArrayInterface{
    protected  $_blockModel;

    /**
     * @param \Magento\Cms\Model\Block $blockModel
     */
    public function __construct(
        \Magento\Cms\Model\Block $blockModel
    ) {
        $this->blockModel = $blockModel;
    }

    /**
     * Options getter
     *
     * @return array
     */
    public function toOptionArray()
    {
        $collection = $this->blockModel->getCollection();
        $blocks = array();
        foreach ($collection as $_block) {
            $blocks[] = [
                'value' => $_block->getId(),
                'label' => $_block->getTitle()
            ];
        }
        $blocks[] = [
            'value' => 'custom_html',
            'label' => 'Custom HTML'];
            array_unshift($blocks, array(
            'value' => '',
            'label' => 'Disable',
        ));
        return $blocks;
    }
}
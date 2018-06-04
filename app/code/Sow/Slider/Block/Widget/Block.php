<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Sow\Slider\Block\Widget;

/**
 * Cms Static Block Widget
 *
 * @author     Magento Core Team <core@magentocommerce.com>
 */
class Block extends \Magento\Framework\View\Element\Template implements \Magento\Widget\Block\BlockInterface
{
    /**
     * @var \Sow\Slider\Model\Template\FilterProvider
     */
    protected $_filterProvider;

    /**
     * Storage for used widgets
     *
     * @var array
     */
    protected static $_widgetUsageMap = [];

    /**
     * Block factory
     *
     * @var \Sow\Slider\Model\BlockFactory
     */
    protected $_blockFactory;

    /**
     * @param \Magento\Framework\View\Element\Template\Context $context
     * @param \Sow\Slider\Model\Template\FilterProvider $filterProvider
     * @param \Sow\Slider\Model\BlockFactory $blockFactory
     * @param array $data
     */
    public function __construct(
        \Magento\Framework\View\Element\Template\Context $context,
        \Sow\Slider\Model\Template\FilterProvider $filterProvider,
        \Sow\Slider\Model\BlockFactory $blockFactory,
        array $data = []
    ) {
        parent::__construct($context, $data);
        $this->_filterProvider = $filterProvider;
        $this->_blockFactory = $blockFactory;
    }

    /**
     * Prepare block text and determine whether block output enabled or not
     * Prevent blocks recursion if needed
     *
     * @return $this
     */
    protected function _beforeToHtml()
    {
        parent::_beforeToHtml();
        $blockId = $this->getData('block_id');
        $blockHash = get_class($this) . $blockId;

        if (isset(self::$_widgetUsageMap[$blockHash])) {
            return $this;
        }
        self::$_widgetUsageMap[$blockHash] = true;

        if ($blockId) {
            $storeId = $this->_storeManager->getStore()->getId();
            /** @var \Sow\Slider\Model\Block $block */
            $block = $this->_blockFactory->create();
            $block->setStoreId($storeId)->load($blockId);
            if ($block->isActive()) {
                $this->setText(
                    $this->_filterProvider->getBlockFilter()->setStoreId($storeId)->filter($block->getContent())
                );
            }
        }

        unset(self::$_widgetUsageMap[$blockHash]);
        return $this;
    }
}

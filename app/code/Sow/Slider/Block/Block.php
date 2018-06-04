<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */

// @codingStandardsIgnoreFile

namespace Sow\Slider\Block;

/**
 * Cms block content block
 */
class Block extends \Magento\Framework\View\Element\AbstractBlock implements \Magento\Framework\DataObject\IdentityInterface
{
    /**
     * @var \Sow\Slider\Model\Template\FilterProvider
     */
    protected $_filterProvider;

    /**
     * Store manager
     *
     * @var \Magento\Store\Model\StoreManagerInterface
     */
    protected $_storeManager;

    /**
     * Block factory
     *
     * @var \Sow\Slider\Model\BlockFactory
     */
    protected $_blockFactory;

    /**
     * Construct
     *
     * @param \Magento\Framework\View\Element\Context $context
     * @param \Sow\Slider\Model\Template\FilterProvider $filterProvider
     * @param \Magento\Store\Model\StoreManagerInterface $storeManager
     * @param \Sow\Slider\Model\BlockFactory $blockFactory
     * @param array $data
     */
    public function __construct(
        \Magento\Framework\View\Element\Context $context,
        \Sow\Slider\Model\Template\FilterProvider $filterProvider,
        \Magento\Store\Model\StoreManagerInterface $storeManager,
        \Sow\Slider\Model\BlockFactory $blockFactory,
        array $data = []
    ) {
        parent::__construct($context, $data);
        $this->_filterProvider = $filterProvider;
        $this->_storeManager = $storeManager;
        $this->_blockFactory = $blockFactory;
    }

    /**
     * Prepare Content HTML
     *
     * @return string
     */
    protected function _toHtml()
    {
        $blockId = $this->getBlockId();
        $html = '';
        if ($blockId) {
            $storeId = $this->_storeManager->getStore()->getId();
            /** @var \Sow\Slider\Model\Block $block */
            $block = $this->_blockFactory->create();
            $block->setStoreId($storeId)->load($blockId);
            if ($block->isActive()) {
                $html = $this->_filterProvider->getBlockFilter()->setStoreId($storeId)->filter($block->getContent());
            }
        }
        return $html;
    }

    /**
     * Return identifiers for produced content
     *
     * @return array
     */
    public function getIdentities()
    {
        return [\Sow\Slider\Model\Block::CACHE_TAG . '_' . $this->getBlockId()];
    }
}

<?php
/**
 * Fieldthemes
 * 
 * NOTICE OF LICENSE
 * 
 * This source file is subject to the Fieldthemes.com license that is
 * available through the world-wide-web at this URL:
 * http://www.fieldthemes.com/license-agreement.html
 * 
 * DISCLAIMER
 * 
 * Do not edit or add to this file if you wish to upgrade this extension to newer
 * version in the future.
 * 
 * @category   Fieldthemes
 * @package    Field_Blog
 * @copyright  Copyright (c) 2014 Fieldthemes (http://www.fieldthemes.com/)
 * @license    http://www.fieldthemes.com/LICENSE-1.0.html
 */
namespace Field\Blog\Model\ResourceModel\Comment;

use \Field\Blog\Model\ResourceModel\AbstractCollection;

class Collection extends AbstractCollection
{
    /**
     * @var string
     */
    protected $_idFieldName = 'comment_id';

    /**
     * Perform operations after collection load
     *
     * @return $this
     */
    protected function _afterLoad()
    {
        $this->performAfterLoad('field_blog_comment_store', 'comment_id');
        return parent::_afterLoad();
    }

    /**
     * Define resource model
     *
     * @return void
     */
    protected function _construct()
    {
        $this->_init('Field\Blog\Model\Comment', 'Field\Blog\Model\ResourceModel\Comment');
        $this->_map['fields']['store'] = 'store_table.store_id';
    }

    /**
     * Returns pairs comment_id - title
     *
     * @return array
     */
    public function toOptionArray()
    {
        return $this->_toOptionArray('comment_id', 'title');
    }

    /**
     * Add filter by store
     *
     * @param int|array|\Magento\Store\Model\Store $store
     * @param bool $withAdmin
     * @return $this
     */
    public function addStoreFilter($store, $withAdmin = true)
    {
        if (!$this->getFlag('store_filter_added')) {
            $this->performAddStoreFilter($store, $withAdmin);
        }
        return $this;
    }

    /**
     * Join store relation table if there is store filter
     *
     * @return void
     */
    protected function _renderFiltersBefore()
    {
        $this->joinStoreRelationTable('field_blog_comment_store', 'comment_id');
    }
}

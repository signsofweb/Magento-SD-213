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
namespace Field\Blog\Block\Adminhtml\Category\Edit;

class Tabs extends \Magento\Backend\Block\Widget\Tabs
{
    /**
     * @return void
     */
    protected function _construct()
    {
        parent::_construct();
        $this->setId('category_tabs');
        $this->setDestElementId('edit_form');
        $this->setTitle(__('Category Information'));

        $this->addTab(
                'main_section',
                [
                    'label' => __('Category Information'),
                    'content' => $this->getLayout()->createBlock('Field\Blog\Block\Adminhtml\Category\Edit\Tab\Main')->toHtml()
                ]
            );

        $this->addTab(
                'design_section',
                [
                    'label' => __('Design'),
                    'content' => $this->getLayout()->createBlock('Field\Blog\Block\Adminhtml\Category\Edit\Tab\Design')->toHtml()
                ]
            );

        $this->addTab(
                'meta_section',
                [
                    'label' => __('SEO'),
                    'content' => $this->getLayout()->createBlock('Field\Blog\Block\Adminhtml\Category\Edit\Tab\Meta')->toHtml()
                ]
            );

        $this->addTab(
                'posts',
                [
                    'label' => __('Posts'),
                    'url' => $this->getUrl('fieldblog/category/posts', ['_current' => true]),
                    'class' => 'ajax'
                ]
            );
    }
}

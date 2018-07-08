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
namespace Field\Blog\Block\Adminhtml\Post;

class Edit extends \Magento\Backend\Block\Widget\Form\Container
{
    /**
     * Core registry
     *
     * @var \Magento\Framework\Registry
     */
    protected $_coreRegistry = null;

    /**
     * @param \Magento\Backend\Block\Widget\Context
     * @param \Magento\Framework\Registry
     * @param array
     */
    public function __construct(
        \Magento\Backend\Block\Widget\Context $context,
        \Magento\Framework\Registry $registry,
        array $data = []
    ) {
        $this->_coreRegistry = $registry;
        parent::__construct($context, $data);
    }

    /**
     * Initialize cms page edit block
     *
     * @return void
     */
    protected function _construct()
    {
        $this->_objectId = 'post_id';
        $this->_blockGroup = 'Field_Blog';
        $this->_controller = 'adminhtml_post';
        parent::_construct();
        if ($this->_isAllowedAction('Field_Blog::post_save')) {
            $this->buttonList->update('save', 'label', __('Save Post'));
            $this->buttonList->add(
                'duplicate',
                [
                    'label' => __('Save and Duplicate'),
                    'class' => 'save'
                ],
                50
                );
            $this->buttonList->add(
                'saveandcontinue',
                [
                    'label' => __('Save and Continue Edit'),
                    'class' => 'save',
                    'data_attribute' => [
                        'mage-init' => [
                            'button' => ['event' => 'saveAndContinueEdit', 'target' => '#edit_form'],
                        ],
                    ]
                ],
                1000
            );
        } else {
            $this->buttonList->remove('save');
        }

        if ($this->_isAllowedAction('Field_Blog::post_delete')) {
            $this->buttonList->update('delete', 'label', __('Delete Post'));
        } else {
            $this->buttonList->remove('delete');
        }
    }

    /**
     * Retrieve text for header element depending on loaded page
     *
     * @return \Magento\Framework\Phrase
     */
    public function getHeaderText()
    {
        if ($this->_coreRegistry->registry('current_post')->getId()) {
            return __("Edit Post '%1'", $this->escapeHtml($this->_coreRegistry->registry('current_post')->getTitle()));
        } else {
            return __('New Post');
        }
    }

    /**
     * Check permission for passed action
     *
     * @param string $resourceId
     * @return bool
     */
    protected function _isAllowedAction($resourceId)
    {
        return $this->_authorization->isAllowed($resourceId);
    }

    /**
     * Getter of url for "Save and Continue" button
     * tab_id will be replaced by desired by JS later
     *
     * @return string
     */
    protected function _getSaveAndContinueUrl()
    {
        return $this->getUrl('cms/*/save', ['_current' => true, 'back' => 'edit', 'active_tab' => '{{tab_id}}']);
    }

    /**
     * Prepare layout
     *
     * @return \Magento\Framework\View\Element\AbstractBlock
     */
    protected function _prepareLayout()
    {
        $this->_formScripts[] = "
            require([
                'jquery',
                'mage/backend/form'
                ], function(){
                jQuery('#duplicate').click(function(){
                    var actionUrl = jQuery('#edit_form').attr('action') + 'duplicate/1';
                    jQuery('#edit_form').attr('action', actionUrl);
                    jQuery('#edit_form').submit();
                });

                function toggleEditor() {
                    if (tinyMCE.getInstanceById('before_form_content') == null) {
                        tinyMCE.execCommand('mceAddControl', false, 'before_form_content');
                    } else {
                        tinyMCE.execCommand('mceRemoveControl', false, 'before_form_content');
                    }
                };
            });

        ";
        return parent::_prepareLayout();
    }
}
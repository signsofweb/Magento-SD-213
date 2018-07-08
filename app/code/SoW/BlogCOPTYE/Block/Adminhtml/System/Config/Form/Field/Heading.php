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
namespace Field\Blog\Block\Adminhtml\System\Config\Form\Field;
use Magento\Config\Block\System\Config\Form\Field;

class Heading extends Field
{

    /**
     * @param  \Magento\Framework\Data\Form\Element\AbstractElement
     */
    public function render(\Magento\Framework\Data\Form\Element\AbstractElement $element)
    {
        $fieldConfig = $element->getFieldConfig();
        $htmlId = $element->getHtmlId();
        $html = '<tr id="row_' . $htmlId . '">'
        . '<td class="label" colspan="3">';

        $html .= '<div style="border-bottom: 1px solid #dfdfdf;
        font-size: 15px;
        color: #666;
        border-left: #CCC solid 5px;
        padding: 2px 12px;
        text-align: left !important;
        margin-left: 10%;
        margin-top: 20px;">';
        $html .= $element->getLabel();
        $html .= '</div></td></tr>';
        
        return $html;
    }

}

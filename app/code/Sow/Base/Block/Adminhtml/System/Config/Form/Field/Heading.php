<?php
namespace Sow\Base\Block\Adminhtml\System\Config\Form\Field;
use Magento\Config\Block\System\Config\Form\Field;

class Heading extends Field
{

    /**
     * render separator config row
     *
     * @param \Magento\Framework\Data\Form\Element\AbstractElement $el
     * @return string
     */
    public function render(\Magento\Framework\Data\Form\Element\AbstractElement $el)
    {
        $htmlId = $el->getHtmlId();
        $html = '<tr id="row_' . $htmlId . '">'
            . '<td class="label" colspan="3">';

        $html .= '<div style="border-bottom: 1px solid #e3e3e3;
        font-size: 15px;
        color: #303030;
        border-left: #eb5202 solid 5px;
        padding: 2px 12px;
        text-align: left !important;
        margin-left: 5%;
        margin-top: 20px;">';
        $html .= $el->getLabel();
        $html .= '</div>';
        $html .= '<p class="note"><span>' . $el->getComment() . '</span></p>';
        $html .= '</td></tr>';
        return $html;
    }
}
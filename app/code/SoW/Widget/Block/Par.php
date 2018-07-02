<?php
namespace SoW\Widget\Block;

class Par extends \Magento\Framework\View\Element\Template{
    protected $_template = 'par.phtml';

    public function getAllItems(){
        return array(
            'p1' => '1',
            'p2' => '2',
            'p3' => '3',

        );
    }

    public function getChiHtml($data){
        $html = $this->getLayout()->createBlock('SoW\Widget\Block\Chi')->setData($data)->setTemplate('chi.phtml')->toHtml();
        return $html;
    }
}
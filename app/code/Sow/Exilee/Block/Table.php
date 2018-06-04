<?php
namespace Sow\Exilee\Block;

use Magento\Framework\View\Element\Template;

class Table extends \Magento\Framework\View\Element\Template{
    public function __construct(Template\Context $context, array $data = [])
    {
        parent::__construct($context, $data);
    }

    public function getTitle(){
        return 'HOPE FOR YESTERDAY';
    }

}
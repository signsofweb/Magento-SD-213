<?php
namespace SoW\Testimonial\Block\Widget;

use Magento\Framework\View\Element\Template;
use SoW\Testimonial\Model\ResourceModel\Testimonial\Collection;

class Testimonial extends Template implements \Magento\Widget\Block\BlockInterface{

    protected $_template = 'widget/testimonial.phtml';

    protected $_testimonialCollection;

    protected $_storeManager;
    public function __construct(
        Template\Context $context,
        Collection $testimonialCollection,
        array $data = []
    )
    {
        $this->_testimonialCollection = $testimonialCollection;
        $this->_storeManager = $context->getStoreManager();
        parent::__construct($context, $data);
    }

    public function getOptions($option, $default = '')
    {
        if($this->hasData($option))
        {
            return $this->getData($option);
        }
        return $default;
    }
    public function getTestimonialCollection(){
        $storeId = $this->_storeManager->getstore()->getId();

        $collection = $this->_testimonialCollection
            ->addFieldToFilter('testimonial_status',['neq' => '0'])
            ->addFieldToFilter(['store_ids','store_ids'],[['finset' => $storeId],['eq'=> 0]])
            ->setOrder(
                'testimonial_order',
                'desc'
            );
        $testimonial_count = $this->getOptions('testimonial_count');
        if($testimonial_count){
            $collection->setPageSize($testimonial_count);
        }else{
            $collection->setPageSize(5);
        }
        return $collection;
    }
    public function getDataSlider(){
        $options = array(
            'item_md' => ($this->getOptions('max_item') != '')? $this->getOptions('max_item') :4,
            'item_sm' => ($this->getOptions('medium_item') != '')? $this->getOptions('medium_item') :2,
            'item_xs' => ($this->getOptions('min_item') != '')? $this->getOptions('min_item') :1,
            'dots' => ($this->getOptions('show_dots') == 0)? false :true,
            'nav'=> ($this->getOptions('show_nav') == 0)? false :true,
            'loop'=> ($this->getOptions('loop') == 0)? false :true,
            'autoplay' => ($this->getOptions('autoplay') == 0)? false :true,
            'autoplaySpeed'=> ($this->getOptions('autoplay_speed') != '')? $this->getOptions('autoplay_speed') :3000,
        );
        return json_encode($options);
    }
}
?>
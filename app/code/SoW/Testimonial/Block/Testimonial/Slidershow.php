<?php
namespace SoW\Testimonial\Block\Testimonial;
use Magento\Framework\View\Element\Template;
use SoW\Testimonial\Model\ResourceModel\Testimonial\Collection;

class Testimonialshow extends \Magento\Framework\View\Element\Template{
    protected  $_testimonialCollection;

    protected $_storeManager;
    public function __construct(
        Collection $testimonialCollection,
        Template\Context $context,
        array $data = []
    )
    {
        $this->testimonialCollection = $testimonialCollection;
        $this->_storeManager = $context->getStoreManager();
        parent::__construct($context, $data);
    }
    public function getTestimonialCollection(){
        $storeId = $this->_storeManager->getstore()->getId();

        $collection = $this->testimonialCollection
            ->addFieldToFilter('testimonial_status',['neq' => '0'])
        ->addFieldToFilter(['store_ids','store_ids'],[['finset' => $storeId],['eq'=> 0]]);
        return $collection;

    }
    public function getDataTestimonial(){
        $options = array(
            'item_md' => 1,
            'item_sm' => 1,
            'item_xs' => 1,
            'dots' => 1,
            'nav'=> 1,
            'loop'=> 1,
            'autoplayHoverPause'=> 1,
            'autoplaySpeed'=> 3000,
            'autoplay' => 1,
        );
        return json_encode($options);
    }
}
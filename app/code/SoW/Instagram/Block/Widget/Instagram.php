<?php
namespace SoW\Instagram\Block\Widget;

use Magento\Framework\View\Element\Template;
use SoW\Instagram\Model\ResourceModel\Instagram\Collection;

class Instagram extends Template implements \Magento\Widget\Block\BlockInterface{

    protected $_template = 'widget/instagram.phtml';

    protected $_instagramCollection;

    protected $_storeManager;
    public function __construct(
        Template\Context $context,
        Collection $instagramCollection,
        array $data = []
    )
    {
        $this->_instagramCollection = $instagramCollection;
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
    public function getInstagramCollection(){
        $storeId = $this->_storeManager->getstore()->getId();

        $collection = $this->_instagramCollection
            ->addFieldToFilter('instagram_status',['neq' => '0'])
            ->addFieldToFilter(['store_ids','store_ids'],[['finset' => $storeId],['eq'=> 0]]);
        $instagram_count = $this->getOptions('instagram_count');
        if($instagram_count){
            $collection->setPageSize($instagram_count);
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
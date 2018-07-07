<?php
namespace SoW\Widget\Block\Product;
use Magento\Framework\View\Element\Template;
use Magento\Widget\Block\BlockInterface;

class TabProductSlider extends Template implements BlockInterface{
    protected $_template = 'widget/tab_productslider.phtml';

    public function getAllOptions(){
        return $options = $this->getData();
    }
    public function getOptions($option, $default = '')
    {
        if($this->hasData($option))
        {
            return $this->getData($option);
        }
        return $default;
    }
    public function getProductSlider($data){
        $html = $this->getLayout()->createBlock('SoW\Widget\Block\Product\ProductSlider')->setData($data)->toHtml();
        return $html;
    }
    public function renderTablist(){
        $options = $this->getAllOptions();
        $tablist = array();
        $tablist[] = [
            'title' => $options['tab_1_title'],
            'tab_id' => '1'
        ];
        if (isset($options['enable_tab_2']) && $options['enable_tab_2'] == 1){
            $tablist[] = [
                'title' => $options['tab_2_title'],
                'tab_id' => '2'
            ];
            if (isset($options['enable_tab_3']) && $options['enable_tab_3'] == 1){
                $tablist[] = [
                    'title' => $options['tab_3_title'],
                    'tab_id' => '3'
                ];
                if (isset($options['enable_tab_4']) && $options['enable_tab_4'] == 1){
                    $tablist[] = [
                        'title' => $options['tab_4_title'],
                        'tab_id' => '4'
                    ];
                    if (isset($options['enable_tab_5']) && $options['enable_tab_5'] == 1){
                        $tablist[] = [
                            'title' => $options['tab_5_title'],
                            'tab_id' => '5'
                        ];
                        if (isset($options['enable_tab_6']) && $options['enable_tab_6'] == 1){
                            $tablist[] = [
                                'title' => $options['tab_6_title'],
                                'tab_id' => '6'
                            ];
                        }
                    }
                }
            }
        }
        return $tablist;
    }
    public function renderDataList(){
        $list = $this->renderTablist();
        $options = $this->getAllOptions();
        $data = array();
        if(count($list)>0){
            foreach ($list as $el){
                $id = $el['tab_id'];
                $cate_key = 'tab_'.$id.'_category';
                $sour_key = 'tab_'. $id.'_source';
                $tab_title = 'tab_'. $id.'_title';
                $options['category'] = $this->getData($cate_key);
                $options['source'] = $this->getData($sour_key);
                $options['tab_title'] = $this->getData($tab_title);
                $options['is_tab'] = true;
                $data[] = [
                    'tab_id' => $el['tab_id'],
                    'data' => $options,
                ];
            }
        }
        return $data;
    }
}

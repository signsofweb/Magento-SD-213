<?php
namespace SoW\Base\Model\System\Config\Source;

class ListCategories implements \Magento\Framework\Option\ArrayInterface
{

    protected $_categoryFactory;

    /**
     * @param \Magento\Cms\Model\Block $blockModel
     */
    public function __construct(
        \Magento\Catalog\Model\CategoryFactory $categoryFactory
    	) {
        $this->_categoryFactory = $categoryFactory;
    }

    /**
     * Options getter
     *
     * @return array
     */
    public function toOptionArray()
    {
        $allCats = $this->_categoryFactory->create()->getCollection()
            ->addAttributeToSelect(['name','id'])
            ->addAttributeToFilter('is_active','1')
            ->addAttributeToSort('id', 'asc');
        $arr= [];
        foreach ($allCats as $cat){
            $arr[] = [
                'value' => $cat->getId(),
                'label' => '(ID:'.$cat->getId().') '.$cat->getName()
            ];
        }
        return $arr;
    }

}
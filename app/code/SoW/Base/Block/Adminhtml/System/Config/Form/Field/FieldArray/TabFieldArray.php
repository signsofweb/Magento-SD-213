<?php

namespace SoW\Base\Block\Adminhtml\System\Config\Form\Field\FieldArray;

class TabFieldArray extends \Magento\Config\Block\System\Config\Form\Field\FieldArray\AbstractFieldArray
{
    /** Rows cache
     *
     * @var array|null\
     */
    private $_arrayRowsCache;

    /**
     * @var Source Products
     */
    protected $_sourceGroupRenderer;
    protected $_categoryGroupRenderer;


    /**
     * @var isAjax
     */
    protected $_isAjax;

    /**
     * Retrieve source column renderer
     *
     * @return Customergroup
     */
    protected function _GetSourceGroupRenderer()
    {
        if (!$this->_sourceGroupRenderer) {
            $this->_sourceGroupRenderer = $this->getLayout()->createBlock(
                'SoW\Base\Block\Adminhtml\Form\Field\Source',
                '',
                ['data' => ['is_render_to_js_template' => true]]
            );
            $this->_sourceGroupRenderer->setClass('source_select');
        }
        return $this->_sourceGroupRenderer;
    }
    protected function _GetCategoryGroupRenderer()
    {
        if (!$this->_categoryGroupRenderer) {
            $this->_categoryGroupRenderer = $this->getLayout()->createBlock(
                'SoW\Base\Block\Adminhtml\Form\Field\ListCategory',
                '',
                ['data' => ['is_render_to_js_template' => true]]
            );
            $this->_categoryGroupRenderer->setClass('category_select');
        }
        return $this->_categoryGroupRenderer;
    }


    /**
     * Prepare to render
     *
     * @return void
     */
    protected function _prepareToRender()
    {
        $this->addColumn(
            'source_id',
            ['label' => __('Source for Tab'),
                'renderer' => $this->_GetSourceGroupRenderer()]
        );
        $this->addColumn(
            'category_id',
            ['label' => __('Category for Tab'),
                'renderer' => $this->_GetCategoryGroupRenderer()]
        );
        $this->addColumn(
            'item_title',
            ['label' => __('Title'),'style' => 'width:150px']
        );
        $this->addColumn(
            'item_class',
            ['label' => __('Class'),'style' => 'width:140px']
        );
        $this->_addAfter = false;
    }

    /**
     * Prepare existing row data object
     *
     * @param \Magento\Framework\DataObject $row
     * @return void
     */

    protected function _prepareArrayRow(\Magento\Framework\DataObject $row)
    {
        $optionExtraAttr = [];
        $optionExtraAttr['option_' . $this->_GetSourceGroupRenderer()->calcOptionHash($row->getData('source_id'))] =
            'selected="selected"';
        $row->setData(
            'option_extra_attrs',
            $optionExtraAttr
        );

        $optionExtraAttr['option_' . $this->_GetCategoryGroupRenderer()->calcOptionHash($row->getData('category_id'))] =
            'selected="selected"';
        $row->setData(
            'option_extra_attrs',
            $optionExtraAttr
        );
    }

    /**
     * Obtain existing data from form element
     *
     * Each row will be instance of Varien_Object
     *
     * @return array
     */
    public function getArrayRows()
    {
        if (null !== $this->_arrayRowsCache) {
            return $this->_arrayRowsCache;
        }
        $result = [];
        $temp = []; // save item position
        /** @var \Magento\Framework\Data\Form\Element\AbstractElement */
        $element = $this->getElement();
        $value = $element->getValue();
        if(is_array($value)){
            unset($value['__empty']);
        }
        if(!is_array($value)){
            if(base64_decode($value, true) == true){
                $value = base64_decode($value);
                if(base64_decode($value, true) == true) {
                    $value = base64_decode($value);
                }
            }
            $value = unserialize($value);
        }
        if ( $value && is_array($value) ) {
            foreach ($value as $rowId => $row) {
                if(is_array($row)){
                    $rowColumnValues = [];
                    foreach ($row as $key => $row_value) {
                        $row[$key] = $this->escapeHtml($row_value);
                        if($key == 'position'){
                            $row[$key] = (int)$row['position'];
                        }
                        $row[$key] = htmlspecialchars_decode($row_value);
                        $rowColumnValues[$this->_getCellInputElementId($rowId, $key)] = $row[$key];
                    }
                    if(isset($row['position'])){
                        $temp[$rowId] = $row['position'];
                    }

                    $row['_id'] = $rowId;
                    $row['column_values'] = $rowColumnValues;
                    $result[$rowId] = new \Magento\Framework\DataObject($row);
                    $this->_prepareArrayRow($result[$rowId]);
                }
            }
        }
        asort($temp);
        $rows = [];
        foreach ($temp as $k => $v) {
            $rows[$k] = $result[$k];
        }
        $this->_arrayRowsCache = $rows;
        return $this->_arrayRowsCache;
    }



    /**
     * Render block HTML
     *
     * @return string
     */
    protected function _toHtml()
    {
        if (!$this->_isPreparedToRender) {
            $this->_prepareToRender();
            $this->_isPreparedToRender = true;
        }
        if (empty($this->_columns)) {
            throw new Exception('At least one column must be defined.');
        }
        return parent::_toHtml();
    }
}
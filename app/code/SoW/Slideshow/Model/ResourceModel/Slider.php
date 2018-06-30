<?php

namespace SoW\Slideshow\Model\ResourceModel;

use Magento\Framework\Model\ResourceModel\Db\AbstractDb;


class Slider extends AbstractDb
{
    /**
     * Define main table
     */
    protected function _construct()
    {
        $this->_init('sow_slider', 'slider_id');
    }
    protected function _afterSave(\Magento\Framework\Model\AbstractModel $object)
    {
        // Get image data before and after save2
        $oldImage = $object->getOrigData('slider_image');
        $newImage = $object->getData('slider_image');
        // Check when new image uploaded
        if ($newImage != null && $newImage != $oldImage) {
            $imageUploader = \Magento\Framework\App\ObjectManager::getInstance()
                ->get('SoW\Slideshow\SliderUpload');
            $imageUploader->moveFileFromTmp($newImage);
        }

        return $this;
    }
}
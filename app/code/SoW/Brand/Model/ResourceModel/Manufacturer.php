<?php

namespace SoW\Brand\Model\ResourceModel;

use Magento\Framework\Model\ResourceModel\Db\AbstractDb;


class Manufacturer extends AbstractDb
{
    /**
     * Define main table
     */
    protected function _construct()
    {
        $this->_init('sow_manufacturer', 'manufacturer_id');
    }
    protected function _afterSave(\Magento\Framework\Model\AbstractModel $object)
    {
        // Get image data before and after save2
        $oldImage = $object->getOrigData('manufacturer_image');
        $newImage = $object->getData('manufacturer_image');
        // Check when new image uploaded
        if ($newImage != null && $newImage != $oldImage) {
            $imageUploader = \Magento\Framework\App\ObjectManager::getInstance()
                ->get('SoW\Brand\ManufacturerUpload');
            $imageUploader->moveFileFromTmp($newImage);
        }

        return $this;
    }
}
<?php

namespace SoW\Instagram\Model\ResourceModel;

use Magento\Framework\Model\ResourceModel\Db\AbstractDb;


class Instagram extends AbstractDb
{
    /**
     * Define main table
     */
    protected function _construct()
    {
        $this->_init('sow_instagram', 'instagram_id');
    }
    protected function _afterSave(\Magento\Framework\Model\AbstractModel $object)
    {
        // Get image data before and after save2
        $oldImage = $object->getOrigData('instagram_image');
        $newImage = $object->getData('instagram_image');
        // Check when new image uploaded
        if ($newImage != null && $newImage != $oldImage) {
            $imageUploader = \Magento\Framework\App\ObjectManager::getInstance()
                ->get('SoW\Instagram\InstagramUpload');
            $imageUploader->moveFileFromTmp($newImage);
        }

        return $this;
    }
}
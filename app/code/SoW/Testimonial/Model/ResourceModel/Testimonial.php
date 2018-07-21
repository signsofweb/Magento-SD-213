<?php

namespace SoW\Testimonial\Model\ResourceModel;

use Magento\Framework\Model\ResourceModel\Db\AbstractDb;


class Testimonial extends AbstractDb
{
    /**
     * Define main table
     */
    protected function _construct()
    {
        $this->_init('sow_testimonial', 'testimonial_id');
    }
    protected function _afterSave(\Magento\Framework\Model\AbstractModel $object)
    {
        // Get image data before and after save2
        $oldImage = $object->getOrigData('testimonial_image');
        $newImage = $object->getData('testimonial_image');
        // Check when new image uploaded
        if ($newImage != null && $newImage != $oldImage) {
            $imageUploader = \Magento\Framework\App\ObjectManager::getInstance()
                ->get('SoW\Testimonial\TestimonialUpload');
            $imageUploader->moveFileFromTmp($newImage);
        }

        return $this;
    }
}
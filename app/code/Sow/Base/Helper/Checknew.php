<?php
namespace Sow\Base\Helper;

class Checknew extends \Magento\Framework\App\Helper\AbstractHelper{

    public function checkProductIsNew($_product = null) {
        $from_date = $_product->getNewsFromDate();
        $to_date = $_product->getNewsToDate();
        $is_new = false;
        $today = strtotime("now");
        if ($from_date && $to_date) {
            $from_date = strtotime($from_date);
            $to_date = strtotime($to_date);
            if ($from_date <= $today && $to_date >= $today) {
                $is_new = true;
            }
        }
        elseif ($from_date && !$to_date) {
            $from_date = strtotime($from_date);
            if ($from_date <= $today) {
                $is_new = true;
            }
        }elseif (!$from_date && $to_date) {
            $to_date = strtotime($to_date);
            if ($to_date >= $today) {
                $is_new = true;
            }
        }
        return $is_new;
    }

}
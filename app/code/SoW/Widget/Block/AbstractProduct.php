<?php
namespace SoW\Widget\Block;

class AbstractProduct extends \Magento\Catalog\Block\Product\AbstractProduct implements \Magento\Widget\Block\BlockInterface
{
    public function __construct(
        \Magento\Catalog\Block\Product\Context $context,
        array $data = []
    )
    {
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
    public function getAllOptions(){
        return $options = $this->getData();
    }
    public function getProductPriceHtml(
        \Magento\Catalog\Model\Product $product,
        $priceType = null,
        $renderZone = \Magento\Framework\Pricing\Render::ZONE_ITEM_LIST,
        array $arguments = []
    )
    {
        if (!isset($arguments['zone'])) {
            $arguments['zone'] = $renderZone;
        }
        $arguments['price_id'] = isset($arguments['price_id']) ? $arguments['price_id'] : 'old-price-' . $product->getId() . '-' . $priceType;
        $arguments['include_container'] = isset($arguments['include_container']) ? $arguments['include_container'] : true;
        $arguments['display_minimal_price'] = isset($arguments['display_minimal_price']) ? $arguments['display_minimal_price'] : true;

        /** @var \Magento\Framework\Pricing\Render $priceRender */
        $priceRender = $this->getLayout()->getBlock('product.price.render.default');

        $price = '';
        if ($priceRender) {
            $price = $priceRender->render(
                \Magento\Catalog\Pricing\Price\FinalPrice::PRICE_CODE,
                $product,
                $arguments
            );
        }

        return $price;
    }

    /**
     * Get start of day
     * @return mixed
     */

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
    public function checkProductIsSale($_product = null){
        $specialprice = $_product->getSpecialPrice();
        $oldPrice = $_product->getPrice();
        $specialPriceFromDate = $_product->getSpecialFromDate();
        $specialPriceToDate = $_product->getSpecialToDate();
        $today =  time();
        if ($specialprice < $oldPrice) {
            if($today >= strtotime( $specialPriceFromDate) && $today <= strtotime($specialPriceToDate) || $today >= strtotime( $specialPriceFromDate) && is_null($specialPriceToDate)) {
                return true;
            }
        }
        return false;
    }

    public function checkCountDownTimer($_product = null){
        $specialPriceToDate = $_product->getSpecialToDate();
        $today =  time();
        $res = array();
        $res['isCountDown'] = false;
        if ($specialPriceToDate && $today <= strtotime($specialPriceToDate)){
            $res['time'] = $specialPriceToDate;
            $res['isCountDown'] = true;
        }
        return $res;
    }
}
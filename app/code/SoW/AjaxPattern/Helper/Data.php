<?php
namespace SoW\AjaxPattern\Helper;

use Magento\Framework\App\Helper\Context;

class Data extends \Magento\Framework\App\Helper\AbstractHelper
{

    protected $_jsonEncoder;

    protected $postHelper;

    protected $_urlBuilder;

    protected $urlEncoder;

    protected $_listcompare;

    public function __construct(
        Context $context,
        \Magento\Framework\Json\EncoderInterface $jsonEncoder,
        \Magento\Framework\Data\Helper\PostHelper $postHelper,
        \Magento\Catalog\Model\Product\Compare\ListCompare $listcompare
    )
    {

        $this->_jsonEncoder = $jsonEncoder;
        $this->postHelper = $postHelper;
        $this->_urlBuilder = $context->getUrlBuilder();
        $this->urlEncoder = $context->getUrlEncoder();
        $this->_listcompare = $listcompare;
        parent::__construct($context);
    }

    public function getDataAction($id){
        $data = 'add';
        $ids = $this->_listcompare->getItemCollection()->getAllIds();
        foreach ($ids as $item_id){
            if ($id == $item_id){
                $data = 'remove';
            }
        }
        return $data;
    }
    public function getCompareData(\Magento\Catalog\Api\Data\ProductInterface $product)
    {
        $config = [
            'id' => $product->getId(),
            'name' => $product->getName(),
            'product_url' => $product->getUrlModel()->getUrl($product),
            'remove_data' => $this->getAjaxPostDataRemove($product),
            'add_data'    => $this->getAjaxPostDataParams($product)
        ];
        return $this->_jsonEncoder->encode($config);
    }

    public function getAjaxPostDataParams($product)
    {
        return $this->postHelper->getPostData($this->getAjaxAddUrl(), ['product' => $product->getId()]);
    }

    public function getAjaxAddUrl()
    {
        return $this->_getUrl('ajaxpattern/product_compare/Add');
    }

    public function getAjaxPostDataRemove($product)
    {
        $listCleanUrl = $this->getEncodedUrl($this->_getUrl('catalog/product_compare'));
        $data = [
            \Magento\Framework\App\ActionInterface::PARAM_NAME_URL_ENCODED => $listCleanUrl,
            'product' => $product->getId()
        ];
        return $this->postHelper->getPostData($this->getAjaxRemoveUrl(), $data);
    }

    public function getAjaxRemoveUrl()
    {
        return $this->_getUrl('catalog/product_compare/Remove');
    }

    public function getEncodedUrl($url = null)
    {
        if (!$url) {
            $url = $this->_urlBuilder->getCurrentUrl();
        }
        return $this->urlEncoder->encode($url);
    }

}
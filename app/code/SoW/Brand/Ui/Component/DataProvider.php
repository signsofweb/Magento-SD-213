<?php
/**
 * Copyright © 2013-2017 Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace SoW\Brand\Ui\Component;

use Magento\Framework\Api\FilterBuilder;
use Magento\Framework\Api\Search\SearchCriteriaBuilder;
use Magento\Framework\App\ObjectManager;
use Magento\Framework\App\RequestInterface;
use Magento\Framework\AuthorizationInterface;
use Magento\Framework\View\Element\UiComponent\DataProvider\Reporting;

class DataProvider extends \Magento\Framework\View\Element\UiComponent\DataProvider\DataProvider
{
    /**
     * Authorization.
     *
     * @var AuthorizationInterface
     */
    private $authorization;

    /**
     * @param string $name
     * @param string $primaryFieldName
     * @param string $requestFieldName
     * @param Reporting $reporting
     * @param SearchCriteriaBuilder $searchCriteriaBuilder
     * @param RequestInterface $request
     * @param FilterBuilder $filterBuilder
     * @param array $meta
     * @param array $data
     */
    public function __construct(
        $name,
        $primaryFieldName,
        $requestFieldName,
        Reporting $reporting,
        SearchCriteriaBuilder $searchCriteriaBuilder,
        RequestInterface $request,
        FilterBuilder $filterBuilder,
        array $meta = [],
        array $data = []
    ) {
        parent::__construct(
            $name,
            $primaryFieldName,
            $requestFieldName,
            $reporting,
            $searchCriteriaBuilder,
            $request,
            $filterBuilder,
            $meta,
            $data
        );

        $this->meta = array_replace_recursive($meta, $this->prepareMetadata());
    }

    /**
     * Get Authorization instance.
     *
     * @deprecated
     * @return AuthorizationInterface|mixed
     */
    private function getAuthorizationInstance()
    {
        if (null === $this->authorization) {
            $this->authorization = ObjectManager::getInstance()->get(AuthorizationInterface::class);
        }

        return $this->authorization;
    }

    /**
     * Prepares Meta.
     *
     * @return array
     */
    public function prepareMetadata()
    {
        $metadata = [];

        if (!$this->getAuthorizationInstance()->isAllowed('SoW_Brand::save')) {
            $metadata = [
                'brand_manufacturer_columns' => [
                    'arguments' => [
                        'data' => [
                            'config' => [
                                'editorConfig' => [
                                    'enabled' => false,
                                ],
                            ],
                        ],
                    ],
                ],
            ];
        }

        return $metadata;
    }
}

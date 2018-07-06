<?php

namespace SoW\Base\Block\Adminhtml\Form\Field;

use Magento\Customer\Api\GroupManagementInterface;
use Magento\Customer\Api\GroupRepositoryInterface;
use Magento\Framework\Api\SearchCriteriaBuilder;

/**
 * HTML select element block with customer groups options
 */
class Source extends \Magento\Framework\View\Element\Html\Select
{
    /**
     * Customer groups cache
     *
     * @var array
     */
    private $_source;

    /**
     * Flag whether to add group all option or no
     *
     * @var bool
     */
    protected $_addGroupAllOption = true;

    /**
     * @var GroupManagementInterface
     */
    protected $groupManagement;

    /**
     * @var GroupRepositoryInterface
     */
    protected $groupRepository;

    /**
     * @var SearchCriteriaBuilder
     */
    protected $searchCriteriaBuilder;

    /**
     * Construct
     *
     * @param \Magento\Framework\View\Element\Context $context
     * @param GroupManagementInterface $groupManagement
     * @param GroupRepositoryInterface $groupRepository
     * @param SearchCriteriaBuilder $searchCriteriaBuilder
     * @param array $data
     */
    public function __construct(
    	\Magento\Framework\View\Element\Context $context,
    	GroupManagementInterface $groupManagement,
    	GroupRepositoryInterface $groupRepository,
    	SearchCriteriaBuilder $searchCriteriaBuilder,
    	array $data = []
    	) {
    	parent::__construct($context, $data);

    	$this->groupManagement = $groupManagement;
    	$this->groupRepository = $groupRepository;
    	$this->searchCriteriaBuilder = $searchCriteriaBuilder;
    }

    /**
     * Retrieve allowed customer groups
     *
     * @param int $groupId return name by customer group id
     * @return array|string
     */
    protected function _getSourceList($groupId = null)
    {
    	if ($this->_source === null) {
            $this->_source                      = [];
            $this->_source['latest']       = __('Latest');
            $this->_source['new_arrival']  = __('New Arrival');
            $this->_source['special']      = __('Special');
            $this->_source['most_view'] = __('Most View');
            $this->_source['best_seller']  = __('Best Seller');
            $this->_source['random']       = __('Random');
    	}
    	return $this->_source;
    }

    /**
     * @param string $value
     * @return $this
     */
    public function setInputName($value)
    {
    	return $this->setName($value);
    }

    /**
     * Render block HTML
     *
     * @return string
     */
    public function _toHtml()
    {
    	if (!$this->getOptions()) {
    		foreach ($this->_getSourceList() as $groupId => $groupLabel) {
    			$this->addOption($groupId, addslashes($groupLabel));
    		}
    	}
    	return parent::_toHtml();
    }
}
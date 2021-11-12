<?php
namespace Rikai\RewardPoint\Model\ResourceModel;


class RewardPoint extends \Magento\Framework\Model\ResourceModel\Db\AbstractDb
{
	
	public function __construct(
		\Magento\Framework\Model\ResourceModel\Db\Context $context
	)
	{
		parent::__construct($context);
	}
	
	protected function _construct()
	{
		$this->_init('customer_reward_point', 'entity_id');
	}
	
}
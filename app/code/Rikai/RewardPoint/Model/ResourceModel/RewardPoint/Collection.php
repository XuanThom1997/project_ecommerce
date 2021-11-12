<?php
namespace Rikai\RewardPoint\Model\ResourceModel\RewardPoint;

class Collection extends \Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection
{
	protected $_idFieldName = 'entity_id';
	protected $_eventPrefix = 'rikai_reward_point_collection';
	protected $_eventObject = 'reward_point_collection';

	/**
	 * Define resource model
	 *
	 * @return void
	 */
	protected function _construct()
	{
		$this->_init('Rikai\RewardPoint\Model\RewardPoint', 'Rikai\RewardPoint\Model\ResourceModel\RewardPoint');
	}

}
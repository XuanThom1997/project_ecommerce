<?php
namespace Rikai\RewardPoint\Model;
class RewardPoint extends \Magento\Framework\Model\AbstractModel implements \Magento\Framework\DataObject\IdentityInterface
{
	const CACHE_TAG = 'customer_reward_point';

	protected $_cacheTag = 'customer_reward_point';

	protected $_eventPrefix = 'customer_reward_point';

	protected function _construct()
	{
		$this->_init('Rikai\RewardPoint\Model\ResourceModel\RewardPoint');
	}

	public function getIdentities()
	{
		return [self::CACHE_TAG . '_' . $this->getId()];
	}

	public function getDefaultValues()
	{
		$values = [];

		return $values;
	}
}
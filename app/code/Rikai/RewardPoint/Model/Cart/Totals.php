<?php
namespace Rikai\RewardPoint\Model\Cart;

class Totals extends \Magento\Quote\Model\Cart\Totals
{
    /**
     * Get grand total in quote currency
     *
     * @return float|null
     */
    public function getRewardPoint()
    {
        return $this->getData(self::REWARD_POINT);
    }

    /**
     * Set grand total in quote currency
     *
     * @param float $rewardPoint
     * @return $this
     */
    public function setRewardPoint($rewardPoint)
    {
        return $this->setData(self::REWARD_POINT, $rewardPoint);
    }

    /**
     * Get grand total in quote currency
     *
     * @return float|null
     */
    public function getSpentRewardPoint()
    {
        return $this->getData(self::SPENT_REWARD_POINT);
    }

    /**
     * Set grand total in quote currency
     *
     * @param float $spentRewardPoint
     * @return $this
     */
    public function setSpentRewardPoint($spentRewardPoint)
    {
        return $this->setData(self::SPENT_REWARD_POINT, $spentRewardPoint);
    }


}
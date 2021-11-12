<?php
namespace Rikai\RewardPoint\Api\Data;

interface TotalsInterface  extends \Magento\Quote\Api\Data\TotalsInterface
{
    const REWARD_POINT = 'reward_point';

    const SPENT_REWARD_POINT = 'spent_reward_point';

   

    /**
     * Get grand total in quote currency
     *
     * @return float|null
     */
    public function getRewardPoint();

    /**
     * Set grand total in quote currency
     *
     * @param float $rewardPoint
     * @return $this
     */
    public function setRewardPoint($rewardPoint);

    /**
     * Get grand total in quote currency
     *
     * @return float|null
     */
    public function getSpentRewardPoint();

    /**
     * Set grand total in quote currency
     *
     * @param float $spentRewardPoint
     * @return $this
     */
    public function setSpentRewardPoint($spentRewardPoint);


}
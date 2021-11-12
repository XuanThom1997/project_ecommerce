<?php
 
namespace Rikai\RewardPoint\Model;
 
/**
 * Class CustomManagement
 * @package ViMagento\CustomApi\Model
 */
class Message implements \Rikai\RewardPoint\Api\MessageInterface
{

    protected $rewardPointFactory;
    protected $_quoteRepository;

   

    public function __construct( \Magento\Quote\Model\QuoteRepository $quoteRepository,
                               \Rikai\RewardPoint\Model\RewardPointFactory $rewardPointFactory
    ) {                             
        $this->_quoteRepository = $quoteRepository;
        $this->_rewardPointFactory = $rewardPointFactory;
    }

      /**
     * @inheritdoc
     */
    public function save($spent,$quoteId)
    {
        $quote = $this->_quoteRepository->get($quoteId);
        $spent = $this->checkSpentReward($quote->getCustomerId(), $spent);
        $quote->setData('grand_total',($quote->getData('grand_total') - $spent + $quote->getData('spent_reward_point')));
        $quote->setData('base_grand_total',($quote->getData('base_grand_total') - $spent + $quote->getData('spent_reward_point')));
        $quote->setData('spent_reward_point',$spent)->save();
        return 'success';
    }

    public function checkSpentReward($customerId,$spent){
        if($customerId != null){
            $collection = $this->_rewardPointFactory->create()->getCollection();
            $data = $collection->addFieldToFilter('parent_id',['eq' => $customerId]);
            $data->setPageSize(1);
            $data->setCurPage(1)
            ->load();
            $spentData = $data->getData('reward_point_available_balance');
            if($spentData < $spent){
                return $spentData;
            }return $spent;
        }
        return null;  
    }
}
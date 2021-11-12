<?php

namespace Rikai\RewardPoint\Model;

use Magento\Checkout\Model\ConfigProviderInterface;

class RewardPointConfigProvider implements ConfigProviderInterface
{

 
    protected $rewardPointFactory;
    protected $_objectManager;

   

    public function __construct(\Magento\Framework\ObjectManagerInterface $objectManager,
                               \Rikai\RewardPoint\Model\RewardPointFactory $rewardPointFactory
    ) {                             
        $this->_objectManager = $objectManager;
        $this->_rewardPointFactory = $rewardPointFactory;
    }
    public function getConfig()
    {
        $customerSession = $this->_objectManager->create('Magento\Customer\Model\Session');
        $additionalVariables['customer_reward_point'] = $this->getRewardPointData($customerSession->getCustomerId());
        return $additionalVariables;
    }

    public function getRewardPointData($customerId){
        if($customerId != null){
            $rewardPoint = $this->_rewardPointFactory->create();
            $collection = $rewardPoint->getCollection();
            
            $data = $collection->addFieldToSelect('reward_point_total')
            ->addFieldToSelect('reward_point_available_balance')
            ->addFieldToFilter('parent_id',['eq' => $customerId])->getFirstItem()->getData();
            return $data;
        }    
    }
}
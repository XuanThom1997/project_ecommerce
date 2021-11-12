<?php
namespace Rikai\RewardPoint\Observer;
use Magento\Framework\Event\Observer;
use Magento\Framework\Event\ObserverInterface;
use Magento\Quote\Model\QuoteFactory;
class CancelOrder implements ObserverInterface
{
     /**
     * @var \Psr\Log\LoggerInterface
     */
    protected $_logger;

    protected $_rewardPointFactory;


    /**
     * Constructor
     *
     * @param \Psr\Log\LoggerInterface $logger
     */

    public function __construct(\Psr\Log\LoggerInterface $logger,
                               \Rikai\RewardPoint\Model\RewardPointFactory $rewardPointFactory
    ) {                             
        $this->_logger = $logger;
        $this->_rewardPointFactory = $rewardPointFactory;
    }

    public function execute(Observer $observer)
    {
        $order = $observer->getOrder();
        $this->setData($order->getSpentRewardPoint(),$order->getCustomerId());
        
    }

    public function setData($param, $customerId){
        if($customerId != null){
            $rewardPoint = $this->_rewardPointFactory->create();
            $collection = $rewardPoint->getCollection();
            
            $datas = $collection->addFieldToFilter('parent_id',['eq' => $customerId]);
           
            foreach($datas as $data){
                $data->setData('reward_point_available_balance',$data->getRewardPointAvailableBalance() + $param);
                $data->save();  
            }      

        }    
    }
}
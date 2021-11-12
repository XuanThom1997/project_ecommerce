<?php
namespace Rikai\RewardPoint\Observer;
use Magento\Framework\Event\Observer;
use Magento\Framework\Event\ObserverInterface;
use Magento\Quote\Model\QuoteFactory;
class PlaceOrder implements ObserverInterface
{
    /**
     * @var \Psr\Log\LoggerInterface
     */
    protected $_logger;

    /**
     * @var \Magento\Customer\Model\Session
     */
    protected $quoteFactory;
    protected $_rewardPointFactory;

    /**
     * Constructor
     *
     * @param \Psr\Log\LoggerInterface $logger
     */

    public function __construct(
        \Psr\Log\LoggerInterface $logger,                      
        \Rikai\RewardPoint\Model\RewardPointFactory $rewardPointFactory,                       
        \Magento\Quote\Model\QuoteFactory $quoteFactory) 
    {
        $this->_logger = $logger;
        $this->_rewardPointFactory = $rewardPointFactory;
        $this->quoteFactory =   $quoteFactory;
    }

    public function execute(Observer $observer)
    {
            $order = $observer->getOrder();
            $quoteId = $order->getQuoteId();
            $quote  =   $this->quoteFactory->create()->load($quoteId);        
            $this->setData($order,$quote);
    }

    public function setCustomerRewardPoint($spentRewardPoint,$customerId){
        $rewardPoint = $this->_rewardPointFactory->create();
        $collection = $rewardPoint->getCollection();
        $datas = $collection->addFieldToFilter('parent_id',['eq' => $customerId]);
        if(count($datas) != 0){
            foreach($datas as $data){
                $data->setData('reward_point_available_balance',$data->getRewardPointAvailableBalance() - $spentRewardPoint);
                $data->save();  
            }    
        }       
    }

    public function setData($order,$quote){
        $spentRewardPoint = $quote->getData('spent_reward_point');
        $rewardPoint =   $quote->getBaseSubtotal()/1000;
        $customerId = $order->getCustomerId();
        if($customerId != null){
            $quote->setGrandTotal($order->getGrandTotal() - $spentRewardPoint);
            $quote->setBaseGrandTotal($order->getBaseGrandTotal() - $spentRewardPoint);
            $quote->setData('reward_point',$rewardPoint)->save();
            $this->setCustomerRewardPoint($spentRewardPoint, $customerId);   
            $order->setRewardPoint($rewardPoint);
            $order->setSpentRewardPoint($spentRewardPoint);
            $order->setGrandTotal($order->getGrandTotal() - $spentRewardPoint);
            $order->setBaseGrandTotal($order->getBaseGrandTotal() - $spentRewardPoint);
            $order->save();
        }
    }
}
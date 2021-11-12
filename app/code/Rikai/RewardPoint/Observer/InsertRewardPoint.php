<?php
namespace Rikai\RewardPoint\Observer;
use Magento\Framework\Event\Observer;
use Magento\Framework\Event\ObserverInterface;
use Magento\Quote\Model\QuoteFactory;
class InsertRewardPoint implements ObserverInterface
{
    /**
     * @var \Psr\Log\LoggerInterface
     */
    protected $_logger;

    protected $_rewardPointFactory;

    /**
     * @var \Magento\Customer\Model\Session
     */
    protected $quoteFactory;
    protected $customer;

    /**
     * Constructor
     *
     * @param \Psr\Log\LoggerInterface $logger
     */

    public function __construct(\Psr\Log\LoggerInterface $logger,
                               \Magento\Quote\Model\QuoteFactory $quoteFactory,
                               \Magento\Customer\Model\Session $customer,
                               \Rikai\RewardPoint\Model\RewardPointFactory $rewardPointFactory
    ) {                             
        $this->customer = $customer;
        $this->_logger = $logger;
        $this->_rewardPointFactory = $rewardPointFactory;
        $this->quoteFactory =   $quoteFactory;
    }

    public function execute(Observer $observer)
    {
        $order = $observer->getEvent()->getInvoice()->getOrder();
        $this->setData($order->getRewardPoint(),$order->getCustomerId());
        $this->updateOrder($order);
    }

    public function setData($param, $customerId){
        if($customerId != null){
            $rewardPoint = $this->_rewardPointFactory->create();
            $collection = $rewardPoint->getCollection();
            
            $datas = $collection->addFieldToFilter('parent_id',['eq' => $customerId]);
            if(count($datas) == 0){
                $rewardPoint->addData([
                    'parent_id' => $customerId,
                    'reward_point_available_balance' => $param,
                    'reward_point_total' => $param
                ])->save();
            }else{
                foreach($datas as $data){
                    $data->setData('reward_point_available_balance',$data->getRewardPointAvailableBalance() + $param);
                    $data->setData('reward_point_total',$data->getRewardPointTotal() + $param);
                    $data->save();  
                }      
            }  
        }    
    }

    public function updateOrder($order){
        $spent = $order->getSpentRewardPoint();
        $order->setTotalInvoiced($order->getTotalInvoiced()  - $spent );
        $order->setBaseTotalInvoiced($order->getBaseTotalInvoiced() - $spent);
        $order->setTotalPaid($order->getTotalPaid()  - $spent);
        $order->setBaseTotalPaid($order->getBaseTotalPaid() - $spent);
        $order->save();
    }
}
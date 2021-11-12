<?php 
namespace Rikai\RewardPoint\Block;

class RewardPoint extends \Magento\Framework\View\Element\Template
{
    protected $_rewardPointFacrory;

    protected $_priceCurrencyInterface;

    protected $_objectManager;

    protected $_helperData;

    public function __construct(\Magento\Framework\View\Element\Template\Context $context,
                                \Rikai\RewardPoint\Model\RewardPointFactory $rewardPointFacrory,
                                \Magento\Framework\ObjectManagerInterface $objectManager,
                                \Magento\Framework\Pricing\PriceCurrencyInterface $priceCurrencyInterface,
                                \Rikai\RewardPoint\Helper\Data $helperData,
                                array $data = [])
    {
        $this->_rewardPointFactory = $rewardPointFacrory;
        $this->_helperData = $helperData;
        $this->_objectManager = $objectManager;
        $this->_priceCurrencyInterface = $priceCurrencyInterface;
        parent::__construct($context, $data);
    }

    public function loadData(){
        $collection = $this->_rewardPointFactory->create()->getCollection();
        $customerSession = $this->_objectManager->create('Magento\Customer\Model\Session');
        $data = $collection->addFieldToFilter('parent_id',$customerSession->getCustomerId())->getFirstItem();
        return $data;
    }

    public function getAvailableBalance(){
        return $this->loadData()->getRewardPointAvailableBalance();
    }

    public function getEarned(){
        return $this->loadData()->getRewardPointTotal();
    }

    public function getSpent(){
        $spent = $this->loadData()->getRewardPointTotal() - $this->loadData()->getRewardPointAvailableBalance();
        return $spent;
    }

    public function getEarningRate(){
        $currentCode =$this->_priceCurrencyInterface->getCurrency()->getCurrencySymbol();
        $html = '';
        $html .= 'Each <strong>1000 '.$currentCode.'</strong> spent for your order will earn <strong>1 Reward</strong></span>';

        return $html;
    }

    public function getSpendingRate(){
        $currentCode =$this->_priceCurrencyInterface->getCurrency()->getCurrencySymbol();
        $html = '';
        $html .= 'Each <strong>1 Reward</strong> can be redeemed for <strong>1 '.$currentCode.'</strong></span>';

        return $html;
    }

    public function getExpired(){
        $day = $this->_helperData->compareDateTime($this->loadData());
        $html = '';
        $html .= 'You just used your reward points '.$day.' ago';

        return $html;
    }

}
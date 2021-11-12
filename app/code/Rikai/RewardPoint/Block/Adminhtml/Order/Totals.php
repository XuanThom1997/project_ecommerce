<?php 

namespace Rikai\RewardPoint\Block\Adminhtml\Order;

class Totals extends \Magento\Sales\Block\Adminhtml\Order\Totals{
    protected function _initTotals()
    {
        parent::_initTotals();
        if ($this->getSource()->getCustomerId() != null) {
            $this->_totals['earned'] = new \Magento\Framework\DataObject(
                [
                    'code' => 'earned', 
                    'value' => $this->getSource()->getRewardPoint(),
                    'label' => __('Earned')]
            );
            $this->_totals['spent'] = new \Magento\Framework\DataObject(
                ['code' => 'spent', 'value' => $this->getSource()->getSpentRewardPoint(), 'label' => __('Spent')]
            );
            $this->_totals['ODDPOINTs'] = new \Magento\Framework\DataObject(
                ['code' => 'ODDPOINTs', 'value' => -$this->getSource()->getSpentRewardPoint(), 'label' => __('ODDPOINTs')]
            );
        }
       
        return $this;
    }
    public function formatValue($total)
    {
        if (!$total->getIsFormated()) {
            if($total->getCode() === 'earned' || $total->getCode() === 'spent'){
                return $this->formatPoint($total->getValue());
            }else{
                return $this->getOrder()->formatPrice($total->getValue());
            }             
        }
        return $total->getValue();
    }

    public function formatPoint($point){
        $html = '';
        if($point > 1){
            $html .= $point.' '.__('Rewards');
        }else{
            $html .= $point.' '.__('Reward');
        } return $html;
    }
}
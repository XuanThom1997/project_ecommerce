<?php 

namespace Rikai\RewardPoint\Block\Order;

class Totals extends \Magento\Sales\Block\Order\Totals{
    protected function _initTotals()
    {
        $source = $this->getSource();

        $this->_totals = [];
        $this->_totals['subtotal'] = new \Magento\Framework\DataObject(
            ['code' => 'subtotal', 'value' => $source->getSubtotal(), 'label' => __('Subtotal')]
        );

        if ($this->getSource()->getCustomerId() != null) {
            if($source->getRewardPoint() != null){
                $this->_totals['earned'] = new \Magento\Framework\DataObject(
                    ['code' => 'earned', 'value' => $source->getRewardPoint(), 'label' => __('Earned')]
                );
            }
            if($source->getSpentRewardPoint() != null){
                $this->_totals['spent'] = new \Magento\Framework\DataObject(
                    ['code' => 'spent', 'value' => $source->getSpentRewardPoint(), 'label' => __('Spent')]
                );
                $this->_totals['ODDPOINTs'] = new \Magento\Framework\DataObject(
                    ['code' => 'ODDPOINTs', 'value' => -$source->getSpentRewardPoint(), 'label' => __('ODDPOINTs')]
                );
            }   
        }

        /**
         * Add discount
         */
        if ((double)$this->getSource()->getDiscountAmount() != 0) {
            if ($this->getSource()->getDiscountDescription()) {
                $discountLabel = __('Discount (%1)', $source->getDiscountDescription());
            } else {
                $discountLabel = __('Discount');
            }
            $this->_totals['discount'] = new \Magento\Framework\DataObject(
                [
                    'code' => 'discount',
                    'field' => 'discount_amount',
                    'value' => $source->getDiscountAmount(),
                    'label' => $discountLabel,
                ]
            );
        }


        /**
         * Add shipping
         */
        if (!$source->getIsVirtual() && ((double)$source->getShippingAmount() || $source->getShippingDescription())) {
            $label = __('Shipping & Handling');
            if ($this->getSource()->getCouponCode() && !isset($this->_totals['discount'])) {
                $label = __('Shipping & Handling (%1)', $this->getSource()->getCouponCode());
            }

            $this->_totals['shipping'] = new \Magento\Framework\DataObject(
                [
                    'code' => 'shipping',
                    'field' => 'shipping_amount',
                    'value' => $this->getSource()->getShippingAmount(),
                    'label' => $label,
                ]
            );
        }

        $this->_totals['grand_total'] = new \Magento\Framework\DataObject(
            [
                'code' => 'grand_total',
                'field' => 'grand_total',
                'strong' => true,
                'value' => $source->getGrandTotal(),
                'label' => __('Grand Total'),
            ]
        );

        /**
         * Base grandtotal
         */
        if ($this->getOrder()->isCurrencyDifferent()) {
            $this->_totals['base_grandtotal'] = new \Magento\Framework\DataObject(
                [
                    'code' => 'base_grandtotal',
                    'value' => $this->getOrder()->formatBasePrice($source->getBaseGrandTotal()),
                    'label' => __('Grand Total to be Charged'),
                    'is_formated' => true,
                ]
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
<?php

namespace Rikai\RewardPoint\Model;

class Cart extends \Magento\Checkout\Model\Cart

{
    public function save()
    {
        $this->_eventManager->dispatch('checkout_cart_save_before', ['cart' => $this]);

        $this->getQuote()->getBillingAddress();
        $this->getQuote()->getShippingAddress()->setCollectShippingRates(true);
        $this->getQuote()->collectTotals();
        $this->quoteRepository->save($this->getQuote());
        $this->updateQuote();
        $this->_checkoutSession->setQuoteId($this->getQuote()->getId());
        /**
         * Cart save usually called after changes with cart items.
         */
        $this->_eventManager->dispatch('checkout_cart_save_after', ['cart' => $this]);
        $this->reinitializeState();
        return $this;
    }

    public function updateQuote(){
        $quote = $this->quoteRepository->get($this->getQuote()->getId()); // Get quote by id
        if($quote->getData('customer_id') != null){
            $rewardPoint = $quote->getData('base_subtotal')/1000;
            $quote->setData('spent_reward_point',null);
            $quote->setData('reward_point', $rewardPoint); // Fill data
            $this->quoteRepository->save($quote); // Save quote
        }
    }
}
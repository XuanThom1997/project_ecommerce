define(
    [
        'jquery',
        'ko',
        'uiComponent',
        'Magento_Checkout/js/model/quote',
        'mage/translate',
        'Magento_Customer/js/model/customer',
        'Rikai_RewardPoint/js/model/quote-reward',
        'Magento_Catalog/js/price-utils'
    ],
    function ($,ko, Component, quote, $t, customer, quoteReward, priceUtils) {
        'use strict';
        var spentRewardPoint = ko.observable(quoteReward.spentRewardPoint());
        
        
        
        return Component.extend({
            defaults: {
                template: 'Rikai_RewardPoint/summary/rewardpoint'
            },

            initialize: function () {
                this.spentRewardPoint = ko.observable(spentRewardPoint()),
                this.isApplied = ko.observable(false);
                this._super();
            },
            totals: quote.getTotals(),

            /**
             * @return {*}
             */
            getRewardValue: function () {
                var reward;
                console.log(this.totals());
                reward = this.totals()['base_subtotal']/1000; 

                return this.getFormatReward(reward);
            },

            /**
            * @return {*|String}
            */
            getSpentValue: function () {
                var price = 0;
    
                if (this.totals()) {
                    price = this.totals()['base_subtotal'];
                }
    
                return price;
            },

            setSpentReward: function () {
                this.spentRewardPoint(10);
            },

            getSpentRewardPrice: function () {
                let price;

                if (this.totals()) {
                    price= -parseInt(this.totals()['spent_reward_point']);
                }
               
                return priceUtils.formatPrice(price)
            },
            
             /**
             * @return {Boolean}
             */
            isSpentRewardValue: function (){
                if(this.totals()['spent_reward_point'] != null){
                    return true;
                }
                return false;
            },

             /**
             * @return {*}
             */
            getSpentRewardValue: function(){
                let spent;

                if (this.totals()) {
                    spent = parseInt(this.totals()['spent_reward_point']);
                }
               
                return this.getFormatReward(spent);
            },

             /**
             * @return {*}
             */
            getFormatReward: function (reward){
                var formatReward; 
                reward = parseInt(reward);
                var transReward; 
                
                if(reward<2){
                    transReward =  $t('Reward');
                }else{
                    transReward =  $t('Rewards'); 
                }
                formatReward = reward + ' ' + transReward;

                return formatReward;
            },

            getTitleReward: function () {
                var title = $t('You will earn');

                return title;
            },

             /**
             * @return {Boolean}
             */
            isCustomerId: function () {
                return customer.isLoggedIn();
            },
        });
    }
);
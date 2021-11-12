define(
    [
        'jquery',
        'ko',
        'uiComponent',
        'mage/translate',
        'Magento_Checkout/js/model/quote',
        'Rikai_RewardPoint/js/action/set-spent-reward-point',
        'Magento_Customer/js/model/customer',
        'Rikai_RewardPoint/js/model/quote-reward',
        'Rikai_RewardPoint/js/view/summary/rewardpoint'
    ],
    function ($,ko, Component,$t, quote,setSpentRewardPointAction, customer, quoteReward, sumRewardPoint ) {
        'use strict';
        var isApplied = false,
        customerRewardPoint = window.checkoutConfig.customer_reward_point;

        return Component.extend({
            defaults: {
                template: 'Rikai_RewardPoint/applyrewardpoint'
            },

            initialize: function () {
                this.rewardPoint = ko.observable(quoteReward.spentRewardPoint()),
                this._super();
            },
            totals: quote.getTotals(),
            totalPoint: customerRewardPoint.reward_point_available_balance,

            /**
             * Applied flag
             */
            isApplied: isApplied,
    
            /**
             * Coupon code application procedure
             */
            applyrewardpoint: function () {
                if (this.validate()) {   
                    if(parseInt(this.rewardPoint()) > parseInt(this.totalPoint)){
                        quoteReward.spentRewardPoint(this.totalPoint);     
                        this.rewardPoint(this.totalPoint);    
                    }
                    setSpentRewardPointAction(this.rewardPoint(), true);             
                }
            },

            getSpentRewardPoint: function(){
                return this.rewardPoint;
            },

            getFormatReward: function (){
                var formatReward,
                transReward;
                
                if(this.totalPoint<2){
                    transReward =  $t('Reward');
                }else{
                    transReward =  $t('Rewards'); 
                }
                formatReward = this.totalPoint + ' ' + transReward;

                return formatReward;
            },

            /**
             * @return {Boolean}
             */
            isApplied2: function() {
                return isApplied;
            },

            /**
             * @return {Boolean}
             */
            setTF: function(TF){
                return TF;
            },
    
            /**
             * Cancel using coupon
             */
            cancel: function () {
                if (this.validate()) {
                    this.setTF(false);
                }
            },
    
            /**
             * Coupon form validation
             *
             * @returns {Boolean}
             */
            validate: function () {
                var form = '#reward-form';
    
                return $(form).validation() && $(form).validation('isValid');
            },

             /**
             * @return {Boolean}
             */
              isCustomer: function () {
                return customer.isLoggedIn();
            }
        });
    }
);
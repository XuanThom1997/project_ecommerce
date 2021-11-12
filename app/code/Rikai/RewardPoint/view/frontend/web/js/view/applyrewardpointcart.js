define(
    [
        'jquery',
        'ko',
        'uiComponent',
        'mage/translate',
        'Rikai_RewardPoint/js/action/set-spent-reward-point',
        'Magento_Customer/js/model/customer',
        'Rikai_RewardPoint/js/model/quote-reward',
        'Rikai_RewardPoint/js/view/summary/rewardpoint'
    ],
    function ($,ko, Component,$t,setSpentRewardPointAction, customer, quoteReward, sumRewardPoint ) {
        'use strict';
        var isApplied = false,
        customerRewardPoint = window.checkoutConfig.customer_reward_point;
        
        return Component.extend({
            defaults: {
                template: 'Rikai_RewardPoint/applyrewardpointcart'
            },

            initialize: function () {
                this.rewardPoint = ko.observable(quoteReward.spentRewardPoint()),
                this._super();
            },
            
            totalPoint: customerRewardPoint.reward_point_available_balance,

    
            /**
             * Coupon code application procedure
             */
            applyrewardpoint: function () {
                if (this.validate()) {   

                    if(parseInt(this.rewardPoint()) > parseInt(this.totalPoint)){
                        quoteReward.spentRewardPoint(this.totalPoint);
                        this.rewardPoint(this.totalPoint);           
                    }
                    setSpentRewardPointAction(this.rewardPoint(),false);                
                }
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
             * Coupon form validation
             *
             * @returns {Boolean}
             */
            validate: function () {
                var form = '#reward-form';
    
                return $(form).validation() && $(form).validation('isValid');
            },
        });
    }
);
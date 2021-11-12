/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */

/**
 * Customer store credit(balance) application
 */
 define([
    'ko',
    'jquery',
    'Magento_Checkout/js/model/quote',
    'Magento_Checkout/js/model/error-processor',
    'Magento_SalesRule/js/model/payment/discount-messages',
    'mage/storage',
    'mage/translate',
    'Magento_Checkout/js/model/totals',
    'Magento_Checkout/js/model/full-screen-loader',
    'Magento_Checkout/js/action/get-payment-information',
    'Rikai_RewardPoint/js/model/quote-reward',
    'Rikai_RewardPoint/js/model/full-screen-loader',
    'Magento_Checkout/js/action/get-totals',
], function (ko, $, quote, errorProcessor, messageContainer,  storage, $t, totals, fullScreenLoader,getPaymentInformationAction, quoteReward, fullScreenLoaders, getTotalsAction) {
    'use strict';

    var dataModifiers = [],
        successCallbacks = [],
        failCallbacks = [],
        action;

    /**
     * Apply provided coupon.
     *
     * @param {String} rewardPoint
     * @param {Boolean}isApplied
     * @returns {Deferred}
     */
    action = function (rewardPoint, $type) {
        var quoteId = quote.getQuoteId(),
            url = 'rest/all/V1/reward-point/mine/spent/',
            message = $t('Your spent reward point was successfully applied.'),
            data = {spent: rewardPoint
                ,quoteId: quoteId},
            headers = {'accept': 'application/json'};

        //Allowing to modify coupon-apply request
        dataModifiers.forEach(function (modifier) {
            modifier(headers, data);
        });
        if($type){
            fullScreenLoader.startLoader();
        }else{
            fullScreenLoaders.startLoader(); 
        }
         
        


        return storage.put(
            url,
            JSON.stringify(data),
            false,
            null,
            headers
        ).done(function (response) {
            
            var deferred;
           
            if (response) {
                deferred = $.Deferred();
                quoteReward.isLoading(true);
                totals.isLoading(true);
                getPaymentInformationAction(deferred);
                getTotalsAction([], deferred);
                $.when(deferred).done(function () {
                    if($type){
                        fullScreenLoader.stopLoader();
                   }else{
                       fullScreenLoaders.stopLoader();
                   }
                    quoteReward.isLoading(false);
                    totals.isLoading(false);
                });
                messageContainer.addSuccessMessage({
                    'message': message
                });
                //Allowing to tap into apply-coupon process.
                successCallbacks.forEach(function (callback) {
                    callback(response);
                });
            }
        }).fail(function (response) {
            console.log(response)
            quoteReward.spentRewardPoint(rewardPoint);
            if($type){
                 fullScreenLoader.stopLoader();
            }else{
                fullScreenLoaders.stopLoader();
            }
             
            quoteReward.isLoading(false);
            totals.isLoading(false);
            errorProcessor.process(response, messageContainer);
            //Allowing to tap into apply-coupon process.
            failCallbacks.forEach(function (callback) {
                callback(response);
            });
        });
    };

    /**
     * Modifying data to be sent.
     *
     * @param {Function} modifier
     */
    action.registerDataModifier = function (modifier) {
        dataModifiers.push(modifier);
    };

    /**
     * When successfully added a coupon.
     *
     * @param {Function} callback
     */
    action.registerSuccessCallback = function (callback) {
        successCallbacks.push(callback);
    };

    /**
     * When failed to add a coupon.
     *
     * @param {Function} callback
     */
    action.registerFailCallback = function (callback) {
        failCallbacks.push(callback);
    };

    return action;
});

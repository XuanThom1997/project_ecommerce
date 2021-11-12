/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */

/**
 * @api
 */
 define([
    'ko',

], function (ko) {
    'use strict';

    var quoteData = window.checkoutConfig.quoteData,
        rewardPoint =  quoteData.spent_reward_point,
        spentRewardPoint = ko.observable(rewardPoint),
        isLoading = ko.observable(false);
    return {
        spentRewardPoint: spentRewardPoint,
        isLoading: isLoading,
    };
});

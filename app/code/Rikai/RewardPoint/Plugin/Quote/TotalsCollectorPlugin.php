<?php

namespace Rikai\RewardPoint\Plugin\Quote;

use Magento\Quote\Model\Quote\TotalsCollector;
use Magento\Quote\Api\Data\TotalsInterface;

class TotalsCollectorPlugin
{
    /**
     * @var TotalsInterface
     */
    protected $totalsInterface;

    /**
     * TotalsCollectorPlugin constructor.
     *
     * @param TotalsInterface $totalsInterface
     */
    public function __construct(
        TotalsInterface $totalsInterface
    ) {
        $this->totalsInterface = $totalsInterface;
    }

    /**
     * @param TotalsCollector $subject
     *
     * @param $result
     * @return mixed
     */
    public function afterCollect(
        TotalsCollector $subject,
        TotalsInterface $result
    ) {
        $extensionAttributes = $result->getExtensionAttributes();
        if ($extensionAttributes === null) {
            $extensionAttributes = $this->totalsInterface->create();
        }
        $pointsDelta = 24;
        $extensionAttributes->setData('reward_point', $pointsDelta);
        $result->setExtensionAttributes($extensionAttributes);
        return $result;
    }
}
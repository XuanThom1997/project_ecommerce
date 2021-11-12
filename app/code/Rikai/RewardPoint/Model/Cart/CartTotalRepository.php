<?php



namespace Rikai\RewardPoint\Model\Cart;

use Magento\Quote\Api;
use Magento\Quote\Api\CartRepositoryInterface;
use Magento\Quote\Api\CartTotalRepositoryInterface;
use Magento\Framework\Api\DataObjectHelper;
use Magento\Framework\Api\ExtensibleDataInterface;
use Magento\Quote\Model\Cart\Totals\ItemConverter;
use Magento\Quote\Api\CouponManagementInterface;
use Magento\Quote\Api\Data\TotalsInterface as QuoteTotalsInterface;

class CartTotalRepository extends \Magento\Quote\Model\Cart\CartTotalRepository
{
     /**
     * Cart totals factory.
     *
     * @var \Magento\Quote\Api\Data\TotalsInterfaceFactory $totalsFactory
     */
    private $totalsFactory;

    /**
     * Quote repository.
     *
     * @var \Magento\Quote\Api\CartRepositoryInterface
     */
    private $quoteRepository;

    /**
     * @var \Magento\Framework\Api\DataObjectHelper
     */
    private $dataObjectHelper;

    /**
     * @var ItemConverter
     */
    private $itemConverter;

    
    /**
     * @var CouponManagementInterface
     */
    protected $couponService;

    /**
     * @var TotalsConverter
     */
    protected $totalsConverter;

    /**
     * @param \Magento\Quote\Api\Data\TotalsInterfaceFactory $totalsFactory
     * @param CartRepositoryInterface $quoteRepository
     * @param DataObjectHelper $dataObjectHelper
     * @param ItemConverter $converter
     * @param TotalsConverter $totalsConverter
     * @param CouponManagementInterface $couponService
     */
    public function __construct(
        \Magento\Quote\Api\Data\TotalsInterfaceFactory $totalsFactory,
        CartRepositoryInterface $quoteRepository,
        DataObjectHelper $dataObjectHelper,
        CouponManagementInterface $couponService,
        \Magento\Quote\Model\Cart\TotalsConverter $totalsConverter,
        ItemConverter $converter
    ) {
        $this->totalsFactory = $totalsFactory;
        $this->quoteRepository = $quoteRepository;
        $this->dataObjectHelper = $dataObjectHelper;
        $this->itemConverter = $converter;
        $this->totalsConverter = $totalsConverter;
        $this->couponService = $couponService;
    }
    /**
     * @inheritdoc
     */
    public function get($cartId):  QuoteTotalsInterface
    {
        /** @var \Magento\Quote\Model\Quote $quote */
        $quote = $this->quoteRepository->getActive($cartId);
        if ($quote->isVirtual()) {
            $addressTotalsData = $quote->getBillingAddress();
            $addressTotals = $quote->getBillingAddress();
        } else {
            $addressTotalsData = $quote->getShippingAddress();
            $addressTotals = $quote->getShippingAddress();
        }
        if($quote->getData('spent_reward_point') > 0){
            $addressTotalsData->setGrandTotal($addressTotalsData->getGrandTotal() - $quote->getData('spent_reward_point'));
            $addressTotalsData->setBaseGrandTotal($addressTotalsData->getBaseGrandTotal() - $quote->getData('spent_reward_point'));
        }
        unset($addressTotalsData[ExtensibleDataInterface::EXTENSION_ATTRIBUTES_KEY]);

        /** @var QuoteTotalsInterface $quoteTotals */
        $quoteTotals = $this->totalsFactory->create();
        $this->dataObjectHelper->populateWithArray(
            $quoteTotals,
            $addressTotalsData->getData(),
            QuoteTotalsInterface::class
        );
        $items = array_map([$this->itemConverter, 'modelToDataObject'], $quote->getAllVisibleItems());
        $calculatedTotals = $this->totalsConverter->process($addressTotals->getTotals());
        $quoteTotals->setTotalSegments($calculatedTotals);

        $amount = $quoteTotals->getGrandTotal() - $quoteTotals->getTaxAmount(); 
        $amount = $amount > 0 ? $amount : 0;
        $quoteTotals->setCouponCode($this->couponService->get($cartId));
        $quoteTotals->setGrandTotal($amount);
        $quoteTotals->setBaseGrandTotal($amount);
        $quoteTotals->setItems($items);
        $quoteTotals->setItemsQty($quote->getItemsQty());
        $quoteTotals->setBaseCurrencyCode($quote->getBaseCurrencyCode());
        $quoteTotals->setQuoteCurrencyCode($quote->getQuoteCurrencyCode());
        $quoteTotals->setRewardPoint($quote->getData('reward_point'));
        $quoteTotals->setSpentRewardPoint($quote->getData('spent_reward_point'));
  
        return $quoteTotals;
    }
}

<?php
 
namespace Rikai\RewardPoint\controller\Mine;

use Magento\Quote\Api;
use Magento\Quote\Api\CartRepositoryInterface;
use Magento\Quote\Api\CartTotalRepositoryInterface;
use Magento\Framework\Api\DataObjectHelper;
use Magento\Framework\Api\ExtensibleDataInterface;
use Magento\Quote\Model\Cart\Totals\ItemConverter;
use Magento\Quote\Api\CouponManagementInterface;
use Magento\Quote\Model\Cart\TotalsConverter;
use Magento\Quote\Api\Data\TotalsInterface as QuoteTotalsInterface;
 
class Index extends \Magento\Framework\App\Action\Action
{
    protected $json;
    protected $resultJsonFactory;
    protected $_configInterface;
    protected $cartTotalRepository;
    private $checkoutSession;
    private $quoteRepository;
    private $totalsFactory;

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


    public function __construct(
        \Magento\Framework\App\Action\Context $context,
        \Magento\Quote\Api\Data\TotalsInterfaceFactory $totalsFactory,
        \Magento\Checkout\Model\Session $checkoutSession,
        \Magento\Framework\App\Config\ConfigResource\ConfigInterface $configInterface,
        \Magento\Quote\Api\CartRepositoryInterface $quoteRepository,
        \Magento\Quote\Api\CartTotalRepositoryInterface $cartTotalRepository,
        DataObjectHelper $dataObjectHelper,
        CouponManagementInterface $couponService,
        TotalsConverter $totalsConverter,
        ItemConverter $converter
       )
    {
        $this->totalsFactory = $totalsFactory;
        $this->_configInterface = $configInterface;
        $this->checkoutSession = $checkoutSession;
        $this->cartTotalRepository = $cartTotalRepository;
        $this->quoteRepository = $quoteRepository;
        $this->dataObjectHelper = $dataObjectHelper;
        $this->couponService = $couponService;
        $this->totalsConverter = $totalsConverter;
        $this->itemConverter = $converter;
        parent::__construct($context);
    }
 
    public function execute()
    {
        $cartId = $this->checkoutSession->getQuote()->getId();
        $quote = $this->quoteRepository->getActive($this->checkoutSession->getQuote()->getId());
        // echo '<pre>';
        // var_dump( $quote->getData());
        // echo '</pre>';  
        // exit;
        
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
            // $addressTotals->setGrandTotal($addressTotals->getGrandTotal() - $quote->getData('spent_reward_point'));
            // $addressTotals->setBaseGrandTotal($addressTotals->getBaseGrandTotal() - $quote->getData('spent_reward_point'));
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
        // echo '<pre>';
        // var_dump(  $addressTotals->getGrandTotal());
        // echo '</pre>';  
        // exit;
        
        $amount = $quoteTotals->getGrandTotal() - $quoteTotals->getTaxAmount();   
        $amount = $amount > 0 ? $amount : 0;
        $quoteTotals->setCouponCode($this->couponService->get($cartId));
        $quoteTotals->setGrandTotal($amount);
        $quoteTotals->setItems($items);
        $quoteTotals->setItemsQty($quote->getItemsQty());
        $quoteTotals->setBaseCurrencyCode($quote->getBaseCurrencyCode());
        $quoteTotals->setQuoteCurrencyCode($quote->getQuoteCurrencyCode());
        $quoteTotals['reward_points'] = $quote->getData('reward_point');
        $quoteTotals['spent_reward_point'] =  $quote->getData('spent_reward_point');
        // echo '<pre>';
        // var_dump( $quoteTotals->getData('reward_point'));
        // echo '</pre>';    
        // exit;
        $totals = $this->cartTotalRepository->get($this->checkoutSession->getQuote()->getId());
        $totals = $quoteTotals;
        // echo '<pre>';
        // var_dump( $totals->getData());
        // echo '</pre>';    
        // exit;
        $items = [];
        /** @var  \Magento\Quote\Model\Cart\Totals\Item $item */
        foreach ($totals->getItems() as $item) {
            $items[] = $item->__toArray();
        }
        $totalSegmentsData = [];

        /** @var \Magento\Quote\Model\Cart\TotalSegment $totalSegment */
        foreach ($totals->getTotalSegments() as $totalSegment) {
            // if($totalSegment->getCode() == 'grand_total'){
            //     $totalSegment->setValue(10000);
            // }
            $totalSegmentArray = $totalSegment->toArray();
            if (is_object($totalSegment->getExtensionAttributes())) {
                $totalSegmentArray['extension_attributes'] = $totalSegment->getExtensionAttributes()->__toArray();
            }
          
            $totalSegmentsData[] = $totalSegmentArray;
        }
        // echo '<pre>';
        // var_dump($totalSegmentsData);
        // echo '</pre>';    
        // exit;
        $totals->setItems($items);
        $totals->setTotalSegments($totalSegmentsData);
        $totalsArray = $totals->toArray();
        if (is_object($totals->getExtensionAttributes())) {
            $totalsArray['extension_attributes'] = $totals->getExtensionAttributes()->__toArray();
        }
        // $totalsArray['reward_point'] = 11;
        echo '<pre>';
        var_dump($totalsArray);
        echo '</pre>';    
        exit;
    }
}
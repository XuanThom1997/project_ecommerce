<?php
namespace Rikai\RewardPoint\Model;


class UpdateQuote
{
      /**
     * @var \Magento\Quote\Model\QuoteRepository
     */
    protected $quoteRepository;

    /**
     * Plugin constructor.
     *
     * @param \Magento\Quote\Model\QuoteRepository $quoteRepository
     */
    public function __construct(
        \Magento\Quote\Model\QuoteRepository $quoteRepository
    ){
        $this->quoteRepository = $quoteRepository;
    }

    /**
     * @param \Magento\Checkout\Model\Cart $subject
     * @param $data
     * @return array
     */
    public function beforeupdateQuoteData($quoteId, int $customData)
    {
        $quote = $this->quoteRepository->get($quoteId); // Get quote by id
        $quote->setData('reward_point', $customData); // Fill data
        $this->quoteRepository->save($quote); // Save quote
    }
}
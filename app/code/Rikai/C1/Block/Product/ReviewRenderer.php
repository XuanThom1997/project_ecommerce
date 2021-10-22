<?php

namespace Rikai\C1\Block\Product;

class ReviewRenderer extends \Magento\Review\Block\Product\ReviewRenderer

{
    public function getReviewsCount()
    {
        if($this->getProduct()->getReviewsCount()){
            return $this->getProduct()->getReviewsCount();
        }
        return 0;    
    }
}
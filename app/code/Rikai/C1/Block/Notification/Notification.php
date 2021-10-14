<?php

namespace Rikai\C1\Block\Notification;

use Magento\Framework\View\Element\Template;

class Notification extends Template{
    
    public function __construct(\Magento\Framework\View\Element\Template\Context $context)
	{
		parent::__construct($context);
	}

	public function getLine()
	{
		return __('News notification');
	}
}
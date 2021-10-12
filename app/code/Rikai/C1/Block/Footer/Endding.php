<?php
namespace Rikai\C1\Block\Footer;

use Magento\Framework\View\Element\Template;

class Endding extends Template
{
    public function __construct(\Magento\Framework\View\Element\Template\Context $context)
	{
		parent::__construct($context);
	}

	public function getLine()
	{
		return __('Hello World');
	}
}

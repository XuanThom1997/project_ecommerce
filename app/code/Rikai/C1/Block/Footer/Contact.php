<?php
namespace Rikai\C1\Block\Footer;

use Magento\Framework\View\Element\Template;

class Contact extends Template
{

    public function __construct(\Magento\Framework\View\Element\Template\Context $context)
	{
		parent::__construct($context);
	}

    public function _beforeToHtml(){
        $config = $this->_scopeConfig;
        $linkFacebook = $config->getValue('social/social_config/facebook');
        $this->setData('link_facebook', $linkFacebook);
        $linkTwitter = $config->getValue('social/social_config/twitter');
        $this->setData('link_twitter', $linkTwitter);
        $linkInstagram = $config->getValue('social/social_config/instagram');
        $this->setData('link_instagram', $linkInstagram);
    }
}

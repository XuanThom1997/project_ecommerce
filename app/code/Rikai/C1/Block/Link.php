<?php 
namespace Rikai\C1\Block;
use Magento\Framework\View\Element\Template\Context;

class Link extends \Magento\Framework\View\Element\Template
{

    protected $_urlInterface;

    public function __construct(Context $context,\Magento\Framework\UrlInterface $urlInterface) 
    {
        $this->_urlInterface = $urlInterface;
        parent::__construct($context);
    }

    /**
     * Render block HTML
     * 
     * @return string
     */

    public function notIsLoggedIn(){
        $html = '';
        $html.= '<div class="login-link"><a href="'.$this->_urlInterface->getUrl('customer/account/login').'" ><i class="fas fa-sign-in-alt"></i></a></div>';
        
        return $html;
    }

    public function isLoggedIn(){
        $html = '';
        $html.= '<div class="dropdown-account"><a href="'.$this->_urlInterface->getUrl('customer/account').'" ><i class="fas fa-user-circle"></i></a>';
        $html.='<div class="dropdown-account-content">
                    <ul>
                        <li><a href="'.$this->_urlInterface->getUrl('customer/account').'">'.__('My Account').'</a></li>
                        <li><a href="'.$this->_urlInterface->getUrl('sales/order/history').'">'.__('My Order').'</a></li>
                        <li><a href="'.$this->_urlInterface->getUrl('reward/customer').'">'.__('Reward Point').'</a></li>
                        <li><a href="'.$this->_urlInterface->getUrl('customer/account/logout').'">'.__('Logout').'</a></li>
                    </ul>
                </div></div>';


        return $html;
    }

    public function test(){
        return $this->_urlInterface->getUrl('customer/account');
    }

}
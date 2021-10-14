<?php 
namespace Rikai\C1\Block;

class Link extends \Magento\Framework\View\Element\Html\Link
{
    /**
     * Render block HTML
     * 
     * @return string
     */
    protected function _toHtml(){
        if(false != $this->getTemplate()){
            return parent::_toHtml();
        }
    	return '<div class="login-link">
		<a '.$this->getLinkAttributes().'>'.$this->escapeHtml($this->getLabel()).'
		</a>
		</div>';   
    }
}
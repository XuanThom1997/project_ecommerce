<?php

namespace Rikai\C1\Controler;

class index extends \Magento\Framework\App\Action\Action{

    public function __construct(\Magento\Framework\App\Action\Context $context)
    {
        parent::__construct($context);
    }

    public function execute(){
        echo "test";
    }

}
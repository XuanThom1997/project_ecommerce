<?php
 
namespace Rikai\RewardPoint\controller\Mine;
use Magento\Framework\Controller\Result\JsonFactory;
use Magento\Framework\App\Action\Context;
class Test extends \Magento\Framework\App\Action\Action
{
    protected $zendClient;
    protected $resultJsonFactory;
   public function __construct(
        \Zend\Http\Client $zendClient,
        JsonFactory $resultJsonFactory, 
        Context $context
    ) {
        $this->resultJsonFactory = $resultJsonFactory;
        $this->zendClient = $zendClient;
        parent::__construct($context);  
    }
 
    public function execute()
    {
        echo("aaa");
        try 
       {
            $this->zendClient->reset();
            $this->zendClient->setUri('https://dev-online-gateway.ghn.vn/shiip/public-api/v2/shipping-order/available-services');
            $this->zendClient->setMethod(\Zend\Http\Request::METHOD_POST); 
       	    $this->zendClient->setHeaders([
                'Content-Type' => 'application/json',
                'token' => '76be816b-3c8e-11ec-ac64-422c37c6de1b',
       	    ]);
       	    $this->zendClient->setParameterPost([
                "shop_id"=>82861,
                "from_district"=> 1447,
                "to_district"=> 1442
            ]);
       	    $this->zendClient->send();
            $response = $this->zendClient->getResponse();
        }
        catch (\Zend\Http\Exception\RuntimeException $runtimeException) 
        {
            echo $runtimeException->getMessage();
        }
        echo"<pre>";
        var_dump($response);
        echo"</pre>";
        exit;
        return $response;
       
    }
}
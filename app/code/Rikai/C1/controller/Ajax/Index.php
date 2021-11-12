<?php
 
namespace Rikai\C1\controller\Ajax;
 
class Index extends \Magento\Framework\App\Action\Action
{
    protected $json;
    protected $resultJsonFactory;
    protected $_configInterface;
    protected $_cacheTypeList;
    protected $_cacheFrontendPool;
    public function __construct(
        \Magento\Framework\App\Action\Context $context,
        \Magento\Framework\App\Config\ConfigResource\ConfigInterface $configInterface,
        \Magento\Framework\App\Cache\TypeListInterface $cacheTypeList,
        \Magento\Framework\App\Cache\Frontend\Pool $cacheFrontendPool)
    {
        $this->_configInterface = $configInterface;
        $this->_cacheTypeList = $cacheTypeList;
        $this->_cacheFrontendPool = $cacheFrontendPool;
        parent::__construct($context);
    }
 
    public function execute()
    {
        // //lấy dữ liệu từ ajax gửi sang
         $response = $this->getRequest()->getParams();
         $value = $response['value'];
         //echo  $value;
         $this->_configInterface->saveConfig('general/locale/code', $value, 'default', 0);
        //echo  $this->_config->getValue('general/locale/code', \Magento\Store\Model\ScopeInterface::SCOPE_STORE,0);
        $types = array('translate');
        foreach ($types as $type) {
            $this->_cacheTypeList->cleanType($type);
        }
        foreach ($this->_cacheFrontendPool as $cacheFrontend) {
            $cacheFrontend->getBackend()->clean();
        }

        // /** @var \Magento\Framework\Controller\Result\Json $resultJson */
        // $resultJson = $this->resultJsonFactory->create();
        //  // chuyển kết quả về dạng object json và trả về cho ajax
        // return $resultJson->setData($response);
       
    }
}
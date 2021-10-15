<?php
/**
 * Copyright © 2015 Magento. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace Rikai\WidgetProduct\Helper;

/**
 * Contact base helper
 */
class Data extends \Magento\Framework\App\Helper\AbstractHelper
{
	protected $_scopeConfig;
	
	protected $_storeManager;
	
	protected $_date;
	
    protected $_catalogProductVisibility;
	
	protected $_url;

	protected $moduleManager;

	
	public function __construct(
		\Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig,
		\Magento\Store\Model\StoreManagerInterface $storeManager,
		\Magento\Framework\Stdlib\DateTime\DateTime $date,
		\Magento\Framework\Url $url,
		\Magento\Catalog\Model\Product\Visibility $catalogProductVisibility,
		\Magento\Widget\Helper\Conditions $conditionsHelper,
		\Magento\CatalogWidget\Model\Rule $rule,
		\Magento\Catalog\Model\CategoryFactory $categoryFactory,
		\Magento\Catalog\Model\Design $catalogDesign,
		\Magento\Catalog\Model\Category $category,
		\Magento\Framework\ObjectManagerInterface $objectManager,
		\Magento\Framework\Module\Manager $moduleManager
	) {
		$this->_scopeConfig = $scopeConfig;
		$this->_storeManager = $storeManager;
		$this->_date = $date;
		$this->_url = $url;
		$this->_catalogProductVisibility = $catalogProductVisibility;
		$this->_categoryFactory = $categoryFactory;
		$this->_catalogProductVisibility = $catalogProductVisibility;
		$this->_catalogDesign = $catalogDesign;
		$this->conditionsHelper = $conditionsHelper;
		$this->_category = $category;
		$this->_objectManager = $objectManager;
		$this->moduleManager = $moduleManager;
	}
	
	public function getStore(){
		return $this->_storeManager->getStore();
	}
	
	public function getStoreConfig($node, $storeId = NULL){
		if($storeId != NULL){
			return $this->_scopeConfig->getValue($node, \Magento\Store\Model\ScopeInterface::SCOPE_STORE, $storeId);
		}
		return $this->_scopeConfig->getValue($node, \Magento\Store\Model\ScopeInterface::SCOPE_STORE, $this->getStore()->getId());
	}
	
	
	/* Get product image size */
	public function getImageSize(){
		$ratio = $this->getStoreConfig('mpanel/catalog/picture_ratio');
		$maxWidth = $this->getStoreConfig('mpanel/catalog/max_width_image');
		$result = [];
        switch ($ratio) {
            // 1/1 Square
            case 1:
                $result = array('width' => round($maxWidth), 'height' => round($maxWidth));
                break;
            // 1/2 Portrait
            case 2:
                $result = array('width' => round($maxWidth), 'height' => round($maxWidth*2));
                break;
            // 2/3 Portrait
            case 3:
                $result = array('width' => round($maxWidth), 'height' => round(($maxWidth * 1.5)));
                break;
            // 3/4 Portrait
            case 4:
                $result = array('width' => round($maxWidth), 'height' => round(($maxWidth * 4) / 3));
                break;
            // 2/1 Landscape
            case 5:
                $result = array('width' => round($maxWidth), 'height' => round($maxWidth/2));
                break;
            // 3/2 Landscape
            case 6:
                $result = array('width' => round($maxWidth), 'height' => round(($maxWidth*2) / 3));
                break;
            // 4/3 Landscape
            case 7:
                $result = array('width' => round($maxWidth), 'height' => round(($maxWidth*3) / 4));
                break;
			default:
				$result = array('width' => round(170) , 'height' =>204);
				break;
        }

        return $result;
	}
	
	/* Get product image size for product details page*/
	public function getImageSizeForDetails() {
		$ratio = $this->getStoreConfig('mpanel/catalog/picture_ratio');
		$maxWidth = $this->getStoreConfig('mpanel/catalog/max_width_image_detail');
        $result = [];
        switch ($ratio) {
            // 1/1 Square
            case 1:
                $result = array('width' => round($maxWidth), 'height' => round($maxWidth));
                break;
            // 1/2 Portrait
            case 2:
                $result = array('width' => round($maxWidth), 'height' => round($maxWidth*2));
                break;
            // 2/3 Portrait
            case 3:
                $result = array('width' => round($maxWidth), 'height' => round(($maxWidth * 1.5)));
                break;
            // 3/4 Portrait
            case 4:
                $result = array('width' => round($maxWidth), 'height' => round(($maxWidth * 4) / 3));
                break;
            // 2/1 Landscape
            case 5:
                $result = array('width' => round($maxWidth), 'height' => round($maxWidth/2));
                break;
            // 3/2 Landscape
            case 6:
                $result = array('width' => round($maxWidth), 'height' => round(($maxWidth*2) / 3));
                break;
            // 4/3 Landscape
            case 7:
                $result = array('width' => round($maxWidth), 'height' => round(($maxWidth*3) / 4));
                break;
        }

        return $result;
    }
	
	public function getImageMinSize() {
        $ratio = $this->getStoreConfig('mpanel/catalog/picture_ratio');
        $result = [];
        switch ($ratio) {
            // 1/1 Square
            case 1:
                $result = array('width' => 80, 'height' => 80);
                break;
            // 1/2 Portrait
            case 2:
                $result = array('width' => 80, 'height' => 160);
                break;
            // 2/3 Portrait
            case 3:
                $result = array('width' => 80, 'height' => 120);
                break;
            // 3/4 Portrait
            case 4:
                $result = array('width' => 80, 'height' => 107);
                break;
            // 2/1 Landscape
            case 5:
                $result = array('width' => 80, 'height' => 40);
                break;
            // 3/2 Landscape
            case 6:
                $result = array('width' => 80, 'height' => 53);
                break;
            // 4/3 Landscape
            case 7:
                $result = array('width' => 80, 'height' => 60);
                break;
        }

        return $result;
    }
	
	public function getProductLabel($product){
		$html = '';
		$newLabel = $this->getStoreConfig('mpanel/catalog/new_label');
		$saleLabel = "";
		if($this->getStoreConfig('mpanel/catalog/sale_label') == 1){
			$saleLabel = 1;
		}
		// Sale label
		$price = $product->getPrice();
		$finalPrice = $product->getFinalPrice();
		$save = $price - $finalPrice;
		if(($finalPrice<$price) && ($saleLabel!='')){
			$percent = round(($save * 100) / $price);
			$html .= '<span class="product-label sale-label"><span>-'.$percent.'%</span></span>';
		}
		
		// New label
		$now = $this->_date->gmtDate();
		$dateTimeFormat = \Magento\Framework\Stdlib\DateTime::DATETIME_PHP_FORMAT;
		$newFromDate1 = $product->getNewsFromDate();
        $newFromDate = date($dateTimeFormat, strtotime($newFromDate1));
        $newToDate1 = $product->getNewsToDate();
        $newToDate = date($dateTimeFormat, strtotime($newToDate1));
		if((!(empty($newToDate1) && empty($newFromDate1)) && ($newFromDate1 < $now) && ($newToDate1 > $now) && $newLabel != '') || ($newLabel != '' && empty($newToDate1) && (!empty($newFromDate1)) && ($newFromDate1 < $now))){
			$html.='<span class="product-label new-label"><span>'.$newLabel.'</span></span>';
		}
		return $html;
	}
	
	//Check if product is in wishlist
	public function getWishlistCount(){
		$wishlist = $this->_wishlistItem->getWishlistItems();
		if(count($wishlist) > 0){
			$ct = count($wishlist);
		}else {
			$ct = 0;
		}
		return $ct;
	}
	
	public function getUrlBuilder(){
		return $this->_url;
	}
	
	public function getCountRelatedProduct($product) {
		$this->_itemCollection = $product->getRelatedProductCollection()->addAttributeToSelect(
            'required_options'
        )->setPositionOrder()->addStoreFilter();
		$this->_itemCollection->setVisibility($this->_catalogProductVisibility->getVisibleInCatalogIds());
		$this->_itemCollection->load();
		$countRelated = count($this->_itemCollection);
		return $countRelated;
	}
	public function checkLayoutPage($category) {
		$settings = $this->_catalogDesign->getDesignSettings($category);
		return $settings;
	}
	public function getConditions($conditions)
    {
        if ($conditions) {
            $conditions = $this->conditionsHelper->decode($conditions);
        }
        return $conditions;
    }
	public function getCategory($categoryId) 
	{
		$category = $this->_categoryFactory->create();
		$category->load($categoryId);
		return $category;
	}
	
	public function getCategoryProducts($categoryId) 
	{
		$products = $this->getCategory($categoryId)->getProductCollection();
		$products->addAttributeToSelect('*');
		return $products;
	}

	public function getModel($model)
	{
		return $this->_objectManager->create($model);
	}

	public function getCategories()
	{
		$rootCategoryId = $this->_storeManager->getStore()->getRootCategoryId();
		$categoriesArray = $this->_category
			->getCollection()
			->setStoreId($this->_storeManager->getStore()->getId())
			->addAttributeToSelect('*')
			->addAttributeToFilter('is_active', 1)
			->addAttributeToFilter('include_in_menu', 1)
			->addAttributeToFilter('path', array('like' => "1/{$rootCategoryId}/%"))
			->addAttributeToSort('path', 'asc')
			->load()
			->toArray();
		$categories = array();
		foreach ($categoriesArray as $categoryId => $category) {
			if (isset($category['name'])) {
				$categories[] = array(
					'label' => $category['name'],
					'level' => $category['level'],
					'value' => $categoryId
				);
			}
		}
		return $categories;
	}
	
	public function getCategoryName($product,$baseName,$categories){
		if($categories != null){
			$_catName = $categories->getName();
		}else{
			$cats = $product->getCategoryIds();
			if(count($cats) > 0){
				$j=0; 
				foreach ($cats as $category_id){
					$j++;
					if($j == 3){
						break;
					}
					$category = $this->_categoryFactory->create();
					$category->load($category_id);
					$_catName = $category->getName();
				}
			}else{
				$_catName = $baseName;
			}
		}
		return $_catName;
	}

	public function checkActiveModule($moduleName) {
		if ($this->moduleManager->isOutputEnabled($moduleName)) {
		    return true;
		} else {
		    return false;
		}
	}
}
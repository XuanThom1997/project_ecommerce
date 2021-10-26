<?php
/**
 * Copyright © 2015 Magento. All rights reserved.
 * See COPYING.txt for license details.
 */

/**
 * Used in creating options for Yes|No config value selection
 *
 */
namespace Rikai\WidgetProduct\Model\Config\Source;

class ListProduct implements \Magento\Framework\Option\ArrayInterface
{
    /**
     * Options getter
     *
     * @return array
     */
    public function toOptionArray()
    {
        return [
			['value' => 'new', 'label' => __('New Product')],
			['value' => 'sale', 'label' => __('Sale')],
			['value' => 'top_rate', 'label' => __('Top Rate')],
			['value' => 'attribute', 'label' => __('Filter By Attribute')],
            ['value' => 'most_view', 'label' => __('Most View')],
            ['value' => 'recen_view', 'label' => __('Recen View')]
		];
    }
}

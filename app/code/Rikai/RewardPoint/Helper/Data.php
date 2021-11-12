<?php
/**
 * Copyright © 2015 Magento. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace Rikai\RewardPoint\Helper;

/**
 * Contact base helper
 */
class Data extends \Magento\Framework\App\Helper\AbstractHelper
{
	
	public function compareDateTime($data){
        $oldDate = $data->getUpdatedAt();
        $currentDate =  date('Y-m-d h:i:s');
        $diff = abs(strtotime($currentDate) - strtotime($oldDate));
        $months = $days = $hours = $minutes = $seconds = 0;
        $years = floor($diff / (365*60*60*24));

        if($years < 1){
            $months = floor($diff / (30*60*60*24));
        }
        if($months < 1){
            $days = floor($diff / (60*60*24));  
        }
        if($days < 1){
            $hours = floor($diff / (60*60));
        }
        if($hours < 1){
            $minutes = floor($diff / 60);
        }
        if($minutes < 1){
            $seconds = $diff;
        }
        
        $html = '';
        if($years>0){ 
            if($years>1){
                $html .= $years.' '.__('years');
            }else{
                $html .= $years.' '.__('year');
            }
            
        }elseif($months>0){
            if($months>1){
                $html .= $months.' '.__('months');
            }else{
                $html .= $months.' '.__('month');
            }  
        }elseif($days>0){
            if($days>1){
                $html .= $days.' '.__('days');
            }else{
                $html .= $days.' '.__('day');
            }  
        }elseif($hours>0){
            if($hours>1){
                $html .= $hours.' '.__('hours');
            }else{
                $html .= $hours.' '.__('hour');
            }  
        }elseif($minutes>0){
            if($minutes>1){
                $html .= $minutes.' '.__('minutes');
            }else{
                $html .= $minutes.' '.__('minute');
            }  
        }elseif($seconds>0){
            if($seconds>1){
                $html .= $seconds.' '.__('seconds');
            }else{
                $html .= $seconds.' '.__('second');
            }  
        }
        return $html;
    }
}
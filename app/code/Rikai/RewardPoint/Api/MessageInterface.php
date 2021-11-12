<?php
 
namespace Rikai\RewardPoint\Api;
 
/**
 * Interface MessageInterface
 * @package Riaki\RewardPoint\Api
 */
interface MessageInterface
{
    /**
     * Returns greeting message to user
     * @param int $spent
     * @param int $quoteId
     * @return string Greeting message with users name
     */
    public function save($spent, $quoteId);
}
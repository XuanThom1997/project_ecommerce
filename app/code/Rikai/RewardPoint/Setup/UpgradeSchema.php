<?php 
namespace Rikai\RewardPoint\Setup;
use Magento\Framework\Setup\SchemaSetupInterface;
use Magento\Framework\Setup\ModuleContextInterface;
use Magento\Framework\DB\Ddl\Table;
class UpgradeSchema implements \Magento\Framework\Setup\UpgradeSchemaInterface{
 
	public function upgrade(SchemaSetupInterface $setup,ModuleContextInterface $context){
        $setup->startSetup();
        if (version_compare($context->getVersion(), '1.2.0', '<')) {
            $setup->getConnection()->addColumn(
                $setup->getTable('quote'),
                'reward_point',
                [
                    'type' => \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
                    'nullable' => true,
                    'comment' => 'Reward Point'
                ]
            );
            $setup->getConnection()->addColumn(
                $setup->getTable('sales_order'),
                'reward_point',
                [
                    'type' => \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
                    'nullable' => true,
                    'comment' => 'Reward Point'
                ]
            );

            $setup->getConnection()->addColumn(
                $setup->getTable('customer_reward_point'),
                'creation_at',
                [
                    'type' => \Magento\Framework\DB\Ddl\Table::TYPE_TIMESTAMP,
                    null,
                    [
                        'nullable' => false,
                        'default' => \Magento\Framework\DB\Ddl\Table::TIMESTAMP_INIT
                    ],
                    'comment' => 'Created At'
                ]
            );
            $setup->getConnection()->addColumn(
                $setup->getTable('customer_reward_point'),
                'updated_at',
                [
                    'type' => \Magento\Framework\DB\Ddl\Table::TYPE_TIMESTAMP,
                    null,
                    [
                        'nullable' => false,
                        'default' => \Magento\Framework\DB\Ddl\Table::TIMESTAMP_INIT_UPDATE
                    ],
                    'comment' => 'Updated At'
                ]
            );
            $setup->getConnection()->addColumn(
                $setup->getTable('quote'),
                'spent_reward_point',
                [
                    'type' => \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
                    'nullable' => true,
                    'comment' => 'Reward Point'
                ]
            );
        }
        $setup->endSetup();
	}
	
}
?>

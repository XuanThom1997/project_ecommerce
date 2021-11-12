<?php 
namespace Rikai\RewardPoint\Setup;
use Magento\Framework\Setup\SchemaSetupInterface;
use Magento\Framework\Setup\ModuleContextInterface;
use Magento\Framework\DB\Ddl\Table;
class InstallSchema implements \Magento\Framework\Setup\InstallSchemaInterface{
    public function install(SchemaSetupInterface $setup,ModuleContextInterface $context){
        $installer = $setup;

        $installer->startSetup();
        $table = $installer->getConnection()->newTable(
            $installer->getTable('customer_reward_point')
                            )->addColumn(
                                'entity_id',
                                Table::TYPE_INTEGER,
                                null,
                                ['identity'=>true,'unsigned'=>true,'nullable'=>false,'primary'=>true]
                                )
                            ->addColumn(
                                'parent_id',
                                Table::TYPE_INTEGER,
                                '2M',
                                ['unsigned' => true,'nullbale'=>false,'primary'=>true]
                                )
                            ->addColumn(
                                'reward_point_available_balance',
                                Table::TYPE_INTEGER,
                                null,
                                ['nullable'=>false]
                                )
                            ->addColumn(
                                'reward_point_total',
                                Table::TYPE_INTEGER,
                                null,
                                ['nullable'=>false]
                                )
                            ->addForeignKey(
                                $installer->getFkName(
                                    $installer->getTable('customer_reward_point'),
                                    'parent_id',
                                    $installer->getTable('customer_entity'),
                                    'entity_id'
                                ),
                                'parent_id',
                                $installer->getTable('customer_entity'),
                                'entity_id',
                                Table::ACTION_CASCADE
                            )
                            ->setOption('charset','utf8');
            $installer->getConnection()->createTable($table);
        $installer->endSetup();
    }
}
 ?>
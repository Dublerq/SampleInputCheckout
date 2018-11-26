<?php

namespace Dublerq\SampleInputCheckout\Setup;

class InstallSchema implements \Magento\Framework\Setup\InstallSchemaInterface
{
    /**
     * @inheritdoc
     */
    // @codingStandardsIgnoreStart
    public function install(
        \Magento\Framework\Setup\SchemaSetupInterface $setup,
        \Magento\Framework\Setup\ModuleContextInterface $context
    ) {
        // @codingStandardsIgnoreStop
        $installer = $setup;
        $installer->startSetup();

        $connection = $installer->getConnection();
        $quoteTable = $installer->getTable('quote');
        $orderTable = $installer->getTable('sales_order');

        $externalOrderIdName = 'external_order_id';
        $externalOrderIdDefinition = [
                'type' => \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                'nullable' => true,
                'length'  => 40,
                'comment' => 'External Order Id',
        ];

        $connection->addColumn($quoteTable, $externalOrderIdName, $externalOrderIdDefinition);
        $connection->addColumn($orderTable, $externalOrderIdName, $externalOrderIdDefinition);

        $installer->endSetup();
    }
}

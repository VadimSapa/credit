<?php
/** @var Mage_Sales_Model_Entity_Setup $installer */
$installer = $this;
$installer->startSetup();

$installer->getConnection()
    ->addColumn($installer->getTable('sales_flat_order'), 'is_credit_request_sent', Varien_Db_Ddl_Table::TYPE_BOOLEAN, null, array(
        'nullable' => false,
        'default' => 0,
    ), 'Is credit request sent');
$installer->endSetup();
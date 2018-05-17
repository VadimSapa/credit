<?php
/** @var Mage_Sales_Model_Entity_Setup $installer */
$installer = $this;
$installer->startSetup();

$installer->getConnection()
    ->addColumn($installer->getTable('sales/quote_address'), 'ii_number', 'varchar(255) DEFAULT NULL');
$installer->getConnection()
    ->addColumn($installer->getTable('sales_flat_order'), 'ii_number', 'varchar(255) DEFAULT NULL');

$installer->removeAttribute('customer', 'ii_number');
$installer->endSetup();
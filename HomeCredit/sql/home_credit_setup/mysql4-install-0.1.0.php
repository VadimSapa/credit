<?php
/* @var $installer Mage_Customer_Model_Resource_Setup */
$installer = $this;
$entity = $installer->getEntityTypeId('customer');
$installer->addAttribute('customer', 'ii_number', array(
    'type' => 'text',
    'label' => 'II Number',
    'input' => 'text',
    'visible' => true,
    'required' => false,
    'default_value' => null,
    'adminhtml_only' => '0'
));

$forms = array(
    'adminhtml_customer',
    'customer_account_edit'
);
$attribute = Mage::getSingleton('eav/config')->getAttribute('customer', 'ii_number');
$attribute->setData('used_in_forms', $forms);
$attribute->save();

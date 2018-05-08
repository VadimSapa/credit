<?php
$installer = $this;

$statusTable = $installer->getTable('sales/order_status');
$statusStateTable = $installer->getTable('sales/order_status_state');

$installer->getConnection()->insertArray(
    $statusTable,
    array(
        'status',
        'label'
    ),
    array(
        array('status' => 'credit_approved', 'label' => 'Credit approved'),
        array('status' => 'credit_processing', 'label' => 'Request sent to bank'),
        array('status' => 'credit_checked', 'label' => 'Request check'),
        array('status' => 'credit_canceled', 'label' => 'Credit canceled')
    )
);

$installer->getConnection()->insertArray(
    $statusStateTable,
    array(
        'status',
        'state',
        'is_default'
    ),
    array(
        array(
            'status' => 'credit_approved',
            'state' => 'approve',
            'is_default' => 1
        ),
        array(
            'status' => 'credit_processing',
            'state' => 'processing',
            'is_default' => 0
        ),
        array(
            'status' => 'credit_checked',
            'state' => 'checked',
            'is_default' => 0
        ),
        array(
            'status' => 'credit_canceled',
            'state' => 'canceled',
            'is_default' => 0
        )
    )
);
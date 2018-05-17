<?php

/**
 * Class Soap_HomeCredit_Model_Observer
 */
class Soap_HomeCredit_Model_Observer extends Varien_Event_Observer
{
    /**
     * @param Varien_Event_Observer $observer
     */
    public function adminhtmlWidgetContainerHtmlBefore(Varien_Event_Observer $observer)
    {
        $block = $observer->getBlock();
        if ($block->getType() === 'adminhtml/sales_order_view') {
            $order = $block->getOrder();
            $observer->getBlock()->addButton(
                'send_to_home_credit',
                array(
                    'label' => Mage::helper('home_credit')->__('Send to Home Credit'),
                    'class' => 'scalable add',
                    'onclick' => 'setLocation(\'' . $observer->getBlock()->getUrl('adminhtml/credit/index') . '\')',
                )
            );

            if ($order->getIsCreditRequestSent()) {
                $observer->getBlock()->addButton(
                    'check_status_home_credit',
                    array(
                        'label' => Mage::helper('home_credit')->__('Check Status HomeCredit'),
                        'class' => 'scalable add',
                        'onclick' => 'setLocation(\'' . $observer->getBlock()->getUrl('adminhtml/status/index') . '\')',
                    )
                );
            }
        }
    }
}
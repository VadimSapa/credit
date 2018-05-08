<?php

/**
 * Class Soap_HomeCredit_PaymentController
 */
class Soap_HomeCredit_PaymentController extends Mage_Core_Controller_Front_Action
{
    /**
     * Check Data
     */
    public function gatewayAction()
    {
        $order = Mage::getSingleton('checkout/session')->getLastRealOrder();

        if ($order->getRealOrderId()) {
            $arr_querystring = array(
                'flag' => 1,
                'orderId' => $order->getRealOrderId()
            );
            Mage_Core_Controller_Varien_Action::_redirect('credit/payment/response', array('_secure' => false, '_query' => $arr_querystring));
        }
    }

    /**
     * @throws Exception
     */
    public function responseAction()
    {
        if ($this->getRequest()->get("flag") == "1" && $this->getRequest()->get("orderId")) {
            $orderId = $this->getRequest()->get("orderId");
            $order = Mage::getModel('sales/order')->loadByIncrementId($orderId);
            $order->setState(Mage_Sales_Model_Order::STATE_PAYMENT_REVIEW, true, 'Payment Success.');
            $order->save();
            Mage::getSingleton('checkout/session')->unsQuoteId();
            Mage_Core_Controller_Varien_Action::_redirect('checkout/onepage/success', array('_secure' => false));
        } else {
            Mage_Core_Controller_Varien_Action::_redirect('checkout/onepage/error', array('_secure' => false));
        }
    }
}
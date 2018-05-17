<?php

/**
 * Class Soap_HomeCredit_PaymentController
 */
class Soap_HomeCredit_PaymentController extends Mage_Core_Controller_Front_Action
{
    const CHECKOUT_FLAG = 'flag';
    
    /**
     * Check Data
     */
    public function gatewayAction()
    {
        $order = Mage::getSingleton('checkout/session')->getLastRealOrder();

        if ($order->getRealOrderId()) {
            $arrQueryString = array(
                self::CHECKOUT_FLAG => true,
                'orderId' => $order->getRealOrderId()
            );
           $this->_redirect('credit/payment/response', array('_query' => $arrQueryString));
        }
    }

    /**
     * @throws Exception
     */
    public function responseAction()
    {
        if ((bool)$this->getRequest()->get(self::CHECKOUT_FLAG, false) && $orderId = $this->getRequest()->get("orderId")) {
            $order = Mage::getModel('sales/order')->loadByIncrementId($orderId);
            $iiNum = Mage::getModel('sales/quote_address')
                ->load($order->getQuoteId(), 'quote_id')
                ->getIiNumber();
            $order->setIiNumber($iiNum);
            $order->setState(Mage_Sales_Model_Order::STATE_PAYMENT_REVIEW, true, $this->__('Payment Success.'));
            $order->save();
            Mage::getSingleton('checkout/session')->unsQuoteId();
            $this->_redirect('checkout/onepage/success');
        } else {
            $this->_redirect('checkout/onepage/error');
        }
    }
}
<?php

/**
 * Class Soap_HomeCredit_Adminhtml_StatusController
 */
class Soap_HomeCredit_Adminhtml_StatusController extends Mage_Adminhtml_Controller_Action
{
    /**
     * @return $this
     * @throws Exception
     */
    public function indexAction()
    {
        $orderId = $this->getRequest()->getParam('order_id');
        $order = Mage::getModel('sales/order')->load($orderId);

        /** @var Soap_HomeCredit_Helper_Data $_helper */
        $_helper = $this->_getCreditHelper();

        $options = array(
            'P_USER' => $_helper->getUser(),
            'P_PASSWORD' => $_helper->getPassword(),
            'P_APPL_NUM' => $order->getIncrementId()
        );

        try {
            $client = new SoapClient($_helper->getHost());
            $result = (array)$client->checkStatus($options);
            $status = $_helper->decodingStatus($result['SCODE']);
            $order->setState($status['status'], $status['status'],
                $this->_getCreditHelper()->__($status['msg'])
            );
            $order->save();
        } catch (Exception $e) {
            throw new Exception($e->getMessage());
        }
        $this->_redirect('*/sales_order/view', array('order_id' => $order->getId()));
        return $this;
    }

    /**
     * @return Soap_HomeCredit_Helper_Data
     */
    protected function _getCreditHelper()
    {
        return Mage::helper('home_credit');
    }
}
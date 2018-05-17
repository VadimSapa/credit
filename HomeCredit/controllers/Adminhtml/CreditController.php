<?php

/**
 * Class Soap_HomeCredit_Adminhtml_CreditController
 */
class Soap_HomeCredit_Adminhtml_CreditController extends Mage_Adminhtml_Controller_Action
{
    /**
     * @return $this
     * @throws Exception
     */
    public function indexAction()
    {
        $orderId = (int)$this->getRequest()->getParam('order_id');
        $order = Mage::getModel('sales/order')->load($orderId);
        $customerAddressInfo = Mage::getModel('sales/order_address')->load($orderId);
        $goods = '';
        foreach ($order->getAllItems() as $item) {
            $goods .= $item->getName() . ',';
        }
        /** @var Soap_HomeCredit_Helper_Data $_helper */
        $_helper = $this->_getCreditHelper();

        $options = array(
            'P_USER' => $_helper->getUser(),
            'P_PASSWORD' => $_helper->getPassword(),
            'P_APPL_NUM' => $order->getIncrementId(),
            'P_IIN' => $order->getIiNumber(),
            'P_CL_NAME' => $customerAddressInfo->getName(),
            'P_MOBPHONE' => $customerAddressInfo->getTelephone(),
            'P_AMOUNT' => $order->getSubtotal(),
            'P_GOODS' => $goods
        );
        try {
            $client = new SoapClient($_helper->getHost());
            $result = (array)$client->SendAppl($options);
            $status = $_helper->decoding($result['RETCODE']);
            if (!$status['error']) {
                $order->setState(
                    Soap_HomeCredit_Model_Credit::STATUS_CREDIT_PROCESSING,
                    Soap_HomeCredit_Model_Credit::STATUS_CREDIT_PROCESSING,
                    $this->__($status['msg'])
                );
                $order->setIsCreditRequestSent(true);
                $order->save();
            } elseif ($status['error']) {
                if ($result['RETCODE'] == '2') {
                    $order->setIsCreditRequestSent(true);
                }
                $order->addStatusHistoryComment($this->__($status['msg']));
                $order->save();
            }
        } catch (Exception $e) {
            throw new Exception($e->getMessage());
        }
        $this->_redirect('*/sales_order/view', array('order_id' => (int)$order->getId()));
        return $this;
    }

    /**
     * @return Soap_HomeCredit_Helper_Data
     */
    protected function _getCreditHelper()
    {
        return Mage::helper('home_credit');
    }

    /**
     * @param $email
     * @param $store
     * @return bool|Mage_Customer_Model_Customer
     */
    protected function _getCustomerByEmail($email, $store)
    {
        $customer = Mage::getModel('customer/customer')->setWebsiteId($store->getWebsiteId())->loadByEmail($email);
        if ($customer->getId()) {
            return $customer;
        }
        return false;
    }
}
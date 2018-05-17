<?php

/**
 * Class Soap_HomeCredit_Model_Credit
 */
class Soap_HomeCredit_Model_Credit extends Mage_Payment_Model_Method_Abstract
{
    const STATUS_CREDIT_CHECKED = 'credit_checked';
    const STATUS_CREDIT_APPROVED = 'credit_approved';
    const STATUS_CREDIT_CANCELED = 'credit_canceled';
    const STATUS_CREDIT_PROCESSING = 'credit_processing';

    protected $_code = 'home_credit';
    protected $_formBlockType = 'home_credit/form_credit';
    protected $_infoBlockType = 'home_credit/info_credit';

    /**
     * @param mixed $data
     * @return $this
     */
    public function assignData($data)
    {
        
        $info = $this->getInfoInstance();
        $customerSession = $this->_getCustomerSession();
        $quote = $this->_getCheckoutSession()->getQuote();
        if ($data->getIiNumber()) {
            $customerSession->getCustomer()->setIiNumber($data->getIiNumber());
            $address = Mage::getModel('sales/quote_address')->load($quote->getId(), 'quote_id');
            if ($address->getId()) {
                $address->setIiNumber($data->getIiNumber())->save();
                $quote->setBillingAddress($address);
                $quote->collectTotals()->save();
            }
            $info->setIiNumber($data->getIiNumber());
        } else {
            $ii_number = $customerSession->getCustomer()->getIiNumber();
            $address = Mage::getModel('sales/quote_address')->load($quote->getId(), 'quote_id');
            if ($address->getId()) {
                $address->setIiNumber($ii_number)->save();
            }
            $info->setIiNumber($ii_number);
        }
        return $this;
    }

    /**
     * @return $this
     * @throws Mage_Core_Exception
     */
    public function validate()
    {
        parent::validate();
        $info = $this->getInfoInstance();
        $errorMsg = '';
        if (!$info->getIiNumber()) {
            $customerSession = $this->_getCustomerSession();
            $iiNumber = $customerSession->getCustomer()->getIiNumber();
            if (!$iiNumber) {
                $errorMsg = $this->_getHelper()->__("IIN is a required field.%n", '\n');
            }
        }
        if (!empty($errorMsg)) {
            Mage::throwException($errorMsg);
        }
        return $this;
    }

    /**
     * @return string
     */
    public function getOrderPlaceRedirectUrl()
    {
        return Mage::getUrl('credit/payment/gateway');
    }

    /**
     * @return Mage_Core_Model_Abstract
     */
    protected function _getCustomerSession()
    {
        return Mage::getSingleton('customer/session');
    }

    /**
     * @return Mage_Checkout_Model_Session
     */
    protected function _getCheckoutSession()
    {
        return Mage::getSingleton('checkout/session');
    }
}
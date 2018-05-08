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
        $customerId = $this->getInfoInstance()->getQuote()->getCustomer()->getId();
        $info = $this->getInfoInstance();
        if ($data->getIiNumber()) {
            $customer = $this->_getCustomerById($customerId);
            if ($customer->getId() && $customer->getIiNumber() !== $data->getIiNumber()) {
                $customer->setIiNumber($data->getIiNumber())->save();
            }
            $info->setIiNumber($data->getIiNumber());
        } else {
            $ii_number = $this->_getCustomerIinById($customerId);
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
            $iin = $this->_getCustomerIinById($info->getMethodInstance()->getInfoInstance()->getOrder()->getCustomerId());
            if (!$iin) {
                $errorMsg = $this->_getHelper()->__("IIN is a required field.\n");
            } else {
                $info->setIiNumber($iin);
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
        return Mage::getUrl('credit/payment/gateway', array('_secure' => false));
    }

    /**
     * @param $customerId
     * @return bool
     */
    protected function _getCustomerIinById($customerId)
    {
        $customer = $this->_getCustomerById($customerId);
        if ($customer && $iiNumber = $customer->getIiNumber()) {
            return $iiNumber;
        }
        return false;
    }

    /**
     * @param $customerId
     * @return bool|Mage_Customer_Model_Customer
     */
    protected function _getCustomerById($customerId)
    {
        if (!$customerId) {
            return false;
        }
        $customer = Mage::getModel('customer/customer')->load($customerId);
        return $customer;
    }
}
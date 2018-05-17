<?php

/**
 * Class Soap_HomeCredit_Block_Info_Credit
 */
class Soap_HomeCredit_Block_Info_Credit extends Mage_Payment_Block_Info
{
    /**
     * @param null $transport
     * @return Varien_Object
     */
    protected function _prepareSpecificInformation($transport = null)
    {
        if (null !== $this->_paymentSpecificInformation) {
            return $this->_paymentSpecificInformation;
        }

        $data = array();
        if ($this->getInfo()->getIiNumber()) {
            $data[$this->__('IIN')] = $this->getInfo()->getIiNumber();
        }

        $transport = parent::_prepareSpecificInformation($transport);

        return $transport->setData(array_merge($data, $transport->getData()));
    }
}
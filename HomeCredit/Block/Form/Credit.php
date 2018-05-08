<?php

/**
 * Class Soap_HomeCredit_Block_Form_Credit
 */
class Soap_HomeCredit_Block_Form_Credit extends Mage_Payment_Block_Form
{
    /**
     * Class construct
     */
    protected function _construct()
    {
        parent::_construct();
        $this->setTemplate('home-credit/form/credit.phtml');
    }
}
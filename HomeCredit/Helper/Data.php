<?php

/**
 * Class Soap_HomeCredit_Helper_Data
 */
class Soap_HomeCredit_Helper_Data extends Mage_Core_Helper_Abstract
{
    const HOME_CREDIT_HOST = 'payment/home_credit/home_credit_action';
    const HOME_CREDIT_USER_NAME = 'payment/home_credit/home_credit_user';
    const HOME_CREDIT_PASSWORD = 'payment/home_credit/home_credit_password';

    /**
     * @return mixed
     */
    public function getUser()
    {
        return Mage::getStoreConfig(self::HOME_CREDIT_USER_NAME);
    }

    /**
     * @return mixed
     */
    public function getPassword()
    {
        return Mage::getStoreConfig(self::HOME_CREDIT_PASSWORD);
    }

    /**
     * @return mixed
     */
    public function getHost()
    {
        return Mage::getStoreConfig(self::HOME_CREDIT_HOST);
    }

    /**
     * @param $retCode
     * @return array|mixed
     */
    public function decoding($retCode)
    {
        $codes = array(
            '-3' => array(
                'error' => true,
                'msg' => 'Internal error'
            ),
            '-1' => array(
                'error' => true,
                'msg' => 'Auth error'
            ),
            '0' => array(
                'error' => true,
                'msg' => 'Error data'
            ),
            '1' => array(
                'error' => false,
                'msg' => 'Application Success'
            ),
            '2' => array(
                'error' => true,
                'msg' => 'Application Exist'
            ),
            '3' => array(
                'error' => true,
                'msg' => 'Incorrect IIN'
            ),
            '4' => array(
                'error' => true,
                'msg' => 'Incorrect Amount'
            ),
            '5' => array(
                'error' => true,
                'msg' => 'Error Phone'
            )
        );
        if (array_key_exists($retCode, $codes)) {
            return $codes[$retCode];
        }
        return array('error' => true, 'msg' => 'Not available error');
    }

    /**
     * @param $sCode
     * @return array|mixed
     */
    public function decodingStatus($sCode)
    {
        $statusCodes = array(
            'NEW' => array(
                'error' => true,
                'msg' => 'The application is on preliminary check',
                'status' => Soap_HomeCredit_Model_Credit::STATUS_CREDIT_PROCESSING
            ),
            'CHECK' => array(
                'error' => true,
                'msg' => 'The application is on preliminary check',
                'status' => Soap_HomeCredit_Model_Credit::STATUS_CREDIT_PROCESSING
            ),
            'CHECKED' => array(
                'error' => true,
                'msg' => 'Application Success, but expects communication with the client',
                'status' => Soap_HomeCredit_Model_Credit::STATUS_CREDIT_CHECKED
            ),
            'REJECTED' => array(
                'error' => true,
                'msg' => 'Application Rejected',
                'status' => Soap_HomeCredit_Model_Credit::STATUS_CREDIT_CANCELED
            ),
            'APPROVED' => array(
                'error' => false,
                'msg' => 'Application Success',
                'status' => Soap_HomeCredit_Model_Credit::STATUS_CREDIT_APPROVED
            ),
            'NEW/CHECK' => array(
                'error' => true,
                'msg' => 'The application is on preliminary check',
                'status' => Soap_HomeCredit_Model_Credit::STATUS_CREDIT_PROCESSING
            )
        );
        if (array_key_exists($sCode, $statusCodes)) {
            return $statusCodes[$sCode];
        }
        return array('error' => true, 'msg' => 'Not available error');
    }
}
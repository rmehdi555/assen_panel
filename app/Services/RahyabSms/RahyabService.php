<?php

namespace App\Services\RahyabSms;

use App\Services\RahyabSms\Exceptions\RahyabSmsException;
use App\Services\RahyabSms\Log\SmsLogging;
use SoapClient;

class RahyabService
{
    protected $username;
    protected $password;
    private $shortcode;
    private $baseUrl;
    private $option;

    public function __construct()
    {
        if (!extension_loaded('curl'))
            throw new RahyabSmsException("Curl extension not loaded");
        if (!extension_loaded('soap'))
            throw new RahyabSmsException("Soap extension not loaded");

        $this->baseUrl = 'https://sms.igama.ir/webservice/sms.asmx?wsdl';
        $this->username = config('services.rahyab_sms.username');
        $this->password = config('services.rahyab_sms.password');
        $this->shortcode = config('services.rahyab_sms.shortcode');




        if (is_null($this->username) || is_null($this->password))
            throw new RahyabSmsException("env values has not been set");

        $this->option = array(
            'cache_wsdl' => 0,
            'trace' => 1,
            'stream_context' => stream_context_create(array(
                'ssl' => array(
                    'verify_peer' => false,
                    'verify_peer_name' => false,
                    'allow_self_signed' => true
                )
            )));
    }

    /**
     * Simple send message with smsonline.ir account and line number
     *
     * @param $number
     * @param $message
     * @param null $recId
     *
     * @return string, return status
     */
    public function send($number, $message, $recId = null)
    {
        try {
            $client = new SoapClient($this->baseUrl, $this->option);
            $parameters = [
                'uUsername' => $this->username,
                'uPassword' => $this->password,
                'uNumber' => $this->shortcode,
                'uCellphones' => $number,
                'uMessage' => $message,
                "uFarsi" => true,
                "uTopic" => false,
                "uFlash" => "",
                "uUDH" => "",
            ];

            $response = ($client->doSendSMS($parameters))->doSendSMSResult;
            if (str_contains($response, 'Send OK')) $status = 1;
            else $status = 0;

            SmsLogging::loggingInDB($number, $message, $status, $recId, json_encode($response));

            return $status;
        } catch (\SoapFault $e) {

            SmsLogging::loggingInDB($number, $message, 0, $recId, json_encode($e));

            return $e->getMessage();
        }
    }

    /**
     * this method return your credit in http://smsonline.ir/
     *
     * @return string
     */
    public function getCredit()
    {
        try {
            $client = new SoapClient($this->baseUrl, $this->option);
            $parameters = [
                'uUsername' => (string)$this->username,
                'uPassword' => (string)$this->password,
            ];
            return ($client->doGetInfo($parameters))->doGetInfoResult;
        } catch (\SoapFault $e) {
            return $e->getMessage();
        }
    }
}

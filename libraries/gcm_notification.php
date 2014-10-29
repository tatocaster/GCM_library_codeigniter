<?php defined('BASEPATH') OR exit('No direct script access allowed.');

/**
*   Codeigniter GCM Notification Library
*   @package GCM (Google Cloud Messaging)
*   @author     tatocaster <kutaliatato@gmail.com>
*/

class GCM_Notification {

    private $_CI;
    private $_postFields = array();
    private $_headers = array();
    private $_result;
    private $_resInfo;
    private $_debug;

    public function __construct() {
        $this->_CI =& get_instance();
    }

    /**
    * sets recipients array
    * @param array $recipients
    */
    public function setRecipients($recipients){
        $this->_postFields['registration_ids'] = $recipients;
    }


    /**
    *   clear recipients
    */

    public function clearRecipients(){
        $this->_postFields['registration_ids'] = array();
    }

    /**
    * sets time to live
    * @param int $ttl
    */
    public function setTTL($ttl){

        if($ttl){
            $this->_postFields['time_to_live'] = intval($ttl);
        }

    }


    /**
    * sets Collapse Key
    * @param string $collapseKey
    */
    public function setCollapseKey($collapseKey){

        if($collapseKey){
            $this->_postFields['collapseKey'] = $collapseKey;
        }
        else{
            $this->_postFields['collapseKey'] = '';
        }

    }


    /**
    * sets delay_while_idle
    * @param boolean $delay
    */
    public function setDelay($delay){

        if($delay){
            $this->_postFields['delay_while_idle'] = (boolean)$delay;
        }

    }


    /**
    * sets Collapse Key
    * @param array $options
    */
    public function setOptions($options = array()){
        if(is_array($options)){
            $this->_postFields['data'] = $options;
        }
    }


    /**
    * sets debug true
    * @param boolean $debug
    */
    public function setDebug($debug){

        if($debug){
            $this->_debug = (boolean)$debug;
        }

    }



    /**
    *   encodes post fields and sends
    */
    public function send(){

        $this->_postFields['registration_ids'] = array_unique($this->_postFields['registration_ids']);

        $fields = json_encode($this->_postFields);
        return $this->_sendCurl($fields);
    }



    private function _sendCurl($curlPostFields){

        $header = array(
            'Content-Type: application/json',
            'Authorization: key=' . API_ACCESS_KEY
        );

        $ch = curl_init();

        curl_setopt($ch,CURLOPT_URL, GCM_URL);
        curl_setopt($ch,CURLOPT_POST, true);
        curl_setopt($ch,CURLOPT_HTTPHEADER, $header);
        curl_setopt($ch,CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch,CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch,CURLOPT_FOLLOWLOCATION, false);
        curl_setopt($ch,CURLOPT_POSTFIELDS, $curlPostFields);

        $this->_result = curl_exec($ch);
        $this->_resInfo = curl_getinfo($ch);

        curl_close($ch);

        if($this->_debug){
            return $this->_debug();
        }

    }

    /**
    * debug http headers
    * @return array
    */
    private function _debug(){

        // check for http_headers activate only when is debug true
        if($this->_resInfo['http_code'] == '200'){
            $response = explode("\n",$this->_result);
            $responseBody = json_decode($response[count($response)-1]);

            if ($responseBody->success && !$responseBody->failure){
                $responseArray['message'] = 'success';
                $responseArray['success'] = 1;
            }
            else if($responseBody->success && $responseBody->failure){
                $responseArray['message'] = $responseBody->success . ' sent';
                $responseArray['success'] = 0;
            }
            else if(!$responseBody->success && $responseBody->failure){
                $responseArray['message'] = 'error';
                $responseArray['success'] = 0;
            }
            return $responseArray;
        }
        else if($this->_resInfo['http_code'] == '400'){
            return $responseArray['http_code'] = $this->_resInfo['http_code'];
        }
        else if($this->_resInfo['http_code'] == '401'){
            return $responseArray['http_code'] = $this->_resInfo['http_code'];
        }
        else if($this->_resInfo['http_code'] == '500'){
            return $responseArray['http_code'] = $this->_resInfo['http_code'];
        }


    }


}

<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * This class provides basic cURL functionality to get and post
 * @author jasonhotsauce
 *
 */
class Service {
    protected static function formatPostToString($fields) {
        $fieldsString = '';
    
        foreach ($fields as $key => $value)
        {
            $fieldsString .= $key . '=' . $value . '&';
        }
        $fieldsString = rtrim($fieldsString, '&');
        return $fieldsString;
    }
    
    protected static function httpParseHeaders( $header ){
        $retVal = array();
        $fields = explode("\r\n", preg_replace('/\x0D\x0A[\x09\x20]+/', ' ', $header));
        foreach( $fields as $field ) {
            if( preg_match('/([^:]+): (.+)/m', $field, $match) ) {
                $match[1] = preg_replace('/(?<=^|[\x09\x20\x2D])./e', 'strtoupper("\0")', strtolower(trim($match[1])));
                if( isset($retVal[$match[1]]) ) {
                    $retVal[$match[1]] = array($retVal[$match[1]], $match[2]);
                } else {
                    $retVal[$match[1]] = trim($match[2]);
                }
            }
        }
        return $retVal;
    }
    
    /**
     * Using CURL to get data from server.
     * @param string $url
     * @param array $httpHeader
     * @param integer $acceptCode
     * @return string|boolean
     */
    protected static function doGet($url, $httpHeader=NULL, $acceptCode=200) {
        $ch = curl_init($url);
        if ($httpHeader)
            curl_setopt($ch, CURLOPT_HTTPHEADER, $httpHeader);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
        $response = curl_exec($ch);
        $httpStatus = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);
        if ($httpStatus === $acceptCode) {
            return $response;
        } else {
            throw new ApiException("Error occured", $httpStatus);
        }
    }
    
    /**
     * Using CURL to post to server
     * HTTP REQUEST METHOD: POST
     * @param string $url
     * @param string $fields
     * @param array $httpHeader
     * @param integer $acceptCode
     * @return mixed|boolean
     */
    protected static function doPost($url, $fields, $httpHeader=NULL, $acceptCode=200){
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        if ($httpHeader) {
            curl_setopt($ch, CURLOPT_HTTPHEADER, $httpHeader);
            curl_setopt($ch, CURLOPT_HEADER, 1);
        }
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_POST, TRUE);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $fields);
        $response = curl_exec($ch);
        $responseCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);
        if($responseCode === $acceptCode){
            return $response;
        }else{
            throw new ApiException("Error occured", $responseCode);
        }
    }
    
    /**
     * Using CURL to PUT to server
     * @param string $url
     * @param string $fields
     * @param array $httpHeader
     * @param integer $acceptCode
     * @return mixed|boolean
     */
    protected static function doPut($url, $fields, $httpHeader=NULL, $acceptCode=200) {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        if ($httpHeader)
            curl_setopt($ch, CURLOPT_HTTPHEADER, $httpHeader);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "PUT");
        curl_setopt($ch, CURLOPT_POSTFIELDS, $fields);
        $response = curl_exec($ch);
        $statusCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);
        if ($statusCode === $acceptCode)
            return $response;
        else
            throw new ApiException("Error occured", $statusCode);
    
    }
    
    /**
     * Using CURL to delete item from sever
     * @param string $url
     * @param array $httpHeader
     * @param integer $acceptCode
     * @return mixed|boolean
     */
    protected static function doDelete($url, $httpHeader=NULL, $acceptCode = 200) {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        if ($httpHeader)
            curl_setopt($ch, CURLOPT_HTTPHEADER, $httpHeader);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "DELETE");
        $response = curl_exec($ch);
        $status = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);
        if ($status === $acceptCode)
            return TRUE;
        else
            throw new ApiException("Error occured", $status);
    }
}
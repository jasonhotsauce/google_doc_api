<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * Concrete class for working with Google Document List API.
 * Migrated to Google Drive API v2
 * @category   Codeigniter
 * @package    Library
 * @subpackage Google Document List API
 * @copyright  Copyright (c) 2012 Wenbin Zhang.
 * @license     MIT License
 */
class Google_api {
	/*
	** Format HTTP Request for access token.
	** HTTP REQUEST METHOD: GET
	** @VAR array $authParams OAuth 2.0 configuration settings. Read from your config.
	** @RETURN redirect to Google OAuth 2.0 server.
	*/
	public static function formatOAuthReq($authParams){
	    
		$endPoint = $authParams['auth_end_point'];
    	$callback = $authParams['redirect_uri'];
    	$scope = $authParams['scopes'];
    	$clientId = $authParams['client_id'];
    	$authURL = $endPoint
          . "?redirect_uri=" . $callback
          . "&client_id=" . $clientId
          . "&scope=".$scope
          . "&response_type=code&access_type=offline";
    	return $authURL;
	}
	
	/*
	** Refresh user's access token. Access token returned by OAuth 2.0 server has a short
	** Limited available time (3600 secs = 1 hour), after this time, we need to call this
	** method to gain the new access token.
	** @VAR string $refreshToken the unique refresh token returned by OAuth 2.0 server, it's only returned at the first time getting OAuth authorization. Will be removed when revoke.
	** @VAR array $params google api setting array 
	*/
	public static function refreshAccessToken($authParams, $refreshToken){
		$endPoint = $authParams['auth_token_uri'];
		$clientId = $authParams['client_id'];
		$clientSecret = $authParams['client_secret'];
		$fields = array(
			'client_id'=>$authParams['client_id'], 
			'client_secret'=>$authParams['client_secret'], 
			'refresh_token'=>$refreshToken, 
			'grant_type'=>'refresh_token'
		);
		$postString = self::formatPostToString($fields);
		$response = self::doPost($endPoint, $postString);
		return $response;
	}
	
	/*
	** Get OAuth 2.0 access token. 
	** @VAR array $params OAuth 2.0 configuration settings. Read from application_setting.php
	** @VAR string $authCode OAuth 2.0 access code.
	** @RETURN OAuth 2.0 access token JSON.
	*/
	public static function getAuthToken($params, $authCode){
    	$url = $params['auth_token_uri'];
    	$fields = array(
			'code' => $authCode,
			'client_id' => $params['client_id'],
			'client_secret' => $params['client_secret'],
			'redirect_uri' => $params['redirect_uri'],
			'grant_type' => 'authorization_code'
		);
    	$postString = self::formatPostToString($fields);
		$response = self::doPost($url, $fields);
    	return $response;
    }
    
    private static function formatPostToString($fields) {
        $fieldsString = '';
        
        foreach ($fields as $key => $value)
        {
            $fieldsString .= $key . '=' . $value . '&';
        }
        $fieldsString = rtrim($fieldsString, '&');
        return $fieldsString;
    }
    
    /*
    ** Revoke google access.
    ** HTTP REQUEST METHOD: POST
    ** @VAR string $refreshToken, unique token returned by OAuth server and stored in database.
    ** @VAR array $params, google api settings.
    */
    public static function revokeAuth($refreshToken, $params){
    	$url = $params['revoke_uri'];
    	$field = '?token='.$refreshToken;
    	$ch = curl_init($url.$field);
    	curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
    	$result = curl_exec($ch);
    	$httpStatus = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    	curl_close($ch);
    	if ($httpStatus === 200)
    	    return TRUE;
        else 
            return FALSE;
    }
    
    /**
     * Using CURL to get data from server.
     * @param string $url
     * @param array $httpHeader
     * @param integer $acceptCode
     * @return string|boolean
     */
    private static function doGet($url, $httpHeader=NULL, $acceptCode=200) {
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
    private static function doPost($url, $fields, $httpHeader=NULL, $acceptCode=200){
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
    private static function doPut($url, $fields, $httpHeader=NULL, $acceptCode=200) {
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
    private static function doDelete($url, $httpHeader=NULL, $acceptCode = 200) {
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
    
    /*
    ** Create collection (folder) in Google Docs (Drive).
    ** HTTP REQUEST METHOD: POST
    ** @VAR string $url the post url for creating collection.
    ** @VAR string $token OAuth 2.0 access token
    ** @VAR string $name collection name, if passing null or empty string, the default name will be selected.
    ** @RETURN 1. string $response xml+atom google doc protocol
    ** @RETURN 2. int HTTP code. 
    */
    public static function createCollection($url, $token, $name=''){
    	$dom = new DOMDocument('1.0', 'UTF-8');
		$entry = $dom->createElementNS('http://www.w3.org/2005/Atom', 'entry');
		$dom->appendChild($entry);
		$category = $dom->createElement('category');
		$category->setAttribute('scheme', 'http://schemas.google.com/g/2005#kind');
		$category->setAttribute('term', 'http://schemas.google.com/docs/2007#folder');
		$entry->appendChild($category);
		$title = $dom->createElement('title', $name);
		$entry->appendChild($title);
    	$postField = $dom->saveXML();
    	$headers = array(
			'Authorization: Bearer '.$token, 
			'Content-Length: '.strlen($postField), 
			'Content-Type: application/atom+xml'
		);
    	$response = self::doPost($url, $postField, $headers, 201);
    	return $response;
    }
    
    /*
    ** Get all files in the collection
    ** HTTP REQUEST METHOD: GET
    ** @VAR string $url collection contents URL
    ** @VAR string $token OAuth 2.0 access token
    ** @RETURN xml string $response xml_atom google document list API protocol
    ** @RETURN int $status HTTP code. 
    */
    public static function recheiveAllFilesInCollection($url, $token, $collection=""){
        if ($collection)
            $url .= $collection.'/children';
    	$header = array('Authorization: Bearer '.$token);
    	$response = self::doGet($url, $header);
    	$jsonObj = json_decode($response, TRUE);
    	$filesArray = $jsonObj['items'];
    	$documents = array();
    	foreach ($filesArray as $file) {
    	    $document = new Document($file);
    	    $documents[] = $document;
    	}
    	return $documents;
    }
    
    /*
    ** Delete selected document
    ** HTTP REQUEST METHOD: DELETE
    ** @VAR string $url document edit URL
    ** @VAR string $etag document Etag
    ** @VAR string $token OAuth 2.0 access token.
    ** @RETURN int $status HTTP code.
    */
    public static function deleteDocument($url, $documentId, $etag, $token){
    	$header = array('Authorization: Bearer '.$token);
    	$response = self::doPost($url.$documentId.'/trash', "", $header);
    	return $response;
    }
    
    public static function uploadDoc($params, $resumableLink, $fileName, $fileLocation, $fileType, $token){
    	$fileSize = filesize($fileLocation);
    	$dom = new DOMDocument('1.0', 'utf-8');
    	$entry = $dom->createElementNS('http://www.w3.org/2005/Atom', 'entry');
    	$entry->setAttribute('xmlns:docs', 'http://schemas.google.com/docs/2007');
    	$dom->appendChild($entry);
    	$title = $dom->createElement('title', $fileName);
    	$entry->appendChild($title);
    	$postField = $dom->saveXML();
    	$header = array(
    		'Authorization: Bearer '.$token, 
    		'Content-Length: '.strlen($postField), 
    		'Content-Type: application/atom+xml', 
    		'X-Upload-Content-Type: '.$fileType,
			'X-Upload-Content-Length: '.$fileSize
    	);
    	$response = self::doPost($resumableLink."?convert=false", $postField, $header);
    	
    	if($response){
    		$responseHeader = self::httpParseHeaders($response);
    		$uploadLink = $responseHeader['Location'];
    		$chunkStarts = 0;
    		$chunkSize = $params['chunk_size'];
    		$retry = $params['retry_times'];
    		while($chunkStarts <= $fileSize && $retry){
    			$chunkEnd = $chunkStarts+$chunkSize-1; // Calculate the end point.
				if($chunkEnd > $fileSize){
					$chunkEnd = $fileSize-1; // If calculated end point is larger than size, then use the max size.
				}
				
				// Create chunked file START
				$tmpFile = tmpfile();
				$chunkedContent = file_get_contents($fileLocation, NULL, NULL, $chunkStarts, $chunkSize);
				fwrite($tmpFile, $chunkedContent);
				fseek($tmpFile, 0); // Move the pointer to the beginning of the file, without this, the following curl PUT will not work because there are no contents to read.
				// Create chunked file END
				
    			$uploadHeader = array(
    				'Content-Type: '.$fileType,
    				'Content-Range: bytes '.$chunkStarts.'-'.$chunkEnd.'/'.$fileSize
    			);
    			$ch = curl_init();
    			curl_setopt($ch, CURLOPT_URL, $uploadLink);
    			curl_setopt($ch, CURLOPT_HTTPHEADER, $uploadHeader);
    			curl_setopt($ch, CURLOPT_BINARYTRANSFER, 1);
    			curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    			curl_setopt($ch, CURLOPT_UPLOAD, 1);
    			curl_setopt($ch, CURLOPT_PUT, 1);
    			curl_setopt($ch, CURLOPT_INFILESIZE, strlen($chunkedContent));
    			curl_setopt($ch, CURLOPT_INFILE, $tmpFile);
    			
    			$response = curl_exec($ch);
    			$status = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    			curl_close($ch);
    			//$response = $this->resumable_upload($uploadLink, $fileLocation, $fileSize, $chunkSize, $chunkStarts);
    			if($status == 308){
    				$chunkStarts += $chunkSize;
    			}elseif($status == 401){
    				return 'AUTH DENIED';
    			}elseif($status == 503 || $status == 404 || $status == 410){
    				continue;
    			}elseif($status == 400){
    				break;
    			}elseif($status == 201){
    				return $response;
    			}
    		}
    		return FALSE;
    	}
    	return FALSE;
    }
    
    public static function retrieveAcl($aclLink, $token){
    	$header = array(
    		'Authorization: Bearer '.$token 
    	);
    	$response = self::doGet($aclLink, $header);
    	return $response;
    }
    
    public static function updateShare($aclLink, $role, $etag, $account, $token){
    	$dom = new DOMDocument('1.0', 'utf-8');
    	$entry = $dom->createElementNS('http://www.w3.org/2005/Atom', 'entry');
    	$entry->setAttribute('xmlns:gAcl', 'http://schemas.google.com/acl/2007');
    	$entry->setAttribute('xmlns:gd', 'http://schemas.google.com/g/2005');
    	$entry->setAttribute('gd:etag', $etag);
    	$dom->appendChild($entry);
    	$category = $dom->createElement('category');
    	$category->setAttribute('scheme', 'http://schemas.google.com/g/2005#kind');
		$category->setAttribute('term', 'http://schemas.google.com/acl/2007#accessRule');
		$entry->appendChild($category);
		$roleEle = $dom->createElement('gAcl:role');
		$roleEle->setAttribute('value', $role);
		$entry->appendChild($roleEle);
		$scope = $dom->createElement('gAcl:scope');
		$scope->setAttribute('type', 'user');
		$scope->setAttribute('value', $account);
		$entry->appendChild($scope);
		$postField = $dom->saveXML();
    	$header = array( 
			'Authorization: Bearer '.$token,
			'Content-Type: application/atom+xml'
		);
    	$response = self::doPut($aclLink, $postField, $header);
    	return $response;
    }
    
    public static function removeShare($aclLink, $token){
    	$header = array(
			'Authorization: Bearer '.$token,
			'Content-Type: application/atom+xml'
		);
		$response = self::doDelete($aclLink, $header);
		return $status;
    }
    
    public static function addShare($aclLink, $sendEmail, $role, $account, $token){
    	$header = array(
    		'Authorization: Bearer '.$token,
    		'Content-Type: application/atom+xml'
    	);
    	$dom = new DOMDocument('1.0', 'utf-8');
    	$entry = $dom->createElementNS('http://www.w3.org/2005/Atom', 'entry');
    	$entry->setAttribute('xmlns:gAcl', 'http://schemas.google.com/acl/2007');
    	$dom->appendChild($entry);
    	$category = $dom->createElement('category');
    	$category->setAttribute('scheme', 'http://schemas.google.com/g/2005#kind');
		$category->setAttribute('term', 'http://schemas.google.com/acl/2007#accessRule');
		$entry->appendChild($category);
		$roleEle = $dom->createElement('gAcl:role');
		$roleEle->setAttribute('value', $role);
		$entry->appendChild($roleEle);
		$scope = $dom->createElement('gAcl:scope');
		$scope->setAttribute('type', 'user');
		$scope->setAttribute('value', $account);
		$entry->appendChild($scope);
		$postField = $dom->saveXML();
    	if(!$sendEmail){
    		$aclLink .= '?send-notification-emails=false';
    	}
    	$response = self::doPost($aclLink, $postField, $header, 201);
    	return $response;
    }
    
    private static function httpParseHeaders( $header ){
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
}
?>
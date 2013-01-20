<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
require_once 'Document.php';
/**
 * Concrete class for working with Google Document List API.
 * Migrated to Google Drive API v2
 * @category   Codeigniter
 * @package    Library
 * @subpackage Google Document List API
 * @copyright  Copyright (c) 2012 Wenbin Zhang.
 * @license     MIT License
 */
class Google_api extends Service{
    const GOOGLE_DRIVE_URL = "https://www.googleapis.com/drive/v2/files/";
    const GOOGLE_OAUTH_URL = "https://accounts.google.com/o/oauth2/auth";
    const GOOGLE_OAUTH_REVOKE_URL = "https://accounts.google.com/o/oauth2/revoke";
    const GOOGLE_OAUTH_TOKEN_URL = "https://accounts.google.com/o/oauth2/token";
    private static $clientId;
    private static $secret;
    public $files;
    
    public function __construct($clientId, $secret) {
        self::$clientId = $clientId;
        self::$secret = $secret;
    }
    
    private function buildOAuthHeader($token) {
        return array('Authorization: Bearer '.$token);
    }
	/*
	** Format HTTP Request for access token.
	** HTTP REQUEST METHOD: GET
	** @VAR array $authParams OAuth 2.0 configuration settings. Read from your config.
	** @RETURN redirect to Google OAuth 2.0 server.
	*/
	public static function formatOAuthReq($authParams){
    	$callback = $authParams['redirect_uri'];
    	$scope = $authParams['scopes'];
    	$authURL = self::GOOGLE_OAUTH_URL
          . "?redirect_uri=" . $callback
          . "&client_id=" . $authParams['client_id']
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
	public function refreshAccessToken($authParams, $refreshToken){
		$fields = array(
			'client_id'=>self::$clientId, 
			'client_secret'=>self::$secret, 
			'refresh_token'=>$refreshToken, 
			'grant_type'=>'refresh_token'
		);
		$postString = self::formatPostToString($fields);
		$response = self::doPost(self::GOOGLE_OAUTH_TOKEN_URL, $postString);
		return $response;
	}
	
	/*
	** Get OAuth 2.0 access token. 
	** @VAR array $params OAuth 2.0 configuration settings. Read from application_setting.php
	** @VAR string $authCode OAuth 2.0 access code.
	** @RETURN OAuth 2.0 access token JSON.
	*/
	public function getAuthToken($params, $authCode){
    	$fields = array(
			'code' => $authCode,
			'client_id' => self::$clientId,
			'client_secret' => self::$secret,
			'redirect_uri' => $params['redirect_uri'],
			'grant_type' => 'authorization_code'
		);
    	$postString = self::formatPostToString($fields);
		$response = self::doPost(self::GOOGLE_OAUTH_TOKEN_URL, $fields);
    	return $response;
    }
    
    /*
    ** Revoke google access.
    ** HTTP REQUEST METHOD: POST
    ** @VAR string $refreshToken, unique token returned by OAuth server and stored in database.
    ** @VAR array $params, google api settings.
    */
    public function revokeAuth($refreshToken, $params){
    	$field = '?token='.$refreshToken;
    	$ch = curl_init(self::GOOGLE_OAUTH_REVOKE_URL.$field);
    	curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
    	$result = curl_exec($ch);
    	$httpStatus = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    	curl_close($ch);
    	if ($httpStatus === 200)
    	    return TRUE;
        else 
            return FALSE;
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
    public function createCollection($token, $name=''){
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
    	$response = self::doPost(self::GOOGLE_DRIVE_URL, $postField, $headers, 201);
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
    public function listAllFilesInCollection($token, $collection=""){
        if ($collection)
            $url = self::GOOGLE_DRIVE_URL.$collection.'/children';
        else 
            $url = self::GOOGLE_DRIVE_URL;
    	$header = self::buildOAuthHeader($token);
    	$response = self::doGet($url, $header);
    	$jsonObj = json_decode($response, TRUE);
    	if (!$collection)
    	    $this->files = new FileList($jsonObj);
    	else
    	    $this->files = new ChildList($jsonObj);
    }
    
    public static function searchFiles($url, $token, $query) {
        $header = self::buildOAuthHeader($token);
        $response = self::doGet(self::GOOGLE_DRIVE_URL.'q='.urlencode($query), $header);
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
    	$header = self::buildOAuthHeader($token);
    	$response = self::doPost(self::GOOGLE_DRIVE_URL.$documentId.'/trash', "", $header);
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
    	$header = self::buildOAuthHeader($token);
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
    
    
}

// class Google_Children {
//     private 
// }
?>
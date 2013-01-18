<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * User object will hold google auth token and refresh token
 * @author Wenbin Zhang
 *
 */
class User {
    private $ci;
//     private $username;
    private $didTryRefresh = FALSE;
    private static $apiSetting; // This will be an array of the setting for your google document list api. 
    private $googleToken;
    private $refreshToken;
    private $id;
    
    /**
     * @param string $apiConfigFile
     * @param string $nameInArray
     */
    public function __construct($configKey = NULL) {
        $this->ci = &get_instance();
        $this->ci->load->model('User_model');
        self::$apiSetting = array();
        if ($configKey) {
            self::$apiSetting = $this->ci->config->item($configKey);
        } else {
            // Default name of the config key in array is "google_api"
            self::$apiSetting = $this->ci->config->item("google_api");
        }
        if (!key_exists('auth_end_point', self::$apiSetting) || !self::$apiSetting['auth_end_point'])
            throw new ApiSettingException("Your google api OAuth 2.0 end point is not setup");
        if (!key_exists('redirect_uri', self::$apiSetting) || !self::$apiSetting['redirect_uri'])
            throw new ApiSettingException("Your google api auth return url is not setup");
        if (!key_exists('scopes', self::$apiSetting) || !self::$apiSetting['scopes'])
            throw new ApiSettingException("Your google api scopes are not setup");
        if (!key_exists('client_id', self::$apiSetting) || !self::$apiSetting['client_id'])
            throw new ApiSettingException("Your google api ID is not setup");
        $userData = $this->ci->User_model->getUser();
        if (isset($userData['google_token']))
            $this->googleToken = $userData['google_token'];
        if (isset($userData['refresh_token']))
            $this->refreshToken = $userData['refresh_token'];
        if (isset($userData['id']))
            $this->id = $userData['id'];
    }
    
    public function getGoogleToken() {
        return $this->googleToken;
    }
    
    public function setGoogleToken($googleToken) {
        $this->googleToken = $googleToken;
    }
    
    public function getRefreshToken() {
        return $this->refreshToken;
    }
    
    public function setRefreshToken($refreshToken) {
        $this->refreshToken = $refreshToken;
    }
    
    /**
     * Format the auth request url with your api setting and return it.
     * You suppose to open a new window and redirect this url.
     * @return string
     */
    public static function getOAuthURL() {
        $authURL = Google_api::formatOAuthReq(self::$apiSetting);
        return $authURL;
    }
    
    /**
     * This method is used to get auth token and refresh token when user accept your request.
     * The original response is encoded as JSON.
     * @param string $authCode
     */
    public function gainToken($authCode) {
        $responseToken = Google_api::getAuthToken(self::$apiSetting, $authCode);
        $jsonObj = json_decode($responseToken);
        $this->googleToken = $jsonObj->access_token;
        $this->refreshToken = $jsonObj->refresh_token;
        $this->store();
    }
    
    public function refreshToken() {
        $responseToken = Google_api::refreshAccessToken(self::$apiSetting, $this->refreshToken);
        $jsonObj = json_decode($responseToken);
        $this->googleToken = $jsonObj->access_token;
        $this->store();
    }

    /**
     * Save user token in database and return user id.
     * @throws Exception
     */
    private function store() {
        try {
            $this->id = $this->ci->User_model->save($this);
        } catch (PDOException $e) {
            throw new Exception($e);
        }
    } 
    
    /**
     * Revoke user access.
     * @throws ApiSettingException
     * @return boolean
     */
    public function revokeToken() {
        if (!$this->refreshToken)
            throw new ApiSettingException("Refresh token is not set.");
        $revoke = Google_api::revokeAuth($this->refreshToken, self::$apiSetting);
        if ($revoke) {
            return TRUE;
        } else {
            return FALSE;
        }
    }
    
    /**
     * Get all files for user.
     */
    public function getAllFiles() {
        try {
            $documents = Google_api::recheiveAllFilesInCollection(self::$apiSetting['google_doc_uri'], $this->googleToken);
            return $documents;
        } catch (ApiException $e) {
            if ($e->getCode() === 401 && !$this->didTryRefresh){
                // Access denied. Try to refresh token
                $this->refreshToken();
                $this->didTryRefresh = TRUE;
                // Try again.
                return $this->getAllFiles();
            } else {
                throw new ErrorException($e->getMessage(), $e->getCode());
            }
        }
    }
    
    public function trashFile($fileId, $etag) {
        try {
            $result = Google_api::deleteDocument(self::$apiSetting['google_doc_uri'], $fileId, $etag, $this->googleToken);
            return $result;
        } catch (ApiException $e) {
            if ($e->getCode() === 401 && !$this->didTryRefresh){
                // Access denied. Try to refresh token
                $this->refreshToken();
                $this->didTryRefresh = TRUE;
                // Try again.
                return $this->trashFile($fileId, $etag);
            } else {
                throw new ErrorException($e->getMessage(), $e->getCode());
            }
        }
    }
    
    public function uploadDocument($document) {
        
    }
}

<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class User_model extends MY_Model {
    const TABLE = "user";
    public function __construct() {
        parent::__construct();
    }
    
    public function save($user) {
        $query = "INSERT INTO ".self::TABLE." (google_token, refresh_token) VALUES (:googleToken, :refreshToken)";
        $sth = $this->prepare($query);
        $sth->bindParam(":googleToken", $user->getGoogleToken(), PDO::PARAM_STR);
        $sth->bindParam(":refreshToken", $user->getRefreshToken(), PDO::PARAM_STR);
        $sth->execute();
        return $this->lastInsertId();
    }
    
    public function getUser() {
        $query = "SELECT * FROM ".self::TABLE;
        $sth = $this->prepare($query);
        $sth->execute();
        return $sth->fetch(PDO::FETCH_ASSOC);
    }
}
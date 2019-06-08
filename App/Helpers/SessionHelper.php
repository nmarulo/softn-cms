<?php
/**
 * softn-cms
 */

namespace App\Helpers;

use App\Facades\Rest\UsersRestFacade;
use App\Rest\Responses\Users\UserResponse;
use Silver\Http\Session;

/**
 * Class SessionHelper
 * @author Nicolás Marulanda P.
 */
class SessionHelper {
    
    private static $KEY_NAME_USER  = 'user_id';
    
    private static $KEY_NAME_TOKEN = 'token';
    
    /**
     * @var UserResponse
     */
    private $user;
    
    public function getUser(bool $currentData = TRUE): UserResponse {
        if ($this->user && $currentData) {
            return $this->user;
        }
        
        return $this->user = UsersRestFacade::getById(Session::get(self::$KEY_NAME_USER));
    }
    
    /**
     * @param UserResponse $response
     */
    public function setUser(UserResponse $response): void {
        $this->user = $response;
        Session::set(self::$KEY_NAME_USER, $response->id);
    }
    
    public function isUserExists(): bool {
        return Session::exists(self::$KEY_NAME_USER);
    }
    
    public function delete() {
        Session::delete(self::$KEY_NAME_USER);
        Session::delete(self::$KEY_NAME_TOKEN);
    }
    
    /**
     * @return string
     */
    public function getToken(): string {
        return Session::get(self::$KEY_NAME_TOKEN, '');
    }
    
    /**
     * @param string $token
     */
    public function setToken(string $token): void {
        Session::set(self::$KEY_NAME_TOKEN, $token);
    }
    
}

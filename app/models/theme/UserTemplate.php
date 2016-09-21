<?php
/**
 * Created by PhpStorm.
 * User: MaruloPC-Desk
 * Date: 17/09/2016
 * Time: 0:11
 */

namespace SoftnCMS\models\theme;

use SoftnCMS\models\admin\User;

class UserTemplate {
    
    /**
     * @var User Instancia
     */
    private $user;
    
    public function __construct($user) {
        $this->user = $user;
    }
    
    public function getInstance() {
        return $this->user;
    }
    
    public function getUserUrl($isEcho = \TRUE) {
        global $urlSite;
        
        if (!$isEcho) {
            
            return $urlSite . 'user/' . $this->getID();
        }
        
        echo $urlSite . 'user/' . $this->getID();
    }
    
    public function getID() {
        return $this->user->getID();
    }
    
    public function getUserName($isEcho = \TRUE) {
        if (!$isEcho) {
            
            return $this->user->getUserName();
        }
        
        echo $this->user->getUserName();
    }
    
    public function getUserWebSite($isEcho = \TRUE) {
        if (!$isEcho) {
            
            return $this->user->getUserUrl();
        }
        
        echo $this->user->getUserUrl();
        
    }
    
}

<?php
/**
 * Users.php
 */

namespace App\Rest\Dto;

use App\Facades\Utils;
use App\Rest\Common\Magic;
use App\Rest\Common\ObjectToArray;

/**
 * @property int       $id
 * @property string    $userLogin
 * @property string    $userName
 * @property string    $userEmail
 * @property \DateTime $userRegistered
 * @property string    $userPassword
 * Class Users
 * @author Nicolás Marulanda P.
 */
class Users implements ObjectToArray {
    
    use Magic;
    
    /**
     * @var int
     */
    private $id;
    
    /**
     * @var string
     */
    private $userLogin;
    
    /**
     * @var string
     */
    private $userName;
    
    /**
     * @var string
     */
    private $userEmail;
    
    /**
     * @var \DateTime
     */
    private $userRegistered;
    
    /**
     * @var string
     */
    private $userPassword;
    
    public function toArray() {
        return Utils::castObjectToArray($this);
    }
    
}

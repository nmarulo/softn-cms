<?php
/**
 * Users.php
 */

namespace App\Rest\Dto;

use App\Rest\Common\Magic;

/**
 * @property int       $id
 * @property string    $userLogin
 * @property string    $userName
 * @property string    $userEmail
 * @property string    $userPassword
 * @property \DateTime $userRegistered
 * Class Users
 * @author Nicolás Marulanda P.
 */
class Users {
    
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
     * @var string
     */
    private $userPassword;
    
    /**
     * @var \DateTime
     */
    private $userRegistered;
    
}

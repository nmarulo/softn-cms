<?php
/**
 * UsersDTO.php
 */

namespace App\Rest\Dto;

use App\Models\Users;
use App\Rest\Common\BaseDTO;

/**
 * @property int       $id
 * @property string    $userLogin
 * @property string    $userName
 * @property string    $userEmail
 * @property \DateTime $userRegistered
 * @property string    $userPassword
 * Class UsersDTO
 * @author Nicolás Marulanda P.
 */
class UsersDTO extends BaseDTO {
    
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
    
    protected static function getComparisionNameDtoToModel(): array {
        return [
                'id'             => 'id',
                'userLogin'      => 'user_login',
                'userEmail'      => 'user_email',
                'userName'       => 'user_name',
                'userRegistered' => 'user_registered',
                'userPassword'   => 'user_password',
        ];
    }
    
    protected static function getClassModel(): string {
        return Users::class;
    }
    
    protected static function getClassDTO(): string {
        return self::class;
    }
    
}

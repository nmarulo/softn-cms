<?php
/**
 * UsersDTO.php
 */

namespace App\Rest\Dto;

use App\Facades\Utils;
use App\Models\Users;
use App\Rest\Common\ConvertModel;
use App\Rest\Common\Magic;
use App\Rest\Common\ObjectToArray;
use Silver\Database\Model;

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
class UsersDTO implements ObjectToArray, ConvertModel {
    
    use Magic;
    
    const COMPARISION_TABLE = [
            'id'             => 'id',
            'userLogin'      => 'user_login',
            'userEmail'      => 'user_email',
            'userName'       => 'user_name',
            'userRegistered' => 'user_registered',
            'userPassword'   => 'user_password',
    ];
    
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
    
    public static function convertToModel($object, bool $hideProps = TRUE): Model {
        return Utils::castDtoToModel(self::COMPARISION_TABLE, $object, Users::class, $hideProps);
    }
    
    public static function convertOfModel(Model $model, bool $hideProps = TRUE): UsersDTO {
        return Utils::castModelToDto(self::COMPARISION_TABLE, $model, UsersDTO::class, $hideProps);
    }
    
    public function toArray() {
        return Utils::castObjectToArray($this);
    }
    
}

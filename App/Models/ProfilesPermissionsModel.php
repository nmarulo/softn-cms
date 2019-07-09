<?php
/**
 * softn-cms
 */

namespace App\Models;

use Silver\Database\Model;
use Silver\Database\Query;

/**
 * @property int $profile_id
 * @property int $permission_id
 * Class ProfilesPermissionsModel
 * @author Nicolás Marulanda P.
 */
class ProfilesPermissionsModel extends Model {
    
    protected static $_table = 'profiles_permissions';
    
    public static function deleteProfiles(int $id): void {
        Query::delete()
             ->from(self::tableName())
             ->where('profile_id', $id)
             ->execute();
    }
    
    public static function deletePermissions(int $id): void {
        Query::delete()
             ->from(self::tableName())
             ->where('permission_id', $id)
             ->execute();
    }
    
    public function saveNew(): void {
        Query::insert(self::tableName(), $this->data())
             ->execute();
    }
    
}

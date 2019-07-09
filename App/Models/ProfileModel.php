<?php
/**
 * softn-cms
 */

namespace App\Models;

use Silver\Database\Model;
use Silver\Database\Query;

/**
 * @property int    $id
 * @property string $profile_name
 * @property string $profile_description
 * @property string $profile_key_name
 * Class ProfileModel
 * @author Nicolás Marulanda P.
 */
class ProfileModel extends Model {
    
    protected static $_table     = 'profiles';
    
    protected static $_primary   = 'id';
    
    protected        $hidden     = [];
    
    protected        $searchable = [
            'id',
            'profile_name',
    ];
    
    public function getPermissions(): array {
        return Query::select()
                    ->from(PermissionModel::class)
                    ->rightJoin(ProfilesPermissionsModel::class, [
                            'permission_id',
                            'id',
                    ])
                    ->where('profile_id', $this->id)
                    ->fetchAll(PermissionModel::class);
    }
}

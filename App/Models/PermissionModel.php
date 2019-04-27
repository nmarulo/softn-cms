<?php
/**
 * softn-cms
 */

namespace App\Models;

use Silver\Database\Model;

/**
 * @property int    $id
 * @property string $permission_name
 * @property string $permission_description
 * @property string $permission_key_name
 * Class PermissionModel
 * @author Nicolás Marulanda P.
 */
class PermissionModel extends Model {
    
    protected static $_table     = 'permissions';
    
    protected static $_primary   = 'id';
    
    protected        $hidden     = [];
    
    protected        $searchable = [
            'id',
            'permission_name',
    ];
}

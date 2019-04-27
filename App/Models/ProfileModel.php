<?php
/**
 * softn-cms
 */

namespace App\Models;

use Silver\Database\Model;

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
}

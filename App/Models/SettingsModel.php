<?php

namespace App\Models;

use Silver\Database\Model;

/**
 * @property int    $id
 * @property string $setting_name
 * @property string $setting_value
 * @property string $setting_description
 */
class SettingsModel extends Model {
    
    protected static $_table     = 'settings';
    
    protected static $_primary   = 'id';
    
    protected        $hidden     = [];
    
    protected        $searchable = [
            'id',
            'setting_name',
    ];
    
    public function getSettings() {
        return $this->select('settings')
                    ->all();
    }
}

<?php

namespace App\Models;

use Silver\Database\Model;
use Silver\Database\Query;

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
    
    public function saveByName() {
        $data = $this->dirtyData();
        
        if (count($data) <= 0) {
            return $this;
        }
        
        $query = Query::update(static::class)
                      ->where('setting_name', $this->setting_name);
        
        foreach ($data as $key => $value) {
            $query->set($key, $value);
        }
        
        $query->execute();
        Query::select()
             ->from(static::class)
             ->where('setting_name', $this->setting_name)
             ->first($this);
        
        return $this;
    }
}

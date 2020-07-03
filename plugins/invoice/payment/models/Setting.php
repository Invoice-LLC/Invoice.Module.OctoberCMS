<?php namespace invoice\payment\Models;

use Model;

class Setting extends Model
{
    public $implement = ['System.Behaviors.SettingsModel'];

    public $settingsCode = 'location_settings';
    public $settingsFields = 'fields.yaml';

    public function initSettingsData()
    {
        $this->login = '';
        $this->api_key = '';
        $this->default_terminal_name = '';
    }

    public static function getApiKey() {
        return ($defaultId = Setting::get('api_key'))
            ? static::find($defaultId)
            : null;
    }
}

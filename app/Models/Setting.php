<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

class Setting extends Model
{
    use HasFactory;

    protected $fillable = ['key', 'value', 'type', 'label'];

    /**
     * Get a setting value by key
     */
    public static function get(string $key, $default = null)
    {
        $setting = static::where('key', $key)->first();
        return $setting ? $setting->value : $default;
    }

    /**
     * Set a setting value by key
     */
    public static function set(string $key, $value): void
    {
        static::updateOrCreate(
            ['key' => $key],
            ['value' => $value]
        );

        // Clear cache
        Cache::forget('app_settings');
    }

    /**
     * Get all settings as key-value array (cached)
     */
    public static function getAllCached(): array
    {
        return Cache::remember('app_settings', 3600, function () {
            return static::pluck('value', 'key')->toArray();
        });
    }

    /**
     * Get default settings
     */
    public static function getDefaults(): array
    {
        return [
            'app_name' => 'E-Num',
            'app_logo' => null,
            'institution_name' => 'Dinas Kominfo Siak',
            'footer_text' => '© ' . date('Y') . ' E-Num - Sistem Penomoran Surat Digital',
        ];
    }
}

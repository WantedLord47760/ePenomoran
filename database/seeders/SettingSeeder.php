<?php

namespace Database\Seeders;

use App\Models\Setting;
use Illuminate\Database\Seeder;

class SettingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $settings = [
            [
                'key' => 'app_name',
                'value' => 'E-Num',
                'type' => 'text',
                'label' => 'Nama Aplikasi',
            ],
            [
                'key' => 'app_logo',
                'value' => null,
                'type' => 'file',
                'label' => 'Logo Aplikasi',
            ],
            [
                'key' => 'institution_name',
                'value' => 'Dinas Kominfo Siak',
                'type' => 'text',
                'label' => 'Nama Instansi',
            ],
            [
                'key' => 'footer_text',
                'value' => '© 2025 E-Num - Sistem Penomoran Surat Digital',
                'type' => 'textarea',
                'label' => 'Teks Footer',
            ],
        ];

        foreach ($settings as $setting) {
            Setting::updateOrCreate(
                ['key' => $setting['key']],
                $setting
            );
        }
    }
}

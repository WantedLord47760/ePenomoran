<?php

namespace App\Http\Controllers;

use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Storage;

class SettingController extends Controller
{
    /**
     * Display settings form
     */
    public function index()
    {
        // Only admin can access
        if (auth()->user()->role !== 'admin') {
            abort(403);
        }

        $settings = Setting::all()->keyBy('key');

        return view('settings.index', compact('settings'));
    }

    /**
     * Update settings
     */
    public function update(Request $request)
    {
        if (auth()->user()->role !== 'admin') {
            abort(403);
        }

        $request->validate([
            'app_name' => 'required|string|max:100',
            'institution_name' => 'required|string|max:255',
            'footer_text' => 'nullable|string|max:500',
            'app_logo' => 'nullable|image|mimes:jpeg,png,jpg,svg|max:2048',
        ]);

        // Update text settings
        Setting::set('app_name', $request->app_name);
        Setting::set('institution_name', $request->institution_name);
        Setting::set('footer_text', $request->footer_text);

        // Handle logo upload
        if ($request->hasFile('app_logo')) {
            // Delete old logo
            $oldLogo = Setting::get('app_logo');
            if ($oldLogo && Storage::disk('public')->exists($oldLogo)) {
                Storage::disk('public')->delete($oldLogo);
            }

            // Store new logo
            $path = $request->file('app_logo')->store('logos', 'public');
            Setting::set('app_logo', $path);
        }

        // Clear settings cache
        Cache::forget('app_settings');

        return redirect()->route('settings.index')
            ->with('success', 'Pengaturan berhasil disimpan.');
    }

    /**
     * Remove logo
     */
    public function removeLogo()
    {
        if (auth()->user()->role !== 'admin') {
            abort(403);
        }

        $oldLogo = Setting::get('app_logo');
        if ($oldLogo && Storage::disk('public')->exists($oldLogo)) {
            Storage::disk('public')->delete($oldLogo);
        }

        Setting::set('app_logo', null);
        Cache::forget('app_settings');

        return redirect()->route('settings.index')
            ->with('success', 'Logo berhasil dihapus.');
    }
}

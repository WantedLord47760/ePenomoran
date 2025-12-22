<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

class PasswordController extends Controller
{
    /**
     * Show change password form
     */
    public function showChangeForm()
    {
        return view('auth.change-password');
    }

    /**
     * Change the user's password
     */
    public function change(Request $request)
    {
        $request->validate([
            'current_password' => 'required',
            'password' => 'required|min:8|confirmed|different:current_password',
        ], [
            'password.different' => 'Password baru harus berbeda dengan password lama.',
            'password.confirmed' => 'Konfirmasi password tidak sesuai.',
            'password.min' => 'Password minimal 8 karakter.',
        ]);

        $user = Auth::user();

        // Verify current password
        if (!Hash::check($request->current_password, $user->password)) {
            return back()->withErrors(['current_password' => 'Password lama tidak sesuai.']);
        }

        // Update password
        $user->update([
            'password' => Hash::make($request->password),
        ]);

        // Log out from other devices (optional security measure)
        // Auth::logoutOtherDevices($request->password);

        return redirect()->route('dashboard')
            ->with('success', 'Password berhasil diubah. Silakan login dengan password baru Anda.');
    }
}

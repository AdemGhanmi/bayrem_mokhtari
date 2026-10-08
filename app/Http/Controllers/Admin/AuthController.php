<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function login()
    {
        $admin = User::query()->whereKey(session('admin_id'))->where('is_admin', true)->first();

        if ($admin) {
            return redirect()->route('admin.dashboard');
        }

        session()->forget(['admin_id', 'admin_name']);

        return view('admin.login');
    }

    public function authenticate(Request $r)
    {
        $d = $r->validate(['email' => 'required|email', 'password' => 'required']);
        $u = User::where('email', $d['email'])->where('is_admin', true)->first();

        if (! $u || ! Hash::check($d['password'], $u->password)) {
            return back()->withInput($r->only('email'))->with('error', __('Invalid email or password.'));
        }

        $locale = session('locale');
        $r->session()->regenerate();
        session(['admin_id' => $u->id, 'admin_name' => $u->name, 'locale' => $locale]);

        return redirect()->intended(route('admin.dashboard'));
    }

    public function logout(Request $r)
    {
        $locale = session('locale');
        $r->session()->invalidate();
        $r->session()->regenerateToken();
        session(['locale' => $locale]);

        return redirect()->route('admin.login');
    }
}

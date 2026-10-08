<?php

namespace App\Http\Middleware;

use App\Models\User;
use Closure;
use Illuminate\Http\Request;

class AdminAuth
{
    public function handle(Request $request, Closure $next)
    {
        $id = session('admin_id');
        $u = $id ? User::find($id) : null;
        if (! $u || ! $u->is_admin) {
            $request->session()->forget(['admin_id', 'admin_name']);

            return redirect()->route('admin.login')->with('error', 'Please login to continue.');
        }

return $next($request);
    }
}

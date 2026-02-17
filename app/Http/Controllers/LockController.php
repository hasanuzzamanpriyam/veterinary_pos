<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;

class LockController extends Controller
{
    public function lock()
    {
        if (Auth::check()) {
            session(['is_locked' => true, 'locked_at' => now()]);
            return view('admin.lock');
        }
        return redirect()->route('login');
    }

    public function unlock(Request $request)
    {
        $request->validate([
            'password' => 'required|string',
        ]);

        if (Auth::check()) {
            $user = Auth::user();
            if (Hash::check($request->password, $user->password)) {
                session(['is_locked' => false]);
                return redirect()->intended('/dashboard');
            }
            return back()->withErrors([
                'password' => 'Incorrect password',
            ]);
        }

        return redirect()->route('login');
    }

    public function checkLocked()
    {
        return response()->json([
            'is_locked' => session('is_locked', false),
        ]);
    }
}
